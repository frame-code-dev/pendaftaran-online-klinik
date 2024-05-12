<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $param['count_pasien'] = PendaftaranPasien::count();
        $param['count_pasien_sudah'] = PendaftaranPasien::where('status_verifikasi','sudah-verifikasi')->latest()->count();
        $param['count_pasien_belum'] = PendaftaranPasien::where('status_verifikasi','belum-verifikasi')->latest()->count();
        $total_online = PendaftaranPasien::select('jenis_pendaftaran',DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d") as month')
                                ,DB::raw("(count(id)) as total_data"))
                                ->orderBy('created_at')
                                ->where('jenis_pendaftaran','online')
                                ->groupBy('month','jenis_pendaftaran')
                                ->get();
        $total_offline = PendaftaranPasien::select('jenis_pendaftaran',DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as month')
                                ,DB::raw("(count(id)) as total_data"))
                                ->orderBy('created_at')
                                ->where('jenis_pendaftaran','offline')
                                ->groupBy('month','jenis_pendaftaran')
                                ->get();
        $currentDate = Carbon::now();

        $chartData = PendaftaranPasien::with('poliklinik')
            ->selectRaw('count(*) as count, poliklinik_id')
            ->groupBy('poliklinik_id')
            ->get();

        $param['dataChart'] = [];

        foreach ($chartData as $dataPoint) {
             $param['dataChart'][] = [
                'name' => 'Klinik '.$dataPoint->poliklinik->name, // Ganti 'nama' dengan nama atribut yang sesuai pada model Poliklinik
                'y' => $dataPoint->count
            ];
        }
        $startDate = $currentDate->startOfMonth();

        // Set the end date to one year from the current date
        $endDate = $currentDate->copy()->addYear()->endOfMonth();

        $param['period'] = CarbonPeriod::create($startDate, '1 month', $endDate);
        $param['total_online'] = $total_online->pluck('total_data')->toArray();
        $param['total_offline'] = $total_offline->pluck('total_data')->toArray();
        return view('dashboard',$param);
    }
}
