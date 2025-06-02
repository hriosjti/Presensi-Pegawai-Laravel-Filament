<?php

namespace App\Filament\Widgets;

use App\Models\AbsensiPegawai;
use Filament\Widgets\ChartWidget;

class StatistikAbsensi extends ChartWidget
{
    protected static ?string $maxHeight = '300px';

    // protected static ?int $columnSpan = 6;
    
  public function getHeading(): string
{
    return 'Statistik Absensi ' . now()->translatedFormat('F Y');
}

    protected function getData(): array
    {
          $bulanIni = now()->month;
    $tahunIni = now()->year;

    $hadir = AbsensiPegawai::whereMonth('tanggal', $bulanIni)
        ->whereYear('tanggal', $tahunIni)
        ->where('status', 'hadir')
        ->count();

    $terlambat = AbsensiPegawai::whereMonth('tanggal', $bulanIni)
        ->whereYear('tanggal', $tahunIni)
        ->where('status', 'terlambat')
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Status Kehadiran Bulan Ini',
                'data' => [$hadir, $terlambat],
                'backgroundColor' => ['#10B981', '#F59E0B'], // Hijau & Kuning
            ],
        ],
        'labels' => ['Hadir', 'Terlambat'],
    ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
