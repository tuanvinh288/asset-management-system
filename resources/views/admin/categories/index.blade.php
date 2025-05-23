@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý danh mục</h4>
                        <span class="ml-1">Danh sách danh mục</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Danh mục</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách danh mục</h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createdCategoryModal">
                                <i class="fa fa-plus"></i> Thêm danh mục
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="20%">Tên danh mục</th>
                                            <th width="25%">Mô tả</th>
                                            <th width="15%">Đơn vị tính</th>
                                            <th width="10%">Trạng thái</th>
                                            <th width="10%">Ngày tạo</th>
                                            <th class="text-center" width="15%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $key => $category)
                                            <tr>
                                                <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="font-weight-bold">{{ $category->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($category->description)
                                                        <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
                                                    @else
                                                        <span class="text-muted">Không có mô tả</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-cube text-primary mr-2"></i>
                                                        <span class="text-muted">{{ $category->unit->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($category->status == 'active')
                                                        <span class="badge badge-success px-3 py-2">Hoạt động</span>
                                                    @else
                                                        <span class="badge badge-danger px-3 py-2">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-calendar text-primary mr-2"></i>
                                                        <span class="text-success">{{ $category->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit-category mr-2" 
                                                            data-id="{{ $category->id }}" data-toggle="tooltip" title="Sửa">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-delete" 
                                                            data-id="{{ $category->id }}" data-toggle="tooltip" title="Xóa">
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

    <!-- Modal Thêm danh mục -->
    <div class="modal fade" id="createdCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="create-category-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm danh mục mới</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Nhập tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Nhập mô tả danh mục"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="unit_id">Đơn vị tính <span class="text-danger">*</span></label>
                            <select class="form-control" name="unit_id" id="unit_id" required>
                                <option value="" selected>--Chọn đơn vị tính--</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="" selected>--Chọn trạng thái--</option>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
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

    <!-- Modal Sửa danh mục -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="edit-category-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật danh mục</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                placeholder="Nhập tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mô tả</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"
                                placeholder="Nhập mô tả danh mục"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit_unit_id">Đơn vị tính <span class="text-danger">*</span></label>
                            <select class="form-control" name="unit_id" id="edit_unit_id" required>
                                <option value="" selected>--Chọn đơn vị tính--</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_status">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
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
            $('#create-category-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('categories.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createdCategoryModal').modal('hide');
                        $('#create-category-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm danh mục thành công!');
                        location.reload();
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#form-error').removeClass('d-none').text(errorText);
                    }
                });
            });

            // Xử lý nút sửa
            $('.btn-edit-category').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/categories/${id}/edit`;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#edit_id').val(response.data.id);
                            $('#edit_name').val(response.data.name);
                            $('#edit_description').val(response.data.description);
                            $('#edit_unit_id').val(response.data.unit_id);
                            
                            // Kiểm tra và set giá trị status
                            if (response.data.status === 'active' || response.data.status === 'inactive') {
                                $('#edit_status').val(response.data.status);
                            } else {
                                // Nếu không phải active/inactive thì set mặc định là active
                                $('#edit_status').val('active');
                            }
                            
                            $('#editCategoryModal').modal('show');
                        } else {
                            toastr.error(response.message || 'Không lấy được dữ liệu danh mục');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        toastr.error('Có lỗi xảy ra khi lấy dữ liệu danh mục');
                    }
                });
            });

            // Xử lý form cập nhật
            $('#edit-category-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/categories/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#editCategoryModal').modal('hide');
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
                                url: "/admin/categories/" + id,
                                type: "POST",
                                data: {
                                    _method: "DELETE",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    resolve(response);
                                },
                                error: function(xhr) {
                                    reject("Có lỗi xảy ra khi xóa danh mục!");
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
                        Swal.fire("Đã hủy", "Danh mục không bị xóa", "info");
                    }
                });
            });
        });
    </script>
@endsection
