<?php

namespace App\Http\Controllers;

use App\Imports\KecamatanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataController extends Controller
{
    public function index() {
        $param['title'] = 'Import Data Pasien';
        return view('backoffice.pasien.import.index',$param);
    }
}
