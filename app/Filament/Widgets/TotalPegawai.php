<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class TotalPegawai extends ChartWidget
{
protected static ?string $maxHeight = '300px';


// protected static ?int $columnSpan = 6;

    protected function getData(): array
{
    $totalAdmin = User::role('admin')->count();
    $totalKepalaUPTD = User::role('kepalaUPTD')->count();
    $totalPegawai = User::role('pegawai')->count();

    return [
        'datasets' => [
            [
                'label' => 'Total User per Role',
                'data' => [$totalAdmin, $totalKepalaUPTD, $totalPegawai],
                'backgroundColor' => ['#3B82F6', '#F59E0B', '#10B981'], // biru, kuning, hijau
            ],
        ],
        'labels' => ['Admin', 'Kepala UPTD', 'Pegawai'],
    ];
}

protected function getType(): string
{
    return 'bar';
}
}
