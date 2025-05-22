<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceItem;
use App\Models\Department;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Exports\DeviceItemsExport;
use App\Exports\MaintenanceCostsExport;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use PDF;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function departmentAssets()
    {
        $departments = Department::with(['deviceItems' => function($query) {
            $query->with('device');
        }])->get();

        return view('admin.reports.department-assets', compact('departments'));
    }

    public function deviceStatus()
    {
        $deviceItems = DeviceItem::all();
        if ($deviceItems->isEmpty()) {
            $statusCounts = collect([
                'available' => 0,
                'borrowed' => 0,
                'maintenance' => 0,
                'damaged' => 0
            ]);
            $total = 0;
        } else {
            $statusCounts = $deviceItems->groupBy('status')->map->count();
            $total = $deviceItems->count();
        }
        
        $statusPercentages = $statusCounts->map(function ($count) use ($total) {
            return $total > 0 ? ($count / $total) * 100 : 0;
        });
        
        $deviceCounts = $statusCounts->toArray();
        
        return view('admin.reports.device-status', compact('statusPercentages', 'deviceCounts'));
    }

    public function deviceStatusPdf()
    {
        $deviceItems = DeviceItem::all();
        $statusLabels = [
            'available' => 'Sẵn sàng',
            'borrowed' => 'Đang mượn',
            'maintenance' => 'Đang bảo trì',
            'damaged' => 'Hỏng'
        ];
        
        if ($deviceItems->isEmpty()) {
            $statusCounts = collect([
                'available' => 0,
                'borrowed' => 0,
                'maintenance' => 0,
                'damaged' => 0
            ]);
            $total = 0;
        } else {
            $statusCounts = $deviceItems->groupBy('status')->map->count();
            $total = $deviceItems->count();
        }
        
        $formattedData = collect();
        foreach ($statusCounts as $status => $count) {
            $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
            $formattedData->push([
                'status' => $statusLabels[$status] ?? $status,
                'count' => $count,
                'percentage' => $percentage
            ]);
        }

        $data = [
            'statusPercentages' => $formattedData,
            'totalDevices' => $total,
            'date' => now()->format('d/m/Y')
        ];

        $pdf = PDF::loadView('admin.reports.exports.device-status-pdf', $data, [], [
            'title' => 'Báo cáo tình trạng thiết bị',
            'format' => 'A4',
            'orientation' => 'P'
        ]);

        return $pdf->stream('bao-cao-tinh-trang-thiet-bi.pdf');
    }

    public function maintenanceCosts()
    {
        $maintenances = Maintenance::with(['deviceItem.device.category'])->get();
        $totalCost = $maintenances->sum('cost');
        $averageCost = $maintenances->count() > 0 ? $totalCost / $maintenances->count() : 0;
        
        return view('admin.reports.maintenance-costs', compact('maintenances', 'totalCost', 'averageCost'));
    }

    public function maintenanceCostsPdf()
    {
        $maintenances = Maintenance::with(['deviceItem.device.category'])->get();
        $totalCost = $maintenances->sum('cost');
        $averageCost = $maintenances->count() > 0 ? $totalCost / $maintenances->count() : 0;

        $data = [
            'maintenances' => $maintenances,
            'totalCost' => $totalCost,
            'averageCost' => $averageCost,
            'date' => now()->format('d/m/Y')
        ];

        $pdf = PDF::loadView('admin.reports.exports.maintenance-costs-pdf', $data, [], [
            'title' => 'Báo cáo chi phí bảo trì',
            'format' => 'A4',
            'orientation' => 'P'
        ]);

        return $pdf->stream('bao-cao-chi-phi-bao-tri.pdf');
    }

    public function maintenanceCostsExcel()
    {
        $maintenances = Maintenance::with(['deviceItem.device.category'])->get();
        
        $headers = [
            'Mã thiết bị',
            'Tên thiết bị',
            'Loại thiết bị',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Chi phí (VNĐ)',
            'Mô tả'
        ];

        $data = $maintenances->map(function ($maintenance) {
            return [
                $maintenance->deviceItem->code,
                $maintenance->deviceItem->device->name,
                $maintenance->deviceItem->device->category->name,
                $maintenance->start_date->format('d/m/Y'),
                $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : 'Chưa kết thúc',
                number_format($maintenance->cost, 0, ',', '.'),
                $maintenance->description
            ];
        })->toArray();

        $filePath = storage_path('app/public/bao-cao-chi-phi-bao-tri.csv');
        
        // Add BOM for UTF-8
        file_put_contents($filePath, "\xEF\xBB\xBF");

        // Create writer after adding BOM
        $writer = SimpleExcelWriter::create($filePath)
            ->addHeader($headers)
            ->addRows($data);

        return response()->download(
            $filePath,
            'bao-cao-chi-phi-bao-tri.csv',
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="bao-cao-chi-phi-bao-tri.csv"'
            ]
        )->deleteFileAfterSend();
    }

    public function exportDepartmentAssetsPDF()
    {
        $departments = Department::with(['deviceItems' => function($query) {
            $query->with('device');
        }])->get();

        $data = [
            'departments' => $departments,
            'date' => now()->format('d/m/Y')
        ];

        $pdf = PDF::loadView('admin.reports.exports.department-assets-pdf', $data, [], [
            'title' => 'Báo cáo tài sản theo phòng ban',
            'format' => 'A4',
            'orientation' => 'L'
        ]);

        return $pdf->stream('bao-cao-tai-san-theo-phong-ban.pdf');
    }

    public function exportDepartmentAssetsExcel()
    {
        return ExcelFacade::download(new DeviceItemsExport, 'bao-cao-tai-san-theo-phong-ban.xlsx');
    }

    public function exportMaintenanceCostsExcel()
    {
        return ExcelFacade::download(new MaintenanceCostsExport, 'bao-cao-chi-phi-bao-tri.xlsx');
    }
} 