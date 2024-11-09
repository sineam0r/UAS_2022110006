<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupirResource\Pages;
use App\Filament\Resources\SupirResource\RelationManagers;
use App\Models\Supir;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupirResource extends Resource
{
    protected static ?string $model = Supir::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manajemen Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->required(),
                TextInput::make('usia')->required()->numeric(),
                TextInput::make('alamat')->required(),
                TextInput::make('no_telp')->required()->label('Nomor Telepon'),
                CheckboxList::make('lisensi')->required()
                    ->options([
                        'A' => 'SIM A',
                        'B1' => 'SIM B1',
                        'B2' => 'SIM B2',
                        'C' => 'SIM C',
                    ])->columns(4),
                TextInput::make('tarif')->required()->numeric()->prefix('Rp.')->label('Tarif per Hari'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('nama')->searchable(),
                TextColumn::make('usia'),
                TextColumn::make('alamat'),
                TextColumn::make('no_telp')->label('Nomor Telepon'),
                TextColumn::make('lisensi')->badge(),
                TextColumn::make('tarif')->sortable()->label('Tarif per Hari')
                    ->prefix('Rp. ')->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))->suffix('/hari'),
            ])
            ->filters([
                SelectFilter::make('lisensi')
                    ->options([
                        'A' => 'SIM A',
                        'B1' => 'SIM B1',
                        'B2' => 'SIM B2',
                        'C' => 'SIM C',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('lisensi', 'LIKE', "%{$data['value']}%");
                        }
                    }),
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
            'index' => Pages\ListSupirs::route('/'),
            'create' => Pages\CreateSupir::route('/create'),
            'view' => Pages\ViewSupir::route('/{record}'),
            'edit' => Pages\EditSupir::route('/{record}/edit'),
        ];
    }
}
