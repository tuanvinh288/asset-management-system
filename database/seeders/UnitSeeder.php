<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Chiếc',
                'symbol' => 'C'
            ],
            [
                'name' => 'Bộ',
                'symbol' => 'B'
            ],
            [
                'name' => 'Ổ',
                'symbol' => 'O'
            ],
            [
                'name' => 'Hộp',
                'symbol' => 'H'
            ],
            [
                'name' => 'Cái',
                'symbol' => 'C'
            ]
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
