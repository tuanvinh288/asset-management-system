<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Công ty TNHH Thiết bị Giáo dục Hòa Phát',
                'address' => 'Số 123 Nguyễn Trãi, Thanh Xuân, Hà Nội',
                'phone' => '0243 567 8910',
                'email' => 'hoaphat@thietbigiaoduc.vn',
                'note' => 'Chuyên cung cấp thiết bị phòng học, bàn ghế, máy chiếu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công ty CP Máy tính An Phát',
                'address' => '456 Trần Duy Hưng, Cầu Giấy, Hà Nội',
                'phone' => '0243 888 9999',
                'email' => 'lienhe@anphatpc.com.vn',
                'note' => 'Chuyên cung cấp máy tính, linh kiện và thiết bị mạng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công ty TNHH Thiết bị Khoa học Kỹ thuật Đại Nam',
                'address' => 'Số 9 Lê Văn Lương, Hà Nội',
                'phone' => '0243 556 7788',
                'email' => 'support@dainamtech.vn',
                'note' => 'Thiết bị phòng lab, vật tư thực hành ngành kỹ thuật',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Công ty TNHH Công nghệ Sao Việt',
                'address' => '18 Tôn Thất Thuyết, Mỹ Đình, Hà Nội',
                'phone' => '0246 258 9999',
                'email' => 'sales@saoviettech.vn',
                'note' => 'Cung cấp thiết bị CNTT, phần mềm quản lý trường học',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
