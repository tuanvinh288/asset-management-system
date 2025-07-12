@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chi tiết thiết bị</h4>
                    <span class="ml-1">{{ $device->name }}</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('devices.index') }}">Thiết bị</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            @if ($device->image)
                                <img src="{{ asset('storage/' . $device->image) }}" alt="Ảnh thiết bị"
                                    class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="rounded bg-light p-4">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                    <p class="mt-2 mb-0">Không có ảnh</p>
                                </div>
                            @endif
                        </div>

                        <div class="profile-info">
                            <h4 class="text-primary mb-4">Thông tin cơ bản</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td><strong>Tên thiết bị</strong></td>
                                            <td>{{ $device->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Danh mục</strong></td>
                                            <td>{{ $device->category->name ?? 'Chưa có danh mục' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Đơn vị tính</strong></td>
                                            <td>{{ $device->category->unit->name ?? 'Chưa có đơn vị' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mô tả</strong></td>
                                            <td>{{ $device->description ?? 'Không có mô tả' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ngày tạo</strong></td>
                                            <td>{{ $device->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách các thiết bị con</h4>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDeviceItemModal">
                            Thêm thiết bị con
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Mã thiết bị</th>
                                        <th>Tình trạng</th>
                                        <th>Ngày tạo</th>
                                        <th style="width: 120px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($device_parts as $key => $part)
                                    <tr>
                                        <td><strong>{{ $key + 1 }}</strong></td>
                                        <td>{{ $part->code }}</td>

                                        <td>
                                            @if($part->status == 'available')
                                                <span class="badge badge-success">Sẵn sàng</span>
                                            @elseif($part->status == 'pending')
                                                <span class="badge badge-warning">Đang chờ duyệt mượn</span>
                                            @elseif($part->status == 'in_use')
                                                <span class="badge badge-info">Đang được mượn</span>
                                            @elseif($part->status == 'maintenance')
                                                <span class="badge badge-warning">Đang bảo trì</span>
                                            @elseif($part->status == 'broken')
                                                <span class="badge badge-danger">Không thể sử dụng</span>
                                            @elseif($part->status == 'assigned')
                                                <span class="badge badge-info">Đã cấp phát</span>
                                            @endif
                                        </td>
                                        <td>{{ $part->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="d-flex">
                                                @if($part->status == 'available')
                                                <a href="{{ route('device-items.show', $part->id) }}" class="btn btn-success btn-sm mr-1" title="Cấp phát thiết bị">
                                                    <i class="fa fa-user-plus"></i> Cấp phát
                                                </a>
                                                @endif
                                                <a href="{{ route('device-items.edit', $part->id) }}"
                                                   class="btn btn-warning btn-sm mr-1 edit-device-item"
                                                   data-toggle="modal"
                                                   data-target="#editDeviceItemModal"
                                                   data-id="{{ $part->id }}"
                                                   data-code="{{ $part->code }}"
                                                   data-serial-number="{{ $part->serial_number }}"
                                                   data-status="{{ $part->status }}"
                                                   data-room-id="{{ $part->room_id }}"
                                                   data-supplier-id="{{ $part->supplier_id }}"
                                                   data-is-fixed="{{ $part->is_fixed }}"
                                                   data-qr-code="{{ $part->qr_code }}"
                                                   data-qr-token="{{ $part->qr_token }}"
                                                   data-last-scanned-at="{{ $part->last_scanned_at }}"
                                                   data-created-at="{{ $part->created_at }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('device-items.destroy', $part->id) }}" method="POST" style="display:inline-block;">
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
                                        <td colspan="6" class="text-center">Chưa có thiết bị chi tiết nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Thêm thiết bị con -->
        <div class="modal fade" id="addDeviceItemModal" tabindex="-1" role="dialog" aria-labelledby="addDeviceItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('device-items.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="device_id" value="{{ $device->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Thêm thiết bị con</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered" id="device-item-table">
                                <thead>
                                    <tr>
                                        <th>Mã thiết bị</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="items[0][code]" class="form-control" required></td>
                                        <td>
                                            <select name="items[0][status]" class="form-control">
                                                <option value="available">Có sẵn</option>
                                                <option value="pending">Đang chờ</option>
                                                <option value="in_use">Đang sử dụng</option>
                                                <option value="maintenance">Bảo trì</option>
                                                <option value="broken">Hỏng</option>
                                            </select>
                                        </td>
                                        <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addRow">+ Thêm dòng</button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu thiết bị con</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Sửa thiết bị con -->
        <div class="modal fade" id="editDeviceItemModal" tabindex="-1" role="dialog" aria-labelledby="editDeviceItemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="editDeviceItemForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cập nhật thiết bị con</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mã thiết bị</label>
                                        <input type="text" name="code" class="form-control" id="edit-code" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Số serial</label>
                                        <input type="text" name="serial_number" class="form-control" id="edit-serial-number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Trạng thái</label>
                                        <select name="status" class="form-control" id="edit-status">
                                            <option value="available">Có sẵn</option>
                                            <option value="pending">Đang chờ</option>
                                            <option value="in_use">Đang sử dụng</option>
                                            <option value="maintenance">Bảo trì</option>
                                            <option value="broken">Hỏng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nhà cung cấp</label>
                                        <select name="supplier_id" class="form-control" id="edit-supplier-id">
                                            <option value="">Chọn nhà cung cấp</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function(){
        // Khởi tạo tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // Xử lý thêm dòng trong modal thêm mới
        let index = 1;
        $('#addRow').click(function() {
            const tableBody = $('#device-item-table tbody');
            const newRow = `
                <tr>
                    <td><input type="text" name="items[${index}][code]" class="form-control" required></td>
                    <td>
                        <select name="items[${index}][status]" class="form-control">
                            <option value="available">Có sẵn</option>
                            <option value="pending">Đang chờ</option>
                            <option value="in_use">Đang sử dụng</option>
                            <option value="maintenance">Bảo trì</option>
                            <option value="broken">Hỏng</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                </tr>
            `;
            tableBody.append(newRow);
            index++;
        });

        // Xử lý xóa dòng
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });

        // Xử lý modal sửa
        $('.edit-device-item').click(function() {
            var id = $(this).data('id');
            var code = $(this).data('code');
            var serialNumber = $(this).data('serial-number');
            var status = $(this).data('status');

            // Set giá trị cho form
            $('#editDeviceItemForm').attr('action', `/admin/device-items/${id}`);
            $('#edit-code').val(code);
            $('#edit-serial-number').val(serialNumber);
            $('#edit-status').val(status);
        });

        // Xử lý submit form
        $('#editDeviceItemForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        $('#editDeviceItemModal').modal('hide');
                        // Hiển thị thông báo thành công
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';
                    if (errors) {
                        for(var key in errors) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    } else {
                        errorMessage = xhr.responseJSON.message || 'Có lỗi xảy ra';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: errorMessage
                    });
                }
            });
        });
    });
</script>
@endsection
