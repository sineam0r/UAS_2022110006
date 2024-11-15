<?php

namespace App\Filament\Resources\PelangganResource\Widgets;

use App\Models\Pelanggan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PelangganStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelanggan', Pelanggan::count()),
            Stat::make('Pelanggan Belum Dewasa', Pelanggan::where('usia', '<', 18)->count()),
            Stat::make('Pelanggan Dewasa', Pelanggan::where('usia', '>=', 18)->count()),
        ];
    }
}
