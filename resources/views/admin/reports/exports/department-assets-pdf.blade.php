<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo tài sản theo phòng ban</title>
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
        .department {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .department-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f5f5f5;
            padding: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO TÀI SẢN THEO PHÒNG BAN</div>
        <div>Ngày xuất báo cáo: {{ date('d/m/Y') }}</div>
    </div>

    @foreach($departments as $department)
    <div class="department">
        <div class="department-title">{{ $department->name }}</div>
        <table>
            <thead>
                <tr>
                    <th>Mã thiết bị</th>
                    <th>Tên thiết bị</th>
                    <th>Trạng thái</th>
                    <th>Ngày mua</th>
                    <th>Giá trị</th>
                </tr>
            </thead>
            <tbody>
                @forelse($department->deviceItems as $item)
                <tr>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->device->name }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->purchase_date }}</td>
                    <td>{{ number_format($item->value) }} VNĐ</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Không có thiết bị nào</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        Trang {PAGE_NUM} / {PAGE_COUNT}
    </div>
</body>
</html> 