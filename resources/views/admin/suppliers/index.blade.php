@extends('layouts.app')
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý nhà cung cấp</h4>
                        <span class="ml-1">Danh sách nhà cung cấp</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Nhà cung cấp</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách nhà cung cấp</h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createSupplierModal">
                                <i class="fa fa-plus"></i> Thêm nhà cung cấp
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th width="20%">Tên nhà cung cấp</th>
                                            <th width="25%">Địa chỉ</th>
                                            <th width="15%">SĐT</th>
                                            <th width="15%">Email</th>
                                            <th width="10%">Ngày tạo</th>
                                            <th class="text-center" width="10%">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $key => $supplier)
                                            <tr>
                                                <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="font-weight-bold">{{ $supplier->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-map-marker text-primary mr-2"></i>
                                                        <span class="text-muted">{{ Str::limit($supplier->address, 50) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-phone text-primary mr-2"></i>
                                                        <span class="text-muted">{{ $supplier->phone }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-envelope text-primary mr-2"></i>
                                                        <span class="text-muted">{{ $supplier->email }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fa fa-calendar text-primary mr-2"></i>
                                                        <span class="text-success">{{ $supplier->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-sm btn-warning btn-edit-supplier mr-2" 
                                                            data-id="{{ $supplier->id }}" data-toggle="tooltip" title="Sửa">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-delete" 
                                                            data-id="{{ $supplier->id }}" data-toggle="tooltip" title="Xóa">
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

    <!-- Modal Thêm nhà cung cấp -->
    <div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="create-supplier-form">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm nhà cung cấp mới</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Tên nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Nhập tên nhà cung cấp">
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address"
                                placeholder="Nhập địa chỉ">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="Nhập số điện thoại">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Nhập email">
                        </div>
                        <div class="form-group">
                            <label for="note">Ghi chú</label>
                            <textarea class="form-control" id="note" name="note" rows="3"
                                placeholder="Nhập ghi chú"></textarea>
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

    <!-- Modal Sửa nhà cung cấp -->
    <div class="modal fade" id="editsupplierModal" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="edit-supplier-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật nhà cung cấp</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Tên nhà cung cấp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                placeholder="Nhập tên nhà cung cấp">
                        </div>
                        <div class="form-group">
                            <label for="edit_address">Địa chỉ</label>
                            <input type="text" class="form-control" id="edit_address" name="address"
                                placeholder="Nhập địa chỉ">
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Số điện thoại</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone"
                                placeholder="Nhập số điện thoại">
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email"
                                placeholder="Nhập email">
                        </div>
                        <div class="form-group">
                            <label for="edit_note">Ghi chú</label>
                            <textarea class="form-control" id="edit_note" name="note" rows="3"
                                placeholder="Nhập ghi chú"></textarea>
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
            $('#create-supplier-form').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('suppliers.store') }}',
                    data: formData,
                    success: function(response) {
                        $('#createSupplierModal').modal('hide');
                        $('#create-supplier-form')[0].reset();
                        $('#form-error').addClass('d-none');
                        toastr.success('Thêm nhà cung cấp thành công!');
                        location.reload();
                    },
                    error: function(xhr) {
                        const errorText = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        $('#form-error').removeClass('d-none').text(errorText);
                    }
                });
            });

            // Xử lý nút sửa
            $('.btn-edit-supplier').on('click', function() {
                const id = $(this).data('id');
                const url = `/admin/suppliers/${id}/edit`;

                $.get(url, function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_address').val(data.address);
                    $('#edit_phone').val(data.phone);
                    $('#edit_email').val(data.email);
                    $('#edit_note').val(data.note);
                    $('#editsupplierModal').modal('show');
                }).fail(() => {
                    toastr.error('Không lấy được dữ liệu nhà cung cấp');
                });
            });

            // Xử lý form cập nhật
            $('#edit-supplier-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit_id').val();
                const url = `/admin/suppliers/${id}`;
                const formData = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editsupplierModal').modal('hide');
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
                                url: "/admin/suppliers/" + id,
                                type: "POST",
                                data: {
                                    _method: "DELETE",
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    resolve(response);
                                },
                                error: function(xhr) {
                                    reject("Có lỗi xảy ra khi xóa nhà cung cấp!");
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
                        Swal.fire("Đã hủy", "Nhà cung cấp không bị xóa", "info");
                    }
                });
            });
        });
    </script>
@endsection
