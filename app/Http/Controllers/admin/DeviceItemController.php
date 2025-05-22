<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceItem;
use App\Models\Device;

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
            'items.*.status' => 'required|in:available,borrowed,damaged,maintenance',
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
            'status' => 'required|in:available,borrowed,damaged,maintenance',
        ], [
            'code.required' => 'Mã thiết bị là bắt buộc.',
            'code.unique' => 'Mã thiết bị đã tồn tại.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        try {
            $item = DeviceItem::findOrFail($id);

            // Kiểm tra nếu thiết bị đang được mượn
            if ($item->status === 'in_use') {
                return back()->with('error', 'Không thể cập nhật thiết bị đang được mượn.');
            }

            $item->update([
                'code' => $request->code,
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Cập nhật thiết bị con thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
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
}
