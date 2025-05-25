<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa tất cả quyền và vai trò hiện có
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo các quyền
        $permissions = [
            'view_dashboard' => 'Xem trang chủ',
            'manage_users' => 'Quản lý người dùng',
            'manage_roles' => 'Quản lý vai trò',
            'manage_devices' => 'Quản lý thiết bị',
            'manage_device_items' => 'Quản lý mặt hàng thiết bị',
            'manage_borrows' => 'Quản lý mượn trả',
            'manage_maintenances' => 'Quản lý bảo trì',
            'manage_rooms' => 'Quản lý phòng',
            'manage_reports' => 'Quản lý báo cáo',
            'manage_settings' => 'Quản lý cài đặt',
            'manage_categories' => 'Quản lý danh mục',
            'manage_units' => 'Quản lý đơn vị',
            'manage_suppliers' => 'Quản lý nhà cung cấp',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
                'description' => $description
            ]);
        }

        // Tạo role admin nếu chưa tồn tại
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(array_keys($permissions));

        // Tạo role user nếu chưa tồn tại
        $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacherRole->syncPermissions([
            'view_dashboard',
            'manage_borrows',
            'manage_maintenances',
            'manage_rooms'
        ]);

        $studentRole = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        $studentRole->syncPermissions([
            'view_dashboard',
            'manage_borrows',
            'manage_maintenances',
            'manage_rooms'
        ]);
    }
}
