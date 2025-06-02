<?php

namespace App\Filament\Resources;

use App\Models\Pegawai;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\PegawaiResource\Pages;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'Data Pegawai';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->unique(),

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->minLength(8)
                    ->dehydrateStateUsing(fn ($state) => $state)
                    ->dehydrated(fn (string $context): bool => $context === 'create')
                    ->live(),

                TextInput::make('nip'),

                TextInput::make('jabatan'),

                TextInput::make('divisi'),

                FileUpload::make('foto')
                    ->image()
                    ->directory('foto')  // rekomendasi agar foto tersimpan di folder 'foto'
                    ->disk('public'),   // pastikan pakai disk public
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),

                TextColumn::make('nip'),

                TextColumn::make('jabatan'),

                TextColumn::make('divisi'),

                ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')  // penting untuk path yang benar
                    ->size(50)
                    ->circular(),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
