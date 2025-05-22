<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_item_id')->constrained('device_items')->onDelete('cascade');
            $table->string('type')->default('repair'); // periodic (định kỳ), repair (sửa chữa)
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->text('description');
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            $table->text('result')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->date('next_maintenance_date')->nullable();
            $table->integer('maintenance_interval')->nullable(); // Số tháng giữa các lần bảo trì
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenances');
    }
};
