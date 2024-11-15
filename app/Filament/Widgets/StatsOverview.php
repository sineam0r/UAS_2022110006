<?php

namespace App\Filament\Widgets;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Perlengkapan;
use App\Models\Supir;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kendaraan', Kendaraan::count()),
            Stat::make('Total Pelanggan', Pelanggan::count()),
            Stat::make('Total Supir', Supir::count()),
            Stat::make('Total Stok Perlengkapan', Perlengkapan::sum('stok')),
        ];
    }
}
