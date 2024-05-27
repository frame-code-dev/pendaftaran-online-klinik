<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List Dokter';
        $param['data'] = Dokter::with('poliklinik','user')->latest()->get();
        $param['data']->transform(function ($value) {
            if ($value->kuota != null) {
                // KUOTA UMUM
                $current_kuota_online_umum = PendaftaranPasien::where('dokter_id',$value->id)->where('jenis_pembayaran','umum')->where('status_pendaftaran','pending')->where('jenis_pendaftaran','online')->count();
                $current_kuota_offline_umum = PendaftaranPasien::where('dokter_id',$value->id)->where('jenis_pembayaran','umum')->where('status_pendaftaran','pending')->where('jenis_pendaftaran','offline')->count();
                $current_kuota_umum = $current_kuota_offline_umum + $current_kuota_online_umum;
                $result_umum = $value->kuota - $current_kuota_umum;
                $value->kuota_terisi_umum = $result_umum <= 0 ? 0 : $result_umum;

            }
            if ($value->kuota_bpjs != null) {
                // KUOTA BPJS
                $current_kuota_online_bpjs = PendaftaranPasien::where('dokter_id',$value->id)->where('jenis_pembayaran','bpjs')->where('status_pendaftaran','pending')->where('jenis_pendaftaran','online')->count();
                $current_kuota_offline_bpjs = PendaftaranPasien::where('dokter_id',$value->id)->where('jenis_pembayaran','bpjs')->where('status_pendaftaran','pending')->where('jenis_pendaftaran','offline')->count();
                $current_kuota_bpjs = $current_kuota_offline_bpjs + $current_kuota_online_bpjs;
                $result_bpjs = $value->kuota_bpjs - $current_kuota_bpjs;
                $value->kuota_terisi_bpjs = $result_bpjs <= 0 ? 0 : $result_bpjs;
            }
            return $value;
        });
        $title = 'Delete Dokter!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.dokter.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Create Dokter';
        $param['poliklinik'] = Poliklinik::latest()->get();
        return view('backoffice.dokter.create',$param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'poliklinik' => 'required|not_in:0',
            'jenis_kelamin' => 'required|not_in:0',
            'file_input' => 'required',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('dokter.index');
        }
        try {
            DB::beginTransaction();

            $dokter = new Dokter;
            $dokter->poliklinik_id = $request->poliklinik;
            $dokter->name = $request->name;
            $dokter->kuota = $request->has('kuota') ? $request->kuota : null;
            $dokter->kuota_bpjs = $request->has('kuota_bpjs') ? $request->kuota_bpjs : null;
            $dokter->jenis_kelamin = $request->jenis_kelamin;
            $dokter->user_id = Auth::user()->id;
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $file = $request->file('file_input');
                $filename = Carbon::now()->translatedFormat('his').'.'.$file->extension();
                $file->storeAs('public/dokter/'.$filename);
                $dokter->gambar = $filename;
            }
            $dokter->save();
            DB::commit();
            toast('Berhasil menambahkan data.','success');
            return redirect()->route('dokter.index');
        }catch (Exception $e){
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('dokter.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = 'Detail Dokter';
        $param['data'] = Dokter::with('poliklinik','user')->find($id);
        return view('backoffice.dokter.show',$param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = 'Edit Dokter';
        $param['data'] = Dokter::with('poliklinik','user')->find($id);;
        $param['poliklinik'] = Poliklinik::latest()->get();
        return view('backoffice.dokter.edit',$param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = Validator::make($request->all(),[
            'name' => 'required',
            'poliklinik' => 'required|not_in:0',
            'jenis_kelamin' => 'required|not_in:0',
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('dokter.index');
        }
        try {
            DB::beginTransaction();
            $dokter = Dokter::find($id);
            $dokter->poliklinik_id = $request->poliklinik;
            $dokter->name = $request->name;
            $dokter->kuota = $request->has('kuota') ? $request->kuota : null;
            $dokter->kuota_bpjs = $request->has('kuota_bpjs') ? $request->kuota_bpjs : null;
            $dokter->jenis_kelamin = $request->jenis_kelamin;
            $dokter->user_id = Auth::user()->id;
            if ($request->has('file_input') || $request->file('file_input') != null) {
                $path = 'public/dokter/' . $dokter->gambar;
                Storage::delete($path);

                $file = $request->file('file_input');
                $filename = Carbon::now()->translatedFormat('his').'.'.$file->extension();
                $file->storeAs('public/dokter/'.$filename);
                $dokter->gambar = $filename;
            }
            $dokter->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('dokter.index');
        }catch (Exception $e){
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('dokter.index');
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
            $delete = Dokter::find($id);
            if ($delete->gambar) {
                $path = 'public/dokter/' . $delete->gambar;
                Storage::delete($path);
            }
            $delete->delete();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('dokter.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('dokter.index');
        }
    }

    public function updateStatus(Request $request){
        DB::beginTransaction();
        try {
            DB::commit();
            $updateStatus = Dokter::find($request->get('id'));
            if ($updateStatus->status == 'aktif') {
                $updateStatus->status = 'non-aktif';
            }else{
                $updateStatus->status = 'aktif';

            }
            $updateStatus->update();
            toast('Berhasil mengganti status dokter.','error');
            return redirect()->route('dokter.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('dokter.index');
        }
    }
}
