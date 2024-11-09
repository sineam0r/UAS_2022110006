<?php

namespace App\Filament\Resources\PerlengkapanResource\Pages;

use App\Filament\Resources\PerlengkapanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePerlengkapan extends CreateRecord
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
