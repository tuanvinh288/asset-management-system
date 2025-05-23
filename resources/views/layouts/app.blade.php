<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Focus - Bootstrap Admin Dashboard </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendor/owl-carousel/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendor/owl-carousel/css/owl.theme.default.min.css') }}">
    <link href="{{ asset('admin/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/vendor/toastr/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendor/select2/css/select2.min.css') }}">
    <link href="{{ asset('admin/vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    @yield('css')


</head>

<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="{{ asset('admin/images/logo.png') }}" alt="">
                <img class="logo-compact" src="{{ asset('admin/images/logo-text.png') }}" alt="">
                <img class="brand-title" src="{{ asset('admin/images/logo-text.png') }}" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        @include('layouts.header')
        @include('layouts.nav')
        @yield('content')
        @include('layouts.footer')


    </div>

    <script src="{{ asset('admin/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('admin/js/quixnav-init.js') }}"></script>
    <script src="{{ asset('admin/js/custom.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('admin/vendor/toastr/js/toastr.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/toastr-init.js') }}"></script>
    <script src="{{ asset('admin/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('admin/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/datatables.init.js') }}"></script>
    <script src="{{ asset('admin/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/select2-init.js') }}"></script>
    <script src="{{ asset('admin/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    @yield('js')

</body>

</html>
