<?php

namespace App\Filament\Resources\RentalResource\Pages;

use App\Filament\Resources\RentalResource;
use App\Models\Kendaraan;
use App\Models\Perlengkapan;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRental extends CreateRecord
{
    protected static string $resource = RentalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $kendaraan = Kendaraan::find($this->record->kendaraan_id);
        $kendaraan->update(['status' => 'Tidak Tersedia']);

        if ($this->record->perlengkapan) {
            foreach ($this->record->perlengkapan as $item) {
                $perlengkapan = Perlengkapan::find($item['perlengkapan_id']);
                if ($perlengkapan) {
                    $newStock = $perlengkapan->stok - $item['stok'];
                    $perlengkapan->update(['stok' => $newStock]);
                }
            }
        }
    }
}
