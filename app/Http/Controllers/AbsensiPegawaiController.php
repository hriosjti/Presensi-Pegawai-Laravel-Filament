<?php
namespace App\Http\Controllers;

use App\Models\LokasiKantor;
use Illuminate\Http\Request;
use App\Models\AbsensiPegawai;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsensiPegawaiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        $riwayat = AbsensiPegawai::where('pegawai_id', $pegawai->id ?? 0)
            ->orderByDesc('tanggal')
            ->take(7)
            ->get();

        $tanggalHariIni = Carbon::now()->toDateString();

        $absensiHariIni = AbsensiPegawai::where('pegawai_id', $pegawai->id ?? 0)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        return view('dashboard', compact('riwayat', 'pegawai', 'absensiHariIni'));
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371 * 1000; // meter
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (!$pegawai) {
            return redirect()->back()->withErrors('Akun ini belum terhubung ke data pegawai.');
        }

        $lokasiKantor = LokasiKantor::first();
        if (!$lokasiKantor) {
            return redirect()->back()->withErrors('Lokasi kantor belum diset.');
        }

        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'aksi' => 'required|in:checkin,checkout',
        ], [
            'latitude.required' => 'Latitude lokasi harus diisi.',
            'longitude.required' => 'Longitude lokasi harus diisi.',
            'aksi.in' => 'Aksi absensi tidak valid.',
        ]);

        $jarak = $this->hitungJarak(
            $request->latitude,
            $request->longitude,
            $lokasiKantor->latitude,
            $lokasiKantor->longitude
        );

        $toleransiMeter = 100;
        if ($jarak > $toleransiMeter) {
            return redirect()->back()->withErrors("Lokasi Anda berada di luar radius absen. Jarak: " . round($jarak, 2) . " meter. Maksimal 100 meter dari kantor.");
        }

        $now = Carbon::now();
        $tanggalHariIni = $now->toDateString();

        $absensiHariIni = AbsensiPegawai::where('pegawai_id', $pegawai->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        if ($request->aksi === 'checkin') {
            // **Batasi waktu check-in antara jam 08:00 sampai 08:59**
            $jamMulai = Carbon::createFromTime(8, 0, 0);
            $jamSelesai = Carbon::createFromTime(18, 0, 0);

            if (! $now->between($jamMulai, $jamSelesai)) {
                return redirect()->back()->withErrors('Check-in hanya bisa dilakukan antara pukul 08:00 sampai 18:00.');
            }

            // **Jika sudah check-in hari ini, cek apakah sudah lewat jam 6 pagi besok**
            if ($absensiHariIni && $absensiHariIni->waktu_checkin) {
                $besokJam6 = $now->copy()->addDay()->setTime(6, 0, 0);
                if ($now->lt($besokJam6)) {
                    return redirect()->back()->withErrors('Anda sudah check-in hari ini. Check-in berikutnya hanya bisa dilakukan setelah pukul 06:00 besok.');
                }
            }

            $absensi = $absensiHariIni ?? new AbsensiPegawai([
                'pegawai_id' => $pegawai->id,
                'tanggal' => $tanggalHariIni,
            ]);

            $absensi->waktu_checkin = $now;
            $absensi->lat_checkin = $request->latitude;
            $absensi->long_checkin = $request->longitude;

            $jamMasuk = Carbon::createFromTime(8, 0, 0);
            $status = $now->greaterThan($jamMasuk) ? 'terlambat' : 'hadir';
            $absensi->status = $status;

            $absensi->save();

        } elseif ($request->aksi === 'checkout') {
            if (!$absensiHariIni || !$absensiHariIni->waktu_checkin) {
                return redirect()->back()->withErrors('Anda belum melakukan check-in hari ini.');
            }

            if ($absensiHariIni->waktu_checkout) {
                return redirect()->back()->withErrors('Anda sudah check-out hari ini.');
            }

            $absensiHariIni->waktu_checkout = $now;
            $absensiHariIni->lat_checkout = $request->latitude;
            $absensiHariIni->long_checkout = $request->longitude;
            $absensiHariIni->save();
        } else {
            return redirect()->back()->withErrors('Aksi absensi tidak valid.');
        }

        return redirect()->route('dashboard')->with('success', 'Absensi berhasil disimpan!');
    }
}