<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">Main Menu</li>
            <li><a href="{{ route('dashboard') }}" aria-expanded="false"><i class="icon icon-single-04"></i><span
                class="nav-text">Trang chủ</span></a></li>

            @role('admin')
            <li class="nav-label">Quản lý hệ thống</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-user menu-icon"></i><span class="nav-text">Quản lý người dùng</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('users.index') }}">Danh sách người dùng</a></li>
                    <li><a href="{{ route('users.create') }}">Thêm người dùng</a></li>
                    <li><a href="{{ route('roles.index') }}">Quản lý vai trò</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-list menu-icon"></i><span class="nav-text">Quản lý danh mục</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('departments.index') }}">Khoa phòng</a></li>
                    <li><a href="{{ route('categories.index') }}">Danh mục thiết bị</a></li>
                    <li><a href="{{ route('units.index') }}">Đơn vị tính</a></li>
                    <li><a href="{{ route('suppliers.index') }}">Nhà cung cấp</a></li>
                </ul>
            </li>

            <li class="nav-label">Quản lý tài sản</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-screen-desktop menu-icon"></i><span class="nav-text">Quản lý thiết bị</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('devices.index') }}">Danh sách thiết bị</a></li>
                    <li><a href="{{ route('devices.create') }}">Thêm thiết bị</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-screen-desktop menu-icon"></i><span class="nav-text">Quản lý phòng</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('rooms.index') }}">Danh sách phòng</a></li>
                    <li><a href="{{ route('rooms.create') }}">Thêm phòng</a></li>
                </ul>
            </li>
            @endrole

            <li class="nav-label">Quản lý mượn trả</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-handbag menu-icon"></i><span class="nav-text">Mượn thiết bị</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('device-borrows.index') }}">Danh sách mượn</a></li>
                    <li><a href="{{ route('device-borrows.create') }}">Đăng ký mượn</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-home menu-icon"></i><span class="nav-text">Mượn phòng</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('room-borrows.index') }}">Danh sách mượn</a></li>
                    <li><a href="{{ route('room-borrows.create') }}">Đăng ký mượn</a></li>
                </ul>
            </li>

            @role('admin')
            <li class="nav-label">Quản lý bảo trì</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-wrench menu-icon"></i><span class="nav-text">Bảo trì</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('maintenances.index') }}">Danh sách bảo trì</a></li>
                    <li><a href="{{ route('maintenances.create') }}">Thêm bảo trì</a></li>
                </ul>
            </li>

            <li class="nav-label">Báo cáo</li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-chart menu-icon"></i><span class="nav-text">Báo cáo</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('reports.index') }}">Tổng quan</a></li>
                    <li><a href="{{ route('reports.device-status') }}">Trạng thái thiết bị</a></li>
                    <li><a href="{{ route('reports.department-assets') }}">Tài sản phòng ban</a></li>
                    <li><a href="{{ route('reports.maintenance-costs') }}">Chi phí bảo trì</a></li>
                </ul>
            </li>
            @endrole
        </ul>
    </div>
</div>
<!-- #/ sidebar -->
