<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'dokter';
    protected $fillable = [
        'poliklinik_id',
        'user_id',
        'name',
        'gambar',
        'kuota',
        'kuota_bpjs',
        'kuota_terisi',
        'status',
        'jenis_kelamin'
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function poliklinik() {
        return $this->belongsTo(Poliklinik::class,'poliklinik_id','id');
    }

    public function jadwal()  {
        return $this->hasMany(JadwalDokter::class,'dokter_id','id');
    }
}
