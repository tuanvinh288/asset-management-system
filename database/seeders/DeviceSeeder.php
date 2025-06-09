<?php

namespace Database\Seeders;

use DB;
use App\Models\Unit;
use App\Models\Device;
use Faker\Factory as Faker;
use App\Models\Category;
use App\Models\DeviceItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Lấy danh sách danh mục và đơn vị tính
        $categories = Category::all();
        $units = Unit::all();

        // Tạo dữ liệu mẫu cho thiết bị
        $devices = [
            [
                'name' => 'Máy tính để bàn',
                'description' => 'Máy tính để bàn phục vụ học tập và nghiên cứu',
                'category_id' => 4, // Máy tính
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Máy chiếu',
                'description' => 'Máy chiếu phục vụ giảng dạy',
                'category_id' => 3, // Thiết bị trình chiếu
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Laptop',
                'description' => 'Laptop phục vụ học tập và nghiên cứu',
                'category_id' => 4, // Máy tính
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Máy in',
                'description' => 'Máy in phục vụ in ấn tài liệu',
                'category_id' => 2, // Thiết bị văn phòng
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Máy quét',
                'description' => 'Máy quét tài liệu',
                'category_id' => 2, // Thiết bị văn phòng
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Máy ảnh',
                'description' => 'Máy ảnh phục vụ ghi hình',
                'category_id' => 5, // Thiết bị ghi hình
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Máy quay phim',
                'description' => 'Máy quay phim phục vụ ghi hình',
                'category_id' => 5, // Thiết bị ghi hình
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Bàn ghế',
                'description' => 'Bàn ghế phục vụ học tập và làm việc',
                'category_id' => 1, // Nội thất
                'unit_id' => 1, // Bộ
            ],
            [
                'name' => 'Tủ đựng tài liệu',
                'description' => 'Tủ đựng tài liệu văn phòng',
                'category_id' => 1, // Nội thất
                'unit_id' => 1, // Cái
            ],
            [
                'name' => 'Điều hòa',
                'description' => 'Điều hòa không khí',
                'category_id' => 1, // Nội thất
                'unit_id' => 1, // Cái
            ]
        ];

        foreach ($devices as $deviceData) {
            // Tạo thiết bị
            $device = Device::create([
                'name' => $deviceData['name'],
                'description' => $deviceData['description'],
                'category_id' => $deviceData['category_id'],
                'unit_id' => $deviceData['unit_id']
            ]);

            // Tạo số lượng thiết bị con ngẫu nhiên (từ 3 đến 10)
            $quantity = rand(3, 10);
            for ($i = 1; $i <= $quantity; $i++) {
                DeviceItem::create([
                    'device_id' => $device->id,
                    'code' => 'DEV-' . $device->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'status' => $faker->randomElement(['available', 'in_use', 'maintenance', 'broken']),
                    'is_fixed' => isset($deviceData['is_fixed']) ? $deviceData['is_fixed'] : false
                ]);
            }
        }
    }
}
