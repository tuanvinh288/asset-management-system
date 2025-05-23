@extends('layouts.app')

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Tạo phiếu mượn thiết bị</h4>
                        <span class="ml-1">Chọn thiết bị muốn mượn</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thông tin phiếu mượn</h4>
                        </div>
                        <div class="card-body">
                            {{-- Hiển thị thông báo lỗi --}}
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Lỗi!</strong> {{ session('error') }}
                                </div>
                            @endif

                            {{-- Hiển thị thông báo thành công --}}
                            @if (session('success'))
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

                            <form action="{{ route('device-borrows.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-label">Ngày mượn *</label>
                                            <input type="date" class="form-control" name="borrow_date"
                                                value="{{ old('borrow_date') }}" required>
                                            @if ($errors->has('borrow_date'))
                                                <span class="text-danger">{{ $errors->first('borrow_date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-label">Ngày trả dự kiến *</label>
                                            <input type="date" class="form-control" name="return_date"
                                                value="{{ old('return_date') }}" required>
                                            @if ($errors->has('return_date'))
                                                <span class="text-danger">{{ $errors->first('return_date') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Thiết bị *</label>
                                    <select class="form-control select2" name="device_id" id="device_id" required>
                                        <option value="">Chọn thiết bị</option>
                                        @foreach ($devices as $device)
                                            <option value="{{ $device->id }}"
                                                {{ old('device_id') == $device->id ? 'selected' : '' }}>
                                                {{ $device->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('device_id'))
                                        <span class="text-danger">{{ $errors->first('device_id') }}</span>
                                    @endif
                                </div>

                                <div class="form-group" id="device_items_container" style="display: none;">
                                    <label class="text-label">Chi tiết thiết bị *</label>
                                    <div id="device_items_list" class="row">
                                        <!-- Chi tiết thiết bị sẽ được tải qua AJAX -->
                                    </div>
                                    @if ($errors->has('device_items'))
                                        <span class="text-danger">{{ $errors->first('device_items') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Lý do mượn *</label>
                                    <textarea class="form-control" name="reason" rows="3" required>{{ old('reason') }}</textarea>
                                    @if ($errors->has('reason'))
                                        <span class="text-danger">{{ $errors->first('reason') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Ghi chú</label>
                                    <textarea class="form-control" name="note" rows="2">{{ old('note') }}</textarea>
                                    @if ($errors->has('note'))
                                        <span class="text-danger">{{ $errors->first('note') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Trạng thái thiết bị trước khi mượn *</label>
                                    <select class="form-control" name="device_status_before" required>
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="new"
                                            {{ old('device_status_before') == 'new' ? 'selected' : '' }}>Mới</option>
                                        <option value="good"
                                            {{ old('device_status_before') == 'good' ? 'selected' : '' }}>Tốt</option>
                                        <option value="normal"
                                            {{ old('device_status_before') == 'normal' ? 'selected' : '' }}>Bình thường
                                        </option>
                                        <option value="damaged"
                                            {{ old('device_status_before') == 'damaged' ? 'selected' : '' }}>Hư hỏng
                                        </option>
                                    </select>
                                    @if ($errors->has('device_status_before'))
                                        <span class="text-danger">{{ $errors->first('device_status_before') }}</span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Ảnh thiết bị trước khi mượn</label>
                                    <input type="file" class="form-control" name="device_image_before" accept="image/*">
                                    @if ($errors->has('device_image_before'))
                                        <span class="text-danger">{{ $errors->first('device_image_before') }}</span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">Tạo phiếu mượn</button>
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
            // Initialize select2
            $('.select2').select2();

            // Load device items when device is selected
            $('#device_id').on('change', function() {
                loadDeviceItems($(this).val());
            });

            // If there's a previously selected device (after validation failure), load its items
            const selectedDeviceId = $('#device_id').val();
            if (selectedDeviceId) {
                loadDeviceItems(selectedDeviceId);
            }

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
                                    <span class=\"device-checkbox\"><input class=\"form-check-input\" type=\"checkbox\" name=\"device_items[]\" value=\"${item.id}\" id=\"item_${item.id}\"></span>
                                    <label class=\"device-code\" for=\"item_${item.id}\">${item.code}</label>
                                    <span class=\"status-badge ${statusClass}\">${statusMap[item.status]}</span>
                                </div>`;
                            });
                            html += '</div>';
                        } else {
                            html = '<div class=\"alert alert-info\">Không có thiết bị con nào khả dụng.</div>';
                        }
                        $('#device_items_list').html(html);

                        // Re-check previously selected items
                        @if (old('device_items'))
                            var oldItems = @json(old('device_items'));
                            oldItems.forEach(function(itemId) {
                                $('#device_items_list input[value="' + itemId + '"]').prop('checked', true);
                            });
                        @endif
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

            // Load borrow details when viewing
            function loadBorrowDetails(borrowId) {
                $.ajax({
                    url: "/admin/borrows/" + borrowId + "/details",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            $('#borrowDetailsContainer').html(response.html);
                        } else {
                            console.error('Error loading borrow details:', response.message);
                            alert('Error loading borrow details: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading borrow details:', error);
                        alert('Error loading borrow details. Please try again.');
                    }
                });
            }

            // Trigger loading borrow details when modal is shown
            $('#borrowDetailsModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var borrowId = button.data('borrow-id');
                loadBorrowDetails(borrowId);
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
