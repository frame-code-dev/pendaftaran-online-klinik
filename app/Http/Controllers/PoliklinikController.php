<?php

namespace App\Http\Controllers;

use App\Models\Poliklinik;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PoliklinikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List Poliklinik';
        $param['data'] = Poliklinik::with('user')->latest()->get();
        $title = 'Delete Poliklinik!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.poliklinik.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Create Poliklinik';
        return view('backoffice.poliklinik.create',$param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'keterangan' => 'required',
            'file_input' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('poliklinik.index');
        }
        try {
            DB::beginTransaction();
            $tambah = new Poliklinik;
            $tambah->name = $request->get('name');
            $tambah->keterangan = $request->get('keterangan');
            $tambah->user_id = Auth::user()->id;
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $file = $request->file('file_input');
                $filename =Carbon::now()->translatedFormat('his').'.'.$file->extension();
                $file->storeAs('public/poliklinik/'.$filename);
                $tambah->gambar = $filename;
            }
            $tambah->save();
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('poliklinik.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('poliklinik.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = "Detail Poliklinik";
        $param['poliklinik'] = Poliklinik::find($id);
        return view('backoffice.poliklinik.show', $param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = "Edit Poliklinik";
        $param['poliklinik'] = Poliklinik::find($id);
        return view('backoffice.poliklinik.edit', $param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'keterangan' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('poliklinik.index');
        }
        try {
            DB::beginTransaction();
            $edit = Poliklinik::find($id);
            $edit->name = $request->get('name');
            $edit->keterangan = $request->get('keterangan');
            $edit->user_id = Auth::user()->id;
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $path = 'public/poliklinik/' . $edit->gambar;
                Storage::delete($path);
                $file = $request->file('file_input');
                $filename =Carbon::now()->translatedFormat('his').'.'.$file->extension();
                $file->storeAs('public/poliklinik/'.$filename);
                $edit->gambar = $filename;
            }
            $edit->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('poliklinik.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('poliklinik.index');
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
            $delete = Poliklinik::find($id);
            if ($delete->gambar) {
                $path = 'public/poliklinik/' . $delete->gambar;
                Storage::delete($path);
            }
            $delete->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('poliklinik.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('poliklinik.index');
        }
    }
}
