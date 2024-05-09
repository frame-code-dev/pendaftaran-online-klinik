<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function LaporanJenis()  {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')->latest()->get();
        return view('backoffice.laporan.laporan-jenis',$param);
    }
}
