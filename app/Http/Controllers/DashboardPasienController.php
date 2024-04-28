<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPasienController extends Controller
{
    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function index()  {
        return view('dashboard-pasien');
    }
    /**
     * A description of the entire PHP function.
     *
     * @param datatype $paramname description
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    public function ketentuan()  {
        return view('pendaftaran.ketentuan');
    }
    /**
     * Retrieves the jenisPembayaran view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function jenisPembayaran() {
        return view('pendaftaran.jenis-pembayaran');
    }
    public function listPoliklinik() {
        return view('pendaftaran.poliklinik');
    }
    public function listDokter() {
        return view('pendaftaran.dokter');
    }
}
