@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý khoa/phòng ban</h4>
                        <span class="ml-1">Danh sách khoa/phòng ban</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Khoa/phòng ban</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách khoa/phòng ban</h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createdepartmentModal">
                                <i class="fa fa-plus"></i> Thêm mới
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="25%">Tên khoa/phòng ban</th>
                                            <th width="40%">Mô tả</th>
                                            <th width="10%">Trạng thái</th>
                                            <th width="10%">Ngày tạo</th>
                                            <th class="text-center" width="10%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($departments as $key => $department)
                                            <tr>
                                                <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="font-weight-bold">{{ $department->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($department->description)
                                                        <span class="text-muted">{{ Str::limit($department->description, 50) }}</span>
                                                    @else
                                                        <span class="text-muted">Không có mô tả</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($department->status == 1)
                                                        <span class="badge badge-success px-3 py-2">Hoạt động</span>
                                                    @else
                                                        <span class="badge badge-danger px-3 py-2">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-calendar text-primary mr-2"></i>
                                                        <span class="text-success">{{ $department->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit-department mr-2" 
                                                            data-id="{{ $department->id }}" data-toggle="tooltip" title="Sửa">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-delete" 
                                                            data-id="{{ $department->id }}" data-toggle="tooltip" title="Xóa">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm khoa/phòng ban -->
    <div class="modal fade" id="createdepartmentModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="create-department-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm khoa/phòng ban mới</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Tên khoa/phòng ban <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Nhập tên khoa/phòng ban">
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Nhập mô tả khoa/phòng ban"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="" selected>--Chọn trạng thái--</option>
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                        <div id="form-error" class="text-danger small d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa khoa/phòng ban -->
    <div class="modal fade" id="editDeparmentModal" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="edit-deparment-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật khoa/phòng ban</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Tên khoa/phòng ban <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                placeholder="Nhập tên khoa/phòng ban">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mô tả</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"
                                placeholder="Nhập mô tả khoa/phòng ban"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                        <div id="edit-form-error" class="text-danger small d-none"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            // Khởi tạo tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Xử lý form thêm mới
            $('#create-department-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('departments.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createdepartmentModal').modal('hide');
                        $('#create-department-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm khoa/phòng ban thành công!');
                        location.reload();
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#form-error').removeClass('d-none').text(errorText);
                    }
                });
            });

            // Xử lý nút sửa
            $('.btn-edit-department').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/departments/${id}/edit`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#edit_id').val(response.data.id);
                            $('#edit_name').val(response.data.name);
                            $('#edit_description').val(response.data.description);
                            
                            // Kiểm tra và set giá trị status
                            if (response.data.status === 1 || response.data.status === 0) {
                                $('#edit_status').val(response.data.status);
                            } else {
                                // Nếu không phải 1/0 thì set mặc định là 1
                                $('#edit_status').val('1');
                            }
                            
                            $('#editDeparmentModal').modal('show');
                        } else {
                            toastr.error(response.message || 'Không lấy được dữ liệu khoa/phòng ban');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        toastr.error('Có lỗi xảy ra khi lấy dữ liệu khoa/phòng ban');
                    }
                });
            });

            // Xử lý form cập nhật
            $('#edit-deparment-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/departments/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editDeparmentModal').modal('hide');
                            toastr.success(response.message || 'Cập nhật thành công!');
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Có lỗi xảy ra!');
                        }
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#edit-form-error').removeClass('d-none').text(errorText);
                    }
                });
            });

            // Xử lý nút xóa
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                Swal.fire({
                    title: "Bạn có chắc chắn muốn xóa?",
                    text: "Hành động này không thể hoàn tác!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy",
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            $.ajax({
                                url: "/admin/departments/" + id,
                                type: "POST",
                                data: {
                                    _method: "DELETE",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    resolve(response);
                                },
                                error: function(xhr) {
                                    reject("Có lỗi xảy ra khi xóa khoa/phòng ban!");
                                }
                            });
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.value.success) {
                        Swal.fire("Đã xóa!", result.value.message, "success");
                        var row = $('button[data-id="' + id + '"]').closest('tr');
                        row.fadeOut(500, function() {
                            $(this).remove();
                        });
                    } else {
                        Swal.fire("Đã hủy", "Khoa/phòng ban không bị xóa", "info");
                    }
                });
            });
        });
    </script>
@endsection
