<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Thiết bị văn phòng',
                'status' => 'active',
                'description' => 'Máy in, máy photocopy, máy chiếu, máy hủy tài liệu,...',
                'unit_id' => 2, // Chiếc
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị phòng học',
                'status' => 'active',
                'description' => 'Bàn ghế học sinh, bảng viết, đèn chiếu,...',
                'unit_id' => 2, // Chiếc
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị phòng lab',
                'status' => 'active',
                'description' => 'Máy thực hành, mô hình kỹ thuật, thiết bị đo lường,...',
                'unit_id' => 1, // Bộ
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị lưu trữ',
                'status' => 'active',
                'description' => 'Ổ cứng, USB, NAS,...',
                'unit_id' => 3, // Ổ
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị điện tử nhỏ',
                'status' => 'active',
                'description' => 'Vi mạch, cảm biến, thiết bị đo nhỏ,...',
                'unit_id' => 4, // Hộp
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thiết bị phụ trợ',
                'status' => 'inactive',
                'description' => 'Dụng cụ lắp đặt, dây cáp, ốc vít,...',
                'unit_id' => 5, // Cái
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
