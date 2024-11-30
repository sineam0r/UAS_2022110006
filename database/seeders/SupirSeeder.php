<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supirs')->insert([
            [
                'id'=>1,
                'nama'=>'Rudi',
                'usia'=>30,
                'alamat'=>'Jl. Raya', 'no_telp'=>'08123456789', 'lisensi'=>'A',
                'tarif'=>35000
            ],
            [
                'id'=>2,
                'nama'=>'Asep',
                'usia'=>25,
                'alamat'=>'Jl. Jend. Sudirman', 'no_telp'=>'081234567890', 'lisensi'=>'B2',
                'tarif'=>30000
            ],
            [
                'id'=>3,
                'nama'=>'Budi',
                'usia'=>40,
                'alamat'=>'Jl. Jalan', 'no_telp'=>'081234567891', 'lisensi'=>'C',
                'tarif'=>40000
            ],
            [
                'id'=>4,
                'nama'=>'Fuad',
                'usia'=>28,
                'alamat'=>'Jl. Dago', 'no_telp'=>'081234567892', 'lisensi'=>'A',
                'tarif'=>32000
            ],
            [
                'id'=>5,
                'nama'=>'Reni',
                'usia'=>32,
                'alamat'=>'Jl. Anggrek', 'no_telp'=>'081234567893', 'lisensi'=>'B1',
                'tarif'=>28000
            ],
        ]);
    }
}
