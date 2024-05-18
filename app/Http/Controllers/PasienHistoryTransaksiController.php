<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PasienHistoryTransaksiController extends Controller
{
    public function index() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $param['data'] = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('jenis_pendaftaran','online')->where('pasien_id',Session::get('user')->id)->get();
        return view('pasien.pendaftaran.history-transaksi',$param);
    }

    public function detail(Request $request) {
        $data = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('pasien_id',Session::get('user')->id)->where('id',$request->id)->first();
        return $data;
    }

    public function download($id) {
        $data = PendaftaranPasien::with('pasien','dokter','poliklinik')->where('pasien_id',Session::get('user')->id)->where('id',$id)->first();
        $img = public_path('qrcodes').'/'.$data->kode_pendaftaran.'.'.'png';
        return response()->download($img);
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
