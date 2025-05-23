<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaintenanceCostsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Maintenance::with(['deviceItem.device.category'])->get();
    }

    public function headings(): array
    {
        return [
            'Mã thiết bị',
            'Tên thiết bị',
            'Loại thiết bị',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Chi phí',
            'Mô tả'
        ];
    }

    public function map($maintenance): array
    {
        return [
            $maintenance->deviceItem->device->code,
            $maintenance->deviceItem->device->name,
            $maintenance->deviceItem->device->category->name,
            $maintenance->start_date ? $maintenance->start_date->format('d/m/Y') : '',
            $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : 'Đang bảo trì',
            number_format($maintenance->cost, 0, ',', '.') . ' VNĐ',
            $maintenance->description
        ];
    }
} 