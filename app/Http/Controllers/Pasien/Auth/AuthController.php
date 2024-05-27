<?php

namespace App\Http\Controllers\Pasien\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login() {
        return view('pasien.auth.login');
    }

    public function store(Request $request) {
        $validateData = Validator::make($request->all(),[
            'nik' => 'required',
            'tgl_lahir' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('pasien.login');
        }
        try {
            $date = DateTime::createFromFormat('m-d-Y', $request->tgl_lahir)->format('Y-m-d');
            $user = Pasien::where('nik', $request->nik)->where('tanggal_lahir', $date)->first() ?? null;
            if ($user == null) {
                alert('Data Tidak Ditemukan','Silahkan menuju loket untuk melakukan pendaftaran','error');
                return redirect()->route('pasien.login');
            }else{
                Session::put('user', $user);
                Session::put('name', $user->name);
                Session::put('nik', $user->nik);
                $param['title'] = 'Login Pasien';
                $param['data'] = $user;
                return redirect()->route('dashboard.pasien');
            }
        } catch (Exception $th) {
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('pasien.login');
        }
    }

    public function logout() {
        Session::forget('user');
        Session::forget('tanggal_kunjungan');
        Session::forget('poliklinik');
        Session::forget('jenis-pembayaran');
        Session::forget('kodeUnik');
        Session::forget('no_bpjs');
        Session::forget('file-bpjs');
        Session::flush();
        return redirect()->route('pasien.login');
    }
}
