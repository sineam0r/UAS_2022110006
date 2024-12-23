<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalResource\Pages;
use App\Filament\Resources\RentalResource\RelationManagers;
use App\Models\Kendaraan;
use App\Models\Perlengkapan;
use App\Models\Rental;
use App\Models\Supir;
use DateTime;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kendaraan_id')->required()->label('Kendaraan')
                ->options(function ($get, $record) {
                    $availableVehicles = Kendaraan::where('status', 'Tersedia')
                        ->get();

                    if ($record) {
                        $currentVehicle = Kendaraan::find($record->kendaraan_id);
                        if ($currentVehicle && !$availableVehicles->contains('id', $currentVehicle->id)) {
                            $availableVehicles->push($currentVehicle);
                        }
                    }

                    return $availableVehicles->mapWithKeys(function ($kendaraan) {
                        return [
                            $kendaraan->id => "{$kendaraan->no_polisi} - {$kendaraan->merk} {$kendaraan->model}",
                        ];
                    });
                })
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set, $get) =>
                    $set('harga', Rental::hitungTotalHarga(
                        $state,
                        $get('supir_id'),
                        $get('tgl_pinjam'),
                        $get('tgl_kembali'),
                        $get('perlengkapan')
                    ))
                ),
                Select::make('supir_id')->relationship('supir', 'nama')
                ->options(function ($get, $record) {
                    if ($record && $record->supir_id) {
                        return Supir::where('status', 'Tersedia')
                            ->orWhere('id', $record->supir_id)
                            ->pluck('nama', 'id');
                    }

                    return Supir::where('status', 'Tersedia')
                        ->pluck('nama', 'id');
                })
                ->reactive()
                ->afterStateUpdated(function($state, callable $set, $get) {
                    $set('harga', Rental::hitungTotalHarga(
                        $get('kendaraan_id'),
                        $state,
                        $get('tgl_pinjam'),
                        $get('tgl_kembali'),
                        $get('perlengkapan')
                    ));

                    if ($state) {
                        $supir = Supir::find($state);
                        if ($supir) {
                            $supir->update(['status' => 'Bertugas']);
                        }
                    }
                }),
                Select::make('pelanggan_id')->relationship('pelanggan', 'nama'),
                Repeater::make('perlengkapan')
                ->schema([
                    Select::make('perlengkapan_id')->label('Perlengkapan')
                        ->options(Perlengkapan::where('stok', '>', 0)->pluck('nama', 'id'))->reactive()
                        ->afterStateUpdated(function (callable $set, $state, $get) {
                            $perlengkapan = Perlengkapan::find($state);
                            if ($perlengkapan) {
                                $quantity = $get('stok') ?? 1;
                                $set('max_stok', $perlengkapan->stok);
                                $set('harga', $perlengkapan->harga * $quantity);
                            }
                        }),
                    TextInput::make('stok')->numeric()->label('Qty')->reactive()
                        ->rules([
                            fn (Get $get): string => "max:{$get('max_stok')}",
                        ])
                        ->helperText(fn (Get $get): string => "Stok tersedia: {$get('max_stok')}")
                        ->afterStateUpdated(function (callable $set, $state, $get) {
                            $hargaSatuan = $get('perlengkapan_id')
                                ? Perlengkapan::find($get('perlengkapan_id'))->harga
                                : 0;
                            $set('harga', $hargaSatuan * $state);
                        }),
                TextInput::make('max_stok')->hidden(),
                ])->reactive()
                    ->afterStateUpdated(fn($state, callable $set, $get) =>
                        $set('harga', Rental::hitungTotalHarga(
                            $get('kendaraan_id'),
                            $get('supir_id'),
                            $get('tgl_pinjam'),
                            $get('tgl_kembali'),
                            $state
                        ))
                    ),
                DatePicker::make('tgl_pinjam')->required()->reactive()
                    ->afterStateUpdated(fn($state, callable $set, $get) =>
                        $set('harga', Rental::hitungTotalHarga(
                            $get('kendaraan_id'),
                            $get('supir_id'),
                            $state,
                            $get('tgl_kembali'),
                            $get('perlengkapan')
                        ))
                    ),
                DatePicker::make('tgl_kembali')->required()->reactive()
                    ->afterStateUpdated(fn($state, callable $set, $get) =>
                        $set('harga', Rental::hitungTotalHarga(
                            $get('kendaraan_id'),
                            $get('supir_id'),
                            $get('tgl_pinjam'),
                            $state,
                            $get('perlengkapan')
                        ))
                    ),
                TextInput::make('harga')->disabled()->numeric()->prefix('Rp. '),
                ToggleButtons::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Berlangsung' => 'Berlangsung',
                        'Selesai' => 'Selesai',
                ])->inline()->default('Pending')
                ->reactive()
                    ->afterStateUpdated(function ($state, $record) {
                        if ($state === 'Selesai' && $record) {
                            $kendaraan = Kendaraan::find($record->kendaraan_id);
                            if ($kendaraan) {
                                $kendaraan->update(['status' => 'Tersedia']);
                            }

                            $supir = Supir::find($record->supir_id);
                            if ($supir) {
                                $supir->update(['status' => 'Tersedia']);
                            }

                            if ($record->perlengkapan) {
                                foreach ($record->perlengkapan as $item) {
                                    if (isset($item['perlengkapan_id']) && isset($item['stok'])) {
                                        $perlengkapan = Perlengkapan::find($item['perlengkapan_id']);
                                        if ($perlengkapan) {
                                            $perlengkapan->update([
                                                'stok' => $perlengkapan->stok + $item['stok']
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('kendaraan.no_polisi')->label('No. Polisi')->searchable(),
                TextColumn::make('supir.nama')->searchable(),
                TextColumn::make('pelanggan.nama')->searchable(),
                TextColumn::make('perlengkapan_formatted')->html()->label('Perlengkapan'),
                TextColumn::make('tgl_pinjam')->sortable()->label('Tanggal Pinjam')->date(),
                TextColumn::make('tgl_kembali')->sortable()->label('Tanggal Kembali')->date(),
                TextColumn::make('harga')->sortable()->numeric()->prefix('Rp. '),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'view' => Pages\ViewRental::route('/{record}'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }


}
