@extends('layouts.app')

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            @extends('admin.components.message')

            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Danh sách mượn thiết bị</h4>
                        <span class="ml-1">Quản lý mượn trả thiết bị</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Danh sách mượn thiết bị</h4>
                            @role('admin')
                            <a href="{{ route('device-borrows.create') }}" class="btn btn-primary">Đăng ký mượn mới</a>
                            @endrole
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã phiếu</th>
                                            <th>Người mượn</th>
                                            <th>Ngày mượn</th>
                                            <th>Ngày trả dự kiến</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($borrows as $borrow)
                                            <tr>
                                                <td>{{ $borrow->id }}</td>
                                                <td>{{ $borrow->user->name }}</td>
                                                <td>{{ $borrow->borrow_date }}</td>
                                                <td>{{ $borrow->return_date }}</td>
                                                <td>
                                                    @switch($borrow->status)
                                                        @case('pending')
                                                            <span class="badge badge-warning">Chờ duyệt</span>
                                                            @break
                                                        @case('approved')
                                                            <span class="badge badge-success">Đã duyệt</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge badge-danger">Từ chối</span>
                                                            @break
                                                        @case('returned')
                                                            <span class="badge badge-info">Đã trả</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <a href="{{ route('device-borrows.show', $borrow->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @role('admin')
                                                    @if($borrow->status == 'pending')
                                                        <form action="{{ route('device-borrows.approve', $borrow->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                <i class="fa fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('device-borrows.cancel', $borrow->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($borrow->status == 'approved')
                                                        <a href="{{ route('device-borrows.return', $borrow->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-undo"></i>
                                                        </a>
                                                    @endif
                                                    @endrole
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có dữ liệu</td>
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

    <style>
        .avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 12px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-pill {
            font-size: 12px;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }

        .table td {
            vertical-align: middle;
        }

        .fa-chevron-down {
            font-size: 12px;
            color: #666;
            transition: transform 0.2s;
        }

        .rotate-icon {
            transform: rotate(180deg);
        }

        .details-row {
            background: none !important;
        }

        .details-row:hover {
            background: none !important;
        }

        .borrow-details-row {
            position: relative;
            width: 100%;
            background: none !important;
        }

        .borrow-details-wrapper {
            margin: 0;
            width: 100%;
            background: white;
            border-bottom: 1px solid #e5e5e5;
        }

        .borrow-details {
            padding: 15px 30px;
            background-color: #f8f9fa;
            border-left: 1px solid #e5e5e5;
            border-right: 1px solid #e5e5e5;
            margin: 0 -1px; /* Để border khớp với table */
        }

        /* Đảm bảo chi tiết nằm đúng vị trí sau mỗi dòng */
        #example tbody tr {
            position: relative;
        }

        /* Fix DataTable styling conflicts */
        .dataTables_wrapper .borrow-details-row {
            background: none !important;
        }

        .dataTables_wrapper .borrow-details-row:hover {
            background: none !important;
        }
    </style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Xử lý sự kiện click vào nút toggle-details
        $('.toggle-details').on('click', function() {
            const borrowId = $(this).data('borrow-id');
            const icon = $(this).find('i');
            const detailsRow = $(`.borrow-details-row[data-parent-row-id="${borrowId}"]`);
            const detailsWrapper = detailsRow.find('.borrow-details-wrapper');
            const detailsContainer = detailsWrapper.find('.borrow-details');

            // Toggle icon
            icon.toggleClass('rotate-icon');

            // Nếu đã có nội dung, chỉ cần toggle hiển thị
            if (detailsContainer.children().length > 0) {
                detailsWrapper.slideToggle();
                return;
            }

            // Hiển thị loading
            detailsContainer.html(`
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            `);
            detailsWrapper.slideDown();

            // Gọi API lấy chi tiết
            $.ajax({
                url: "{{ route('device-borrows.details', ['id' => ':id']) }}".replace(':id', borrowId),
                method: 'GET',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success) {
                        detailsContainer.html(response.html);
                    } else {
                        detailsContainer.html(`
                            <div class="alert alert-danger m-3">
                                ${response.message || 'Không thể tải chi tiết phiếu mượn'}
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);

                    detailsContainer.html(`
                        <div class="alert alert-danger m-3">
                            Có lỗi xảy ra khi tải chi tiết phiếu mượn
                        </div>
                    `);
                }
            });
        });
    });
</script>
@endsection
