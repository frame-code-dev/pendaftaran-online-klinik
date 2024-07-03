<?php

namespace App\Http\Controllers;

use App\Imports\KecamatanImport;
use App\Imports\PasienImport;
use App\Services\LocationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }
    public function index() {
        $param['title'] = 'Import Data Pasien';
        return view('backoffice.pasien.import.index',$param);
    }

    public function store(Request $request) {

        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        DB::beginTransaction();
        try {
            Excel::import(new PasienImport($this->locationService), $request->file('file'));
            toast('Berhasil menambahkan data.','success');
            DB::commit();
            return redirect()->route('pasien.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('Terjadi kesalahan eror!', $e->getMessage());
            return redirect()->route('pasien.index');
        }
    }
}
