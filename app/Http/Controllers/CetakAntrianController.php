<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;

class CetakAntrianController extends Controller
{
    public function index(Request $request) {
        $param['title'] = 'Cetak Antrian';
        $param['data'] = PendaftaranPasien::with('pasien','dokter','poliklinik')->find($request->id);
        return view('backoffice.cetak-antrian.index',$param);
    }

    public function pdf($id) {
        $param['title'] = 'Cetak Antrian';
        $param['data'] = PendaftaranPasien::with('pasien','dokter','poliklinik')->find($id);
        return view('backoffice.cetak-antrian.pdf',$param);
    }
}
