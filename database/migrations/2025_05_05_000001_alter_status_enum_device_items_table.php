<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Chỉ thực hiện với MySQL
        DB::statement("ALTER TABLE device_items MODIFY COLUMN status ENUM('available', 'pending', 'in_use', 'maintenance', 'broken') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nếu muốn rollback về enum cũ (tuỳ vào enum cũ của bạn)
        DB::statement("ALTER TABLE device_items MODIFY COLUMN status ENUM('available', 'in_use', 'maintenance', 'broken') NOT NULL DEFAULT 'available'");
    }
};
