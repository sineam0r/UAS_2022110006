<?php

namespace App\Filament\Resources\PerlengkapanResource\Widgets;

use App\Models\Perlengkapan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerlengkapanStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Perlengkapan', Perlengkapan::count()),
            Stat::make('Total Stok Perlengkapan', Perlengkapan::sum('stok')),
        ];
    }
}
