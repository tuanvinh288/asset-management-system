@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Báo cáo và thống kê</h4>
                        <span class="ml-1">Danh sách báo cáo</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Báo cáo</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Danh sách</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Danh sách báo cáo</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Báo cáo tài sản theo phòng ban</h5>
                                            <p class="card-text">Xem danh sách tài sản được phân bổ cho từng phòng ban</p>
                                            <div class="d-flex">
                                                <a href="{{ route('reports.department-assets') }}" class="btn btn-primary mr-2">Xem báo cáo</a>
                                                <a href="{{ route('reports.export-department-assets-pdf') }}" class="btn btn-secondary mr-2">Xuất PDF</a>
                                                <a href="{{ route('reports.export-department-assets-excel') }}" class="btn btn-success">Xuất Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Thống kê tình trạng thiết bị</h5>
                                            <p class="card-text">Xem tỷ lệ thiết bị theo từng trạng thái</p>
                                            <div class="d-flex">
                                                <a href="{{ route('reports.device-status') }}" class="btn btn-primary mr-2">Xem báo cáo</a>
                                                <a href="{{ route('reports.export-device-status-pdf') }}" class="btn btn-secondary">Xuất PDF</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Báo cáo chi phí bảo trì</h5>
                                            <p class="card-text">Xem chi tiết chi phí bảo trì thiết bị</p>
                                            <div class="d-flex">
                                                <a href="{{ route('reports.maintenance-costs') }}" class="btn btn-primary mr-2">Xem báo cáo</a>
                                                <a href="{{ route('reports.export-maintenance-costs-pdf') }}" class="btn btn-secondary mr-2">Xuất PDF</a>
                                                <a href="{{ route('reports.export-maintenance-costs-excel') }}" class="btn btn-success">Xuất Excel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
