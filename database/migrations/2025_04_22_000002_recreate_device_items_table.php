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
        // Lưu dữ liệu hiện tại
        $deviceItems = DB::table('device_items')->get();

        // Xóa các bảng phụ thuộc trước
        Schema::dropIfExists('borrow_details');
        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('qr_scans');
        Schema::dropIfExists('device_items');

        // Tạo lại bảng device_items
        Schema::create('device_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->string('code')->unique();
            $table->string('serial_number')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'broken'])->default('available');
            $table->boolean('is_fixed')->default(false);
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('qr_token')->unique()->nullable();
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });

        // Khôi phục dữ liệu
        foreach ($deviceItems as $item) {
            DB::table('device_items')->insert([
                'id' => $item->id,
                'device_id' => $item->device_id,
                'code' => $item->code,
                'serial_number' => $item->serial_number,
                'status' => $item->status,
                'is_fixed' => $item->is_fixed ?? false,
                'room_id' => $item->room_id,
                'supplier_id' => $item->supplier_id,
                'qr_code' => $item->qr_code,
                'qr_token' => $item->qr_token,
                'last_scanned_at' => $item->last_scanned_at,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ]);
        }

        // Tạo lại các bảng phụ thuộc
        Schema::create('borrow_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrow_id');
            $table->unsignedBigInteger('device_item_id');
            $table->dateTime('actual_return_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('borrow_id')->references('id')->on('borrows')->onDelete('cascade');
            $table->foreign('device_item_id')->references('id')->on('device_items')->onDelete('cascade');
        });

        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_item_id');
            $table->string('type')->default('repair');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->text('description');
            $table->string('status')->default('pending');
            $table->text('result')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->date('next_maintenance_date')->nullable();
            $table->integer('maintenance_interval')->nullable();
            $table->timestamps();

            $table->foreign('device_item_id')->references('id')->on('device_items')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::create('qr_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_item_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action')->nullable();
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('maintenances');
        Schema::dropIfExists('borrow_details');
        Schema::dropIfExists('device_items');
    }
};
