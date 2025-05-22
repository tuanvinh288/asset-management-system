<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceItem;
use App\Models\Borrow;
use App\Models\Maintenance;
use App\Models\Room;
use App\Models\RoomBorrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $totalDevices = Device::count();
        $totalDeviceItems = DeviceItem::count();
        $totalBorrows = Borrow::count();
        $totalRoomBorrows = RoomBorrow::count();
        $totalRooms = Room::count();
        $totalDamaged = DeviceItem::where('status', 'broken')->count();
        $totalBorrowed = DeviceItem::where('status', 'in_use')->count();
        $totalMaintenance = DeviceItem::where('status', 'maintenance')->count();
        $totalAvailable = DeviceItem::where('status', 'available')->count();

        // Tính hiệu suất sử dụng
        $usageEfficiency = $totalDeviceItems > 0 ? round(($totalBorrowed / $totalDeviceItems) * 100) : 0;

        // Thống kê trạng thái thiết bị
        $deviceStatusStats = DeviceItem::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Thống kê mượn theo tháng
        $borrowStats = Borrow::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Thống kê mượn phòng theo tháng
        $roomBorrowStats = RoomBorrow::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Danh sách thiết bị hỏng
        $damagedDevices = DeviceItem::with('device')
            ->where('status', 'broken')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Danh sách thiết bị đang mượn
        $borrowedDevices = DeviceItem::with('device')
            ->where('status', 'in_use')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Danh sách phòng đang mượn
        $borrowedRooms = RoomBorrow::with(['room', 'user'])
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Danh sách thiết bị đang bảo trì
        $maintenanceDevices = DeviceItem::with(['device', 'maintenances'])
            ->where('status', 'maintenance')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Thống kê chi phí bảo trì
        $maintenanceCosts = Maintenance::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COALESCE(SUM(cost), 0) as total_cost')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Thống kê thiết bị theo danh mục
        $deviceByCategory = Device::with('category')
            ->select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->get();

        // Thống kê mượn theo phòng ban
        $borrowsByDepartment = Borrow::with('user.department')
            ->select('user_id', DB::raw('count(*) as total'))
            ->groupBy('user_id')
            ->get()
            ->groupBy('user.department.name')
            ->map(function ($items) {
                return $items->sum('total');
            });

        return view('admin.dashboard', compact(
            'totalDevices',
            'totalDeviceItems',
            'totalBorrows',
            'totalRoomBorrows',
            'totalRooms',
            'totalDamaged',
            'totalBorrowed',
            'totalMaintenance',
            'totalAvailable',
            'usageEfficiency',
            'deviceStatusStats',
            'borrowStats',
            'roomBorrowStats',
            'damagedDevices',
            'borrowedDevices',
            'borrowedRooms',
            'maintenanceDevices',
            'maintenanceCosts',
            'deviceByCategory',
            'borrowsByDepartment'
        ));
    }
}
