<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            UnitSeeder::class,
            CategorySeeder::class,
            DeviceSeeder::class,
            RoomSeeder::class,
        ]);
        // User::factory(10)->create();
    }
}
