@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            @extends('admin.components.message')

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Quản lý bảo trì thiết bị</h4>
                        <span class="ml-1">Quản lý yêu cầu bảo trì & sửa chữa thiết bị</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="widget-stat card bg-primary">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3">
                                    <i class="fa fa-tools"></i>
                                </span>
                                <div class="media-body text-white">
                                    <p class="mb-1">Tổng số yêu cầu bảo trì</p>
                                    <h3 class="text-white">{{ $maintenances->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="widget-stat card bg-warning">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </span>
                                <div class="media-body text-white">
                                    <p class="mb-1">Yêu cầu quá hạn</p>
                                    <h3 class="text-white">{{ $overdueMaintenances }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="widget-stat card bg-info">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3">
                                    <i class="fa fa-clock"></i>
                                </span>
                                <div class="media-body text-white">
                                    <p class="mb-1">Đang bảo trì</p>
                                    <h3 class="text-white">{{ $inProgressMaintenances }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-3 col-sm-6">
                    <div class="widget-stat card bg-success">
                        <div class="card-body">
                            <div class="media">
                                <span class="mr-3">
                                    <i class="fa fa-check-circle"></i>
                                </span>
                                <div class="media-body text-white">
                                    <p class="mb-1">Đã hoàn thành</p>
                                    <h3 class="text-white">{{ $maintenances->where('status', 'completed')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Chi phí bảo trì theo tháng</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="maintenanceCostChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Thiết bị cần bảo trì sắp tới</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Thiết bị</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Loại bảo trì</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingMaintenances as $maintenance)
                                            <tr>
                                                <td>{{ $maintenance->deviceItem->device->name }}</td>
                                                <td>{{ $maintenance->start_date->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($maintenance->type === 'periodic')
                                                        <span class="badge badge-info">Định kỳ</span>
                                                    @else
                                                        <span class="badge badge-warning">Sửa chữa</span>
                                                    @endif
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

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Danh sách yêu cầu bảo trì</h4>
                    <div>
                        <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus-circle"></i> Tạo yêu cầu bảo trì
                        </a>
                        <button class="btn btn-info" onclick="checkPeriodicMaintenance()">
                            <i class="fa fa-sync"></i> Kiểm tra bảo trì định kỳ
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover" style="min-width: 845px">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" width="5%">#</th>
                                    <th width="10%">Mã thiết bị</th>
                                    <th width="15%">Tên thiết bị</th>
                                    <th width="10%">Loại bảo trì</th>
                                    <th width="12%">Ngày bắt đầu</th>
                                    <th width="12%">Ngày kết thúc</th>
                                    <th width="10%">Chi phí</th>
                                    <th width="12%">Trạng thái</th>
                                    <th width="10%">Người tạo</th>
                                    <th class="text-center" width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenances as $key => $maintenance)
                                    <tr class="border-bottom {{ $maintenance->isOverdue() ? 'table-warning' : '' }}">
                                        <td class="text-center align-middle">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="mr-2">{{ $key + 1 }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $maintenance->deviceItem->code }}</td>
                                        <td class="align-middle">{{ $maintenance->deviceItem->device->name }}</td>
                                        <td class="align-middle">
                                            @if($maintenance->type === 'periodic')
                                                <span class="badge badge-info">Định kỳ</span>
                                            @else
                                                <span class="badge badge-warning">Sửa chữa</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $maintenance->start_date->format('d/m/Y') }}</td>
                                        <td class="align-middle">{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : '-' }}</td>
                                        <td class="align-middle">{{ number_format($maintenance->cost) }} đ</td>
                                        <td class="align-middle">
                                            @switch($maintenance->status)
                                                @case('pending')
                                                    <span class="badge badge-warning badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Chờ xử lý
                                                    </span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge badge-info badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Đang thực hiện
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-success badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Hoàn thành
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-danger badge-pill px-3 py-2">
                                                        <i class="fa fa-circle mr-1 small"></i>
                                                        Đã hủy
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm mr-2">
                                                    <div class="avatar-title rounded-circle bg-primary">
                                                        {{ substr($maintenance->creator->name, 0, 1) }}
                                                    </div>
                                                </div>
                                                <span>{{ $maintenance->creator->name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm mr-2" data-toggle="modal" data-target="#statusModal{{ $maintenance->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            
                                                <form action="{{ route('maintenances.destroy', $maintenance) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal cập nhật trạng thái -->
                                    <div class="modal fade" id="statusModal{{ $maintenance->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('maintenances.update-status', $maintenance) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Cập nhật trạng thái</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
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
                                                        <div class="form-group">
                                                            <label>Chi phí</label>
                                                            <input type="number" name="cost" class="form-control" value="{{ $maintenance->cost }}" min="0">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-pill {
            font-size: 12px;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }

        .table td {
            vertical-align: middle;
        }

        .fa-circle {
            font-size: 8px;
        }
    </style>
@endsection

@section('js')
<script>
    $(document).ready(function() {

        // Biểu đồ chi phí bảo trì
        var ctx = document.getElementById('maintenanceCostChart').getContext('2d');
        var maintenanceCosts = @json($maintenanceCosts);
        var months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 
                     'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        var costs = new Array(12).fill(0);
        
        maintenanceCosts.forEach(function(item) {
            costs[item.month - 1] = item.total_cost;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Chi phí bảo trì',
                    data: costs,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' đ';
                            }
                        }
                    }
                }
            }
        });
    });

    function checkPeriodicMaintenance() {
        $.ajax({
            url: '{{ route("maintenances.check-periodic") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                toastr.info('Đang kiểm tra bảo trì định kỳ...');
            },
            success: function(response) {
                toastr.success(response.message);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    toastr.error(xhr.responseJSON.message);
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(function(error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error('Có lỗi xảy ra khi kiểm tra bảo trì định kỳ');
                }
            }
        });
    }
</script>
@endsection

