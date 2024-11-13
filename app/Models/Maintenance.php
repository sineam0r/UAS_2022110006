<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use  HasFactory;

    protected $fillable = [
        'kendaraan_id',
        'rental_id',
        'tgl_maintenance',
        'jenis',
        'harga',
        'keterangan',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
