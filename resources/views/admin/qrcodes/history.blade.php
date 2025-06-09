@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Lịch sử quét QR Code</h4>
                    <span class="ml-1">{{ $deviceItem->device->name }} - {{ $deviceItem->code }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('qrcode.show', $deviceItem->id) }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Người dùng</th>
                                        <th>Hành động</th>
                                        <th>Trạng thái cũ</th>
                                        <th>Trạng thái mới</th>
                                        <th>Ghi chú</th>
                                        <th>Thiết bị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($scans as $scan)
                                        <tr>
                                            <td>{{ $scan->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $scan->user ? $scan->user->name : 'Không đăng nhập' }}</td>
                                            <td>
                                                @switch($scan->action)
                                                    @case('view')
                                                        <span class="badge badge-info">Xem</span>
                                                        @break
                                                    @case('update_status')
                                                        <span class="badge badge-primary">Cập nhật trạng thái</span>
                                                        @break
                                                    @default
                                                        <span class="badge badge-secondary">{{ $scan->action }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($scan->old_status)
                                                    <span class="badge badge-{{ 
                                                        match($scan->old_status) {
                                                            'available' => 'success',
                                                            'pending' => 'warning',
                                                            'in_use' => 'info',
                                                            'maintenance' => 'primary',
                                                            'broken' => 'danger',
                                                            default => 'secondary'
                                                        }
                                                    }}">
                                                        {{ match($scan->old_status) {
                                                            'available' => 'Có sẵn',
                                                            'pending' => 'Đang chờ',
                                                            'in_use' => 'Đang sử dụng',
                                                            'maintenance' => 'Bảo trì',
                                                            'broken' => 'Hỏng',
                                                            default => $scan->old_status
                                                        }}}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($scan->new_status)
                                                    <span class="badge badge-{{ 
                                                        match($scan->new_status) {
                                                            'available' => 'success',
                                                            'pending' => 'warning',
                                                            'in_use' => 'info',
                                                            'maintenance' => 'primary',
                                                            'broken' => 'danger',
                                                            default => 'secondary'
                                                        }
                                                    }}">
                                                        {{ match($scan->new_status) {
                                                            'available' => 'Có sẵn',
                                                            'pending' => 'Đang chờ',
                                                            'in_use' => 'Đang sử dụng',
                                                            'maintenance' => 'Bảo trì',
                                                            'broken' => 'Hỏng',
                                                            default => $scan->new_status
                                                        }}}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $scan->notes ?: '-' }}</td>
                                            <td>
                                                <small>
                                                    IP: {{ $scan->ip_address }}<br>
                                                    UA: {{ Str::limit($scan->user_agent, 30) }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Chưa có lịch sử quét</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $scans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 