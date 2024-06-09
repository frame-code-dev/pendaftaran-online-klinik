<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'Halaman Petugas';
        $param['petugas'] = User::with('roles')->latest()->get();
        $title = 'Delete Petugas!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.petugas.index', $param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Tambah Petugas';
        return view('backoffice.petugas.create', $param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'hak_akses' => 'required|not_in:0'
        ],[
            'required' => ':attribute Harus Terisi !',
            'not_in' => 'Hak Akses Harus Dipilih !'
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('petugas.index');
        }
        try {
            DB::beginTransaction();
            $petugas = new User;
            $petugas->name = $request->name;
            $petugas->email = $request->email;
            $petugas->password = Hash::make($request->password);
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $file = $request->file('file_input');
                $filename = Carbon::now()->translatedFormat('his').Str::slug($request->name).'.'.$file->extension();
                $file->storeAs('public/petugas/'.$filename);
                $petugas->gambar = $filename;
            }
            $petugas->save();

            $petugas->assignRole($request->hak_akses);
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('petugas.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('petugas.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = "Detail Petugas";
        $param['petugas'] = User::find($id);
        return view('backoffice.petugas.show', $param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = "Edit Petugas";
        $param['petugas'] = User::find($id);
        return view('backoffice.petugas.edit', $param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'hak_akses' => 'required|not_in:0'
        ],[
            'required' => ':attribute Harus Terisi !',
            'not_in' => 'Hak Akses Harus Dipilih !'
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('petugas.index');
        }
        try {
            DB::beginTransaction();
            $petugas = User::find($id);
            $petugas->name = $request->name;
            $petugas->email = $request->email;
            if ($request->has('password') || $request->get('password') != NULL || $request->get('password') != '') {
                $petugas->password = Hash::make($request->password);
            }
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $path = 'public/petugas/' . $petugas->gambar;
                Storage::delete($path);

                $file = $request->file('file_input');
                $filename = Carbon::now()->translatedFormat('his').Str::slug($request->username).'.'.$file->extension();
                $file->storeAs('public/petugas/'.$filename);
                $petugas->gambar = $filename;
            }

            $petugas->update();

            // update roles
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $current = User::find($id);
            $current->assignRole($request->hak_akses);

            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('petugas.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('petugas.index');
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
            $delete = User::find($id);
            if ($delete->gambar) {
                $path = 'public/petugas/' . $delete->gambar;
                Storage::delete($path);
            }
            $delete->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('petugas.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('petugas.index');
        }
    }

    public function updateStatus(Request $request){
        DB::beginTransaction();
        try {
            DB::commit();
            $updateStatus = User::find($request->get('id'));
            if ($updateStatus->status == 'aktif') {
                $updateStatus->status = 'non-aktif';
            }else{
                $updateStatus->status = 'aktif';

            }
            $updateStatus->update();
            toast('Berhasil mengganti status petugas.','error');
            return redirect()->route('petugas.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('petugas.index');
        }
    }
}
