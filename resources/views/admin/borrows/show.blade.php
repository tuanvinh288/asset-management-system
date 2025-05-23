@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết phiếu mượn</h4>
                    <span class="ml-1">Thông tin chi tiết phiếu mượn thiết bị</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin phiếu mượn</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Người mượn</label>
                                    <p class="form-control-static">{{ $borrow->user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Ngày mượn</label>
                                    <p class="form-control-static">{{ $borrow->borrow_date }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Ngày trả</label>
                                    <p class="form-control-static">{{ $borrow->return_date ?? 'Chưa trả' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Trạng thái</label>
                                    <p class="form-control-static">
                                        @php
                                            $color = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'borrowed' => 'primary',
                                                'returned' => 'success',
                                                'cancelled' => 'danger'
                                            ][$borrow->status];
                                        @endphp
                                        <span class="badge badge-{{ $color }}">{{ ucfirst($borrow->status) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="text-label">Lý do mượn</label>
                            <p class="form-control-static">{{ $borrow->reason }}</p>
                        </div>

                        @if($borrow->note)
                        <div class="form-group">
                            <label class="text-label">Ghi chú</label>
                            <p class="form-control-static">{{ $borrow->note }}</p>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Trạng thái thiết bị trước khi mượn</label>
                                    <p class="form-control-static">{{ ucfirst($borrow->device_status_before) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Trạng thái thiết bị sau khi trả</label>
                                    <p class="form-control-static">{{ $borrow->device_status_after ? ucfirst($borrow->device_status_after) : 'Chưa trả' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($borrow->device_image_before)
                        <div class="form-group">
                            <label class="text-label">Ảnh thiết bị trước khi mượn</label>
                            <div>
                                <img src="{{ asset('storage/' . $borrow->device_image_before) }}" alt="Ảnh thiết bị trước khi mượn" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif

                        @if($borrow->device_image_after)
                        <div class="form-group">
                            <label class="text-label">Ảnh thiết bị sau khi trả</label>
                            <div>
                                <img src="{{ asset('storage/' . $borrow->device_image_after) }}" alt="Ảnh thiết bị sau khi trả" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="text-label">Danh sách thiết bị mượn</label>
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
                                        @foreach($borrow->deviceItems as $item)
                                        <tr>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->serial_number }}</td>
                                            <td>
                                                @switch($item->status)
                                                    @case('available')
                                                        <span class="badge badge-success">Có sẵn</span>
                                                        @break
                                                    @case('borrowed')
                                                        <span class="badge badge-warning">Đang mượn</span>
                                                        @break
                                                    @case('damaged')
                                                        <span class="badge badge-danger">Hỏng</span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="badge badge-info">Bảo trì</span>
                                                        @break
                                                @endswitch
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('borrows.index') }}" class="btn btn-secondary">Quay lại</a>
                            @if($borrow->status === 'pending')
                                <form method="POST" action="{{ route('borrows.approve', $borrow->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success">Duyệt</button>
                                </form>
                                <form method="POST" action="{{ route('borrows.cancel', $borrow->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-danger">Hủy</button>
                                </form>
                            @elseif($borrow->status === 'approved')
                                <form method="POST" action="{{ route('borrows.return', $borrow->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-secondary">Trả thiết bị</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 