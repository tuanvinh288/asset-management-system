<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('device_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id'); // Liên kết về bảng devices
            $table->string('code')->unique(); // Mã thiết bị riêng (như QR code hay dán label)
            $table->enum('status', ['available', 'pending', 'in_use', 'maintenance', 'broken'])->default('available');
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_items');
    }
};
