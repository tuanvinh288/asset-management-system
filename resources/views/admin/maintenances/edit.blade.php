@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Chỉnh sửa yêu cầu bảo trì</h4>
                    <span class="ml-1">Cập nhật thông tin bảo trì</span>
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

                        <form action="{{ route('maintenances.update', $maintenance) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Thiết bị</label>
                                        <input type="text" class="form-control" value="{{ $maintenance->deviceItem->device->name }} ({{ $maintenance->deviceItem->code }})" readonly>
                                        <input type="hidden" name="device_item_id" value="{{ $maintenance->device_item_id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Loại bảo trì</label>
                                        <select name="type" class="form-control" required>
                                            <option value="repair" {{ $maintenance->type === 'repair' ? 'selected' : '' }}>Sửa chữa</option>
                                            <option value="periodic" {{ $maintenance->type === 'periodic' ? 'selected' : '' }}>Bảo trì định kỳ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ngày bắt đầu</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ $maintenance->start_date->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Khoảng thời gian bảo trì (tháng)</label>
                                        <input type="number" name="maintenance_interval" class="form-control" min="1" id="maintenanceInterval" value="{{ $maintenance->maintenance_interval }}" {{ $maintenance->type === 'periodic' ? '' : 'style="display: none;"' }}>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control" rows="3" required>{{ $maintenance->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Chi phí dự kiến</label>
                                <input type="number" name="cost" class="form-control" min="0" value="{{ $maintenance->cost }}">
                            </div>

                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="status" class="form-control" required>
                                    <option value="pending" {{ $maintenance->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="in_progress" {{ $maintenance->status === 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="completed" {{ $maintenance->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ $maintenance->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kết quả</label>
                                <textarea name="result" class="form-control" rows="3">{{ $maintenance->result }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật</button>
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
        // Hiển thị/ẩn trường khoảng thời gian bảo trì
        $('select[name="type"]').change(function() {
            if ($(this).val() === 'periodic') {
                $('#maintenanceInterval').show();
            } else {
                $('#maintenanceInterval').hide();
            }
        });
    });
</script>
@endsection
