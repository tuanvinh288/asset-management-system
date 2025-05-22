<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = DB::table('devices')->get();
        $now = now();

        foreach ($devices as $device) {
            // Mỗi thiết bị có 3-7 bản sao ngẫu nhiên
            $quantity = rand(3, 7);

            for ($i = 1; $i <= $quantity; $i++) {
                // Lấy supplier ngẫu nhiên từ bảng suppliers
                $supplier = DB::table('suppliers')->inRandomOrder()->first();

                DB::table('device_items')->insert([
                    'device_id' => $device->id,
                    'code' => 'DEV-' . $device->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'serial_number' => 'SN-' . strtoupper(Str::random(8)),
                    'status' => 'available',
                    'is_fixed' => false,
                    'supplier_id' => $supplier->id,
                    'qr_token' => Str::random(32),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
