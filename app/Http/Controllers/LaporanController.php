<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPasien;
use App\Models\Poliklinik;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function LaporanJenis(Request $request)  {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')->get();
        return view('backoffice.laporan.laporan-jenis',$param);
    }

    public function LaporanJenisPdf(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end) {
                            $query->whereBetween('created_at', [$start, $end]);
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')->get();
        $param['count_umum'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end) {
                                $query->whereBetween('created_at', [$start, $end]);
                            })
                            ->where('status_verifikasi','sudah-verifikasi')
                            ->where('jenis_pembayaran','umum')->count();
        $param['count_bpjs'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end) {
                                $query->whereBetween('created_at', [$start, $end]);
                            })
                            ->where('status_verifikasi','sudah-verifikasi')
                            ->where('jenis_pembayaran','bpjs')->count();
        $param['start'] = $start;
        $param['end'] = $end;
        $param['jenis'] = $jenis;
        return view('backoffice.laporan.laporan-jenis-pdf',$param);
    }

    public function LaporanJenisExcel(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien BPJS Umum';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $jenis = $request->get('pembayaran');
        $diffstart = Carbon::parse($request->start);
        $diffend = Carbon::parse($request->end);

        $diff_in_days = $diffstart->diffInDays($diffend);
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                            if ($diff_in_days == 0) {
                                $query->whereDate('tanggal_kunjungan', $end);
                            }else{
                                $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                            }
                        })
                        ->when($request->get('pembayaran'), function ($query) use ($jenis) {
                            $query->where('jenis_pembayaran', $jenis);
                        })
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')
                        ->get();
        $param['count_umum'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                            ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                                if ($diff_in_days == 0) {
                                    $query->whereDate('tanggal_kunjungan', $end);
                                }else{
                                    $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                                }
                            })->where('jenis_pembayaran','umum')->count();
        $param['count_bpjs'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                    ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                        if ($diff_in_days == 0) {
                            $query->whereDate('tanggal_kunjungan', $end);
                        }else{
                            $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                        }
                    })
                    ->where('status_verifikasi','sudah-verifikasi')
                    ->where('jenis_pembayaran','bpjs')
                    ->count();
        $param['jenis'] = $jenis;
        return view('backoffice.laporan.laporan-jenis-excel',$param);
    }

    public function LaporanKunjunganPasien(Request $request)  {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $diffstart = Carbon::parse($request->start);
        $diffend = Carbon::parse($request->end);

        $diff_in_days = $diffstart->diffInDays($diffend);
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                            if ($diff_in_days == 0) {
                                $query->whereDate('tanggal_kunjungan', $end);
                            }else{
                                $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                            }
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                        ->where('jenis_pendaftaran','online')
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')
                        ->get();
        $param['poliklinik'] = Poliklinik::latest()->get();
        return view('backoffice.laporan.laporan-kunjungan',$param);
    }

    public function LaporanKunjunganPasienPdf(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $poliklinik = $request->get('poliklinik');

        $diffstart = Carbon::parse($request->start);
        $diffend = Carbon::parse($request->end);

        $diff_in_days = $diffstart->diffInDays($diffend);
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                            if ($diff_in_days == 0) {
                                $query->whereDate('tanggal_kunjungan', $end);
                            }else{
                                $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                            }
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                        ->where('jenis_pendaftaran','online')
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')
                        ->get();
        $param['count_pendaftaran_online'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                                        ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                                            if ($diff_in_days == 0) {
                                                $query->whereDate('tanggal_kunjungan', $end);
                                            }else{
                                                $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                                            }
                                        })
                                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                                            $query->where('poliklinik_id', $poliklinik);
                                        })
                                        ->where('jenis_pendaftaran','online')
                                        ->latest()
                                        ->count();
        $param['start'] = $start;
        $param['end'] = $end;
        return view('backoffice.laporan.laporan-kunjungan-pdf',$param);
    }

    public function LaporanKunjunganPasienExcel(Request $request) {
        $param['title'] = 'Laporan Kunjungan Pasien Pendaftaran Online';
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');
        $poliklinik = $request->get('poliklinik');
        $diffstart = Carbon::parse($request->start);
        $diffend = Carbon::parse($request->end);

        $diff_in_days = $diffstart->diffInDays($diffend);
        $poliklinik = $request->get('poliklinik');
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
                        ->when($request->get('start'), function ($query) use ($start, $end, $diff_in_days) {
                            if ($diff_in_days == 0) {
                                $query->whereDate('tanggal_kunjungan', $end);
                            }else{
                                $query->whereBetween('tanggal_kunjungan', [$start, $end]);
                            }
                        })
                        ->when($request->get('poliklinik'), function ($query) use ($poliklinik) {
                            $query->where('poliklinik_id', $poliklinik);
                        })
                        ->where('jenis_pendaftaran','online')
                        ->where('status_verifikasi','sudah-verifikasi')
                        ->orderByDesc('tanggal_kunjungan')
                        ->get();
        $param['start'] = $start;
        $param['end'] = $end;
        return view('backoffice.laporan.laporan-kunjungan-excel',$param);
    }



}
