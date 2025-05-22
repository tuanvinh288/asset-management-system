<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DeparmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('admin.departments.index', compact('departments'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('admin.departments.create', ['department' => null]);
    }


    // Lưu người dùng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        Department::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tạo khoa thành công'
        ]);
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $department
        ]);
    }
    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $department->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật khoa thành công',
            'department' => $department
        ]);
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);  // Tìm phòng ban theo ID

            $department->delete();  // Xóa phòng ban

            return response()->json([
                'success' => true,
                'message' => 'Phòng ban đã bị xóa thành công!'
            ]);
        } catch (\Exception $e) {
            // Xử lý lỗi nếu không tìm thấy phòng ban hoặc lỗi xóa
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa phòng ban. Vui lòng thử lại!'
            ], 400);
        }
    }
}
