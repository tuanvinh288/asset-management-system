<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\UserAccountInfoMail;
use App\Models\Borrow;
use Illuminate\Support\Facades\Mail;
use App\Models\DeviceItem;

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
        ]);
        $user->assignRole($request->role_id);

        // Gửi mail thông tin tài khoản
        Mail::to($user->email)->send(new UserAccountInfoMail($user, $request->password));

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
        Borrow::where('user_id', $id)->update(['user_id' => 1]);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index');
    }

    // Import giảng viên từ file Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');
        $data = Excel::toArray([], $file);

        // Sheet đầu tiên
        $rows = $data[0];
        unset($rows[0]); // Bỏ dòng tiêu đề

        foreach ($rows as $row) {
            $name = trim($row[1] ?? '');
            $departmentName = trim($row[2] ?? '');
            if (!$name) continue;

            $department_id = null;
            if ($departmentName) {
                $department = Department::firstOrCreate(['name' => $departmentName]);
                $department_id = $department->id;
            }

            $email = Str::slug($name, '.') . '@example.com';
            $i = 1;
            $baseEmail = $email;
            while (User::where('email', $email)->exists()) {
                $email = Str::slug($name, '.') . $i . '@example.com';
                $i++;
            }

            $password = '123456';
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'department_id' => $department_id,
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('teacher');
            } else {
                $user->roles()->attach(2);
            }

            // Gửi mail thông tin tài khoản
            Mail::to($user->email)->send(new UserAccountInfoMail($user, $password));
        }

        return back()->with('success', 'Import thành công!');
    }

    // Hiển thị form đổi mật khẩu lần đầu
    public function showFirstPasswordForm()
    {
        return view('auth.first_password_change');
    }

    // Xử lý đổi mật khẩu lần đầu
    public function updateFirstPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->first_login = false;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Đổi mật khẩu thành công!');
    }



    public function show($id)
    {
        $user = User::with(['department', 'assignedDevices'])->findOrFail($id);
        $deviceItems = DeviceItem::whereNull('user_id')->where('status', 'available')->get();
        return view('admin.users.show', compact('user', 'deviceItems'));
    }

    public function assignedDevices($id)
    {
        $user = \App\Models\User::with(['assignedDevices.device'])->findOrFail($id);
        return view('admin.users.assigned_devices', compact('user'));
    }
}