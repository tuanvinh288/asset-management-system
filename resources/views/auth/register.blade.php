<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sign up - Laravel Auth</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/images/favicon.png') }}">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-4">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Đăng ký tài khoản</h4>

                                    <!-- Laravel Form -->
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <div class="form-group">
                                            <label><strong>Họ và tên</strong></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   name="name" value="{{ old('name') }}" required autofocus placeholder="Tên tài khoản">
                                            @error('name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required placeholder="hello@example.com">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Mật khẩu</strong></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" required placeholder="Vui lòng nhập mật khẩu">
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label><strong>Nhập lại mật khẩu</strong></label>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   name="password_confirmation" required placeholder="Xác nhận mật khẩu">
                                            @error('password_confirmation')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                                        </div>
                                    </form>

                                    <div class="new-account mt-3">
                                        <p>Bạn đã có tài khoản?
                                            <a class="text-primary" href="{{ route('login') }}">Đăng nhập</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('admin/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('admin/js/quixnav-init.js') }}"></script>
</body>
</html>
