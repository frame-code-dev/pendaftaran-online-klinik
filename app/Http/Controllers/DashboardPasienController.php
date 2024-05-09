<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        Session::put('no_bpjs',$request->get('bpjs'));
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
        Session::put('poliklinik',$param['poli']);
        $search = $request->get('search');
        $param['data'] = Dokter::with('poliklinik','user')->when($search,function($query) use ($search) {
            $query->where('name','like','%'.$search.'%')
                 ->orWhere('name','like','%'.$search.'%');
        })->where('poliklinik_id',$id)->latest()->get();
        $param['data']->transform(function ($value) {
            $current_kuota_online = PendaftaranPasien::where('dokter_id',$value->id)->where('status_pendaftaran','proses')->where('jenis_pendaftaran','online')->count();
            $current_kuota_offline = PendaftaranPasien::where('dokter_id',$value->id)->where('status_pendaftaran','pending')->where('jenis_pendaftaran','offline')->count();
            $current_kuota = $current_kuota_offline + $current_kuota_online;
            $value->kuota_terisi = $value->kuota - $current_kuota;
            return $value;
        });
        return view('pasien.pendaftaran.dokter',$param);
    }
}
