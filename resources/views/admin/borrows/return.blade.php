@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Trả thiết bị</h4>
                    <span class="ml-1">Xác nhận trả thiết bị</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin trả thiết bị</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('device-borrows.return.post', $borrow->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="text-label">Ngày trả *</label>
                                <input type="date" class="form-control" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Trạng thái thiết bị sau khi trả *</label>
                                <select class="form-control" name="device_status_after" required>
                                    <option value="new">Mới</option>
                                    <option value="good">Tốt</option>
                                    <option value="normal">Bình thường</option>
                                    <option value="damaged">Hư hỏng</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Ảnh thiết bị sau khi trả</label>
                                <input type="file" class="form-control" name="device_image_after" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label class="text-label">Ghi chú</label>
                                <textarea class="form-control" name="note" rows="2">{{ old('note') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Danh sách thiết bị trả</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã thiết bị</th>
                                                <th>Số serial</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($borrow->details as $detail)
                                        <tr>
                                            <td>{{ $detail->deviceItem->code }}</td>
                                            <td>{{ $detail->deviceItem->serial_number }}</td>
                                            <td>
                                            
                                                @php
                                            $statusConfig2 = [
                                                'available' => ['color' => 'success', 'text' => 'Sẵn sàng'],
                                                'pending' => ['color' => 'warning', 'text' => 'Đang chờ duyệt mượn'],
                                                'in_use' => ['color' => 'info', 'text' => 'Đang được mượn'],
                                                'maintenance' => ['color' => 'warning', 'text' => 'Đang bảo trì'],
                                                'broken' => ['color' => 'danger', 'text' => 'Không thể sử dụng'],
                                                'assigned' => ['color' => 'info', 'text' => 'Thiết bị đã cấp']
                                            ];

                                                    $status = $statusConfig2[$detail->deviceItem->status] ?? ['color' => 'secondary', 'text' => 'Không xác định'];
                                                @endphp
                                                <span class="badge badge-{{ $status['color'] }}">{{ $status['text'] }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <a href="{{ route('device-borrows.show', $borrow->id) }}" class="btn btn-secondary">Quay lại</a>
                                <button type="submit" class="btn btn-primary">Xác nhận trả thiết bị</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection