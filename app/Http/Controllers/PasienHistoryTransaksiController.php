<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\PendaftaranPasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PasienHistoryTransaksiController extends Controller
{
    public function index() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $param['data'] = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('jenis_pendaftaran','online')->where('pasien_id',Session::get('user')->id)->get();

        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
        return view('pasien.pendaftaran.history-transaksi',$param);
    }

    public function detail(Request $request) {
        $data = PendaftaranPasien::with('pasien','dokter','poliklinik','jadwal')->where('pasien_id',Session::get('user')->id)->where('id',$request->id)->first();
        if ($data) {
            $hari_kunjungan = strtolower(\Carbon\Carbon::parse($data->tanggal_kunjungan)->translatedFormat('l'));

            foreach ($data->jadwal as $key => $value) {
                if ($value->status == $data->jenis_pembayaran) {
                    $jadwalArray = $value->toArray();

                    // Ubah 'Jumat' menjadi 'jumaat' agar sesuai dengan kunci di array
                    $hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan;

                    // Periksa apakah kunci hari ada dalam array, jika ada, set estimasi dokter
                    if (array_key_exists($hari_kunjungan, $jadwalArray)) {
                        $data->estimasi_dokter = $jadwalArray[$hari_kunjungan];
                    }
                }
            }
        }
        return $data;
    }

    public function download($id) {
        $data = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('pasien_id',Session::get('user')->id)->where('id',$id)->first();
        if ($data) {
            $hari_kunjungan = strtolower(\Carbon\Carbon::parse($data->tanggal_kunjungan)->translatedFormat('l'));

            foreach ($data->jadwal as $key => $value) {
                if ($value->status == $data->jenis_pembayaran) {
                    $jadwalArray = $value->toArray();

                    // Ubah 'Jumat' menjadi 'jumaat' agar sesuai dengan kunci di array
                    $hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan;

                    // Periksa apakah kunci hari ada dalam array, jika ada, set estimasi dokter
                    if (array_key_exists($hari_kunjungan, $jadwalArray)) {
                        $data->estimasi_dokter = $jadwalArray[$hari_kunjungan];
                    }
                }
            }
        }
        $pdf = Pdf::loadView('pasien.pendaftaran.cetak', compact('data'));
        $filename = $data->kode_pendaftaran.'.'.'pdf';
        $pdf->save(storage_path('app/public/pdf/'.$filename));
        return $pdf->download($filename);
        // return view('pasien.pendaftaran.cetak',compact('data'));

        // $img = public_path('qrcodes').'/'.$data->kode_pendaftaran.'.'.'png';
        // return response()->download($img);
    }

    public function updateStatus(Request $request){
        $current_status = PendaftaranPasien::find($request->get('id'));
        if ($current_status->status_pendaftaran == 'selesai') {
            toast('Status Pendaftaran telah selesai','error');
            return redirect()->route('pasien.history-transaksi.index');
        }
        $data = PendaftaranPasien::find($request->id);
        $data->status_pendaftaran = $request->status;
        $data->update();
        toast('Berhasil mengubah status pendaftaran','success');
        return redirect()->route('pasien.history-transaksi.index');
    }
}
