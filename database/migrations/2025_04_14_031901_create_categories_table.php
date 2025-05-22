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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // ID tự tăng
            $table->string('name'); // Tên danh mục thiết bị
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái (active/inactive)
            $table->text('description')->nullable(); // Mô tả chi tiết về danh mục
            $table->timestamps(); // Lưu thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
