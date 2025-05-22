<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->string('description')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, select
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'max_borrow_days',
                'value' => json_encode(7),
                'description' => 'Số ngày mượn thiết bị tối đa',
                'type' => 'number',
                'group' => 'borrow'
            ],
            [
                'key' => 'max_devices_per_borrow',
                'value' => json_encode(5),
                'description' => 'Số lượng thiết bị tối đa được mượn mỗi lần',
                'type' => 'number',
                'group' => 'borrow'
            ],
            [
                'key' => 'max_room_borrow_days',
                'value' => json_encode(14),
                'description' => 'Số ngày mượn phòng tối đa',
                'type' => 'number',
                'group' => 'room'
            ],
            [
                'key' => 'notification_email',
                'value' => json_encode('admin@example.com'),
                'description' => 'Email nhận thông báo',
                'type' => 'text',
                'group' => 'notification'
            ],
            [
                'key' => 'qr_code_size',
                'value' => json_encode(200),
                'description' => 'Kích thước QR code (pixel)',
                'type' => 'number',
                'group' => 'qr_code'
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
