<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\PendaftaranPasien;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TransaksiVerifikasiController extends Controller
{
    public function index(Request $request) {
        $param['title']  = 'Verifikasi';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                    ->orderByDesc('tanggal_kunjungan')
                    ->where('jenis_pendaftaran','online')->get();
        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
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
                PendaftaranPasien::where('kode_pendaftaran',(string)$kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
                return 'sukses';
            }else{
                PendaftaranPasien::where('kode_pendaftaran',(string)$kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
                return 'sukses';
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
                ]);
            }
            toast('Pendaftaran diverifikasi','success');
            return redirect()->route('verifikasi.index');
        }
    }

    public function scanManual(Request $request) {
        $kode_pendaftaran = $request->get('kode_pendaftaran');
        if ($kode_pendaftaran == '') {
            toast('Kode pendaftaran Harap terisi','error');
            return redirect()->route('verifikasi.detail');
        }
        try {
            $pendaftaran = PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->first();
            if (!isset($pendaftaran)) {
                toast('Kode pendaftaran tidak ditemukan','error');
                return redirect()->route('verifikasi.detail');
            }
            if (Carbon::parse($pendaftaran->tanggal_kunjungan)->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
            }else{
                PendaftaranPasien::where('kode_pendaftaran',(string) $kode_pendaftaran)->update([
                    'status_verifikasi' => 'sudah-verifikasi',
                ]);
            }
            toast('Pendaftaran berhasil di verifikasi.','success');
            return redirect()->route('verifikasi.index');
        } catch (Exception $th) {
            return $th;
        }
    }
}
