<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProfilePasienController extends Controller
{
    public function index() {
        if (Session::get('user') == null) {
            return view('pasien.auth.login');
        }
        $id = Session::get('user')->id;
        $param['title'] = 'Edit Profile';
        $param['pasien'] = Pasien::find($id);
        return view('pasien.profile.index',$param);
    }

    public function update(Request $request){
        $validateData = Validator::make($request->all(),[
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('profile-pasien.index');
        }
        $id = Session::get('user')->id;
        DB::beginTransaction();
        try {
            $update = Pasien::find($id);
            $update->alamat = $request->alamat;
            $update->no_hp = $request->no_hp;
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $file = $request->file('file_input');
                $filename = Carbon::now()->translatedFormat('his').'.'.$file->extension();
                $file->storeAs('public/pasien/'.$filename);
                $update->gambar = $filename;
            }
            $update->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('profile-pasien.edit');
        } catch (Exception $th) {
            return $th;
        }
    }
}
