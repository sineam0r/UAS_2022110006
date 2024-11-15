<?php

namespace App\Filament\Resources\RentalResource\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Rental;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateColumn;

class RentalStats extends BaseWidget
{
    protected static ?string $heading = 'Statistik Rental';

    public function table(Tables\Table $table): Tables\Table
{
    return $table
        ->query(
            Rental::query()
                ->select('id', 'kendaraan_id', 'supir_id', 'pelanggan_id', 'tgl_pinjam', 'tgl_kembali', 'perlengkapan', 'harga')
                ->with(['kendaraan', 'pelanggan'])
                ->orderBy('tgl_pinjam', 'desc')
        )
        ->columns([
            TextColumn::make('kendaraan.no_polisi'),
            TextColumn::make('supir.nama'),
            TextColumn::make('pelanggan.nama'),
            TextColumn::make('tgl_pinjam')->label('Tanggal Pinjam')->sortable(),
            TextColumn::make('tgl_kembali')->label('Tanggal Kembali')->sortable(),
            TextColumn::make('perlengkapan_formatted')->html()->label('Perlengkapan'),
            TextColumn::make('harga')->numeric()->sortable()->prefix('Rp. '),
        ])
        ->defaultSort('tgl_pinjam', 'desc');
}

}
