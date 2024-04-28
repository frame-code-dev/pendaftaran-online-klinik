<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPasienController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
    });
});
// pasien
Route::middleware(['auth','role:pasien'])->group(function () {
    Route::prefix('dashboard-pasien')->group(function () {
        // dashboard
        Route::get('/',[DashboardPasienController::class,'index'])->name('dashboard');
        // list ketentuan umum
        Route::get('ketentuan-umum',[DashboardPasienController::class,'ketentuan'])->name('pasien.ketentuan');
        // jenis pembayaran
        Route::get('jenis-pembayaran',[DashboardPasienController::class,'jenisPembayaran'])->name('pasien.jenis-pembayaran');
        // list poliklinik
        Route::get('list-poliklinik',[DashboardPasienController::class,'listPoliklinik'])->name('pasien.list-poliklinik');
        // list dokter
        Route::get('list-dokter',[DashboardPasienController::class,'listDokter'])->name('pasien.list-dokter');
    });
});

Route::middleware('auth','role:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
