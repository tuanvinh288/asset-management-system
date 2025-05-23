@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Danh sách phòng</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Thêm phòng mới
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên phòng</th>
                                        <th>Mã phòng</th>
                                        <th>Khoa</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rooms as $room)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $room->name }}</td>
                                        <td>{{ $room->code }}</td>
                                        <td>{{ $room->department->name }}</td>
                                        <td>
                                            @if($room->status == 'available')
                                                <span class="badge badge-success">Có sẵn</span>
                                            @else
                                                <span class="badge badge-danger">Đang sử dụng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('rooms.show', $room->id) }}" class="btn btn-info btn-sm mr-1">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning btn-sm mr-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có phòng nào</td>
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
