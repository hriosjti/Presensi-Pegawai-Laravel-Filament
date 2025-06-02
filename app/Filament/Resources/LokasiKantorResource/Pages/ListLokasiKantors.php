<?php

namespace App\Filament\Resources\LokasiKantorResource\Pages;

use App\Filament\Resources\LokasiKantorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLokasiKantors extends ListRecords
{
    protected static string $resource = LokasiKantorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
