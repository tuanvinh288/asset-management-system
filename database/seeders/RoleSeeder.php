<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các quyền
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_devices',
            'manage_device_items',
            'manage_borrows',
            'manage_maintenances',
            'manage_rooms',
            'manage_reports',
            'manage_settings',
            'manage_categories',
            'manage_units',
            'manage_suppliers'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Tạo role admin nếu chưa tồn tại
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        // Tạo role user nếu chưa tồn tại
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view_dashboard',
            'manage_borrows',
            'manage_maintenances'
        ]);
    }
}
