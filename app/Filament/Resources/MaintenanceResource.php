<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\MaintenanceResource\RelationManagers;
use App\Models\Maintenance;
use App\Models\Rental;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kendaraan_id')->required()->relationship('kendaraan', 'no_polisi')->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('rental_id', null);
                    }),
                Select::make('rental_id')->relationship('rental', 'id')
                    ->options(function (callable $get) {
                        $kendaraanId = $get('kendaraan_id');
                        if ($kendaraanId) {
                            return Rental::where('kendaraan_id', $kendaraanId)->pluck('id', 'id');
                        }
                        return [];
                    })->required()->reactive(),
                DatePicker::make('tgl_maintenance')->required(),
                Select::make('jenis')->required()
                    ->options([
                        'Ringan' => 'Ringan',
                        'Sedang' => 'Sedang',
                        'Berat' => 'Berat',
                    ]),
                TextInput::make('harga')->required()->numeric()->prefix('Rp.'),
                Textarea::make('keterangan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('kendaraan.no_polisi')->label('No. Polisi')->searchable(),
                TextColumn::make('rental.id')->label('ID Rental')->searchable(),
                TextColumn::make('tgl_maintenance')->label('Tanggal')->date(),
                TextColumn::make('jenis'),
                TextColumn::make('harga')->sortable()->numeric()->prefix('Rp. '),
                TextColumn::make('keterangan')->searchable(),
            ])
            ->filters([
                Filter::make('tgl_maintenance')
                    ->form([
                        DatePicker::make('tgl_maintenance')->label('Tanggal Awal'),
                        DatePicker::make('tgl_maintenance_akhir')->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['tgl_maintenance'],
                                fn (Builder $query) => $query->whereDate('tgl_maintenance', '>=', $data['tgl_maintenance'])
                            )
                            ->when(
                                $data['tgl_maintenance_akhir'],
                                fn (Builder $query) => $query->whereDate('tgl_maintenance', '<=', $data['tgl_maintenance_akhir'])
                            );
                    })->columns(2),
                SelectFilter::make('jenis')
                    ->options([
                        'Ringan' => 'Ringan',
                        'Sedang' => 'Sedang',
                        'Berat' => 'Berat',
                    ]),
                Filter::make('harga')
                    ->form([
                        TextInput::make('min')->numeric()->label('Min. Harga'),
                        TextInput::make('max')->numeric()->label('Max. Harga'),
                    ])->columns(2)
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['min'],
                                fn (Builder $query) => $query->where('harga', '>=', $data['min'])
                            )
                            ->when(
                                $data['max'],
                                fn (Builder $query) => $query->where('harga', '<=', $data['max'])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'view' => Pages\ViewMaintenance::route('/{record}'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
