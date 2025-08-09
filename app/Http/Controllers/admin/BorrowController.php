<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Borrow;
use App\Models\Device;
use App\Models\DeviceItem;
use App\Mail\ReturnReminder;
use App\Models\BorrowDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class BorrowController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $borrows = Borrow::with(['user', 'details.deviceItem'])->latest()->get();
        } else {
            // Nếu là teacher hoặc student thì chỉ hiển thị danh sách mượn của họ
            $borrows = Borrow::with(['user', 'details.deviceItem'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get();
        }
        return view('admin.borrows.index', compact('borrows'));
    }

    public function create()
    {
        $devices = Device::all();
        $users = User::all();
        $deviceItems = DeviceItem::where('status', 'available')->whereNull('user_id')->get();
        return view('admin.borrows.create', compact('deviceItems', 'devices', 'users'));
    }

    public function store(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'borrow_date' => 'required|date',
            'return_date' => 'required|date|after:borrow_date',
            'device_id' => 'required|exists:devices,id',
            'device_items' => 'required|array|min:1',
            'device_items.*' => 'exists:device_items,id',
            'reason' => 'required|string|max:1000',
            'note' => 'nullable|string|max:1000',
            'device_status_before' => 'required|in:new,good,normal,damaged',
            'device_image_before' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'borrow_date.required' => 'Vui lòng chọn ngày mượn',
            'return_date.required' => 'Vui lòng chọn ngày trả dự kiến',
            'return_date.after' => 'Ngày trả phải sau ngày mượn',
            'device_id.required' => 'Vui lòng chọn thiết bị',
            'device_id.exists' => 'Thiết bị không tồn tại',
            'device_items.required' => 'Vui lòng chọn ít nhất một chi tiết thiết bị',
            'device_items.min' => 'Vui lòng chọn ít nhất một chi tiết thiết bị',
            'device_items.*.exists' => 'Chi tiết thiết bị không tồn tại',
            'reason.required' => 'Vui lòng nhập lý do mượn',
            'reason.max' => 'Lý do mượn không được vượt quá 1000 ký tự',
            'device_status_before.required' => 'Vui lòng chọn trạng thái thiết bị',
            'device_status_before.in' => 'Trạng thái thiết bị không hợp lệ',
            'device_image_before.image' => 'File tải lên phải là ảnh',
            'device_image_before.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif',
            'device_image_before.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ]);
        if (auth()->user()->hasRole('admin')) {
            $userId = $request['user_id'];
        } else {
            $userId = auth()->id();
        }
        DB::beginTransaction();
        try {
            // Tạo phiếu mượn
            $borrowData = [
                'user_id' => $userId,
                'borrow_date' => $validated['borrow_date'],
                'return_date' => $validated['return_date'],
                'reason' => $validated['reason'],
                'note' => $validated['note'],
                'device_status_before' => $validated['device_status_before'],
                'status' => 'pending'
            ];

            // Lưu ảnh thiết bị trước khi mượn nếu có
            if ($request->hasFile('device_image_before')) {
                $imagePath = $request->file('device_image_before')->store('borrows', 'public');
                $borrowData['device_image_before'] = $imagePath;
            }

            $borrow = Borrow::create($borrowData);

            // Tạo chi tiết phiếu mượn
            foreach ($validated['device_items'] as $deviceItemId) {
                try {
                    $deviceItem = DeviceItem::find($deviceItemId);
                    if ($deviceItem) {
                        $deviceItem->status = 'pending';
                        $deviceItem->save();
                        // Tạo chi tiết mượn
                        $borrowDetailData = [
                            'borrow_id' => $borrow->id,
                            'device_item_id' => $deviceItemId
                        ];
                        BorrowDetail::create($borrowDetailData);
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()
                        ->withErrors(['error' => 'Lỗi khi tạo chi tiết mượn: ' . $e->getMessage()])
                        ->withInput();
                }
            }

            DB::commit();
            return redirect()->route('device-borrows.index')
                ->with('success', 'Phiếu mượn đã được tạo thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function approve($id)
    {
        try {
            $borrow = Borrow::with('details.deviceItem')->findOrFail($id);

            // Kiểm tra trạng thái phiếu mượn
            if ($borrow->status !== 'pending') {
                return back()->with('error', 'Không thể duyệt phiếu mượn vì trạng thái hiện tại là: ' . ucfirst($borrow->status));
            }

            // Kiểm tra trạng thái các thiết bị
            foreach ($borrow->details as $detail) {
                if ($detail->deviceItem->status !== 'pending') {
                    return back()->with('error', 'Không thể duyệt phiếu mượn vì có thiết bị không khả dụng.');
                }
            }

            // Cập nhật trạng thái các thiết bị sang 'in_use'
            foreach ($borrow->details as $detail) {
                $detail->deviceItem->update(['status' => 'in_use']);
            }

            $borrow->update([
                'status' => 'approved',
                'staff_id' => auth()->id(),
                'approved_at' => now()
            ]);

            return back()->with('success', 'Phiếu mượn đã được duyệt.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function markReturned(Request $request, $id)
    {
        

        $request->validate([
            'return_date' => 'required|date',
            'device_status_after' => 'required|in:new,good,normal,damaged',
            'device_image_after' => 'nullable|image|max:2048',
            'note' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $borrow = Borrow::findOrFail($id);

            if ($borrow->status !== 'approved') {
                return back()->with('error', 'Chỉ có thể đánh dấu trả cho phiếu mượn đã được duyệt.');
            }

            // Xử lý upload ảnh nếu có
            $imagePath = null;
            if ($request->hasFile('device_image_after')) {
                $imagePath = $request->file('device_image_after')->store('device-returns', 'public');
            }

            // Cập nhật thông tin phiếu mượn
            $borrow->update([
                'status' => 'returned',
                'actual_return_date' => $request->return_date,
                'return_note' => $request->note,
                'device_image_after' => $imagePath,
                'device_status_after' => $request->device_status_after
            ]);
            // Cập nhật trạng thái các thiết bị
            foreach ($borrow->details as $detail) {
                $deviceID = $detail->device_item_id;
                $deviceDetail = DeviceItem::find($deviceID);
                if ($request->device_status_after == "damaged") {
                    $status = "broken";
                } else {
                    $status = "available";
            
                }
                $deviceDetail->status = $status;
$deviceDetail->save();
            }

            DB::commit();
            return redirect()->route('device-borrows.show', $borrow->id)
                ->with('success', 'Thiết bị đã được trả thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi xử lý trả thiết bị: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function getDeviceItems($device_id)
    {
        $deviceItems = DeviceItem::where('device_id', $device_id)
            ->where('status', 'available')
            ->get();

        return response()->json([
            'device_items' => $deviceItems
        ]);
    }

    public function getBorrowDetails($id)
    {
        try {
            $borrow = Borrow::with(['details.deviceItem'])->findOrFail($id);
            $html = view('admin.borrows.partials.details', compact('borrow'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải chi tiết mượn thiết bị'
            ], 500);
        }
    }

    public function show($id)
    {
        $borrow = Borrow::with(['user', 'staff', 'details.deviceItem'])->findOrFail($id);
        return view('admin.borrows.show', compact('borrow'));
    }

    public function cancel($id)
    {
        DB::beginTransaction();
        try {
            $borrow = Borrow::with('details.deviceItem')->findOrFail($id);

            // Kiểm tra trạng thái phiếu mượn
            if ($borrow->status !== 'pending') {
                return back()->with('error', 'Chỉ có thể hủy phiếu mượn đang chờ duyệt.');
            }

            // Cập nhật trạng thái các thiết bị về available
            foreach ($borrow->details as $detail) {
                if ($detail->deviceItem && $detail->deviceItem->status === 'pending') {
                    $detail->deviceItem->update(['status' => 'available']);
                }
            }

            // Cập nhật trạng thái phiếu mượn
            $borrow->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Phiếu mượn đã được hủy thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Có lỗi xảy ra khi hủy phiếu mượn: ' . $e->getMessage());
        }
    }

    public function showReturnForm($id)
    {
        $borrow = Borrow::with(['details.deviceItem'])->findOrFail($id);
        return view('admin.borrows.return', compact('borrow'));
    }

    public function sendReturnReminder(Borrow $borrow)
    {
        try {
            // Load các relationship cần thiết
            $borrow->load(['user', 'details.deviceItem.device']);

            // Gửi email thông báo
            Mail::to($borrow->user->email)->send(new ReturnReminder($borrow, 'device', false));

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi thông báo thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi thông báo: ' . $e->getMessage()
            ], 500);
        }
    }
}
