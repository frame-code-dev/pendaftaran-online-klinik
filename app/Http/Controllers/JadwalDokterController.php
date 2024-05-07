<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $param['title'] = 'List Jadwal Dokter';
        $param['data'] = JadwalDokter::with('dokter')->latest()->get();
        $param['dokter'] = Dokter::with('jadwal')->latest()->get();
        $title = 'Delete Jadwal Dokter!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backoffice.jadwal-dokter.index',$param);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $param['title'] = 'Create Jadwal Dokter';
        $param['dokter'] = Dokter::latest()->get();
        return view('backoffice.jadwal-dokter.create',$param);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'dokter' => 'required|not_in:0'
        ]);
        if ($validateData->fails()) {
            $html = "<ol class='max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400'>";
            foreach($validateData->errors()->getMessages() as $error) {
                $html .= "<li>$error[0]</li>";
            }
            $html .= "</ol>";

            alert()->html('Terjadi kesalahan eror!', $html, 'error')->autoClose(5000);
            return redirect()->route('jadwal-dokter.index');
        }
        try {
            $cek_current = JadwalDokter::where('dokter_id',$request->dokter)->get();
            if ($cek_current->count() > 0) {
               alert()->error('Error', 'Jadwal Dokter sudah ada')->autoClose(5000);
               return redirect()->route('jadwal-dokter.index');
            }
            DB::beginTransaction();
            $tambah_umum = new JadwalDokter;
            $tambah_umum->dokter_id = $request->dokter;
            $tambah_umum->status = $request->get('status_umum')[0];
            $tambah_umum->senin = $request->get('status_umum_senin');
            $tambah_umum->selasa = $request->get('status_umum_selasa');
            $tambah_umum->rabu = $request->get('status_umum_rabu');
            $tambah_umum->kamis = $request->get('status_umum_kamis');
            $tambah_umum->jumaat = $request->get('status_umum_jumaat');
            $tambah_umum->save();
            $tambah_bpjs = new JadwalDokter;
            $tambah_bpjs->dokter_id = $request->dokter;
            $tambah_bpjs->status = $request->get('status_bpjs')[0];
            $tambah_bpjs->senin = $request->get('status_bpjs_senin');
            $tambah_bpjs->selasa = $request->get('status_bpjs_selasa');
            $tambah_bpjs->rabu = $request->get('status_bpjs_rabu');
            $tambah_bpjs->kamis = $request->get('status_bpjs_kamis');
            $tambah_bpjs->jumaat = $request->get('status_bpjs_jumaat');
            $tambah_bpjs->save();
            DB::commit();
            toast('Berhasil menambah data.','success');
            return redirect()->route('jadwal-dokter.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('jadwal-dokter.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $param['title'] = "Detail Jadwal Dokter";
        $param['data'] = Dokter::with('jadwal')->find($id);
        return view('backoffice.jadwal-dokter.show',$param);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $param['title'] = "Edit Jadwal Dokter";
        $param['data'] = Dokter::with('jadwal')->find($id);
        return view('backoffice.jadwal-dokter.edit',$param);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $tambah_umum = JadwalDokter::find($request->get('id_umum'));
            $tambah_umum->dokter_id = $request->id;
            $tambah_umum->status = $request->get('status_umum')[0];
            $tambah_umum->senin = $request->get('status_umum_senin');
            $tambah_umum->selasa = $request->get('status_umum_selasa');
            $tambah_umum->rabu = $request->get('status_umum_rabu');
            $tambah_umum->kamis = $request->get('status_umum_kamis');
            $tambah_umum->jumaat = $request->get('status_umum_jumaat');
            $tambah_umum->update();
            $tambah_bpjs = JadwalDokter::find($request->get('id_bpjs'));
            $tambah_bpjs->dokter_id = $request->id;
            $tambah_bpjs->status = $request->get('status_bpjs')[0];
            $tambah_bpjs->senin = $request->get('status_bpjs_senin');
            $tambah_bpjs->selasa = $request->get('status_bpjs_selasa');
            $tambah_bpjs->rabu = $request->get('status_bpjs_rabu');
            $tambah_bpjs->kamis = $request->get('status_bpjs_kamis');
            $tambah_bpjs->jumaat = $request->get('status_bpjs_jumaat');
            $tambah_bpjs->update();
            DB::commit();
            toast('Berhasil mengganti data.','success');
            return redirect()->route('jadwal-dokter.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('jadwal-dokter.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $jadwal = JadwalDokter::where('dokter_id',$id)->delete();
            DB::commit();
            toast('Berhasil menghapus data.','error');
            return redirect()->route('jadwal-dokter.index');
        } catch (Exception $th) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $th->getMessage());
            return redirect()->route('jadwal-dokter.index');
        }
    }

    public function cekDokter(Request $request) {
        $data = Dokter::with('poliklinik')->find($request->id);
        return $data;
    }
}
