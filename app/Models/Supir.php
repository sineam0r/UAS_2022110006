<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supir extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'usia',
        'alamat',
        'no_telp',
        'lisensi',
        'tarif',
    ];

    public function setLisensiAttribute($value)
    {
        $this->attributes['lisensi'] = is_array($value) ? implode(',', $value) : $value;
    }

    public function getLisensiAttribute($value)
    {
        $lisensiArray = explode(',', $value);
        sort($lisensiArray);
        return $lisensiArray;
    }
}
