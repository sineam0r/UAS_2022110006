<?php

namespace App\Filament\Resources\SupirResource\Widgets;

use App\Models\Supir;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupirStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Supir', Supir::count()),
        ];
    }
}
