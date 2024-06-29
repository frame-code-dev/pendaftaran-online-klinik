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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use stdClass;

class DashboardPasienController extends Controller
{
    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function index()  {

        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        Session::put('jenis-pembayaran', null);
        return view('dashboard-pasien');
    }
    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function ketentuan()  {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        Session::put('jenis-pembayaran', null);
        return view('pasien.pendaftaran.ketentuan');
    }
    /**
     * Retrieves the jenisPembayaran view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function jenisPembayaran() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        Session::put('jenis-pembayaran', null);
        $currentTime = Carbon::now()->parse('Asia/Jakarta');

        $start = Carbon::createFromTime(7, 0, 0, 'Asia/Jakarta'); // 7 AM
        $end = Carbon::createFromTime(21, 0, 0, 'Asia/Jakarta'); // 9 PM
        if (!$currentTime->between($start, $end)) {
            toast('Pendaftaran ditutup pada pukul 21.00 WIB dan akan dibuka kembali pada pukul 07.00 WIB.','error');
            return redirect()->route('pasien.ketentuan');
        }
        return view('pasien.pendaftaran.jenis-pembayaran');
    }
    public function jenisPembayaranBpjs() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        Session::put('jenis-pembayaran', 'bpjs');
        // Cek jam kerja
        return view('pasien.pendaftaran.jenis-pembayaran-bpjs');
    }

    public function jenisPembayaranBpjsStore(Request $request) {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $validateData = Validator::make($request->all(),[
            'no_bpjs' => 'required',
            'file_input' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pasien.jenis-pembayaran-bpjs');
        }
        Session::put('no_bpjs',$request->get('no_bpjs'));
        if ($request->has('file_input') || $request->file('file_input') != null) {
            $file = $request->file('file_input');
            $filename = $request->get('no_bpjs').'.'.$file->extension();
            $file->storeAs('public/files-bpjs/'.$filename);
            Session::put('file-bpjs', $filename);
        }
        return redirect()->route('pasien.list-poliklinik');
    }
    public function listPoliklinik(Request $request) {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $search = $request->get('search');
        $param['data'] = Poliklinik::with('user')->when($search,function($query) use ($search) {
            $query->where('name','like','%'.$search.'%')
                 ->orWhere('name','like','%'.$search.'%');
        })->latest()->get();
        return view('pasien.pendaftaran.poliklinik',$param);
    }
    public function listDokter($id,Request $request) {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $param['poli'] = Poliklinik::find($id);
        Session::put('poliklinik',$id);
        $tanggal_kunjungan = $request->has('tanggal') ? $request->tanggal : date('Y-m-d');
        $hari_kunjungan = $request->has('tanggal') ? strtolower(Carbon::parse($request->tanggal)->translatedFormat('l')) : null;
        Session::put('tanggal_kunjungan',$tanggal_kunjungan);
        Session::put('poliklinik',$param['poli']);
        $jenis_pembayaran = Session::has('jenis-pembayaran') ? Session::get('jenis-pembayaran') : 'umum';
        $search = $request->get('search');
        // cek hari kunjungan
        $param['data'] = Dokter::with('poliklinik','user','jadwal')->when($search,function($query) use ($search) {
            $query->where('name','like','%'.$search.'%')
                 ->orWhere('name','like','%'.$search.'%');
        })
        ->whereHas('jadwal', function ($query) use ($hari_kunjungan,$jenis_pembayaran) {
            if ($hari_kunjungan != null) {
                $hari_kunjungan = $hari_kunjungan;
                if ($hari_kunjungan == 'jumat') {
                    $hari_kunjungan = 'jumaat';
                }
                $query->where($hari_kunjungan,'!=',null)
                    ->where('status',$jenis_pembayaran);
            }else{
                $query->where('status',$jenis_pembayaran);
            }
        })
        ->where('poliklinik_id',$id)->latest()->get();
        // cek kuota dokter
        $param['data']->transform(function ($value) use ($request,$jenis_pembayaran) {
            $current_kuota_online = PendaftaranPasien::where('dokter_id',$value->id)
                                                    ->where('status_pendaftaran','pending')
                                                    ->where('jenis_pembayaran',$jenis_pembayaran)
                                                    ->where('jenis_pendaftaran','online')
                                                    ->whereDate('tanggal_kunjungan',Carbon::parse($request->tanggal)->format('Y-m-d'))
                                                    ->count();
            $current_kuota_offline = PendaftaranPasien::where('dokter_id',$value->id)
                                                    ->where('status_pendaftaran','pending')
                                                    ->where('jenis_pembayaran',$jenis_pembayaran)
                                                    ->where('jenis_pendaftaran','offline')
                                                    ->whereDate('tanggal_kunjungan',Carbon::parse($request->tanggal)->format('Y-m-d'))
                                                    ->count();
            if ($value->kuota_umum != null || $value->kuota_bpjs != null) {
                $current_kuota = $current_kuota_offline + $current_kuota_online;
                $result = $jenis_pembayaran == 'umum' ? $value->kuota - $current_kuota : $value->kuota_bpjs - $current_kuota;
                $value->kuota_terisi = $result <= 0 ? 0 : $result;
            }else{
                $value->kuota_terisi = '-';
            }
            return $value;
        });

        $param['hari_kunjungan'] = $request->has('tanggal') ? strtolower(Carbon::parse($request->tanggal)->translatedFormat('l')) : strtolower(Carbon::parse(now())->translatedFormat('l'));
        return view('pasien.pendaftaran.dokter',$param);
    }

    public function konfirmasiPendaftaran($id){
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        // Berdasarkan hari
        $tanggal_kunjungan_pasien = Session::get('tanggal_kunjungan') != null ? Session::get('tanggal_kunjungan') : date('Y-m-d');
        $tanggal_kunjungan = $tanggal_kunjungan_pasien ? $tanggal_kunjungan_pasien : date('Y-m-d');
        $hari_kunjungan = $tanggal_kunjungan ? strtolower(Carbon::parse($tanggal_kunjungan)->translatedFormat('l')) : null;
        $jenis_pembayaran = Session::has('jenis-pembayaran') ? Session::get('jenis-pembayaran') : 'umum';
        if ($hari_kunjungan == 'minggu' || $hari_kunjungan == 'sabtu') {
            $message = 'Tanggal yang dipilih tidak valid. Silakan pilih tanggal lain.';
            toast($message,'error');
            return redirect()->route('pasien.list-poliklinik',['id' => Session::get('poliklinik')->id]);
        }
        // berdasarkan tanggal kunjungan dan poliklinik
        // -- Check berdasarkan tidak boleh di poliklinik yang berbeda dan dokter berbeda di tanggal kunjungan sama
        $cek_pendaftaran = PendaftaranPasien::where('poliklinik_id',Session::get('poliklinik')->id)
                                        ->where('status_pendaftaran','pending')->where('jenis_pendaftaran','online')
                                        ->where('pasien_id',Session::get('user')->id)
                                        ->whereDate('tanggal_kunjungan',Carbon::parse($tanggal_kunjungan_pasien)->format('Y-m-d'))
                                        ->count();
        if ($cek_pendaftaran > 0) {
            $message = 'User Sudah Mendaftarkan di poliklinik yang sama.';
            toast($message,'error');
            return redirect()->route('pasien.list-poliklinik',['id' => Session::get('poliklinik')->id]);
        }
        // data pendaftaran pasien
        $dokter_id = $id;
        // Check Kuota
        $current_kuota_online = PendaftaranPasien::where('dokter_id',$dokter_id)
                                            ->where('status_pendaftaran','pending')
                                            ->where('jenis_pembayaran',$jenis_pembayaran)
                                            ->where('jenis_pendaftaran','online')
                                            ->whereDate('tanggal_kunjungan',Carbon::parse($tanggal_kunjungan_pasien)->format('Y-m-d'))
                                                ->count();
        $current_kuota_offline = PendaftaranPasien::where('dokter_id',$dokter_id)
                                                ->where('status_pendaftaran','pending')
                                                ->where('jenis_pendaftaran','offline')
                                                ->where('jenis_pembayaran',$jenis_pembayaran)
                                                ->whereDate('tanggal_kunjungan',Carbon::parse($tanggal_kunjungan_pasien)->format('Y-m-d'))
                                                ->count();
        $current_kuota = $current_kuota_offline + $current_kuota_online;
        $param['dokter'] = Dokter::with('poliklinik')->find($dokter_id);
        $jadwal_dokter =  JadwalDokter::where('dokter_id',$dokter_id)->where('status',$jenis_pembayaran)->where($hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan,'!=','-')->first();
        if ($jadwal_dokter == null) {
            toast('Jam Praktek Tidak Tersedia.','error');
            return redirect()->route('pasien.list-dokter',[$param['dokter']->poliklinik_id]);
        }
        $current_kuota_dokter = $jenis_pembayaran == 'umum' ? $param['dokter']->kuota : $param['dokter']->kuota_bpjs;
        if ($current_kuota_dokter != 0) {
            $sisa_kuota = $jenis_pembayaran == 'umum' ? $param['dokter']->kuota - $current_kuota : $param['dokter']->kuota_bpjs - $current_kuota;
            if ($sisa_kuota <= 0) {
                toast('Kuota dokter habis.','error');
                return redirect()->route('pasien.list-dokter',[$param['dokter']->poliklinik_id]);
            }
        }

        $data_pasien_id = Session::get('user')->id;
        // data pendaftaran pasien
        $param['title'] = 'Konfirmasi Pembayaran';
        // 1 Data Pasien
        // 2 Nama Dokter
        // 3 klinik
        // 4 jenis jenisPembayaran
        // 5 tanggal kunjungan
        $param['data_pasien'] = Pasien::find($data_pasien_id);
        $param['jenis_pembayaran'] = $jenis_pembayaran;
        $param['tanggal_kunjungan'] = $tanggal_kunjungan_pasien;
        $param['simple'] = QrCode::size(120)->generate('https://www.binaryboxtuts.com/');
        $param['kodeUnik'] = KodeUnikGenerator::generate();

        return view('pasien.pendaftaran.konfirmasi-pendaftaran',$param);
    }

    public function konfirmasiPendaftaranStore($id){
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        // data pendaftaran pasien
        $poliklinik_id = Session::get('poliklinik') != null ? Session::get('poliklinik')->id : null;
        $dokter_id = $id;
        $tanggal_kunjungan_pasien = Session::get('tanggal_kunjungan') != null ? Session::get('tanggal_kunjungan') : date('Y-m-d');

        $jenis_pembayaran = Session::has('jenis-pembayaran') ? Session::get('jenis-pembayaran') : 'umum';
        $no_bpjs = Session::has('jenis-pembayaran') ? Session::get('no_bpjs') : null;
        $file_bpjs = Session::has('jenis-pembayaran') ? Session::get('file-bpjs') : null;
        $data_pasien_id = Session::get('user')->id;
        // data pendaftaran pasien
        $param['title'] = 'Konfirmasi Pembayaran';
        // 1 Data Pasien
        // 2 Nama Dokter
        // 3 klinik
        // 4 jenis jenisPembayaran
        // 5 tanggal kunjungan
        $param['data_pasien'] = Pasien::find($data_pasien_id);
        $param['dokter'] = Dokter::with('poliklinik')->find($dokter_id);
        $param['jenis_pembayaran'] = $jenis_pembayaran;
        $param['tanggal_kunjungan'] = $tanggal_kunjungan_pasien;

        $param['simple'] = QrCode::size(120)->generate('https://www.binaryboxtuts.com/');
        $tanggalKunjungan = Carbon::parse($tanggal_kunjungan_pasien)->format('Y-m-d');
        $param['noAntrian'] = NomorAntrianGenerator::generate($tanggalKunjungan,$dokter_id);
        $nomorAntrian = $param['noAntrian']; // Nomor antrian
        $param['kodeUnik'] = KodeUnikGenerator::generate();
        $data = JadwalDokter::where('dokter_id',$dokter_id)->where('status',$jenis_pembayaran)->get();
        $hari_kunjungan = $tanggalKunjungan ? strtolower(Carbon::parse($tanggalKunjungan)->translatedFormat('l')) : null;
        $jadwalArray = $data->toArray();

        // Ubah 'Jumat' menjadi 'jumaat' agar sesuai dengan kunci di array
        $hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan;
        // Periksa apakah kunci hari ada dalam array, jika ada, set estimasi dokter
        $estimasi_dokter = '';
        foreach ($jadwalArray as $key => $value) {
            $estimasi_dokter = $value[$hari_kunjungan];
        }
        $param['estimasi_dokter'] = $estimasi_dokter;

        $estimasiWaktu = EstimasiWaktuLayanan::estimasi($tanggalKunjungan, $nomorAntrian,$estimasi_dokter);

        $param['estimasi_waktu'] = $estimasiWaktu->format('H:i:s');
        $set_no_antrian = null;
        if ($param['dokter']->poliklinik->name == 'klinik Integrasi spesialis bedah mulut' || $param['dokter']->poliklinik->name == 'klinik Integrasi spesialis konservasi gigi' || $param['dokter']->poliklinik->name == 'klinik Integrasi spesialis orthodensia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Pedodonsia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Prosthodonsia' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Penyakit Mulut' || $param['dokter']->poliklinik->name == 'Klinik integrasi Spesialis Periodonsia') {
            $set_no_antrian = null;
        }else{
            $set_no_antrian = $nomorAntrian;
        }
        try {
            DB::beginTransaction();
            $pendaftaran = new PendaftaranPasien;
            $pendaftaran->kode_pendaftaran = $param['kodeUnik'];
            $pendaftaran->no_kartu = $no_bpjs;
            $pendaftaran->no_antrian = $set_no_antrian;
            $pendaftaran->jenis_pembayaran = $jenis_pembayaran;
            $pendaftaran->dokter_id = $dokter_id;
            $pendaftaran->pasien_id = $data_pasien_id;
            $pendaftaran->tanggal_kunjungan = $tanggal_kunjungan_pasien;
            $pendaftaran->estimasi_dilayani = $estimasiWaktu->format('H:i:s');
            $pendaftaran->status_pendaftaran = 'pending';
            $pendaftaran->jenis_pendaftaran = 'online';
            $pendaftaran->poliklinik_id = $poliklinik_id;
            $pendaftaran->gambar = $file_bpjs;
            $pendaftaran->save();
            DB::commit();
            $param['current_pendaftaran'] = PendaftaranPasien::find($pendaftaran->id)->kode_pendaftaran;
            Session::put('kodeUnik',$param['current_pendaftaran']);
            $data = JadwalDokter::where('dokter_id',$dokter_id)->where('status',$jenis_pembayaran)->get();
            $hari_kunjungan = strtolower(\Carbon\Carbon::parse($tanggal_kunjungan_pasien)->translatedFormat('l'));
            $jadwalArray = $data->toArray();

            // Ubah 'Jumat' menjadi 'jumaat' agar sesuai dengan kunci di array
            $hari_kunjungan = ($hari_kunjungan == 'jumat') ? 'jumaat' : $hari_kunjungan;
            // Periksa apakah kunci hari ada dalam array, jika ada, set estimasi dokter
            $estimasi_dokter = '';
            foreach ($jadwalArray as $key => $value) {
                $estimasi_dokter = $value[$hari_kunjungan];
            }
            $param['estimasi_dokter'] = $estimasi_dokter;
            return $param;
            // $param['data'] = PendaftaranPasien::find($pendaftaran->id);
        //     return redirect()->route('dashboard.pasien');
        } catch (Exception $th) {
            return $th;
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('dashboard.pasien');

        }
    }

    public function cetakQrcode($id){
        $noBooking = Session::get('kodeUnik');
        $data = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('pasien_id',Session::get('user')->id)->where('kode_pendaftaran',$noBooking)->first();
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
        $qrCode = QrCode::format('png')
                        ->backgroundColor(255, 255, 255)
                        ->margin(1)
                        ->size(500)->generate($data->kode_pendaftaran);
        $path = public_path('qrcodes/'.$data->kode_pendaftaran.'.png');
                        file_put_contents($path, $qrCode);
        return $pdf->download($filename);


        // // Simpan QR Code ke dalam folder public/qrcodes

        // return response()->download($path);
    }


}
