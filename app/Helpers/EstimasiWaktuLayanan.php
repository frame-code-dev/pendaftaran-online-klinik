<?php

namespace App\Helpers;

use Carbon\Carbon;

class EstimasiWaktuLayanan
{
    public static function estimasi($tanggalKunjungan, $nomorAntrian)
    {
        // Mengatur waktu buka dan waktu tutup
        $waktuBuka = Carbon::createFromTime(7, 0, 0); // Jam 07:00:00
        $waktuTutup = Carbon::createFromTime(21, 0, 0); // Jam 21:00:00

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
