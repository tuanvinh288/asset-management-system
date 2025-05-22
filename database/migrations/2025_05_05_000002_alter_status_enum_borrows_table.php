<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'approved', 'borrowed', 'returned', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'approved', 'borrowed', 'returned') NOT NULL DEFAULT 'pending'");
    }
};
