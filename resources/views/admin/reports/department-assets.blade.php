@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Báo cáo tài sản theo phòng ban</h4>
                        <span class="ml-1">Chi tiết báo cáo</span>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Báo cáo</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Tài sản theo phòng ban</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Danh sách tài sản theo phòng ban</h4>
                            <div class="d-flex">
                                <a href="{{ route('reports.export-department-assets-pdf') }}" class="btn btn-secondary mr-2">Xuất PDF</a>
                                <a href="{{ route('reports.export-department-assets-excel') }}" class="btn btn-success">Xuất Excel</a>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($departments as $department)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h4>{{ $department->name }}</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tên phòng</th>
                                                        <th>Mã phòng</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($department->rooms as $i => $room)
                                                    <tr>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td>{{ $room->name }}</td>
                                                        <td>{{ $room->code }}</td>
                                                        <td>{{ $room->note }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Không có phòng nào</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
