@extends('layouts.app')
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Thiết bị đã cấp cho giảng viên</h4>
                    <span class="ml-1">Giảng viên: <b>{{ $user->name }}</b></span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Quay lại</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Danh sách thiết bị đã cấp</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" width="5%">#</th>
                                        <th width="20%">Mã thiết bị</th>
                                        <th width="25%">Tên thiết bị</th>
                                        <th width="20%">Ngày cấp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($user->assignedDevices as $key => $item)
                                        <tr>
                                            <td class="text-center"><strong>{{ $key + 1 }}</strong></td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->device->name ?? '' }}</td>
                                            <td>{{ $item->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có thiết bị nào được cấp phát.</td>
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
