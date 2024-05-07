<?php

namespace App\Http\Controllers;

use App\Imports\KecamatanImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportDataController extends Controller
{
    public function index() {
        return Excel::import(new KecamatanImport, storage_path('app/public/import/districts.csv'));
    }
}
