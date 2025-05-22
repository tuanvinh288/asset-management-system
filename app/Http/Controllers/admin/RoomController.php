<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('department')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.rooms.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string'
        ]);

        try {
            Room::create($validated);
            return redirect()->route('rooms.index')
                ->with('success', 'Phòng đã được tạo thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Room $room)
    {
        $room->load(['department']);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $departments = Department::all();
        return view('admin.rooms.edit', compact('room', 'departments'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:rooms,code,' . $room->id,
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string'
        ]);

        try {
            $room->update($validated);
            return redirect()->route('rooms.index')
                ->with('success', 'Phòng đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Room $room)
    {
        try {
            $room->delete();
            return redirect()->route('rooms.index')
                ->with('success', 'Phòng đã được xóa thành công.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
