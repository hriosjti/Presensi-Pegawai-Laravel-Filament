<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LokasiKantor;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LokasiKantorResource\Pages;
use App\Filament\Resources\LokasiKantorResource\RelationManagers;

class LokasiKantorResource extends Resource
{
    protected static ?string $model = LokasiKantor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Lokasi Kantor';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
           TextInput::make('nama')->required(),

            Map::make('location')
                ->label('Pilih Lokasi di Peta')
                ->defaultLocation(latitude: -5.102991952839205, longitude: 105.33804423926264)
                ->zoom(15)
                ->draggable(true)
                ->clickable(true)
                ->showMarker(true)
                ->liveLocation(true, true, 5000)
                ->rangeSelectField('radius_meter')
                ->geoMan(true)
                ->afterStateUpdated(function (Set $set, ?array $state): void {
                    $set('latitude', $state['lat'] ?? null);
                    $set('longitude', $state['lng'] ?? null);
                    $set('geojson', isset($state['geojson']) ? json_encode($state['geojson']) : null);
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    if ($record) {
                        $set('location', [
                            'lat' => $record->latitude,
                            'lng' => $record->longitude,
                            'geojson' => $record->geojson ? json_decode($record->geojson) : null,
                        ]);
                    }
                }),

            TextInput::make('radius_meter')
                ->label('Radius (meter)')
                ->numeric()
                ->required(),

            // Field tersembunyi untuk latitude dan longitude supaya tersimpan
            TextInput::make('latitude')->required(),
            TextInput::make('longitude')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('nama')->label('Nama Lokasi'),

            TextColumn::make('latitude'),
            TextColumn::make('longitude')
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
            'index' => Pages\ListLokasiKantors::route('/'),
            'create' => Pages\CreateLokasiKantor::route('/create'),
            'edit' => Pages\EditLokasiKantor::route('/{record}/edit'),
        ];
    }
}
