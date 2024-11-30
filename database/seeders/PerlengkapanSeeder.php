<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerlengkapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perlengkapans')->insert([
            [
                'id'=>1,
                'nama'=>'Kursi Bayi',
                'harga'=>45000,
                'stok'=>5,
            ],
            [
                'id'=>2,
                'nama'=>'Roll Bar',
                'harga'=>55000,
                'stok'=>6,
            ],
            [
                'id'=>3,
                'nama'=>'GPS Tracker',
                'harga'=>65000,
                'stok'=>7,
            ],
        ]);
    }
}
