<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerlengkapanResource\Pages;
use App\Filament\Resources\PerlengkapanResource\RelationManagers;
use App\Models\Perlengkapan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PerlengkapanResource extends Resource
{
    protected static ?string $model = Perlengkapan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required()->label('Nama Perlengkapan'),
                TextInput::make('harga')->required()->numeric()->prefix('Rp.')->label('Harga Perlengkapan'),
                TextInput::make('stok')->required()->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('nama')->label('Nama Perlengkapan')->searchable(),
                TextColumn::make('harga')->label('Harga Perlengkapan')
                    ->prefix('Rp. ')->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))->suffix('/hari'),
            ])
            ->filters([
                Filter::make('harga')
                    ->form([
                        TextInput::make('min')->numeric()->label('Min. Harga'),
                        TextInput::make('max')->numeric()->label('Max. Harga'),
                    ])->columns(2)
                    ->query(function (Builder $query, array $data) {
                        return $query->when(
                            $data['min'],
                            fn (Builder $query) => $query->where('harga', '>=', $data['min'])
                        )->when(
                            $data['max'],
                            fn (Builder $query) => $query->where('harga', '<=', $data['max'])
                        );
                    })
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
            'index' => Pages\ListPerlengkapans::route('/'),
            'create' => Pages\CreatePerlengkapan::route('/create'),
            'view' => Pages\ViewPerlengkapan::route('/{record}'),
            'edit' => Pages\EditPerlengkapan::route('/{record}/edit'),
        ];
    }
}
