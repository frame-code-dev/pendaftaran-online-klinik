<?php

namespace App\Http\Controllers;

use App\Helpers\EstimasiWaktuLayanan;
use App\Helpers\KodeUnikGenerator;
use App\Helpers\NomorAntrianGenerator;
use App\Models\Dokter;
use App\Models\JadwalDokter;
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
            $dokter_id = $request->get('dokter');
            $tanggalKunjungan = Carbon::now();
            $jenis_pembayaran = $request->get('cara_pembayaran');
            $data = JadwalDokter::where('dokter_id',$dokter_id)->where('status',$jenis_pembayaran)->get();
            $hari_kunjungan = $tanggalKunjungan ? strtolower(Carbon::parse($tanggalKunjungan)->translatedFormat('l')) : null;
            $jadwalArray = $data->toArray();

            // Ubah 'Jumat' menjadi 'jumaat' agar sesuai dengan kunci di array
            $hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan;
            if ($hari_kunjungan == 'minggu' || $hari_kunjungan == 'sabtu') {
                $message = 'Tanggal yang dipilih tidak valid. Silakan pilih tanggal lain.';
                toast($message,'error');
                return redirect()->back();
            }
            // Periksa apakah kunci hari ada dalam array, jika ada, set estimasi dokter
            $estimasi_dokter = '';
            foreach ($jadwalArray as $key => $value) {
                $estimasi_dokter = $value[$hari_kunjungan];
            }
            $param['estimasi_dokter'] = $estimasi_dokter;

            $estimasiWaktu = EstimasiWaktuLayanan::estimasi($tanggalKunjungan, $noAntrian,$estimasi_dokter);

            $param['dokter'] = Dokter::with('poliklinik')->find($request->get('dokter'));
            $set_no_antrian = null;
            if ($param['dokter']->poliklinik->name == 'klinik Integrasi spesialis bedah mulut' || $param['dokter']->poliklinik->name == 'klinik Integrasi spesialis konservasi gigi' || $param['dokter']->poliklinik->name == 'klinik Integrasi spesialis orthodensia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Pedodonsia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Prosthodonsia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Penyakit Mulut' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Periodonsia') {
                $set_no_antrian = null;
            }else{
                $set_no_antrian = $noAntrian;
            }
            $pendaftaran = new PendaftaranPasien;
            $pendaftaran->kode_pendaftaran = $kodeUnik;
            $pendaftaran->no_kartu = $request->get('cara_pembayaran') == 'bpjs' ? $request->get('no_bpjs') : null;
            $pendaftaran->no_antrian = $set_no_antrian;
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
