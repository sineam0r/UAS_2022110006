<?php

namespace App\Filament\Widgets;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Perlengkapan;
use App\Models\Supir;
use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Colors\Color;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalKendaraan = Kendaraan::count();
        $kendaraanDisewa = Rental::where('tgl_kembali', '>=', now())->count();
        $kendaraanTersedia = $totalKendaraan - $kendaraanDisewa;

        $totalSupir = Supir::count();
        $supirOnDuty = Rental::where('tgl_kembali', '>=', now())
            ->whereNotNull('supir_id')
            ->count();
        $supirAvailable = $totalSupir - $supirOnDuty;

        $perlengkapanLow = Perlengkapan::where('stok', '<=', 2)->count();
        $totalStok = Perlengkapan::sum('stok');

        $pelangganBulanIni = Pelanggan::whereMonth('created_at', now()->month)->count();
        $totalPelanggan = Pelanggan::count();

        return [
            Stat::make('Total Kendaraan', $totalKendaraan)
                ->icon('heroicon-o-truck')
                ->color('info')
                ->description("$kendaraanTersedia Tersedia • $kendaraanDisewa Disewa")
                ->descriptionIcon('heroicon-o-information-circle'),

            Stat::make('Total Pelanggan', $totalPelanggan)
                ->icon('heroicon-o-users')
                ->color('success')
                ->description("+$pelangganBulanIni pelanggan bulan ini")
                ->descriptionIcon('heroicon-o-arrow-trending-up'),

            Stat::make('Total Supir', $totalSupir)
                ->icon('heroicon-o-user-group')
                ->color('warning')
                ->description("$supirAvailable Available • $supirOnDuty On Duty")
                ->descriptionIcon('heroicon-o-information-circle'),

            Stat::make('Total Stok Perlengkapan', $totalStok)
                ->icon('heroicon-o-cube')
                ->color($perlengkapanLow > 0 ? 'danger' : 'success')
                ->description($perlengkapanLow > 0 ? "$perlengkapanLow items perlu restock" : "Stok aman")
                ->descriptionIcon($perlengkapanLow > 0 ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-check-circle'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
