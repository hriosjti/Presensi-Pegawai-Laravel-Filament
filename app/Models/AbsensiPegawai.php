<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPegawai extends Model
{
    use HasFactory;

    protected $table = 'absensi_pegawai';

    protected $fillable = [
        'pegawai_id', 'tanggal', 'waktu_checkin', 'waktu_checkout',
        'lat_checkin', 'long_checkin', 'lat_checkout', 'long_checkout',
        'status', 'catatan',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
