<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPasien extends Model
{
    use HasFactory;
    protected $table = 'pendaftaran_pasien';
    protected $fillable = [
        'kode_pendaftaran',
        'no_kartu',
        'no_antrian',
        'jenis_pembayaran',
        'dokter_id',
        'pasien_id',
        'tanggal_kunjungan',
        'estimasi_dilayani',
        'status_pendaftaran',
        'jenis_pendaftaran',
        'poliklinik_id',
        'gambar',
        'status_verifikasi',
    ];
    public function dokter()  {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function poliklinik()  {
        return $this->belongsTo(Poliklinik::class, 'poliklinik_id');
    }

    public function pasien() {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
    public function jadwal() {
        return $this->hasMany(JadwalDokter::class,'dokter_id','dokter_id');
    }
}
