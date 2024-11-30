<?php

namespace App\Filament\Resources\MaintenanceResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Maintenance;
use Illuminate\Support\Facades\DB;

class MaintenanceStats extends ChartWidget
{
    protected static ?string $heading = 'Statistik Maintenance';

    protected function getData(): array
    {
        $maintenanceData = Maintenance::select(
                DB::raw("DATE_FORMAT(tgl_maintenance, '%Y-%m') as bulan"),
                DB::raw("COUNT(*) as jumlah")
            )
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        $labels = $maintenanceData->pluck('bulan')->toArray();
        $data = $maintenanceData->pluck('jumlah')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Maintenance',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
