<?php

namespace App\Filament\Resources\RentalResource\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RentalStats extends ChartWidget
{
    protected static ?string $heading = 'Statistik Rental';
    protected static ?string $maxHeight = '300px';
    public ?string $filter = '7d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => '7 hari terakhir',
            '30d' => '30 hari terakhir',
            'month' => 'Bulan ini',
            'year' => 'Tahun ini',
        ];
    }

    protected function getData(): array
    {
        $data = match ($this->filter) {
            '7d' => $this->getLastNDaysData(7),
            '30d' => $this->getLastNDaysData(30),
            'month' => $this->getCurrentMonthData(),
            'year' => $this->getCurrentYearData(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Rental',
                    'data' => $data['counts'],
                    'borderColor' => '#9333ea',
                    'fill' => false,
                ],
                [
                    'label' => 'Pendapatan (dalam ribuan Rp)',
                    'data' => $data['revenues'],
                    'borderColor' => '#22c55e',
                    'fill' => false,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    private function getLastNDaysData(int $days): array
    {
        $results = Rental::select(
            DB::raw('DATE(tgl_pinjam) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(harga) as revenue')
        )
            ->where('tgl_pinjam', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $counts = [];
        $revenues = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayData = $results->firstWhere('date', $date);

            $labels[] = now()->subDays($i)->format('d M');
            $counts[] = $dayData ? $dayData->count : 0;
            $revenues[] = $dayData ? $dayData->revenue / 1000 : 0;
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'revenues' => $revenues,
        ];
    }

    private function getCurrentMonthData(): array
    {
        $results = Rental::select(
            DB::raw('DATE(tgl_pinjam) as date'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(harga) as revenue')
        )
            ->whereMonth('tgl_pinjam', now()->month)
            ->whereYear('tgl_pinjam', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $counts = [];
        $revenues = [];

        $daysInMonth = now()->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = now()->setDay($day)->format('Y-m-d');
            $dayData = $results->firstWhere('date', $date);

            $labels[] = now()->setDay($day)->format('d M');
            $counts[] = $dayData ? $dayData->count : 0;
            $revenues[] = $dayData ? $dayData->revenue / 1000 : 0;
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'revenues' => $revenues,
        ];
    }

    private function getCurrentYearData(): array
    {
        $results = Rental::select(
            DB::raw('MONTH(tgl_pinjam) as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(harga) as revenue')
        )
            ->whereYear('tgl_pinjam', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $counts = [];
        $revenues = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthData = $results->firstWhere('month', $month);

            $labels[] = Carbon::create()->month($month)->format('M');
            $counts[] = $monthData ? $monthData->count : 0;
            $revenues[] = $monthData ? $monthData->revenue / 1000 : 0;
        }

        return [
            'labels' => $labels,
            'counts' => $counts,
            'revenues' => $revenues,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
