<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = User::with('department')->get(); // Nếu có quan hệ với khoa
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        $departments = Department::all(); // Nếu có quan hệ với khoa
        return view('admin.users.create', ['user' => null, 'departments' => $departments]);
    }


    // Lưu người dùng mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
        ])->assignRole($request->role_id);

        return redirect()->route('users.index');
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit($id)
    {
        $departments = Department::all(); // Nếu có quan hệ với khoa
        $user = User::findOrFail($id);
        return view('admin.users.create', compact('user', 'departments'));
    }
    // Cập nhật thông tin người dùng
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
        ]);

        // Sync the role
        $user->syncRoles([$request->role_id]);

        return redirect()->route('users.index');
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index');
    }
}
