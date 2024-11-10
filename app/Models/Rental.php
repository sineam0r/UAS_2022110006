<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'kendaraan_id',
        'supir_id',
        'pelanggan_id',
        'tgl_pinjam',
        'tgl_kembali',
        'perlengkapan',
        'harga',
        'status',
    ];

    protected $casts = [
        'perlengkapan' => 'array',
        'tgl_pinjam' => 'date',
        'tgl_kembali' => 'date',
        'harga' => 'decimal:0'
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class);
    }

    public function getPerlengkapanFormattedAttribute()
    {
        if (empty($this->perlengkapan)) {
            return '-';
        }

        $items = collect($this->perlengkapan)->map(function ($item) {
            $perlengkapan = Perlengkapan::find($item['perlengkapan_id']);
            if ($perlengkapan) {
                $stok = $item['stok'] ?? 1;
                return "<div>{$perlengkapan->nama} ({$stok} unit)</div>";
            }
            return null;
        })->filter()->join('');

        return empty($items) ? '-' : $items;
    }

    public function setPerlengkapanAttribute($value)
    {
        $this->attributes['perlengkapan'] = json_encode($value);
    }

    public static function hitungTotalHarga($kendaraanId, $supirId, $tglPinjam, $tglKembali, $perlengkapanItems)
    {
        if (!$kendaraanId || !$tglPinjam || !$tglKembali) {
            return null;
        }

        try {
            $jumlahHari = (new DateTime($tglPinjam))->diff(new DateTime($tglKembali))->days + 1;

            $kendaraan = Kendaraan::find($kendaraanId);
            $tarifKendaraan = $kendaraan ? $kendaraan->harga_sewa * $jumlahHari : 0;

            $supir = $supirId ? Supir::find($supirId) : null;
            $tarifSupir = $supir ? $supir->tarif * $jumlahHari : 0;

            $totalPerlengkapan = static::hitungTotalPerlengkapan($perlengkapanItems);

            return $tarifKendaraan + $tarifSupir + $totalPerlengkapan;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected static function hitungTotalPerlengkapan($perlengkapanItems)
    {
        $totalPerlengkapan = 0;

        if (!is_array($perlengkapanItems)) {
            return $totalPerlengkapan;
        }

        foreach ($perlengkapanItems as $item) {
            if (isset($item['perlengkapan_id'])) {
                $perlengkapan = Perlengkapan::find($item['perlengkapan_id']);
                $qty = $item['stok'] ?? 1;
                if ($perlengkapan) {
                    $totalPerlengkapan += $perlengkapan->harga * $qty;
                }
            }
        }

        return $totalPerlengkapan;
    }

    public function hitungHargaPerHari()
    {
        if (!$this->kendaraan_id || !$this->tgl_pinjam || !$this->tgl_kembali) {
            return null;
        }

        return static::hitungTotalHarga(
            $this->kendaraan_id,
            $this->supir_id,
            $this->tgl_pinjam,
            $this->tgl_kembali,
            $this->perlengkapan
        );
    }

    protected static function booted()
    {
        static::saving(function ($rental) {
            $rental->harga = static::hitungTotalHarga(
                $rental->kendaraan_id,
                $rental->supir_id,
                $rental->tgl_pinjam,
                $rental->tgl_kembali,
                $rental->perlengkapan
            );
        });
    }
}
