@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>QR Code Thiết bị</h4>
                    <span class="ml-1">{{ $deviceItem->device->name }} - {{ $deviceItem->code }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('device-items.index') }}" class="btn btn-secondary ml-2">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
                <form action="{{ route('qrcode.regenerate', $deviceItem->id) }}" method="POST" class="ml-2">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-sync"></i> Tạo lại QR Code
                    </button>
                </form>
                <a href="{{ route('qrcode.history', $deviceItem->id) }}" class="btn btn-info ml-2">
                    <i class="fa fa-history"></i> Lịch sử quét
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Thông tin thiết bị</h4>
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
                                    <tr>
                                        <th>Lần quét cuối:</th>
                                        <td>{{ $deviceItem->last_scanned_at ? $deviceItem->last_scanned_at->format('d/m/Y H:i:s') : 'Chưa quét' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6 text-center">
                                <h4>QR Code</h4>
                                @if($deviceItem->qr_code)
                                    <img src="{{ Storage::url($deviceItem->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                                    <div class="mt-3">
                                        <a href="{{ Storage::url($deviceItem->qr_code) }}" download class="btn btn-success">
                                            <i class="fa fa-download"></i> Tải xuống
                                        </a>
                                        <button type="button" class="btn btn-primary" onclick="window.print()">
                                            <i class="fa fa-print"></i> In
                                        </button>
                                    </div>
                                @else
                                    <p>Chưa có QR Code</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 