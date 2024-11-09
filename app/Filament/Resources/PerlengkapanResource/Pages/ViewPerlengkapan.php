<?php

namespace App\Filament\Resources\PerlengkapanResource\Pages;

use App\Filament\Resources\PerlengkapanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPerlengkapan extends ViewRecord
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
