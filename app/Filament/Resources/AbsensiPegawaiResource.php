<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AbsensiPegawai;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AbsensiPegawaiResource\Pages;
use App\Filament\Resources\AbsensiPegawaiResource\RelationManagers;

class AbsensiPegawaiResource extends Resource
{
    protected static ?string $model = AbsensiPegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Absensi Pegawai';

    protected static ?string $navigationGroup = 'Data Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('pegawai_id')
                    ->relationship('pegawai', 'name')
                    ->required(),
                DatePicker::make('tanggal')->required(),
                DateTimePicker::make('waktu_checkin'),
                DateTimePicker::make('waktu_checkout'),
                TextInput::make('lat_checkin')->numeric(),
                TextInput::make('long_checkin')->numeric(),
                TextInput::make('lat_checkout')->numeric(),
                TextInput::make('long_checkout')->numeric(),
                Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'terlambat' => 'Terlambat',
                        'tanpa_keterangan' => 'Tanpa Keterangan',
                        'cuti' => 'Cuti',
            ])
            ->required(),
        Textarea::make('catatan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                   TextColumn::make('pegawai.name')
                ->label('Nama Pegawai')
                ->searchable()
                ->sortable(),

            TextColumn::make('tanggal')
                ->label('Tanggal')
                ->date('d-m-Y')
                ->sortable(),

            TextColumn::make('waktu_checkin')
                ->label('Check-in')
                ->dateTime('H:i:s'),

            TextColumn::make('waktu_checkout')
                ->label('Check-out')
                ->dateTime('H:i:s'),

            TextColumn::make('lat_checkin')
                ->label('Lat Check-in')
                ->formatStateUsing(fn ($state) => number_format($state, 6)),

            TextColumn::make('long_checkin')
                ->label('Long Check-in')
                ->formatStateUsing(fn ($state) => number_format($state, 6)),

            TextColumn::make('lat_checkout')
                ->label('Lat Check-out')
                ->formatStateUsing(fn ($state) => number_format($state, 6)),

            TextColumn::make('long_checkout')
                ->label('Long Check-out')
                ->formatStateUsing(fn ($state) => number_format($state, 6)),

            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'hadir' => 'success',
                    'terlambat' => 'warning',
                    'cuti' => 'info',
                    'tanpa_keterangan' => 'danger',
                    default => 'gray',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAbsensiPegawais::route('/'),
            'create' => Pages\CreateAbsensiPegawai::route('/create'),
            'edit' => Pages\EditAbsensiPegawai::route('/{record}/edit'),
        ];
    }
}
