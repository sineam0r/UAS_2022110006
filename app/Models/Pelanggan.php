<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama',
        'usia',
        'alamat',
        'no_telp',
        'no_sim',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
