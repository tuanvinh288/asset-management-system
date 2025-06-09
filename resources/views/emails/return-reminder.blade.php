<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo trả {{ $type == 'device' ? 'thiết bị' : 'phòng' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: {{ $isOverdue ? '#dc3545' : '#ffc107' }};
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .details {
            margin: 20px 0;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $isOverdue ? 'Cảnh báo: Quá hạn trả' : 'Nhắc nhở: Sắp đến hạn trả' }} {{ $type == 'device' ? 'thiết bị' : 'phòng' }}</h2>
        </div>
        <div class="content">
            <p>Kính gửi {{ $borrow->user ? $borrow->user->name : 'Người dùng' }},</p>

            <p>{{ $isOverdue ? 'Phiếu mượn của bạn đã quá hạn trả.' : 'Phiếu mượn của bạn sắp đến hạn trả.' }}</p>

            <div class="details">
                <p><strong>Mã phiếu:</strong> {{ $borrow->id }}</p>
                <p><strong>Ngày mượn:</strong> {{ $borrow->borrow_date->format('d/m/Y H:i') }}</p>
                <p><strong>Ngày trả dự kiến:</strong> {{ $borrow->return_date->format('d/m/Y H:i') }}</p>
                @if($type == 'device')
                    <p><strong>Thiết bị:</strong></p>
                    <ul>
                        @foreach($borrow->details as $detail)
                            <li>{{ $detail->deviceItem->device->name ?? 'N/A' }} ({{ $detail->deviceItem->code ?? 'N/A' }})</li>
                        @endforeach
                    </ul>
                @else
                    <p><strong>Phòng:</strong> {{ $borrow->room->name ?? 'N/A' }}</p>
                @endif
            </div>

            <p>Vui lòng sắp xếp thời gian để trả {{ $type == 'device' ? 'thiết bị' : 'phòng' }} đúng hạn.</p>

            <p>Trân trọng,<br>Phòng Quản lý Tài sản</p>
        </div>
        <div class="footer">
            <p>Đây là email tự động, vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html> 