@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ $borrow->return_date ? 'Chi tiết phiếu trả' : 'Chi tiết phiếu mượn' }}</h4>
                    <span class="ml-1">#{{ $borrow->id }}</span>
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
                                    <label class="text-label">Trạng thái</label>
                                    <p class="form-control-static">
                                        @php
                                            $statusConfig1 = [
                                                'pending' => ['color' => 'warning', 'text' => 'Chờ duyệt'],
                                                'approved' => ['color' => 'info', 'text' => 'Đã duyệt'],
                                                'borrowed' => ['color' => 'primary', 'text' => 'Đang mượn'],
                                                'returned' => ['color' => 'success', 'text' => 'Đã trả'],
                                                'cancelled' => ['color' => 'danger', 'text' => 'Đã hủy']
                                            ];
                                            $status = $statusConfig1[$borrow->status] ?? ['color' => 'secondary', 'text' => 'Không xác định'];
                                        @endphp
                                        <span class="badge badge-{{ $status['color'] }}">{{ $status['text'] }}</span>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-label">Ngày mượn</label>
                                    <p class="form-control-static">{{ $borrow->borrow_date->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-label">Ngày trả dự kiến</label>
                                    <p class="form-control-static">{{ $borrow->return_date ? $borrow->return_date->format('d/m/Y H:i') : 'Chưa trả' }}</p>
                                </div>
                            </div>
                            @if($borrow->status === 'returned')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-label">Ngày trả thực tế</label>
                                    <p class="form-control-static">{{ $borrow->updated_at ? $borrow->updated_at->format('d/m/Y H:i') : 'Chưa trả' }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="text-label">Lý do mượn</label>
                            <p class="form-control-static">{{ $borrow->reason }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                        @if($borrow->note)
                        <div class="form-group">
                            <label class="text-label">Ghi chú trước trả</label>
                            <p class="form-control-static">{{ $borrow->note }}</p>
                        </div>
                        @endif
                            </div>
                            <div class="col-md-6">
                                @if($borrow->return_note)
                                <div class="form-group">
                                    <label class="text-label">Ghi chú sau trả</label>
                                    <p class="form-control-static">{{ $borrow->return_note }}</p>
                                </div>
                                @endif
                                    </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Trạng thái thiết bị trước khi mượn</label>
                                    <p class="form-control-static">
                                        @php
                                            $statusConfig = [
                                                'new' => ['color' => 'success', 'text' => 'Mới'],
                                                'good' => ['color' => 'info', 'text' => 'Tốt'],
                                                'normal' => ['color' => 'warning', 'text' => 'Bình thường'],
                                                'damaged' => ['color' => 'danger', 'text' => 'Hỏng']
                                            ];
                                            $status = $statusConfig[$borrow->device_status_before] ?? ['color' => 'secondary', 'text' => 'Không xác định'];
                                        @endphp
                                        <span class="badge badge-{{ $status['color'] }}">{{ $status['text'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="text-label">Trạng thái thiết bị sau khi trả</label>
                                    <p class="form-control-static">
                                        @if($borrow->device_status_after)
                                            @php
                                                $status = $statusConfig[$borrow->device_status_after] ?? ['color' => 'secondary', 'text' => 'Không xác định'];
                                            @endphp
                                            <span class="badge badge-{{ $status['color'] }}">{{ $status['text'] }}</span>
                                        @else
                                            <span class="badge badge-secondary">Chưa trả</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                        @if($borrow->device_image_before)
                        <div class="form-group">
                            <label class="text-label">Ảnh thiết bị trước khi mượn</label>
                            <div>
                                <img src="{{ asset('storage/' . $borrow->device_image_before) }}" alt="Ảnh thiết bị trước khi mượn" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif
                        </div>
                        <div class="col-md-6">

                        @if($borrow->device_image_after)
                        <div class="form-group">
                            <label class="text-label">Ảnh thiết bị sau khi trả</label>
                            <div>
                                <img src="{{ asset('storage/' . $borrow->device_image_after) }}" alt="Ảnh thiết bị sau khi trả" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>
                        @endif
                        </div>
                        </div>

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
                            @role('admin')
                            @if($borrow->status === 'pending')
                                <form method="POST" action="{{ route('device-borrows.approve', $borrow->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-success">
                                        <i class="fa fa-check"></i> Duyệt
                                    </button>
                                </form>
                            @elseif($borrow->status === 'approved')
                                <form method="POST" action="{{ route('device-borrows.return', $borrow->id) }}" style="display:inline;">
                                    @csrf
                                    <button class="btn btn-primary">
                                        <i class="fa fa-undo"></i> Trả thiết bị
                                    </button>
                                </form>
                            @endif
                            @endrole
                            @if($borrow->status === 'pending')
                            <form method="POST" action="{{ route('device-borrows.cancel', $borrow->id) }}" style="display:inline;">
                                @csrf
                                <button class="btn btn-danger">
                                    <i class="fa fa-times"></i> Hủy
                                </button>
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