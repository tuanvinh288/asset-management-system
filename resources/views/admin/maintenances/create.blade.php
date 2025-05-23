@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Tạo yêu cầu bảo trì mới</h4>
                    <span class="ml-1">Thêm yêu cầu bảo trì mới</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Thông tin yêu cầu bảo trì</h4>
                    </div>
                    <div class="card-body">
                        {{-- Hiển thị thông báo lỗi --}}
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Lỗi!</strong> {{ session('error') }}
                            </div>
                        @endif

                        {{-- Hiển thị thông báo thành công --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Thành công!</strong> {{ session('success') }}
                            </div>
                        @endif

                        {{-- Hiển thị lỗi validation --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('maintenances.store') }}" method="POST" id="createMaintenanceForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại thiết bị</label>
                                        <select name="device_id" id="device_id" class="form-control select2" required>
                                            <option value="">Chọn loại thiết bị</option>
                                            @foreach($devices as $device)
                                                <option value="{{ $device->id }}">{{ $device->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('device_id'))
                                        <span class="text-danger">{{ $errors->first('device_id') }}</span>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại bảo trì</label>
                                        <select name="type" class="form-control" required>
                                            <option value="repair">Sửa chữa</option>
                                            <option value="periodic">Bảo trì định kỳ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="device_items_container" style="display: none;">
                                <label>Thiết bị cần bảo trì</label>
                                <div id="device_items_list" class="row">
                                    <!-- Chi tiết thiết bị sẽ được tải qua AJAX -->
                                </div>
                                <input type="hidden" name="device_item_id" id="selected_device_item_id" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày bắt đầu</label>
                                        <input type="date" name="start_date" class="form-control" required min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group maintenance-interval-group" style="display: none;">
                                        <label>Khoảng thời gian bảo trì (tháng) <span class="text-danger">*</span></label>
                                        <input type="number" name="maintenance_interval" class="form-control" min="1" value="{{ old('maintenance_interval') }}" id="maintenanceInterval">
                                        @error('maintenance_interval')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Chi phí dự kiến</label>
                                <input type="number" name="cost" class="form-control" min="0">
                            </div>

                            <button type="submit" class="btn btn-primary">Tạo yêu cầu</button>
                            <a href="{{ route('maintenances.index') }}" class="btn btn-secondary">Quay lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Khởi tạo select2
        $('.select2').select2();

        // Hiển thị/ẩn trường khoảng thời gian bảo trì
        function toggleMaintenanceInterval(type) {
            const intervalGroup = $('.maintenance-interval-group');
            const intervalInput = $('#maintenanceInterval');
            
            if (type === 'periodic') {
                intervalGroup.show();
                intervalInput.prop('required', true);
            } else {
                intervalGroup.hide();
                intervalInput.prop('required', false);
                intervalInput.val(''); // Xóa giá trị khi chuyển sang loại khác
            }
        }

        // Xử lý khi thay đổi loại bảo trì
        $('select[name="type"]').change(function() {
            toggleMaintenanceInterval($(this).val());
        });

        // Khởi tạo trạng thái ban đầu
        toggleMaintenanceInterval($('select[name="type"]').val());

        // Load device items khi chọn loại thiết bị
        $('#device_id').on('change', function() {
            loadDeviceItems($(this).val());
        });

        // Function to load device items
        function loadDeviceItems(deviceId) {
                if (!deviceId) {
                    $('#device_items_container').hide();
                    return;
                }

                $.ajax({
                    url: `/admin/device-items/${deviceId}/json`,
                    method: 'GET',
                    beforeSend: function() {
                        $('#device_items_list').html(
                            '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Đang tải...</div>'
                        );
                        $('#device_items_container').show();
                    },
                    success: function(response) {
                        console.log('Response:', response);
                        let html = '';
                        if (response.device_items && response.device_items.length > 0) {
                            html = '<div class="device-items-container">';
                            response.device_items.forEach(function(item) {
                                const statusMap = {
                                    'available': 'Có sẵn',
                                    'in_use': 'Đang sử dụng',
                                    'maintenance': 'Đang bảo trì',
                                    'broken': 'Hư hỏng'
                                };
                                const statusClass = `status-${item.status}`;
                                html += `<div class=\"device-item\">
                                    <span class=\"device-checkbox\"><input class=\"form-check-input device-item-radio\" type=\"radio\" name=\"device_item_id\" value=\"${item.id}\" id=\"item_${item.id}\"></span>
                                    <label class=\"device-code\" for=\"item_${item.id}\">${item.code}</label>
                                    <span class=\"status-badge ${statusClass}\">${statusMap[item.status]}</span>
                                </div>`;
                            });
                            html += '</div>';
                        } else {
                            html = '<div class=\"alert alert-info\">Không có thiết bị con nào khả dụng.</div>';
                        }
                        $('#device_items_list').html(html);

                        // Khi chọn radio, cập nhật input hidden
                        $(document).on('change', '.device-item-radio', function() {
                            $('#selected_device_item_id').val($(this).val());
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Debug log
                        console.error('Status:', status); // Debug log
                        console.error('Response:', xhr.responseText); // Debug log
                        $('#device_items_list').html(
                            '<div class="alert alert-danger">Không thể tải danh sách thiết bị. Vui lòng thử lại sau.</div>'
                        );
                    }
                });
            }

        // Validate form trước khi submit
        $('#createMaintenanceForm').on('submit', function(e) {
            const selectedDeviceId = $('#selected_device_item_id').val();
            const maintenanceType = $('select[name="type"]').val();
            const maintenanceInterval = $('#maintenanceInterval').val();

            if (!selectedDeviceId) {
                e.preventDefault();
                toastr.error('Vui lòng chọn thiết bị cần bảo trì');
                return false;
            }

            if (maintenanceType === 'periodic') {
                if (!maintenanceInterval || maintenanceInterval < 1) {
                    e.preventDefault();
                    toastr.error('Vui lòng nhập khoảng thời gian bảo trì (tối thiểu 1 tháng)');
                    return false;
                }
            }
        });
    });
</script>
@endsection
@section('css')
<style>
    .device-items-container {
        max-width: 400px;
        margin: 0 auto 20px auto;
        padding: 24px 18px 18px 18px;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .device-item {
        display: flex;
        align-items: center;
        background: #f6f8fa;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(60,60,60,0.06);
        padding: 14px 18px;
        transition: box-shadow 0.2s, background 0.2s;
        border: 1.5px solid #e3e6ea;
        font-size: 1.15rem;
        font-weight: 500;
        gap: 18px;
    }
    .device-item:hover {
        background: #e3f2fd;
        box-shadow: 0 6px 18px rgba(33,150,243,0.10);
        border-color: #90caf9;
    }
    .device-checkbox {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }
    .device-code {
        font-size: 1.15rem;
        font-weight: 700;
        color: #222b45;
        letter-spacing: 1px;
        margin-right: 18px;
    }
    .status-badge {
        min-width: 90px;
        text-align: center;
        padding: 8px 0;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: inline-block;
    }
    .status-available {
        background: linear-gradient(90deg, #43a047 60%, #66bb6a 100%);
        color: #fff;
    }
    .status-in_use {
        background: linear-gradient(90deg, #1976d2 60%, #42a5f5 100%);
        color: #fff;
    }
    .status-maintenance {
        background: linear-gradient(90deg, #f57c00 60%, #ffb74d 100%);
        color: #fff;
    }
    .status-broken {
        background: linear-gradient(90deg, #d32f2f 60%, #ef5350 100%);
        color: #fff;
    }
</style>
@endsection
