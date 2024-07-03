<?php

use App\Http\Controllers\CetakAntrianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPasienController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\ImportDataController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ListJadwalDokterController;
use App\Http\Controllers\Pasien\Auth\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PasienHistoryTransaksiController;
use App\Http\Controllers\PendaftaranPasienOfflineController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PoliklinikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePasienController;
use App\Http\Controllers\TransaksHistoryController;
use App\Http\Controllers\TransaksiAntrianKlinikController;
use App\Http\Controllers\TransaksiVerifikasiController;
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
Route::middleware(['auth'])->group(function () {
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
            // pendaftaran online - cetak antrian
            Route::get('cetak-antrian',[CetakAntrianController::class,'index'])->name('cetak-antrian');
            Route::get('cetak-antrian/{id}',[CetakAntrianController::class,'pdf'])->name('cetak-antrian.pdf');
        });
        Route::group(['prefix' => 'transaksi'], function () {
            // History Transaksi
            Route::get('history-transaksi/pdf',[TransaksHistoryController::class,'pdf'])->name('history-transaksi.pdf');
            Route::get('history-transaksi/excel',[TransaksHistoryController::class,'excel'])->name('history-transaksi.excel');
            Route::get('history-transaksi/search',[TransaksHistoryController::class,'index'])->name('history-transaksi.search');
            Route::get('history-transaksi',[TransaksHistoryController::class,'index'])->name('history-transaksi.index');
            // Verifikasi Transaksi
            Route::post('verifikasi/scan-manual',[TransaksiVerifikasiController::class,'scanManual'])->name('verifikasi.read.manual');
            Route::get('verifikasi/read-scan',[TransaksiVerifikasiController::class,'readScan'])->name('verifikasi.read');
            Route::get('verifikasi/scan',[TransaksiVerifikasiController::class,'detail'])->name('verifikasi.detail');
            Route::get('verifikasi/update/{id}',[TransaksiVerifikasiController::class,'updateManual'])->name('verifikasi.update-manual');
            Route::get('verifikasi',[TransaksiVerifikasiController::class,'index'])->name('verifikasi.index');
            // Antrian Klinik
            Route::get('antrian-klinik/search',[TransaksiAntrianKlinikController::class,'index'])->name('antrian-klinik.search');
            Route::get('antrian-klinik/update/{id}',[TransaksiAntrianKlinikController::class,'updateManual'])->name('antrian-klinik.update-manual');
            Route::get('antrian-klinik',[TransaksiAntrianKlinikController::class,'index'])->name('antrian-klinik.index');
        });
        Route::group(['prefix' => 'laporan'], function () {
            // Laporan Kunjungan Pasien Pendaftaran Online
            Route::get('laporan-kunjungan-pasien/excel',[LaporanController::class,'LaporanKunjunganPasienExcel'])->name('laporan.kunjungan-pasien.excel');
            Route::get('laporan-kunjungan-pasien/pdf',[LaporanController::class,'LaporanKunjunganPasienPdf'])->name('laporan.kunjungan-pasien.pdf');
            Route::get('laporan-kunjungan-pasien/search',[LaporanController::class,'LaporanKunjunganPasien'])->name('laporan.kunjungan-pasien.search');
            Route::get('laporan-kunjungan-pasien',[LaporanController::class,'LaporanKunjunganPasien'])->name('laporan.kunjungan-pasien.index');
            // Laporan Kunjungan Pasien BPJS/Umum
            Route::get('laporan-kunjungan-jenis/pdf',[LaporanController::class,'LaporanJenisPdf'])->name('laporan.kunjungan-jenis.pdf');
            Route::get('laporan-kunjungan-jenis/excel',[LaporanController::class,'LaporanJenisExcel'])->name('laporan.kunjungan-jenis.excel');
            Route::get('laporan-kunjungan-jenis/search',[LaporanController::class,'LaporanJenis'])->name('laporan.kunjungan-jenis.search');
            Route::get('laporan-kunjungan-jenis',[LaporanController::class,'LaporanJenis'])->name('laporan.kunjungan-jenis');
        });

    });
});
// pasien
Route::group(['prefix' => 'auth'], function () {
    Route::get('login',[AuthController::class,'login'])->name('pasien.login');
    Route::post('login/store',[AuthController::class,'store'])->name('pasien.login.store');
    Route::get('pasien/logout',[AuthController::class,'logout'])->name('pasien.login.logout');
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
    // Profile
    Route::post('profile-pasien',[ProfilePasienController::class,'update'])->name('profile-pasien.update');
    Route::get('profile-pasien',[ProfilePasienController::class,'index'])->name('profile-pasien.edit');
    // History Transaksi
    Route::get('history-transaksi/download/{id}',[PasienHistoryTransaksiController::class,'download'])->name('pasien.history-transaksi.download');
    Route::get('history-transaksi/detail',[PasienHistoryTransaksiController::class,'detail'])->name('pasien.history-transaksi.detail');
    Route::get('history-transaksi',[PasienHistoryTransaksiController::class,'index'])->name('pasien.history-transaksi.index');
    // Antrian Klinik
    Route::get('history-transaksi/update',[PasienHistoryTransaksiController::class,'updateStatus'])->name('pasien.history-transaksi.update');
    // Import data
    Route::get('import',[ImportDataController::class,'index'])->name('import.create');
    Route::post('import/store',[ImportDataController::class,'store'])->name('import.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API
Route::get('pendaftaran-pasien-offline/list-dokter',[PendaftaranPasienOfflineController::class,'dokter'])->name('pendaftaran-offline.list-dokter');


require __DIR__.'/auth.php';
