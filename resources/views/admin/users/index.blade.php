@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Hi, welcome back!</h4>
                    <span class="ml-1">Danh sách tài khoản</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Tài khoản</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                </ol>
            </div>
        </div>

        <!-- Danh sách tài khoản -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách tài khoản</h4>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Thêm tài khoản
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Khoa</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td><strong>{{ $key + 1 }}</strong></td>
                                            <td><span class="text-primary font-weight-bold">{{ $user->name }}</span></td>
                                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                            <td>
                                                {{$user->role}}
                                                @if($user->role === 'admin')
                                                    <span class="badge badge-danger">Admin</span>
                                                @elseif($user->role === 'teacher')
                                                    <span class="badge badge-warning">Manager</span>
                                                @else
                                                    <span class="badge badge-secondary">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->department)
                                                    <span class="badge badge-info">{{ $user->department->name }}</span>
                                                @else
                                                    <span class="text-muted">Chưa có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-success">{{ $user->created_at->format('d/m/Y') }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Sửa
                                                </a>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                                        <i class="fa fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Vai trò</th>
                                        <th>Khoa</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end .container-fluid -->
</div>
@endsection
