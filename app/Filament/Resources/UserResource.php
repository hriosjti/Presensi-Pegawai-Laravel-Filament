<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'Data Akun User';

    protected static ?string $navigationGroup = 'Setting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique('users', 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->label('Password (biarkan kosong jika tidak diubah)')
                    ->minLength(6)
                    ->maxLength(255)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn (string $context) => $context === 'create'),

                Select::make('roles')
                        ->multiple()
                        ->relationship('roles', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable()->sortable(),
            BadgeColumn::make('roles.name')
                        ->label('Role')
                        ->colors([
                            'info' => 'kepala uptd', // biru
                            'success' => 'admin',
                            'gray' => 'pegawai',
                                 ])
                             ->separator(', '),
            TextColumn::make('created_at')->dateTime()->label('Dibuat'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
