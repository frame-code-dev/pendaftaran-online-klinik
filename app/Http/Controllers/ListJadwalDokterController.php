<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poliklinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ListJadwalDokterController extends Controller
{
    public function index(Request $request) {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $param['title'] = 'List Dokter';
        $param['data'] = $request->dokter;
        $param['poliklinik'] = Poliklinik::latest()->get();
        $param['data'] = null;
        if ($request->get('dokter') != null) {
            $param['data'] = Dokter::with('jadwal')->find($request->get('dokter'));
        }
        return view('pasien.list-dokter.index',$param);
    }
}
