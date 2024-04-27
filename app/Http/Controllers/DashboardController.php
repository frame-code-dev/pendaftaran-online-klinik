<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public $param;
    /**
     * Index function.
     *
     * This function is responsible for displaying the index page of the dashboard.
     *
     * @return void
     */
    public function index() {
        return view('dashboard');
    }
}
