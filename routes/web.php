<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ImportDataController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ListJadwalDokterController;
use App\Http\Controllers\Pasien\Auth\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranPasienOfflineController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PoliklinikController;
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


// admin
Route::middleware(['auth','role:admin'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        // Dashboard
        Route::get('/',[DashboardController::class,'index'])->name('dashboard');
        // petugas
        Route::group(['prefix' => 'master-data'], function () {
            // petugas
            Route::post('petugas/update-status',[PetugasController::class,'updateStatus'])->name('petugas.update-status');
            Route::resource('petugas', PetugasController::class);
            // poliklinik
            Route::resource('poliklinik',PoliklinikController::class);
            // dokter
            Route::post('dokter/update-status',[DokterController::class,'updateStatus'])->name('dokter.update-status');
            Route::resource('dokter', DokterController::class);
            // jadwal dokter
            Route::get('jadwal-dokter/cek-dokter',[JadwalDokterController::class,'cekDokter'])->name('jadwal-dokter.cek-dokter');
            Route::resource('jadwal-dokter',JadwalDokterController::class);
            // pasien
            Route::resource('pasien', PasienController::class);
            // pendaftaran pasien offline
            Route::get('pendaftaran-pasien-offline/{id}',[PendaftaranPasienOfflineController::class,'index'])->name('pendaftaran-offline.index');
            Route::post('pendaftaran-pasien-offline/store',[PendaftaranPasienOfflineController::class,'store'])->name('pendaftaran-offline.store');
        });

        Route::group(['prefix' => 'laporan'], function () {
            Route::get('laporan-kunjungan-jenis',[LaporanController::class,'LaporanJenis'])->name('laporan.kunjungan-jenis');
        });

    });
});
// pasien
Route::group(['prefix' => 'auth'], function () {
    Route::get('login',[AuthController::class,'login'])->name('pasien.login');
    Route::post('login/store',[AuthController::class,'store'])->name('pasien.login.store');
});
Route::get('/',[DashboardPasienController::class,'index'])->name('dashboard.pasien');
Route::prefix('dashboard-pasien')->group(function () {
    // dashboard
    // list ketentuan umum
    Route::get('ketentuan-umum',[DashboardPasienController::class,'ketentuan'])->name('pasien.ketentuan');
    // jenis pembayaran
    Route::get('jenis-pembayaran',[DashboardPasienController::class,'jenisPembayaran'])->name('pasien.jenis-pembayaran');
    Route::post('jenis-pembayaran-bpjs/store',[DashboardPasienController::class,'jenisPembayaranBpjsStore'])->name('pasien.jenis-pembayaran-bpjs.store');
    Route::get('jenis-pembayaran-bpjs',[DashboardPasienController::class,'jenisPembayaranBpjs'])->name('pasien.jenis-pembayaran-bpjs');
    // list poliklinik
    Route::get('list-poliklinik/search',[DashboardPasienController::class,'listPoliklinik'])->name('pasien.list-poliklinik.search');
    Route::get('list-poliklinik',[DashboardPasienController::class,'listPoliklinik'])->name('pasien.list-poliklinik');
    // list dokter
    Route::get('list-dokter/search/{id}',[DashboardPasienController::class,'listDokter'])->name('pasien.list-dokter.search');
    Route::get('list-dokter/{id}',[DashboardPasienController::class,'listDokter'])->name('pasien.list-dokter');
    // konfirmasi pembayaran
    Route::get('konfirmasi-pendaftaran/{id}',[DashboardPasienController::class,'konfirmasiPendaftaran'])->name('pasien.konfirmasi-pendaftaran');
    Route::get('konfirmasi-pendaftaran/store/{id}',[DashboardPasienController::class,'konfirmasiPendaftaranStore'])->name('pasien.konfirmasi-pendaftaran.store');
    // cetak qrcode
    Route::get('generate-qrcode/{id}',[DashboardPasienController::class,'cetakQrcode'])->name('pasien.qrcode');

    // List Jadwal Dokter
    Route::get('list-jadwal-dokter/search',[ListJadwalDokterController::class,'index'])->name('pasien.list-jadwal-dokter.search');
    Route::get('list-jadwal-dokter',[ListJadwalDokterController::class,'index'])->name('pasien.list-jadwal-dokter');
});

Route::middleware('auth','role:admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('import',[ImportDataController::class,'index']);
// API
Route::get('pendaftaran-pasien-offline/list-dokter',[PendaftaranPasienOfflineController::class,'dokter'])->name('pendaftaran-offline.list-dokter');


require __DIR__.'/auth.php';
