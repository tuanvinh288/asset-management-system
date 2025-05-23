@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết phòng</h4>
                    <span class="ml-1">{{ $room->name }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning mr-2">
                    <i class="fa fa-edit"></i> Sửa
                </a>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-primary mb-4">Thông tin cơ bản</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><strong>Tên phòng</strong></td>
                                        <td>{{ $room->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mã phòng</strong></td>
                                        <td>{{ $room->code }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Khoa</strong></td>
                                        <td>{{ $room->department->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái</strong></td>
                                        <td>
                                            @if($room->status == 'available')
                                                <span class="badge badge-success">Có sẵn</span>
                                            @else
                                                <span class="badge badge-danger">Đang sử dụng</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mô tả</strong></td>
                                        <td>{{ $room->description ?? 'Không có mô tả' }}</td>
                                    </tr>
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
