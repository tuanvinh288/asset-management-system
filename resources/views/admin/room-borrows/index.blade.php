@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Danh sách mượn phòng</h4>
                    <span class="ml-1">Quản lý mượn trả phòng</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Danh sách mượn phòng</h4>
                        @role('admin')
                        <a href="{{ route('room-borrows.create') }}" class="btn btn-primary">Đăng ký mượn mới</a>
                        @endrole
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã phiếu</th>
                                        <th>Người mượn</th>
                                        <th>Phòng</th>
                                        <th>Ngày mượn</th>
                                        <th>Ngày trả dự kiến</th>
                                        <th>Ngày trả thực tế</th>
                                        <th>Trạng thái</th>
                                        <th>Người duyệt</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roomBorrows as $roomBorrow)
                                        <tr>
                                            <td>{{ $roomBorrow->id }}</td>
                                            <td>{{ $roomBorrow->user->name }}</td>
                                            <td>{{ $roomBorrow->room->name }}</td>
                                            <td>{{ $roomBorrow->borrow_date }}</td>
                                            <td>
                                                {{ $roomBorrow->return_date }}
                                                @if($roomBorrow->status == 'returned' && $roomBorrow->actual_return_date > $roomBorrow->return_date)
                                                    <span class="badge badge-danger">Trả muộn</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($roomBorrow->status == 'returned')
                                                    {{ $roomBorrow->actual_return_date }}
                                                @else
                                                    <span class="text-muted">Chưa trả</span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($roomBorrow->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">Chờ duyệt</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge badge-success">Đã duyệt</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge badge-danger">Từ chối</span>
                                                        @break
                                                    @case('returned')
                                                        <span class="badge badge-info">Đã trả</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($roomBorrow->staff)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm mr-2">
                                                            <div class="avatar-title rounded-circle bg-primary">
                                                                {{ substr($roomBorrow->staff->name, 0, 1) }}
                                                            </div>
                                                        </div>
                                                        <span>{{ $roomBorrow->staff->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('room-borrows.show', $roomBorrow->id) }}" class="btn btn-info btn-sm" title="Xem chi tiết phiếu mượn">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @role('admin')
                                                @if($roomBorrow->status == 'pending')
                                                    <form action="{{ route('room-borrows.approve', $roomBorrow->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" title="Duyệt phiếu mượn">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>

                                                @endif
                                                @if($roomBorrow->status == 'approved')
                                                    <form action="{{ route('room-borrows.return', $roomBorrow->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary btn-sm" title="Đánh dấu đã trả phòng">
                                                            <i class="fa fa-undo"></i>
                                                        </button>
                                                    </form>
                                                    @if($roomBorrow->return_date > now())
                                                        <button type="button" class="btn btn-warning btn-sm send-reminder" data-id="{{ $roomBorrow->id }}" title="Gửi thông báo nhắc trả phòng">
                                                            <i class="fas fa-bell"></i>Thông báo
                                                        </button>
                                                    @endif
                                                @endif
                                                @endrole
                                                @if($roomBorrow->status == 'pending')
                                                <form action="{{ route('room-borrows.cancel', $roomBorrow->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Huỷ phiếu mượn">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$(document).ready(function() {
    // Xử lý gửi thông báo
    $('.send-reminder').click(function() {
        debugger;
        const button = $(this);
        const borrowId = button.data('id');

        // Vô hiệu hóa nút trong khi đang xử lý
        button.prop('disabled', true);

        $.ajax({
            url: `/admin/room-borrows/${borrowId}/send-reminder`,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                debugger;
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Có lỗi xảy ra khi gửi thông báo');
            },
            complete: function() {
                // Kích hoạt lại nút sau khi xử lý xong
                button.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection