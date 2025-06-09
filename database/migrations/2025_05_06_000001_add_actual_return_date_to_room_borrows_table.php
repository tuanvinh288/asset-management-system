<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_borrows', function (Blueprint $table) {
            $table->dateTime('actual_return_date')->nullable()->after('return_date');
        });
    }

    public function down(): void
    {
        Schema::table('room_borrows', function (Blueprint $table) {
            $table->dropColumn('actual_return_date');
        });
    }
}; 