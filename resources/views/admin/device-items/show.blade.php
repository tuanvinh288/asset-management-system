@extends('layouts.app')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-6 col-xxl-6 mx-auto">
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cấp phát thiết bị cho giảng viên</h5>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="mb-3">
                            <a href="{{ route('devices.show', $deviceItem->device_id) }}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Quay lại</a>
                        </div>
                            <h3 class="text-center">
                                Danh mục <b>{{ $deviceItem->device->name ?? 'Không xác định' }}</b>
                            </h3>
                            <h4 class="text-center">
                                Tên thiết b: <b>{{ $deviceItem->code }}</b>
                            </h4>
                            @if ($deviceItem->status == 'assigned')
                                <h4 class="">
                                    Thiết bị đã được cấp cho: <b>{{ $deviceItem->user->name }}</b>
                                </h4>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('device-items.assignTeacher', $deviceItem->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="text-label">Chọn giảng viên để cấp phát</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">-- Chọn giảng viên --</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Cấp phát thiết bị</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
