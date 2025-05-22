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
        // database/migrations/xxxx_xx_xx_create_borrows_table.php
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người mượn
            $table->unsignedBigInteger('staff_id')->nullable(); // Người duyệt
            $table->date('borrow_date');
            $table->date('return_date')->nullable();
            $table->text('reason')->nullable(); // Lý do mượn
            $table->text('note')->nullable(); // Ghi chú
            $table->enum('device_status_before', ['new', 'good', 'normal', 'damaged'])->nullable(); // Trạng thái thiết bị trước khi mượn
            $table->string('device_image_before')->nullable(); // Ảnh thiết bị trước khi mượn
            $table->enum('status', ['pending', 'approved', 'borrowed', 'returned'])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('staff_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
