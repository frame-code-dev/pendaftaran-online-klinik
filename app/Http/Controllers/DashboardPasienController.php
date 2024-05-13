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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        return view('pasien.pendaftaran.jenis-pembayaran');
    }
    public function jenisPembayaranBpjs() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        Session::put('jenis-pembayaran', 'bpjs');
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
        $param['data']->transform(function ($value) {
            $current_kuota_online = PendaftaranPasien::where('dokter_id',$value->id)->where('status_pendaftaran','pending')->where('jenis_pendaftaran','online')->count();
            $current_kuota_offline = PendaftaranPasien::where('dokter_id',$value->id)->where('status_pendaftaran','pending')->where('jenis_pendaftaran','offline')->count();
            if ($value->kuota != 0) {
                $current_kuota = $current_kuota_offline + $current_kuota_online;
                $result = $value->kuota - $current_kuota;
                $value->kuota_terisi = $result <= 0 ? 0 : $result;
            }
            return $value;
        });
        return view('pasien.pendaftaran.dokter',$param);
    }

    public function konfirmasiPendaftaran($id){
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        // data pendaftaran pasien
        $dokter_id = $id;
        // Check Kuota
        $current_kuota_online = PendaftaranPasien::where('dokter_id',$dokter_id)->where('status_pendaftaran','pending')->where('jenis_pendaftaran','online')->count();
        $current_kuota_offline = PendaftaranPasien::where('dokter_id',$dokter_id)->where('status_pendaftaran','pending')->where('jenis_pendaftaran','offline')->count();
        $current_kuota = $current_kuota_offline + $current_kuota_online;
        $param['dokter'] = Dokter::with('poliklinik')->find($dokter_id);
        if ($param['dokter']->kuota != 0) {
            $sisa_kuota = $param['dokter']->kuota - $current_kuota;
            if ($sisa_kuota <= 0) {
                toast('Kuota penuh mencoba menambahkan data.','error');
                return redirect()->route('pasien.list-dokter',[$param['dokter']->poliklinik_id]);
            }
        }
        $tanggal_kunjungan_pasien = Session::get('tanggal_kunjungan') != null ? Session::get('tanggal_kunjungan') : date('Y-m-d');
        $jenis_pembayaran = Session::has('jenis-pembayaran') ? Session::get('jenis-pembayaran') : 'umum';
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
        $param['noAntrian'] = NomorAntrianGenerator::generate($tanggalKunjungan);
        $nomorAntrian = $param['noAntrian']; // Nomor antrian
        $param['kodeUnik'] = KodeUnikGenerator::generate();
        $estimasiWaktu = EstimasiWaktuLayanan::estimasi($tanggalKunjungan, $nomorAntrian); // Tanggal kunjungan (format: YYYY-MM-DD)
        $param['estimasi_waktu'] = $estimasiWaktu->format('H:i:s');
        try {
            DB::beginTransaction();
            $pendaftaran = new PendaftaranPasien;
            $pendaftaran->kode_pendaftaran = $param['kodeUnik'];
            $pendaftaran->no_kartu = $no_bpjs;
            $pendaftaran->no_antrian = $nomorAntrian;
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

        $qrCode = QrCode::format('png')
                        ->backgroundColor(255, 255, 255)
                        ->margin(1)
                        ->size(500)->generate($noBooking);

        // Simpan QR Code ke dalam folder public/qrcodes
        $path = public_path('qrcodes/'.$noBooking.'.png');
        file_put_contents($path, $qrCode);

        return response()->download($path);
    }


}
