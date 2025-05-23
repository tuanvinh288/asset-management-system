<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('admin.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:units,name',
        ]);

        Unit::create($request->all());

        return response()->json(['success' => true, 'message' => 'Thêm đơn vị tính thành công!']);
    }

    public function edit($id)
    {
        return Unit::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:units,name,' . $unit->id,
        ]);
        $unit->update($request->all());

        return response()->json(['success' => true, 'message' => 'Cập nhật đơn vị tính thành công!']);
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return response()->json(['success' => true, 'message' => 'Xóa đơn vị tính thành công!']);
    }
}
