@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thông tin thiết bị</h4>
                    <span class="ml-1">{{ $deviceItem->device->name }} - {{ $deviceItem->code }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Chi tiết thiết bị</h4>
                                <table class="table">
                                    <tr>
                                        <th>Tên thiết bị:</th>
                                        <td>{{ $deviceItem->device->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mã thiết bị:</th>
                                        <td>{{ $deviceItem->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>Trạng thái:</th>
                                        <td>
                                            @switch($deviceItem->status)
                                                @case('available')
                                                    <span class="badge badge-success">Sẵn sàng</span>
                                                    @break
                                                @case('borrowed')
                                                    <span class="badge badge-warning">Đang mượn</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge badge-info">Đang bảo trì</span>
                                                    @break
                                                @case('damaged')
                                                    <span class="badge badge-danger">Hỏng</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $deviceItem->status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                @auth
                                    <h4>Cập nhật trạng thái</h4>
                                    <form action="{{ route('qrcode.update-status', $deviceItem->qr_token) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Trạng thái mới</label>
                                            <select name="status" class="form-control" required>
                                                <option value="available" {{ $deviceItem->status == 'available' ? 'selected' : '' }}>Sẵn sàng</option>
                                                <option value="borrowed" {{ $deviceItem->status == 'borrowed' ? 'selected' : '' }}>Đang mượn</option>
                                                <option value="maintenance" {{ $deviceItem->status == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                                <option value="damaged" {{ $deviceItem->status == 'damaged' ? 'selected' : '' }}>Hỏng</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Ghi chú</label>
                                            <textarea name="notes" class="form-control" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Vui lòng đăng nhập để cập nhật trạng thái thiết bị.
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 