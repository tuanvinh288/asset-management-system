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
            $table->string('qr_code')->nullable()->after('code'); // Đường dẫn đến hình QR code
            $table->string('qr_token')->unique()->nullable()->after('qr_code'); // Token để xác thực QR code
            $table->timestamp('last_scanned_at')->nullable()->after('qr_token'); // Thời điểm quét QR code gần nhất
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_items', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'qr_token', 'last_scanned_at']);
        });
    }
}; 