@extends('layouts.app')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Sửa thiết bị</h4>
                    <span class="ml-1">Quản lý thiết bị</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Chỉnh sửa thông tin thiết bị</h4>
                    </div>
                    <div class="card-body">

                        {{-- Hiển thị lỗi nếu có --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('devices.update', $device->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="text-label">Tên thiết bị *</label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $device->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Loại người dùng được mượn</label>
                                <select name="borrower_type" class="form-control">
                                    <option value="both" {{ old('borrower_type', $device->borrower_type) == 'both' ? 'selected' : '' }}>Sinh viên & Giảng viên</option>
                                    <option value="student" {{ old('borrower_type', $device->borrower_type) == 'student' ? 'selected' : '' }}>Chỉ sinh viên</option>
                                    <option value="teacher" {{ old('borrower_type', $device->borrower_type) == 'teacher' ? 'selected' : '' }}>Chỉ giảng viên</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Danh mục *</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $device->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $device->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label class="text-label">Ảnh thiết bị</label>
                                <input type="file" name="image" class="form-control-file">
                                @if ($device->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $device->image) }}" alt="Ảnh thiết bị hiện tại" width="100" style="border-radius: 8px;">
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-success">Cập nhật thiết bị</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
