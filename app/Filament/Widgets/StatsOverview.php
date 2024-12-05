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
    protected static ?string $pollingInterval = '5s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $activeRentals = Rental::where('status', '!=', 'Selesai')
            ->where('tgl_kembali', '>=', now())
            ->get();

        $totalKendaraan = Kendaraan::count();
        $kendaraanDisewa = Kendaraan::where('status', 'Tidak Tersedia')->count();
        $kendaraanTersedia = $totalKendaraan - $kendaraanDisewa;

        $totalSupir = Supir::count();
        $supirOnDuty = Supir::where('status', 'Bertugas')->count();
        $supirAvailable = $totalSupir - $supirOnDuty;

        $perlengkapanLow = Perlengkapan::where('stok', '<=', 2)->count();
        $totalStok = Perlengkapan::sum('stok');

        $pelangganBulanIni = Pelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $totalPelanggan = Pelanggan::count();

        return [
            Stat::make('Total Kendaraan', $totalKendaraan)
                ->icon('heroicon-o-truck')
                ->color('info')
                ->description("$kendaraanTersedia Tersedia • $kendaraanDisewa Disewa")
                ->descriptionIcon('heroicon-o-information-circle')
                ->chart([
                    $kendaraanTersedia,
                    $kendaraanDisewa
                ]),

            Stat::make('Total Pelanggan', $totalPelanggan)
                ->icon('heroicon-o-users')
                ->color('success')
                ->description("+$pelangganBulanIni pelanggan bulan ini")
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->chart([
                    $totalPelanggan - $pelangganBulanIni,
                    $pelangganBulanIni
                ]),

            Stat::make('Total Supir', $totalSupir)
                ->icon('heroicon-o-user-group')
                ->color('warning')
                ->description("$supirAvailable Tersedia • $supirOnDuty Bertugas")
                ->descriptionIcon('heroicon-o-information-circle')
                ->chart([
                    $supirAvailable,
                    $supirOnDuty
                ]),

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
