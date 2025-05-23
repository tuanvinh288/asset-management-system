<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password - Laravel Auth</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/images/favicon.png') }}">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Reset your password</h4>

                                    <form method="POST" action="{{ route('password.store') }}">
                                        @csrf

                                        <!-- Token -->
                                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                        <!-- Email -->
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email', $request->email) }}" required autofocus>
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- New Password -->
                                        <div class="form-group">
                                            <label><strong>New Password</strong></label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">
                                            @error('password')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="form-group">
                                            <label><strong>Confirm Password</strong></label>
                                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                                name="password_confirmation" required autocomplete="new-password">
                                            @error('password_confirmation')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-3 text-center">
                                        <a href="{{ route('login') }}" class="text-primary">Back to Login</a>
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
