<?php

namespace App\Http\Controllers;

use App\Helpers\EstimasiWaktuLayanan;
use App\Helpers\KodeUnikGenerator;
use App\Helpers\NomorAntrianGenerator;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PendaftaranPasienOfflineController extends Controller
{
    public function index($id)  {
        $param['title'] = 'Daftar Kunjungan Pasien';
        $param['data'] = Pasien::find($id);
        $param['poliklinik'] = Poliklinik::latest()->get();
        return view('backoffice.pendaftaran-offline.index',$param);
    }

    public function store(Request $request) {
        $validateData = Validator::make($request->all(),[
            'poliklinik' => 'required|not_in:0',
            'dokter' => 'required|not_in:0',
            'cara_pembayaran' => 'required|not_in:0',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pasien.index');
        }
        try {
            DB::beginTransaction();
            // mendaptkan kode unik
            $kodeUnik = KodeUnikGenerator::generate();
            // mendapatkan no antrian
            $noAntrian = NomorAntrianGenerator::generate(null,$request->get('dokter'));
            // mendapatkan estimasi waktu
            $tanggalKunjungan = Carbon::now();
            $nomorAntrian = $noAntrian; // Nomor antrian
            $estimasiWaktu = EstimasiWaktuLayanan::estimasi($tanggalKunjungan, $nomorAntrian); // Tanggal kunjungan (format: YYYY-MM-DD)
            $param['dokter'] = Dokter::with('poliklinik')->find($request->get('dokter'));
            $pendaftaran = new PendaftaranPasien;
            $pendaftaran->kode_pendaftaran = $kodeUnik;
            $pendaftaran->no_kartu = $request->get('cara_pembayaran') == 'bpjs' ? $request->get('no_bpjs') : null;
            $pendaftaran->no_antrian = $param['dokter']->poliklinik->name != 'klinik integrasi' ? $noAntrian : null;
            $pendaftaran->jenis_pembayaran = $request->get('cara_pembayaran');
            $pendaftaran->dokter_id = $request->get('dokter');
            $pendaftaran->pasien_id = $request->get('id');
            $pendaftaran->tanggal_kunjungan = Carbon::now();
            $pendaftaran->estimasi_dilayani = $estimasiWaktu->format('H:i:s');
            $pendaftaran->status_pendaftaran = 'pending';
            $pendaftaran->jenis_pendaftaran = 'offline';
            $pendaftaran->status_verifikasi = 'sudah-verifikasi';
            $pendaftaran->poliklinik_id = $request->get('poliklinik');
            $pendaftaran->gambar = null;
            $pendaftaran->save();
            DB::commit();
            toast('Berhasil mendaftarkan kunjungan pasien.','success');
            return redirect()->route('cetak-antrian',["id" => $pendaftaran->id]);
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('pasien.index');

        }
    }

    public function dokter(Request $request) {
        $data = Dokter::where('poliklinik_id', $request->get('id'))->get();
        return $data;
    }

}
