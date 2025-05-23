@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Báo cáo chi phí bảo trì</h4>
                    <span class="ml-1">Chi tiết báo cáo</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Chi phí bảo trì</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="fas fa-dollar-sign text-success"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Tổng chi phí</div>
                            <div class="stat-digit">{{ number_format($totalCost, 0, ',', '.') }} VNĐ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="fas fa-calculator text-primary"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Chi phí trung bình</div>
                            <div class="stat-digit">{{ number_format($averageCost, 0, ',', '.') }} VNĐ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="stat-widget-one card-body">
                        <div class="stat-icon d-inline-block">
                            <i class="fas fa-tools text-warning"></i>
                        </div>
                        <div class="stat-content d-inline-block">
                            <div class="stat-text">Số lần bảo trì</div>
                            <div class="stat-digit">{{ $maintenances->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách chi phí bảo trì</h4>
                        <div class="d-flex">
                            <a href="{{ route('reports.export-maintenance-costs-pdf') }}" class="btn btn-secondary mr-2">
                                <i class="fas fa-file-pdf"></i> Xuất PDF
                            </a>
                            <a href="{{ route('reports.export-maintenance-costs-excel') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Xuất Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Mã thiết bị</th>
                                        <th>Tên thiết bị</th>
                                        <th>Loại thiết bị</th>
                                        <th>Ngày bắt đầu</th>
                                        <th>Ngày kết thúc</th>
                                        <th>Chi phí</th>
                                        <th>Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($maintenances as $maintenance)
                                    <tr>
                                        <td>{{ $maintenance->deviceItem->device->code }}</td>
                                        <td>{{ $maintenance->deviceItem->device->name }}</td>
                                        <td>{{ $maintenance->deviceItem->device->category->name }}</td>
                                        <td>{{ $maintenance->start_date ? $maintenance->start_date->format('d/m/Y') : '' }}</td>
                                        <td>{{ $maintenance->end_date ? $maintenance->end_date->format('d/m/Y') : 'Đang bảo trì' }}</td>
                                        <td>{{ number_format($maintenance->cost, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $maintenance->description }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Không có dữ liệu bảo trì</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('js/quixnav-init.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/plugins-init/datatables.init.js') }}"></script>
@endsection
