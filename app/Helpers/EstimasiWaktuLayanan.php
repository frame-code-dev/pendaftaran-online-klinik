<?php

namespace App\Helpers;

use Carbon\Carbon;

class EstimasiWaktuLayanan
{
    public static function estimasi($tanggalKunjungan, $nomorAntrian,$jam_kerja)
    {
        $waktu = explode('-', $jam_kerja);
        // Mengatur waktu buka dan waktu tutup
        // Mengatur waktu buka dan waktu tutup
        list($waktuBuka, $waktuTutup) = array_map(function($time) {
            // Mengurai jam dan menit dari string
            list($jam, $menit) = explode(':', $time);
            return Carbon::createFromTime($jam, $menit, 0); // Menetapkan detik ke 0
        }, $waktu);

        // Menghitung selang waktu berdasarkan nomor antrian (dalam menit)
        $selangWaktu = ($nomorAntrian - 1) * 30; // Nomor antrian dimulai dari 1

        // Menambahkan selang waktu ke waktu buka
        $estimasiWaktu = $waktuBuka->copy()->addMinutes($selangWaktu);

        // Mengatur tanggal kunjungan
        $tanggalKunjungan = Carbon::parse($tanggalKunjungan);

        // Jika estimasi waktu melebihi waktu tutup, kembalikan waktu tutup
        if ($estimasiWaktu->greaterThanOrEqualTo($waktuTutup)) {
            return $waktuTutup;
        }

        // Menggabungkan tanggal kunjungan dengan estimasi waktu
        $estimasiWaktu->setDate($tanggalKunjungan->year, $tanggalKunjungan->month, $tanggalKunjungan->day);

        return $estimasiWaktu;
    }
}
