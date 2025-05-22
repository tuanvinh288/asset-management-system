<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Department;
use App\Models\DeviceItem;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách khoa
        $departments = Department::all();

        // Tạo dữ liệu mẫu cho phòng
        $rooms = [
            [
                'name' => 'Phòng học A1.01',
                'code' => 'A1.01',
                'description' => 'Phòng học lý thuyết 50 chỗ',
                'status' => 'available'
            ],
            [
                'name' => 'Phòng thực hành CNTT 1',
                'code' => 'TH.CNTT.1',
                'description' => 'Phòng thực hành máy tính 30 máy',
                'status' => 'available'
            ],
            [
                'name' => 'Phòng thực hành Điện tử',
                'code' => 'TH.DT.1',
                'description' => 'Phòng thực hành điện tử cơ bản',
                'status' => 'available'
            ],
            [
                'name' => 'Phòng họp khoa CNTT',
                'code' => 'HK.CNTT',
                'description' => 'Phòng họp khoa CNTT',
                'status' => 'available'
            ],
            [
                'name' => 'Phòng thực hành Cơ khí',
                'code' => 'TH.CK.1',
                'description' => 'Phòng thực hành cơ khí',
                'status' => 'available'
            ]
        ];

        foreach ($rooms as $roomData) {
            // Chọn ngẫu nhiên một khoa
            $department = $departments->random();

            // Tạo phòng
            $room = Room::create([
                'name' => $roomData['name'],
                'code' => $roomData['code'],
                'description' => $roomData['description'],
                'status' => $roomData['status'],
                'department_id' => $department->id
            ]);
        }
    }
}
