<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiAntrianKlinikController extends Controller
{
    public function index(Request $request) {
        $dokter = $request->get('dokter');
        $tanggal = Carbon::parse($request->tanggal)->format('Y-m-d');
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
                    ->when($request->get('tanggal'),function($query) use ($tanggal) {
                        $query->whereDate('tanggal_kunjungan',$tanggal);
                    })
                    ->orderByDesc('tanggal_kunjungan')->get();

        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
        return view('backoffice.transaksi.antrian.index',$param);
    }

    public function updateManual($id,Request $request) {
        $pendaftaran = PendaftaranPasien::find($id);
        if ($pendaftaran->status_pendaftaran == 'selesai') {
            toast('Pendaftaran sudah diverifikasi','error');
            return redirect()->route('antrian-klinik.index');
        }else{
            PendaftaranPasien::find($id)->update([
                'status_pendaftaran' => $request->get('status'),
            ]);
            toast('Berhasil mengubah status pendaftaran','success');
            return redirect()->route('antrian-klinik.index');
        }
    }
}
