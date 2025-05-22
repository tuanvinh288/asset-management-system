<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\DeviceItem;
use App\Models\Device;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with(['deviceItem.device', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $overdueMaintenances = Maintenance::pending()
            ->where('start_date', '<', now())
            ->count();

        $inProgressMaintenances = Maintenance::inProgress()
            ->count();

        // Thống kê chi phí bảo trì
        $maintenanceCosts = Maintenance::where('status', 'completed')
            ->whereYear('end_date', now()->year)
            ->selectRaw('MONTH(end_date) as month, SUM(cost) as total_cost')
            ->groupBy('month')
            ->get();

        // Thiết bị cần bảo trì sắp tới
        $upcomingMaintenances = Maintenance::where('status', 'pending')
            ->where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(7))
            ->with('deviceItem.device')
            ->get();

        return view('admin.maintenances.index', compact(
            'maintenances',
            'overdueMaintenances',
            'inProgressMaintenances',
            'maintenanceCosts',
            'upcomingMaintenances'
        ));
    }

    public function create()
    {
        $devices = Device::with(['deviceItems' => function($query) {
            $query->where('status', 'available')
                ->whereDoesntHave('maintenances', function($q) {
                    $q->whereIn('status', ['pending', 'in_progress']);
                })
                ->whereDoesntHave('borrowDetails', function($q) {
                    $q->whereHas('borrow', function($q) {
                        $q->whereIn('status', ['pending', 'approved']);
                    });
                });
        }])->get();

        return view('admin.maintenances.create', compact('devices'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_item_id' => 'required|exists:device_items,id',
            'type' => 'required|in:periodic,repair',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
        ]);

        $maintenance = Maintenance::create([
            'device_item_id' => $request->device_item_id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cost' => $request->cost,
            'description' => $request->description,
            'status' => 'pending',
            'created_by' => auth()->id(),
            'maintenance_interval' => $request->maintenance_interval
        ]);

        // Cập nhật trạng thái thiết bị
        $deviceItem = DeviceItem::find($request->device_item_id);
        $deviceItem->update(['status' => 'maintenance']);

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã tạo yêu cầu bảo trì thành công!');
    }

    public function edit(Maintenance $maintenance)
    {
        $devices = Device::all();
        return view('admin.maintenances.edit', compact('maintenance', 'devices'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'device_item_id' => 'required|exists:device_items,id',
            'type' => 'required|in:periodic,repair',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'cost' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'result' => 'nullable|string',
            'maintenance_interval' => 'required_if:type,periodic|integer|min:1'
        ]);

        $maintenance->update($request->all());

        // Nếu hoàn thành bảo trì, cập nhật trạng thái thiết bị và tính ngày bảo trì tiếp theo
        if ($request->status === 'completed') {
            $deviceItem = DeviceItem::find($request->device_item_id);
            $deviceItem->update(['status' => 'available']);
            
            if ($maintenance->type === 'periodic') {
                $maintenance->calculateNextMaintenanceDate();
            }
        }

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã cập nhật yêu cầu bảo trì thành công!');
    }

    public function destroy(Maintenance $maintenance)
    {
        // Cập nhật trạng thái thiết bị về available
        $deviceItem = $maintenance->deviceItem;
        $deviceItem->update(['status' => 'available']);

        $maintenance->delete();

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã xóa yêu cầu bảo trì thành công!');
    }

    public function updateStatus(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'result' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0'
        ]);

        $maintenance->update([
            'status' => $request->status,
            'result' => $request->result,
            'cost' => $request->cost,
            'end_date' => $request->status === 'completed' ? Carbon::now() : null
        ]);

        // Cập nhật trạng thái thiết bị
        $deviceItem = $maintenance->deviceItem;
        if ($request->status === 'completed') {
            $deviceItem->update(['status' => 'available']);
            if ($maintenance->type === 'periodic') {
                $maintenance->calculateNextMaintenanceDate();
            }
        } elseif ($request->status === 'cancelled') {
            $deviceItem->update(['status' => 'available']);
        }

        return redirect()->route('maintenances.index')
            ->with('success', 'Đã cập nhật trạng thái bảo trì thành công!');
    }

    public function checkPeriodicMaintenance()
    {
        $devices = Device::whereHas('deviceItems', function($query) {
            $query->where('status', 'available')
                ->whereDoesntHave('maintenances', function($q) {
                    $q->whereIn('status', ['pending', 'in_progress']);
                });
        })->get();

        $createdCount = 0;
        foreach ($devices as $device) {
            foreach ($device->deviceItems as $item) {
                $lastMaintenance = $item->maintenances()
                    ->where('type', 'periodic')
                    ->where('status', 'completed')
                    ->latest()
                    ->first();

                if ($lastMaintenance && $lastMaintenance->next_maintenance_date <= now()) {
                    Maintenance::create([
                        'device_item_id' => $item->id,
                        'type' => 'periodic',
                        'start_date' => now(),
                        'description' => 'Bảo trì định kỳ theo lịch',
                        'status' => 'pending',
                        'created_by' => auth()->id(),
                        'maintenance_interval' => $lastMaintenance->maintenance_interval,
                        'cost' => 0
                    ]);
                    $createdCount++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Đã tạo {$createdCount} yêu cầu bảo trì định kỳ mới"
        ]);
    }
}
