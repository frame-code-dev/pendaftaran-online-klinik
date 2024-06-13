<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\PendaftaranPasien;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksHistoryController extends Controller
{
    public function index(Request $request) {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $param['title']  = 'History Reservasi';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
            ->when($request->get('start'), function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderByDesc('tanggal_kunjungan')->get();
        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
        return view('backoffice.transaksi.history-transaksi.index',$param);
    }

    public function pdf(Request $request)  {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $param['title']  = 'History Reservasi';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
            ->when($request->get('start'), function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderByDesc('tanggal_kunjungan')->get();
        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
        $param['start'] = $start;
        $param['end'] = $end;
        return view('backoffice.transaksi.history-transaksi.pdf',$param);

    }
    public function excel(Request $request)  {
        $start = Carbon::parse($request->start)->format('Y-m-d');
        $end = Carbon::parse($request->end)->format('Y-m-d');

        $param['title']  = 'History Reservasi';
        $param['data'] = PendaftaranPasien::with('dokter','poliklinik','pasien')
            ->when($request->get('start'), function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->orderByDesc('tanggal_kunjungan')->get();
        $param['data']->transform(function ($item, $key) {
            $jadwal = JadwalDokter::where('dokter_id',$item->dokter_id)->where('status',$item->jenis_pembayaran)->get();
            $item->jadwal_dokter = $jadwal;
            return $item;
        });
        $param['start'] = $start;
        $param['end'] = $end;
        return view('backoffice.transaksi.history-transaksi.excel',$param);

    }
}
