<?php

namespace App\Http\Controllers\admin;

use App\Models\Device;
use App\Models\Category;
use App\Models\DeviceItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    public function index()
    {
        // Hiển thị danh sách thiết bị
        $devices = Device::with([
            'category.unit',
            'deviceItems.borrowDetails.borrow',
            'deviceItems.maintenances'
        ])->get();
        return view('admin.devices.index', compact('devices'));
    }

    public function show($id)
    {
        $device = Device::with(['category.unit', 'deviceItems.borrowDetails.borrow', 'deviceItems.maintenances'])->findOrFail($id);
        $device_parts = $device->deviceItems;  // Sửa từ items thành deviceItems

        return view('admin.devices.show', compact('device', 'device_parts'));
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $categories = Category::all(); // nếu có dùng danh mục
        return view('admin.devices.edit', compact('device', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:devices,name,' . $id,
            'borrower_type' => 'in:both,student,teacher',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Tên thiết bị là bắt buộc.',
            'name.unique' => 'Tên thiết bị này đã tồn tại trong hệ thống.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận ảnh định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        $device->name = $request->name;
        $device->category_id = $request->category_id;
        $device->borrower_type = $request->borrower_type;
        $device->description = $request->description;


        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($device->image && file_exists(public_path('storage/' . $device->image))) {
                unlink(public_path('storage/' . $device->image));
            }

            $imagePath = $request->file('image')->store('devices', 'public');
            $device->image = $imagePath;
        }

        $device->save();

        return redirect()->route('devices.index')->with('success', 'Cập nhật thiết bị thành công.');
    }


    // Hiển thị form thêm thiết bị và chi tiết thiết bị
    public function create()
    {
        $categories = Category::all(); // Lấy danh mục thiết bị
        return view('admin.devices.create', compact('categories'));
    }

    // Lưu thiết bị và chi tiết thiết bị
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:devices,name',
            'borrower_type' => 'required|in:both,student,teacher',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.required' => 'Tên thiết bị là bắt buộc.',
            'name.unique' => 'Tên thiết bị này đã tồn tại trong hệ thống.',
            'borrower_type.required' => 'Vui lòng chọn loại người mượn.',
            'borrower_type.in' => 'Loại người mượn không hợp lệ.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists' => 'Danh mục không hợp lệ.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận ảnh định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        try {
            $device = new Device($request->except('image'));

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->store('devices', 'public');
                $device->image = $path;
            }

            $device->save();

            return redirect()->route('devices.index')->with('success', 'Thêm thiết bị thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $device = Device::with('deviceItems')->findOrFail($id);

            // Kiểm tra xem có thiết bị con nào đang không ở trạng thái available không
            $activeItems = $device->deviceItems()->whereNotIn('status', ['available'])->count();
            if ($activeItems > 0) {
                return redirect()->route('devices.index')
                    ->with('error', 'Không thể xóa thiết bị vì có thiết bị con đang ở trạng thái: đang chờ duyệt, đang sử dụng, đang bảo trì hoặc hỏng.');
            }

            // Xóa ảnh nếu có
            if ($device->image && file_exists(public_path('storage/' . $device->image))) {
                unlink(public_path('storage/' . $device->image));
            }

            // Xóa thiết bị con
            $device->deviceItems()->delete();

            // Xóa thiết bị
            $device->delete();

            return redirect()->route('devices.index')
                ->with('success', 'Thiết bị đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('devices.index')
                ->with('error', 'Đã xảy ra lỗi khi xóa thiết bị: ' . $e->getMessage());
        }
    }
}
