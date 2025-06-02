<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PegawaiResource;

class CreatePegawai extends CreateRecord
{
    protected static string $resource = PegawaiResource::class;
    
      protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buat akun user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Masukkan user_id ke data student
        $data['user_id'] = $user->id;

        return $data;
    }
}
