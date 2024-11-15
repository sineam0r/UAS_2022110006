<?php

namespace App\Filament\Resources\KendaraanResource\Widgets;

use App\Models\Kendaraan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KendaraanStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kendaraan', Kendaraan::count()),
            Stat::make('Kendaraan Tersedia', Kendaraan::where('status', 'Tersedia')->count()),
            Stat::make('Kendaraan Dirental', Kendaraan::where('status', 'Tidak Tersedia')->count()),
        ];
    }
}
