<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:suppliers,name',
        ]);

        Supplier::create($request->all());

        return response()->json(['success' => true, 'message' => 'Thêm nhà cung cấp thành công!']);
    }

    public function edit($id)
    {
        return Supplier::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:suppliers,name,' . $supplier->id,
        ]);
        $supplier->update($request->all());

        return response()->json(['success' => true, 'message' => 'Cập nhật nhà cung cấp thành công!']);
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json(['success' => true, 'message' => 'Xóa nhà cung cấp thành công!']);
    }
}
