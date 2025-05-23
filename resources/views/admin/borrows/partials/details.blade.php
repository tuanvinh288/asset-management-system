<div class="card">
    <div class="card-body">
        <h5 class="card-title">Chi tiết thiết bị mượn</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên thiết bị</th>
                        <th>Mã thiết bị</th>
                        <th>Trạng thái trước khi mượn</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrow->details as $key => $detail)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $detail->deviceItem->device->name }}</td>
                            <td>{{ $detail->deviceItem->code }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'new' => 'success',
                                        'good' => 'info',
                                        'normal' => 'warning',
                                        'damaged' => 'danger'
                                    ];
                                    $statusTexts = [
                                        'new' => 'Mới',
                                        'good' => 'Tốt',
                                        'normal' => 'Bình thường',
                                        'damaged' => 'Hư hỏng'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$borrow->device_status_before] }}">
                                    {{ $statusTexts[$borrow->device_status_before] }}
                                </span>
                            </td>
                            <td>{{ $borrow->note }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 