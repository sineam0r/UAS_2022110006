<?php

namespace App\Filament\Resources\PerlengkapanResource\Pages;

use App\Filament\Resources\PerlengkapanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerlengkapan extends EditRecord
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }
}
