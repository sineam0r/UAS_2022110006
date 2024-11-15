<?php

namespace App\Filament\Resources\PerlengkapanResource\Pages;

use App\Filament\Resources\PerlengkapanResource;
use App\Filament\Resources\PerlengkapanResource\Widgets\PerlengkapanStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPerlengkapans extends ListRecords
{
    protected static string $resource = PerlengkapanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PerlengkapanStats::class
        ];
    }
}
