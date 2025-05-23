@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            @extends('admin.components.message')
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Danh sách thiết bị</h4>
                        <span class="ml-1">Quản lý thiết bị trường học</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Thiết bị</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <!-- Danh sách thiết bị -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách thiết bị</h4>
                            <a href="{{ route('devices.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-plus"></i> Thêm thiết bị
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="width: 80px;">Ảnh</th>
                                            <th style="min-width: 200px;">Tên thiết bị</th>
                                            <th style="min-width: 150px;">Danh mục</th>
                                            <th style="min-width: 200px;">Thông tin</th>
                                            <th style="width: 120px;">Ngày tạo</th>
                                            <th style="width: 120px;">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($devices as $key => $device)
                                            <tr>
                                                <td class="align-middle"><strong>{{ $key + 1 }}</strong></td>
                                                <td class="align-middle">
                                                    @if ($device->image)
                                                        <img src="{{ asset('storage/' . $device->image) }}"
                                                            alt="Ảnh thiết bị" width="60" height="60"
                                                            style="object-fit: cover; border-radius: 6px;">
                                                    @else
                                                        <span class="text-muted">Không có ảnh</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column">
                                                        <strong class="mb-1" style="font-size: 1.1rem;">{{ $device->name }}</strong>
                                                        <small class="text-muted" style="font-size: 0.9rem;">{{ $device->description ?? 'Không có mô tả' }}</small>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column">
                                                        <span style="font-size: 1rem;">{{ $device->category->name ?? 'Chưa có danh mục' }}</span>
                                                        <small class="text-muted" style="font-size: 0.9rem;">{{ $device->category->unit->name ?? '' }}</small>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column">
                                                        <div class="mb-2">
                                                            <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.5em 1em;">
                                                                Tổng: {{ $device->deviceItems->count() }} {{ $device->category->unit->name ?? '' }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="badge badge-info mr-2" style="font-size: 0.9rem; padding: 0.5em 1em;" data-toggle="tooltip" title="Số lượng đang được mượn">
                                                                @php
                                                                    $borrowCount = 0;
                                                                    foreach($device->deviceItems as $item) {
                                                                        if($item->borrowDetails && $item->borrowDetails->whereIn('borrow.status', ['pending', 'approved'])->count() > 0) {
                                                                            $borrowCount++;
                                                                        }
                                                                    }
                                                                @endphp
                                                                Đang mượn: {{ $borrowCount }}
                                                            </span>
                                                            <span class="badge badge-warning" style="font-size: 0.9rem; padding: 0.5em 1em;" data-toggle="tooltip" title="Số lượng đang bảo trì">
                                                                @php
                                                                    $maintenanceCount = 0;
                                                                    foreach($device->deviceItems as $item) {
                                                                        if($item->maintenances && $item->maintenances->whereIn('status', ['pending', 'in_progress'])->count() > 0) {
                                                                            $maintenanceCount++;
                                                                        }
                                                                    }
                                                                @endphp
                                                                Bảo trì: {{ $maintenanceCount }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex flex-column">
                                                        <span class="text-success" style="font-size: 1rem;">{{ $device->created_at->format('d/m/Y') }}</span>
                                                        <small class="text-muted" style="font-size: 0.9rem;">{{ $device->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <div class="d-flex">
                                                        <a href="{{ route('devices.show', $device->id) }}"
                                                            class="btn btn-sm btn-info mr-2" data-toggle="tooltip" title="Xem chi tiết">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('devices.edit', $device->id) }}"
                                                            class="btn btn-sm btn-warning mr-2" data-toggle="tooltip" title="Sửa thông tin">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger btn-delete"
                                                            data-id="{{ $device->id }}" data-toggle="tooltip" title="Xóa thiết bị">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Ảnh</th>
                                            <th>Tên thiết bị</th>
                                            <th>Danh mục</th>
                                            <th>Thông tin</th>
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
@section('js')
    <script>
        // Khởi tạo tooltip
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // SweetAlert2 confirmation with loading
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
                            url: "/admin/devices/" + id,
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
    </script>
@endsection
