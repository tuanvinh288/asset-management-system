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
                                            <span class="badge badge-{{ 
                                                match($deviceItem->status) {
                                                    'available' => 'success',
                                                    'pending' => 'warning',
                                                    'in_use' => 'info',
                                                    'maintenance' => 'primary',
                                                    'broken' => 'danger',
                                                    default => 'secondary'
                                                }
                                            }}">
                                                {{ match($deviceItem->status) {
                                                    'available' => 'Có sẵn',
                                                    'pending' => 'Đang chờ',
                                                    'in_use' => 'Đang sử dụng',
                                                    'maintenance' => 'Bảo trì',
                                                    'broken' => 'Hỏng',
                                                    default => $deviceItem->status
                                                }}}
                                            </span>
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
                                                <option value="available" {{ $deviceItem->status == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                                <option value="pending" {{ $deviceItem->status == 'pending' ? 'selected' : '' }}>Đang chờ</option>
                                                <option value="in_use" {{ $deviceItem->status == 'in_use' ? 'selected' : '' }}>Đang sử dụng</option>
                                                <option value="maintenance" {{ $deviceItem->status == 'maintenance' ? 'selected' : '' }}>Bảo trì</option>
                                                <option value="broken" {{ $deviceItem->status == 'broken' ? 'selected' : '' }}>Hỏng</option>
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