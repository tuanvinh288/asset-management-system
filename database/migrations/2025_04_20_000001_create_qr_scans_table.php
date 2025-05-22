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
        Schema::create('qr_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_item_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Người quét, có thể null nếu không yêu cầu đăng nhập
            $table->string('action')->nullable(); // Hành động thực hiện: view, update_status, etc.
            $table->string('old_status')->nullable(); // Trạng thái cũ (nếu có thay đổi trạng thái)
            $table->string('new_status')->nullable(); // Trạng thái mới (nếu có thay đổi trạng thái)
            $table->string('ip_address')->nullable(); // Địa chỉ IP của người quét
            $table->string('user_agent')->nullable(); // Thông tin trình duyệt/thiết bị
            $table->text('notes')->nullable(); // Ghi chú thêm
            $table->timestamps();

            $table->foreign('device_item_id')->references('id')->on('device_items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
    }
}; 