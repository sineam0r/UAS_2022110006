<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kendaraans')->insert([
            [
                'id' => 1,
                'jenis' => 'Mobil',
                'no_polisi' => 'T 1234 AB',
                'merk' => 'Toyota',
                'model' => 'Avanza',
                'harga_sewa' => 100000,
                'status' => 'Tersedia',
                'gambar' => 'avanza.jpg'
            ],
            [
                'id' => 2,
                'jenis' => 'Mobil',
                'no_polisi' => 'Z 5678 CD',
                'merk' => 'Mitsubishi',
                'model' => 'Xpander',
                'harga_sewa' => 120000,
                'status' => 'Tersedia',
                'gambar' => 'xpander.jpg'
            ],
            [
                'id' => 3,
                'jenis' => 'Motor',
                'no_polisi' => 'BK 9101 EF',
                'merk' => 'Honda',
                'model' => 'PCX',
                'harga_sewa' => 110000,
                'status' => 'Tersedia',
                'gambar' => 'pcx.jpg'
            ],
            [
                'id' => 4,
                'jenis' => 'Motor',
                'no_polisi' => 'D 1213 GH',
                'merk' => 'Yamaha',
                'model' => 'NMAX',
                'harga_sewa' => 50000,
                'status' => 'Tersedia',
                'gambar' => 'nmax.jpg'
            ],
        ]);
    }
}
