<?php

namespace App\Http\Controllers\admin;

use App\Models\Device;
use App\Models\DeviceItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class DeviceItemController extends Controller
{
    public function index(Request $request, $device_id)
    {
        $deviceItems = DeviceItem::where('device_id', $device_id)
            ->where('status', 'available')
            ->whereDoesntHave('maintenances', function($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            })
            ->get();

        return view('admin.device-items.list', compact('deviceItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'items' => 'required|array|min:1',
            'items.*.code' => 'required|string|max:50|unique:device_items,code',
            'items.*.status' => 'required|in:available,pending,in_use,maintenance,broken',
        ], [
            'device_id.required' => 'Vui lòng chọn thiết bị.',
            'device_id.exists' => 'Thiết bị không tồn tại.',
            'items.required' => 'Vui lòng thêm ít nhất một thiết bị con.',
            'items.min' => 'Vui lòng thêm ít nhất một thiết bị con.',
            'items.*.code.required' => 'Mã thiết bị là bắt buộc.',
            'items.*.code.unique' => 'Mã thiết bị đã tồn tại.',
            'items.*.status.required' => 'Trạng thái là bắt buộc.',
            'items.*.status.in' => 'Trạng thái không hợp lệ.',
        ]);

        try {
            foreach ($request->items as $item) {
                DeviceItem::create([
                    'device_id' => $request->device_id,
                    'code' => $item['code'],
                    'status' => $item['status'],
                ]);
            }

            return redirect()->back()->with('success', 'Thêm thiết bị con thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:device_items,code,' . $id,
            'serial_number' => 'nullable|string|max:100',
            'status' => 'required|in:available,pending,in_use,maintenance,broken',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ], [
            'code.required' => 'Mã thiết bị là bắt buộc.',
            'code.unique' => 'Mã thiết bị đã tồn tại.',
            'serial_number.max' => 'Số serial không được vượt quá 100 ký tự.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'supplier_id.exists' => 'Nhà cung cấp không tồn tại.',
        ]);

        try {
            $item = DeviceItem::findOrFail($id);

            // Kiểm tra nếu thiết bị đang được mượn
            if ($item->status === 'in_use') {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể cập nhật thiết bị đang được mượn.'
                ], 422);
            }

            $item->update([
                'code' => $request->code,
                'serial_number' => $request->serial_number,
                'status' => $request->status,
                'supplier_id' => $request->supplier_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thiết bị con thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $item = DeviceItem::findOrFail($id);

            // Kiểm tra nếu thiết bị đang được mượn
            if ($item->status === 'in_use') {
                return back()->with('error', 'Không thể xóa thiết bị đang được mượn.');
            }

            $item->delete();

            return redirect()->back()->with('success', 'Xoá thiết bị con thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function getDeviceItems($device_id)
    {
        $deviceItems = DeviceItem::where('device_id', $device_id)
            ->where('status', '!=', 'damaged')
            ->get();

        return response()->json([
            'device_items' => $deviceItems
        ]);
    }

    public function json($deviceId)
    {
        $deviceItems = DeviceItem::where('device_id', $deviceId)
            ->where('status', 'available')
            ->get(['id', 'code', 'status', 'serial_number']);

        return response()->json([
            'device_items' => $deviceItems
        ]);
    }

    // Hiển thị chi tiết thiết bị (bổ sung danh sách giảng viên)
    public function show($id)
    {
        $deviceItem = DeviceItem::with('user', 'device')->findOrFail($id);
        $teachers = User::role('teacher')->get();
        return view('admin.device-items.show', compact('deviceItem', 'teachers'));
    }

    // Xử lý cấp phát thiết bị cho giảng viên
    public function assignToTeacher(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        $deviceItem = DeviceItem::findOrFail($id);
        $deviceItem->user_id = $request->user_id;
        $deviceItem->status = 'assigned';
        $deviceItem->save();
        return back()->with('success', 'Cấp phát thiết bị thành công!');
    }
}
