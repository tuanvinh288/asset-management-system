<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Category; // Giả sử bạn có mô hình Category
use Illuminate\Http\Request;
use App\Models\Unit;

class CategoryController extends Controller
{
    // Hiển thị danh sách danh mục
    public function index()
    {
        $units = Unit::all();
        $categories = Category::all(); // Lấy tất cả danh mục
        return view('admin.categories.index', compact('categories', 'units'));
    }

    // Tạo mới danh mục
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'status' => 'required|in:active,inactive'
        ]);

        Category::create($request->all());

        return response()->json(['message' => 'Thêm danh mục thành công!']);
    }

    // Hiển thị form sửa danh mục
    public function edit($id)
    {
        $units = Unit::all();
        $category = Category::findOrFail($id);
        return response()->json(['success' => true, 'data' => $category, 'units' => $units]);
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'unit_id' => 'nullable|exists:units,id',
            'status' => 'required|in:active,inactive'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json(['success' => true, 'message' => 'Cập nhật danh mục thành công!']);
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Xóa danh mục thành công!']);
    }
}
