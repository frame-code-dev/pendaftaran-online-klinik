<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TransaksiVerifikasiController extends Controller
{
    public function index(Request $request) {
        $param['title']  = 'Verifikasi';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
        ->orderBy('no_antrian','ASC')->get();
        return view('backoffice.transaksi.verifikasi.index',$param);
    }

    public function detail() {
        $param['title'] = 'Verifikasi Data Pasien';
        return view('backoffice.transaksi.verifikasi.verifikasi',$param);
    }

    public function readScan(Request $request) {
        $kode_pendaftaran = $request->get('no_antrian');
        if ($kode_pendaftaran == '') {
            return 'error';
        }
        try {
            $pendaftaran = PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->first();
            if (!isset($pendaftaran)) {
                return 'error';
            }
            if (Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
            }else{
                PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                    'status_pendaftaran' => 'batal',
                ]);
            }
            return 'sukses';

        } catch (Exception $th) {
            return $th;
        }
    }

    public function updateManual($id) {
        $pendaftaran = PendaftaranPasien::find($id);
        if ($pendaftaran->status_verifikasi == 'sudah-verifikasi') {
            toast('Pendaftaran sudah diverifikasi','error');
            return redirect()->route('verifikasi.index');
        }else{
            if (Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                PendaftaranPasien::find($id)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
            }else{
                PendaftaranPasien::find($id)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                    'status_pendaftaran' => 'batal',
                ]);
            }
            toast('Pendaftaran diverifikasi','success');
            return redirect()->route('verifikasi.index');
        }
    }
}
