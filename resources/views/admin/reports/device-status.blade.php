@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Báo cáo tình trạng thiết bị</h4>
                    <span class="ml-1">Chi tiết báo cáo</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Báo cáo</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tình trạng thiết bị</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Thống kê tình trạng thiết bị</h4>
                        <a href="{{ route('reports.export-device-status-pdf') }}" class="btn btn-secondary">
                            <i class="fas fa-file-pdf"></i> Xuất PDF
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart-container" style="position: relative; height:400px; width:100%;">
                                    <canvas id="deviceStatusChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>Tình trạng</th>
                                                <th>Số lượng</th>
                                                <th>Tỷ lệ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statusPercentages as $status => $percentage)
                                            <tr>
                                                <td>
                                                    @switch($status)
                                                        @case('available')
                                                            <span class="badge badge-success">Sẵn sàng</span>
                                                            @break
                                                        @case('borrowed')
                                                            <span class="badge badge-warning">Đang mượn</span>
                                                            @break
                                                        @case('maintenance')
                                                            <span class="badge badge-info">Bảo trì</span>
                                                            @break
                                                        @case('damaged')
                                                            <span class="badge badge-danger">Hỏng</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">{{ $status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $deviceCounts[$status] }}</td>
                                                <td>{{ number_format($percentage, 2) }}%</td>
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
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('deviceStatusChart').getContext('2d');
        const statusData = @json($statusPercentages);
        const deviceCounts = @json($deviceCounts);

        const labels = Object.keys(statusData).map(status => {
            switch(status) {
                case 'available': return 'Sẵn sàng';
                case 'borrowed': return 'Đang mượn';
                case 'maintenance': return 'Bảo trì';
                case 'damaged': return 'Hỏng';
                default: return status;
            }
        });

        const data = {
            labels: labels,
            datasets: [{
                data: Object.values(deviceCounts),
                backgroundColor: [
                    '#28a745', // Success
                    '#ffc107', // Warning
                    '#17a2b8', // Info
                    '#dc3545'  // Danger
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const percentage = statusData[Object.keys(statusData)[context.dataIndex]];
                                return `${label}: ${value} (${percentage.toFixed(2)}%)`;
                            }
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    });
</script>
@endsection
