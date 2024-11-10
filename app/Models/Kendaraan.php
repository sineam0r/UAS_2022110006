<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis',
        'no_polisi',
        'merk',
        'model',
        'harga_sewa',
        'status',
        'gambar',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
