<?php

namespace App\Filament\Resources\SupirResource\Pages;

use App\Filament\Resources\SupirResource;
use App\Filament\Resources\SupirResource\Widgets\SupirStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupirs extends ListRecords
{
    protected static string $resource = SupirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SupirStats::class
        ];
    }
}
