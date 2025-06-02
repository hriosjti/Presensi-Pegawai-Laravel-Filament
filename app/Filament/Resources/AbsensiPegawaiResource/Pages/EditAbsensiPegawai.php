<?php

namespace App\Filament\Resources\AbsensiPegawaiResource\Pages;

use App\Filament\Resources\AbsensiPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAbsensiPegawai extends EditRecord
{
    protected static string $resource = AbsensiPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
