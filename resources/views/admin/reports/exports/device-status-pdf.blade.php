<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Báo cáo tình trạng thiết bị</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100%;
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">BÁO CÁO TÌNH TRẠNG THIẾT BỊ</div>
        <div>Ngày xuất báo cáo: {{ date('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Trạng thái</th>
                <th>Số lượng</th>
                <th>Tỷ lệ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statusPercentages as $status)
            <tr>
                <td>{{ $status['status'] }}</td>
                <td>{{ $status['count'] }}</td>
                <td>{{ $status['percentage'] }}%</td>
            </tr>
            @endforeach
            <tr>
                <td><strong>Tổng cộng</strong></td>
                <td><strong>{{ $totalDevices }}</strong></td>
                <td><strong>100%</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Trang {PAGENO} / {nbpg}
    </div>
</body>
</html> 