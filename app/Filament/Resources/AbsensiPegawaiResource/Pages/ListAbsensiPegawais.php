<?php

namespace App\Filament\Resources\AbsensiPegawaiResource\Pages;

use App\Filament\Resources\AbsensiPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAbsensiPegawais extends ListRecords
{
    protected static string $resource = AbsensiPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
