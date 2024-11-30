<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pelanggans')->insert([
            [
                'id'=>1,
                'nik'=>'123456789',
                'nama'=>'Bambang',
                'usia'=>40,
                'alamat'=>'Jl. Raya', 'no_telp'=>'08123456789', 'no_sim'=>'123456789'
            ],
            [
                'id'=>2,
                'nik'=>'987654321',
                'nama'=>'Santi',
                'usia'=>16,
                'alamat'=>'Jl. Jend. Sudirman', 'no_telp'=>'081234567890', 'no_sim'=>''
            ],
            [
                'id'=>3,
                'nik'=>'111222333',
                'nama'=>'Kevin',
                'usia'=>45, 'alamat'=>'Jl. Jalan', 'no_telp'=>'081234567891', 'no_sim'=>'111222333'
            ],
            [
                'id'=>4,
                'nik'=>'444555666',
                'nama'=>'Tia',
                'usia'=>30,
                'alamat'=>'Jl. Dago', 'no_telp'=>'081234567892', 'no_sim'=>'444555666'
            ],
            [
                'id'=>5,
                'nik'=>'777888999',
                'nama'=>'Budi',
                'usia'=>50,
                'alamat'=>'Jl. Anggrek', 'no_telp'=>'081234567893', 'no_sim'=>'777888999'
            ],
        ]);
    }
}
