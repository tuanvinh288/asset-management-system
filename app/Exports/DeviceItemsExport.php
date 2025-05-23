<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeviceItemsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Department::with(['deviceItems' => function($query) {
            $query->with('device');
        }])->get();
    }

    public function headings(): array
    {
        return [
            'Phòng ban',
            'Mã thiết bị',
            'Tên thiết bị',
            'Trạng thái',
            'Ngày mua',
            'Giá trị'
        ];
    }

    public function map($department): array
    {
        $rows = [];
        foreach ($department->deviceItems as $item) {
            $rows[] = [
                $department->name,
                $item->code,
                $item->device->name,
                $item->status,
                $item->purchase_date,
                $item->value
            ];
        }
        return $rows;
    }
} 