<?php

namespace App\Helpers;

use App\Models\Antrian; // Sesuaikan dengan model yang digunakan untuk data antrian
use App\Models\PendaftaranPasien;
use Carbon\Carbon;

class NomorAntrianGenerator
{
    public static function generate($date = null, $dokter_id)
    {
        // Mendapatkan jumlah antrian yang sudah ada dalam database untuk status online
        $jumlahAntrianOnline = 0;
        $jumlahAntrianOffline = 0;
        if ($date != null) {
            $jumlahAntrianOnline = PendaftaranPasien::where('no_antrian','!=',null )->where('jenis_pendaftaran', 'online')->where('dokter_id',$dokter_id)->where('tanggal_kunjungan', $date)->count();

            // Mendapatkan jumlah antrian yang sudah ada dalam database untuk jenis_pendaftaran offline
            $jumlahAntrianOffline = PendaftaranPasien::where('no_antrian','!=',null)->where('jenis_pendaftaran', 'offline')->where('dokter_id',$dokter_id)->where('tanggal_kunjungan', $date)->count();
        }else{
            $jumlahAntrianOnline = PendaftaranPasien::where('no_antrian','!=',null)->where('jenis_pendaftaran', 'online')->where('dokter_id',$dokter_id)->latest()->count();

            // Mendapatkan jumlah antrian yang sudah ada dalam database untuk jenis_pendaftaran offline
            $jumlahAntrianOffline = PendaftaranPasien::where('no_antrian','!=',null)->where('jenis_pendaftaran', 'offline')->where('dokter_id',$dokter_id)->latest()->count();
        }
        // Menentukan nomor antrian berdasarkan status yang didahulukan (online atau offline)
        if ($jumlahAntrianOnline > $jumlahAntrianOffline) {
            $nomorAntrian = $jumlahAntrianOnline + 1;
        } else {
            $nomorAntrian = $jumlahAntrianOffline + 1;
        }

        // Mengatur waktu buka dan waktu tutup
        $waktuBuka = Carbon::createFromTime(7, 0, 0); // Jam 07:00:00
        $waktuTutup = Carbon::createFromTime(21, 0, 0); // Jam 21:00:00

        // Mendapatkan waktu saat ini
        $waktuSaatIni = Carbon::now();

        // Jika waktu saat ini melebihi waktu tutup, reset nomor antrian ke 1
        if ($waktuSaatIni->greaterThanOrEqualTo($waktuTutup)) {
            $nomorAntrian = 1;
        }

        // Format nomor antrian (misal: ANTRIAN-01)
        $nomorAntrianFormatted = str_pad($nomorAntrian, 2, '0', STR_PAD_LEFT);

        return $nomorAntrianFormatted;
    }
}
