@extends('layouts.app')
@section('content')

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>{{ isset($user) ? 'Chỉnh sửa tài khoản' : 'Thêm tài khoản mới' }}</h4>
                    <span class="ml-1">Quản lý người dùng</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-xxl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($user) ? 'Chỉnh sửa thông tin' : 'Thêm người dùng' }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                                @csrf
                                @if(isset($user))
                                    @method('PUT')
                                @endif

                                <div class="form-group">
                                    <label class="text-label">Họ tên *</label>
                                    <input type="text" class="form-control" name="name" placeholder="Nhập họ tên"
                                        value="{{ old('name', $user->name ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Email *</label>
                                    <input type="email" class="form-control" name="email" placeholder="Nhập email"
                                        value="{{ old('email', $user->email ?? '') }}" {{ isset($user) ? 'readonly' : 'required' }}>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại"
                                        value="{{ old('phone', $user->phone ?? '') }}">
                                </div>

                                {{-- Chỉ nhập mật khẩu khi tạo mới hoặc đổi --}}
                                <div class="form-group">
                                    <label class="text-label">Mật khẩu {{ isset($user) ? '(nếu muốn đổi)' : '*' }}</label>
                                    <div class="input-group transparent-append">
                                        <input type="password" class="form-control password-field" id="password" name="password"
                                            placeholder="Nhập mật khẩu" {{ isset($user) ? '' : 'required' }}>
                                        <div class="input-group-append show-pass">
                                            <span class="input-group-text toggle-password"><i class="fa fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="text-label">Xác nhận mật khẩu {{ isset($user) ? '(nếu có nhập mật khẩu)' : '*' }}</label>
                                    <div class="input-group transparent-append">
                                        <input type="password" class="form-control password-field" id="password_confirmation" name="password_confirmation"
                                            placeholder="Nhập lại mật khẩu" {{ isset($user) ? '' : 'required' }}>
                                        <div class="input-group-append show-pass">
                                            <span class="input-group-text toggle-password-confirm"><i class="fa fa-eye-slash"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="role_id">Quyền</label>
                                    <select name="role_id" class="form-control" id="role_id">
                                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                            <option value="{{ $role->name }}" {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Khoa</label>
                                    <select id="single-select" name="department_id" class="form-control">
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" {{ (isset($user) && $user->department_id == $department->id) ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Cập nhật' : 'Thêm mới' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')

<script>
    $(document).ready(function () {
        // Toggle show/hide password
        $('.toggle-password').on('click', function () {
            let input = $(this).closest('.input-group').find('input');
            let icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        // Toggle show/hide password confirmation
        $('.toggle-password-confirm').on('click', function () {
            let input = $(this).closest('.input-group').find('input');
            let icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });

        // Validate password confirmation (optional)
        $('form').on('submit', function (e) {
            const password = $('#password').val();
            const confirm = $('#password_confirmation').val();

            if (password && password !== confirm) {
                e.preventDefault();
                alert('Mật khẩu và xác nhận mật khẩu không khớp!');
            }
        });
    });
</script>

@endsection
