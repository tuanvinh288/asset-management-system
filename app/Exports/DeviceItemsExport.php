<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeviceItemsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rows;

    public function collection()
    {
        // Lấy tất cả phòng ban, kèm khoa và phòng
        $departments = Department::with(['rooms'])->get();
        $this->rows = collect();
        foreach ($departments as $department) {
            foreach ($department->rooms as $room) {
                $this->rows->push([
                    $department->name,
                    $room->name,
                    $room->code,
                    $room->note
                ]);
            }
            // Nếu phòng ban không có phòng, vẫn xuất 1 dòng
            if ($department->rooms->isEmpty()) {
                $this->rows->push([
                    $department->name,
                    '', '', ''
                ]);
            }
        }
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Tên khoa',
            'Tên phòng',
            'Mã phòng',
            'Ghi chú'
        ];
    }

    public function map($row): array
    {
        // $row đã là mảng đúng thứ tự
        return $row;
    }
}
