<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Poliklinik;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JadwalDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $param['title'] = 'List Jadwal Dokter';
        $param['data'] = JadwalDokter::with('dokter')->latest()->get();
        $param['dokter'] = Dokter::latest()->get();
        $param['poliklinik'] = Poliklinik::latest()->get();
        $search = $request->get('search');

        $param['dokter'] = Dokter::with(['jadwal', 'poliklinik'])
        ->whereHas('poliklinik', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
        ->when($search, function ($query) use ($search) {
            $query->orWhere('name', 'like', '%' . $search . '%');
        })
        ->latest()
        ->get();
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
            $tambah_umum->senin = $request->get('daristatus_umum_senin').'-'.$request->get('sampaistatus_umum_senin');
            $tambah_umum->selasa = $request->get('daristatus_umum_selasa').'-'.$request->get('sampaistatus_umum_selasa');
            $tambah_umum->rabu = $request->get('daristatus_umum_rabu').'-'.$request->get('sampaistatus_umum_rabu');
            $tambah_umum->kamis = $request->get('daristatus_umum_kamis').'-'.$request->get('sampaistatus_umum_kamis');
            $tambah_umum->jumaat = $request->get('daristatus_umum_jumaat').'-'.$request->get('sampaistatus_umum_jumaat');
            $tambah_umum->save();
            $tambah_bpjs = new JadwalDokter;
            $tambah_bpjs->dokter_id = $request->dokter;
            $tambah_bpjs->status = $request->get('status_bpjs')[0];
            $tambah_bpjs->senin = $request->get('daristatus_bpjs_senin').'-'.$request->get('sampaistatus_bpjs_senin');
            $tambah_bpjs->selasa = $request->get('daristatus_bpjs_selasa').'-'.$request->get('sampaistatus_bpjs_selasa');
            $tambah_bpjs->rabu = $request->get('daristatus_bpjs_rabu').'-'.$request->get('sampaistatus_bpjs_rabu');
            $tambah_bpjs->kamis = $request->get('daristatus_bpjs_kamis').'-'.$request->get('sampaistatus_bpjs_kamis');
            $tambah_bpjs->jumaat = $request->get('daristatus_bpjs_jumaat').'-'.$request->get('sampaistatus_bpjs_jumaat');
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
            $update_umum = JadwalDokter::find($request->get('id_umum'));
            $update_umum->dokter_id = $request->id;
            $update_umum->status = $request->get('status_umum')[0];
            $update_umum->senin = $request->get('daristatus_umum_senin').'-'.$request->get('sampaistatus_umum_senin');
            $update_umum->selasa = $request->get('daristatus_umum_selasa').'-'.$request->get('sampaistatus_umum_selasa');
            $update_umum->rabu = $request->get('daristatus_umum_rabu').'-'.$request->get('sampaistatus_umum_rabu');
            $update_umum->kamis = $request->get('daristatus_umum_kamis').'-'.$request->get('sampaistatus_umum_kamis');
            $update_umum->jumaat = $request->get('daristatus_umum_jumaat').'-'.$request->get('sampaistatus_umum_jumaat');
            $update_umum->update();

            $update_bpjs = JadwalDokter::find($request->get('id_bpjs'));
            $update_bpjs->dokter_id = $request->id;
            $update_bpjs->status = $request->get('status_bpjs')[0];
            $update_bpjs->senin = $request->get('daristatus_bpjs_senin').'-'.$request->get('sampaistatus_bpjs_senin');
            $update_bpjs->selasa = $request->get('daristatus_bpjs_selasa').'-'.$request->get('sampaistatus_bpjs_selasa');
            $update_bpjs->rabu = $request->get('daristatus_bpjs_rabu').'-'.$request->get('sampaistatus_bpjs_rabu');
            $update_bpjs->kamis = $request->get('daristatus_bpjs_kamis').'-'.$request->get('sampaistatus_bpjs_kamis');
            $update_bpjs->jumaat = $request->get('daristatus_bpjs_jumaat').'-'.$request->get('sampaistatus_bpjs_jumaat');
            $update_bpjs->update();

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
