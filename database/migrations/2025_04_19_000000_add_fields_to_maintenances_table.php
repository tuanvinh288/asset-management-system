<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'next_maintenance_date')) {
                $table->date('next_maintenance_date')->nullable();
            }
            if (!Schema::hasColumn('maintenances', 'maintenance_interval')) {
                $table->integer('maintenance_interval')->nullable(); // Số tháng giữa các lần bảo trì
            }
            if (!Schema::hasColumn('maintenances', 'type')) {
                $table->string('type')->default('repair'); // periodic, repair
            }
        });
    }

    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $columns = [
                'next_maintenance_date',
                'maintenance_interval',
                'type'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('maintenances', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}; 