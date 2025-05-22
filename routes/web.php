<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\DeparmentController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DeviceController;
use App\Http\Controllers\admin\UnitController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\DeviceItemController;
use App\Http\Controllers\admin\BorrowController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MaintenanceController;
use App\Http\Controllers\admin\QrCodeController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\RoomController;
use App\Http\Controllers\admin\RoomBorrowController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\SettingController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::prefix('admin')->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // User management
        Route::resource('users', UserController::class);
        Route::resource('departments', DeparmentController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('units', UnitController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('categories', CategoryController::class);

        // Device management
        Route::resource('devices', DeviceController::class);
        Route::resource('device-items', DeviceItemController::class);
        Route::get('/device-items/{device_id}', [DeviceItemController::class, 'getDeviceItems'])->name('api.device-items');
        Route::get('/device-items/{device}/json', [DeviceItemController::class, 'json'])->name('device-items.json');

        // Room management
        Route::resource('rooms', RoomController::class);
        Route::resource('room-borrows', RoomBorrowController::class);
        Route::post('room-borrows/{id}/approve', [RoomBorrowController::class, 'approve'])->name('room-borrows.approve');
        Route::post('room-borrows/{id}/return', [RoomBorrowController::class, 'markReturned'])->name('room-borrows.return');
        Route::post('room-borrows/{id}/cancel', [RoomBorrowController::class, 'cancel'])->name('room-borrows.cancel');

        // Device borrowing management
        Route::resource('device-borrows', BorrowController::class);
        Route::get('device-borrows/{id}/return', [BorrowController::class, 'showReturnForm'])->name('device-borrows.return');
        Route::post('device-borrows/{id}/return', [BorrowController::class, 'markReturned'])->name('device-borrows.return.post');
        Route::get('/device-borrows/device-items/{device_id}', [BorrowController::class, 'getDeviceItems']);
        Route::post('device-borrows/{id}/approve', [BorrowController::class, 'approve'])->name('device-borrows.approve');
        Route::post('device-borrows/{id}/cancel', [BorrowController::class, 'cancel'])->name('device-borrows.cancel');
        Route::get('/device-borrows/{id}/details', [BorrowController::class, 'getBorrowDetails'])->name('device-borrows.details');

        // Maintenance
        Route::resource('maintenances', MaintenanceController::class);
        Route::post('maintenances/{maintenance}/update-status', [MaintenanceController::class, 'updateStatus'])->name('maintenances.update-status');
        Route::post('maintenances/check-periodic', [MaintenanceController::class, 'checkPeriodicMaintenance'])->name('maintenances.check-periodic');

        // QR Code
        Route::get('device-items/{id}/qrcode', [QrCodeController::class, 'show'])->name('qrcode.show');
        Route::post('device-items/{id}/qrcode/regenerate', [QrCodeController::class, 'regenerate'])->name('qrcode.regenerate');
        Route::get('device-items/{id}/qrcode/history', [QrCodeController::class, 'history'])->name('qrcode.history');
        Route::post('qrcode/print', [QrCodeController::class, 'printMultiple'])->name('qrcode.print');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/device-status', [ReportController::class, 'deviceStatus'])->name('reports.device-status');
        Route::get('/reports/device-status/pdf', [ReportController::class, 'deviceStatusPdf'])->name('reports.export-device-status-pdf');
        Route::get('/reports/department-assets', [ReportController::class, 'departmentAssets'])->name('reports.department-assets');
        Route::get('/reports/department-assets/pdf', [ReportController::class, 'exportDepartmentAssetsPDF'])->name('reports.export-department-assets-pdf');
        Route::get('/reports/department-assets/excel', [ReportController::class, 'exportDepartmentAssetsExcel'])->name('reports.export-department-assets-excel');
        Route::get('/reports/maintenance-costs', [ReportController::class, 'maintenanceCosts'])->name('reports.maintenance-costs');
        Route::get('/reports/maintenance-costs/pdf', [ReportController::class, 'maintenanceCostsPdf'])->name('reports.export-maintenance-costs-pdf');
        Route::get('/reports/maintenance-costs/excel', [ReportController::class, 'exportMaintenanceCostsExcel'])->name('reports.export-maintenance-costs-excel');

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});

// Public QR code scan route
Route::get('scan/{token}', [QrCodeController::class, 'scan'])->name('device-items.scan');
Route::post('scan/{token}/update-status', [QrCodeController::class, 'updateStatus'])->name('qrcode.update-status');

// Borrowing routes for teachers and students
Route::middleware(['auth'])->group(function () {
    // Device borrowing (accessible by both teachers and students)
    Route::get('/borrow-device', [BorrowController::class, 'borrowDevice'])->name('borrow-device');
    Route::post('/borrow-device', [BorrowController::class, 'storeDeviceBorrow'])->name('store-device-borrow');
    Route::get('/borrow-history', [BorrowController::class, 'borrowHistory'])->name('borrow-history');

    // Room borrowing (only for teachers)
    Route::middleware(['room.borrow'])->group(function () {
        Route::get('/borrow-room', [BorrowController::class, 'borrowRoom'])->name('borrow-room');
        Route::post('/borrow-room', [BorrowController::class, 'storeRoomBorrow'])->name('store-room-borrow');
    });
});

require __DIR__ . '/auth.php';
