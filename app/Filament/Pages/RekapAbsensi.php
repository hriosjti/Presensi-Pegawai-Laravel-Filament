<?php
namespace App\Filament\Pages;

use App\Models\AbsensiPegawai;
use App\Models\Pegawai;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Livewire\Component;


class RekapAbsensi extends Page
{
    use \Livewire\WithPagination;

    protected static string $view = 'filament.pages.rekap-absensi';
    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Laporan Absensi';

    protected static ?string $navigationGroup = 'Laporan';



    public $pegawaiId = '';
    public $tanggalMulai;
    public $tanggalSampai;
    public $namaPegawai = '';

    public $pegawaiList;
    public $data = [];

    public function mount()
    {
        $this->pegawaiList = \App\Models\Pegawai::all();
        $this->tanggalMulai = now()->startOfMonth()->format('Y-m-d');
        $this->tanggalSampai = now()->endOfMonth()->format('Y-m-d');
        $this->filterData();
    }

    public function updated($property)
    {
        $this->filterData();
    }

    public function filterData()
    {
        $query = \App\Models\AbsensiPegawai::with('pegawai')
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSampai]);

        if ($this->pegawaiId) {
            $query->where('pegawai_id', $this->pegawaiId);
        }

        if ($this->namaPegawai) {
            $query->whereHas('pegawai', function ($q) {
                $q->where('name', 'like', '%' . $this->namaPegawai . '%');
            });
        }

        $absensis = $query->get()->groupBy('pegawai_id');

        $this->data = [];

        foreach ($absensis as $pegawaiId => $absensiGroup) {
            $pegawai = $absensiGroup->first()->pegawai;
            $totalHari = $absensiGroup->count();
            $hadir = $absensiGroup->where('status', 'hadir')->count();
            $terlambat = $absensiGroup->where('status', 'terlambat')->count();
            $tanggal = $absensiGroup->pluck('tanggal')->toArray();

            $this->data[] = (object)[
                'pegawai' => $pegawai,
                'total_hari' => $totalHari,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'tanggal' => $tanggal,
            ];
        }
    }
}
