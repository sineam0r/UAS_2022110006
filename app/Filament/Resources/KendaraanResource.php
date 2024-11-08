<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Filament\Resources\KendaraanResource\RelationManagers;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('jenis')
                    ->options([
                        'Mobil' => 'Mobil',
                        'Motor' => 'Motor',
                    ])->required()->native(false),
                TextInput::make('no_polisi')->required()->label('Nomor Polisi'),
                TextInput::make('merk')->required(),
                TextInput::make('model')->required(),
                TextInput::make('harga_sewa')->required()->numeric()->prefix('Rp.')->label('Harga Sewa per Hari'),
                ToggleButtons::make('status')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Tidak Tersedia' => 'Tidak Tersedia',
                    ])->required()->default('Tersedia')->grouped()
                    ->colors([
                        'Tersedia' => 'success',
                        'Tidak Tersedia' => 'danger',
                    ])
                    ->icons([
                        'Tersedia' => 'heroicon-o-check-circle',
                        'Tidak Tersedia' => 'heroicon-o-x-circle',
                    ]),
                FileUpload::make('gambar'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('jenis'),
                TextColumn::make('no_polisi'),
                TextColumn::make('merk'),
                TextColumn::make('model'),
                TextColumn::make('harga_sewa')->sortable()->label('Harga Sewa')
                    ->prefix('Rp. ')->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->suffix('/hari'),
                IconColumn::make('status')->boolean(),
                ImageColumn::make('gambar'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'view' => Pages\ViewKendaraan::route('/{record}'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }
}
