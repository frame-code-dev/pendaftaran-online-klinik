<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasien';
    protected $fillable = [
        'no_rm',
        'name',
        'gambar',
        'nik',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'tempat_lahir',
        'tanggal_lahir',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'desa_id',
        'agama',
        'status_kawin',
        'pendidikan',
        'pekerjaan',
        'suku',
        'bahasa',
        'no_hp',
        'nama_ortu',
        'user_id',
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function provinsi() {
        return $this->belongsTo(Provinsi::class,'provinsi_id','id');
    }

    public function kabupaten() {
        return $this->belongsTo(Kabupaten::class,'kabupaten_id','id');
    }
    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class,'kecamatan_id','id');
    }
}
