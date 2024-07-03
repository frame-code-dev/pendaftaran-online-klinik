<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List Pasien';
        $param['pasien'] = Pasien::latest()->get();
        $title = 'Delete Pasien!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.pasien.index', $param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Create Pasien';
        return view('backoffice.pasien.create', $param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|unique:pasien,no_rm',
            'nik' => 'required|unique:pasien,nik|max:16|min:16',
            'nama' => 'required',
            'jenis_kelamin' => 'required|not_in:0',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'agama' => 'required',
            'status_kawin' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'suku' => 'required',
            'bahasa' => 'required',
            'no_hp' => 'required',
            'nama_ortu' => 'required',

        ],[
            'no_rm.required' => 'Nomor Rekam Medis harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'nik.max' => 'NIK maksimal 16 karakter',
            'nik.min' => 'NIK minimal 16 karakter',
            'required' => ':attribute harus diisi'
        ]);

        // if ($validateData->fails()) {
        //     $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
        //     foreach($validateData->errors()->getMessages() as $error) {
        //         $html .= "<li>$error[0]</li>";
        //     }
        //     $html .= "</ol>";

        //     alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
        //     return redirect()->back();
        // }
        DB::beginTransaction();
        try {
            $date = DateTime::createFromFormat('m-d-Y', $request->tgl_lahir)->format('Y-m-d');
            $tambah = new Pasien;
            $tambah->no_rm = $request->get('no_rm');
            $tambah->name = $request->get('nama');
            $tambah->nik = $request->get('nik');
            $tambah->jenis_kelamin = $request->get('jenis_kelamin');
            $tambah->alamat = $request->get('alamat');
            $tambah->rt = $request->get('rt');
            $tambah->rw = $request->get('rw');
            $tambah->tempat_lahir = $request->get('tempat_lahir');

            $tambah->tanggal_lahir = $date;
            $tambah->provinsi_id = (int)$request->get('provinsi');
            $tambah->kabupaten_id = (int)$request->get('kabupaten');
            $tambah->kecamatan_id = (int)$request->get('kecamatan');
            $tambah->desa_id = $request->get('desa');
            $tambah->agama = $request->get('agama');
            $tambah->status_kawin = $request->get('status');
            $tambah->pendidikan = $request->get('pendidikan');
            $tambah->pekerjaan = $request->get('pekerjaan');
            $tambah->suku = $request->get('suku');
            $tambah->bahasa = $request->get('bahasa');
            $tambah->no_hp = $request->get('no_hp');
            $tambah->nama_ortu = $request->get('nama_ortu');
            $tambah->user_id = Auth::user()->id;
            $tambah->save();
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('pasien.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pasien.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = 'Detail Pasien';
        $param['data'] = Pasien::with('provinsi','kabupaten','kecamatan')->find($id);
        return view('backoffice.pasien.show',$param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = 'Edit Pasien';
        $param['data'] = Pasien::with('provinsi','kabupaten','kecamatan')->find($id);
        return view('backoffice.pasien.edit',$param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'no_rm' => 'required|max:16|min:16',
            'nik' => 'required',
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'agama' => 'required',
            'status_kawin' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'suku' => 'required',
            'bahasa' => 'required',
            'no_hp' => 'required',
            'nama_ortu' => 'required',

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
        DB::beginTransaction();
        try {
            $date = DateTime::createFromFormat('m-d-Y', $request->tgl_lahir)->format('Y-m-d');
            $update = Pasien::find($id);
            $update->no_rm = $request->get('no_rm');
            $update->name = $request->get('nama');
            $update->nik = $request->get('nik');
            $update->jenis_kelamin = $request->get('jenis_kelamin');
            $update->alamat = $request->get('alamat');
            $update->rt = $request->get('rt');
            $update->rw = $request->get('rw');
            $update->tempat_lahir = $request->get('tempat_lahir');

            $update->tanggal_lahir = $date;
            $update->provinsi_id = (int)$request->get('provinsi');
            $update->kabupaten_id = (int)$request->get('kabupaten');
            $update->kecamatan_id = (int)$request->get('kecamatan');
            $update->desa_id = $request->get('desa');
            $update->agama = $request->get('agama');
            $update->status_kawin = $request->get('status');
            $update->pendidikan = $request->get('pendidikan');
            $update->pekerjaan = $request->get('pekerjaan');
            $update->suku = $request->get('suku');
            $update->bahasa = $request->get('bahasa');
            $update->no_hp = $request->get('no_hp');
            $update->nama_ortu = $request->get('nama_ortu');
            $update->user_id = Auth::user()->id;
            $update->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('pasien.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pasien.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            DB::commit();
            Pasien::find($id)->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('pasien.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('pasien.index');
        }
    }
}
