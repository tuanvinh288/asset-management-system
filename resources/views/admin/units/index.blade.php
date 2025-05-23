@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý đơn vị tính</h4>
                        <span class="ml-1">Danh sách đơn vị tính</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Đơn vị tính</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách đơn vị tính</h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createdunitModal">
                                <i class="fa fa-plus"></i> Thêm đơn vị tính
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="15%">Mã đơn vị</th>
                                            <th width="20%">Tên đơn vị</th>
                                            <th>Mô tả</th>
                                            <th width="12%">Ngày tạo</th>
                                            <th class="text-center" width="15%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($units as $key => $unit)
                                            <tr>
                                                <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                                <td>
                                                    <span class="badge badge-primary px-3 py-2">{{ $unit->code }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                    
                                                        <span class="font-weight-bold">{{ $unit->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($unit->description)
                                                        <span class="text-muted">{{ Str::limit($unit->description, 100) }}</span>
                                                    @else
                                                        <span class="text-muted">Không có mô tả</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-calendar text-primary mr-2"></i>
                                                        <span class="text-success">{{ $unit->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit-unit mr-2" 
                                                            data-id="{{ $unit->id }}" data-toggle="tooltip" title="Sửa">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-delete" 
                                                            data-id="{{ $unit->id }}" data-toggle="tooltip" title="Xóa">
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

    <!-- Modal Thêm đơn vị tính -->
    <div class="modal fade" id="createdunitModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="create-unit-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm đơn vị tính mới</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="code">Mã đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required
                                placeholder="Nhập mã đơn vị tính">
                        </div>
                        <div class="form-group">
                            <label for="name">Tên đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Nhập tên đơn vị tính">
                        </div>
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                placeholder="Nhập mô tả đơn vị tính"></textarea>
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

    <!-- Modal Sửa đơn vị tính -->
    <div class="modal fade" id="editunitModal" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="edit-unit-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật đơn vị tính</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_code">Mã đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_code" name="code" required
                                placeholder="Nhập mã đơn vị tính">
                        </div>
                        <div class="form-group">
                            <label for="edit_name">Tên đơn vị tính <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                placeholder="Nhập tên đơn vị tính">
                        </div>
                        <div class="form-group">
                            <label for="edit_description">Mô tả</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"
                                placeholder="Nhập mô tả đơn vị tính"></textarea>
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
            // Khởi tạo DataTable

            // Khởi tạo tooltip
            $('[data-toggle="tooltip"]').tooltip();

            // Xử lý form thêm mới
            $('#create-unit-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('units.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createdunitModal').modal('hide');
                        $('#create-unit-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm đơn vị tính thành công!');
                        location.reload();
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#form-error').removeClass('d-none').text(errorText);
                    }
                });
            });

            // Xử lý nút sửa
            $('.btn-edit-unit').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/units/${id}/edit`;

                $.get(url, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_description').val(data.description);
                    $('#edit_code').val(data.code);
                    $('#editunitModal').modal('show');
                }).fail(() => {
                    toastr.error('Không lấy được dữ liệu đơn vị tính');
                });
            });

            // Xử lý form cập nhật
            $('#edit-unit-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/units/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editunitModal').modal('hide');
                        toastr.success('Cập nhật thành công!');
                        location.reload();
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
                                url: "/admin/units/" + id,
                                type: "POST",
                                data: {
                                    _method: "DELETE",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    resolve(response);
                                },
                                error: function(xhr) {
                                    reject("Có lỗi xảy ra khi xóa đơn vị tính!");
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
                        Swal.fire("Đã hủy", "Đơn vị tính không bị xóa", "info");
                    }
                });
            });
        });
    </script>
@endsection
