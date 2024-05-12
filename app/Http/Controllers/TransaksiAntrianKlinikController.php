<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiAntrianKlinikController extends Controller
{
    public function index(Request $request) {
        $dokter = $request->get('dokter');
        $poliklinik = $request->get('poliklinik');
        $param['title'] = 'Antrian Poliklinik';
        $param['dokter'] = Dokter::latest()->get();
        $param['poliklinik'] = Poliklinik::latest()->get();
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                    ->when($request->get('dokter'), function ($query) use ($dokter) {
                        $query->where('dokter_id', $dokter);
                    })
                    ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                        $query->where('poliklinik_id', $poliklinik);
                    })
                    ->orderBy('no_antrian','ASC')->get();
        return view('backoffice.transaksi.antrian.index',$param);
    }

    public function updateManual($id) {
        $pendaftaran = PendaftaranPasien::find($id);
        if ($pendaftaran->status_pendaftaran == 'selesai') {
            toast('Pendaftaran sudah diverifikasi','error');
            return redirect()->route('antrian-klinik.index');
        }else{
            if (Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                PendaftaranPasien::find($id)->update([
                    'status_verifikasi' => 'selesai',
                ]);
            }else{
                PendaftaranPasien::find($id)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                    'status_pendaftaran' => 'batal',
                ]);
            }
            toast('Pendaftaran diverifikasi','success');
            return redirect()->route('antrian-klinik.index');
        }
    }
}
