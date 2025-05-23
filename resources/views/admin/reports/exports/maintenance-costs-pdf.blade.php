<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo chi phí bảo trì</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100%;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO CHI PHÍ BẢO TRÌ</div>
        <div>Ngày xuất báo cáo: {{ date('d/m/Y') }}</div>
    </div>

    <div class="summary">
        <div class="summary-item"><strong>Tổng chi phí bảo trì:</strong> {{ number_format($totalCost, 0, ',', '.') }} VNĐ</div>
        <div class="summary-item"><strong>Chi phí trung bình:</strong> {{ number_format($averageCost, 0, ',', '.') }} VNĐ</div>
        <div class="summary-item"><strong>Số lượt bảo trì:</strong> {{ count($maintenances) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã thiết bị</th>
                <th>Tên thiết bị</th>
                <th>Loại thiết bị</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Chi phí (VNĐ)</th>
                <th>Mô tả</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintenances as $maintenance)
            <tr>
                <td>{{ $maintenance->deviceItem->code }}</td>
                <td>{{ $maintenance->deviceItem->device->name }}</td>
                <td>{{ $maintenance->deviceItem->device->category->name }}</td>
                <td>{{ $maintenance->start_date->format('d/m/Y') }}</td>
                <td>{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : 'Chưa kết thúc' }}</td>
                <td class="text-right">{{ number_format($maintenance->cost, 0, ',', '.') }}</td>
                <td>{{ $maintenance->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Trang {PAGENO} / {nbpg}
    </div>
</body>
</html> 