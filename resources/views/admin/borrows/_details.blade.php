<div class="p-4 bg-light">
    <h6 class="mb-3 font-weight-bold">
        <i class="fa fa-info-circle mr-2"></i>
        Chi tiết thiết bị mượn
    </h6>
    <div class="table-responsive">
        <table class="table bg-white mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Mã thiết bị</th>
                    <th>Số serial</th>
                    <th>Trạng thái trước khi mượn</th>
                    <th>Ngày trả thực tế</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>
                            <span class="font-weight-bold">{{ $detail->deviceItem->code }}</span>
                        </td>
                        <td>{{ $detail->deviceItem->serial_number }}</td>
                        <td>
                            <span class="badge badge-info badge-pill px-3">
                                {{ $detail->device_status_before_text }}
                            </span>
                        </td>
                        <td>
                            @if($detail->actual_return_date)
                                <span class="text-success">
                                    <i class="fa fa-calendar-check mr-1"></i>
                                    {{ \Carbon\Carbon::parse($detail->actual_return_date)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted">
                                    <i class="fa fa-clock mr-1"></i>
                                    Chưa trả
                                </span>
                            @endif
                        </td>
                        <td>{{ $detail->note ?: 'Không có ghi chú' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 