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
                        <a href="{{ route('borrow-room') }}" class="btn btn-primary">Đăng ký mượn mới</a>
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
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($roomBorrows as $roomBorrow)
                                        <tr>
                                            <td>{{ $roomBorrow->code }}</td>
                                            <td>{{ $roomBorrow->user->name }}</td>
                                            <td>{{ $roomBorrow->room->name }}</td>
                                            <td>{{ $roomBorrow->borrow_date }}</td>
                                            <td>{{ $roomBorrow->expected_return_date }}</td>
                                            <td>
                                                @switch($roomBorrow->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">Chờ duyệt</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge badge-success">Đã duyệt</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge badge-danger">Từ chối</span>
                                                        @break
                                                    @case('returned')
                                                        <span class="badge badge-info">Đã trả</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('room-borrows.show', $roomBorrow->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @role('admin')
                                                @if($roomBorrow->status == 'pending')
                                                    <form action="{{ route('room-borrows.approve', $roomBorrow->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('room-borrows.reject', $roomBorrow->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($roomBorrow->status == 'approved')
                                                    <a href="{{ route('room-borrows.return', $roomBorrow->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-undo"></i>
                                                    </a>
                                                @endif
                                                @endrole
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
