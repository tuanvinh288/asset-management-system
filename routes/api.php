<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeviceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/device-items/{device}/available-for-maintenance', function (App\Models\Device $device) {
    $items = $device->deviceItems()
        ->where('status', 'available')
        ->whereDoesntHave('maintenances', function($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        })
        ->whereDoesntHave('borrowDetails', function($query) {
            $query->whereHas('borrow', function($query) {
                $query->whereIn('status', ['pending', 'approved']);
            });
        })
        ->select('id', 'code', 'name')
        ->get();

    return response()->json($items);
});
