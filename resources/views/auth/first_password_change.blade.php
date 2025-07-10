<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Laravel Admin</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/images/favicon.png')}}">
    <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">
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
                                    <h4 class="text-center mb-4">Đăng nhập</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('first-password.update') }}">
                        @csrf
                        <div class="form-group">
                            <label for="password">Mật khẩu mới</label>
                            <input id="password" type="password" class="form-control" name="password" required minlength="8">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required minlength="8">
                        </div>
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </form>
                    <div class="new-account mt-3">
                        <p>Bạn chưa có tài khoản? <a class="text-primary" href="{{ route('register') }}">Đăng ký</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!--**********************************
Scripts
***********************************-->
<!-- Required vendors -->
<script src="{{asset('admin/vendor/global/global.min.js')}}"></script>
<script src="{{asset('admin/js/quixnav-init.js')}}"></script>
<script src="{{asset('admin/js/custom.min.js')}}"></script>

</body>

</html>
