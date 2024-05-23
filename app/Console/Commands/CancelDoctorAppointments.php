<?php

namespace App\Console\Commands;

use App\Models\PendaftaranPasien;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CancelDoctorAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-doctor-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
          // Tentukan tanggal kunjungan yang menjadi acuan pembatalan (misal hari ini)
          $today = Carbon::today();

          // Ambil semua janji temu yang perlu dibatalkan
          $appointments = PendaftaranPasien::where('jenis_pendaftaran','online')->where('status_pendaftaran','pending')->where('tanggal_kunjungan', '<', $today)->get();

          foreach ($appointments as $appointment) {
              // Logika pembatalan janji temu
              $appointment->status_pendaftaran = 'batal';
              $appointment->save();

          }

          $this->info('Appointments older than today have been cancelled.');
    }
}
