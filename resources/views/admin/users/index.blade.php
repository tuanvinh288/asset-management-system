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

        <!-- Nút mở modal import -->
        <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#importModal">
            <i class="fa fa-file-excel-o"></i> Import Excel
        </button>

        <!-- Modal Import Excel -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Giảng viên từ Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                  <div class="form-group">
                    <label for="file">Chọn file Excel (.xlsx, .xls)</label>
                    <input type="file" name="file" class="form-control" required accept=".xlsx,.xls">
                  </div>
                </div>
                <div class="modal-footer">
                  <a href="/admin/template_excel/import_excel.xlsx" class="btn btn-info" download>
                    <i class="fa fa-download"></i> Tải file mẫu
                  </a>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                  <button type="submit" class="btn btn-success">Import Excel</button>
                </div>
              </form>
            </div>
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
                                                @foreach($user->roles as $role)
                                                    @if($role->name === 'admin')
                                                        <span class="badge badge-danger">Quản trị viên</span>
                                                    @elseif($role->name === 'teacher')
                                                        <span class="badge badge-warning">Giảng viên</span>
                                                    @elseif($role->name === 'student')
                                                        <span class="badge badge-info">Sinh viên</span>
                                                    @endif
                                                @endforeach
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
                                                @if($user->hasRole('teacher'))
                                                    <a href="{{ route('users.assignedDevices', $user->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-laptop"></i> Thiết bị đã cấp
                                                    </a>
                                                @endif
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
