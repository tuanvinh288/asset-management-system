<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('device_items', function (Blueprint $table) {
            $table->string('serial_number')->unique()->after('device_id');
        });
    }

    public function down(): void
    {
        Schema::table('device_items', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
    }
};
