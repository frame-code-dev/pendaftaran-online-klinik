<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_pasien', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pendaftaran');
            $table->string('no_kartu')->nullable();
            $table->bigInteger('no_antrian');
            $table->enum('jenis_pembayaran',['bpjs','umum']);
            $table->bigInteger('dokter_id');
            $table->bigInteger('pasien_id');
            $table->date('tanggal_kunjungan');
            $table->string('estimasi_dilayani');
            $table->enum('status_pendaftaran',['proses','pending','selesai','batal']);
            $table->enum('jenis_pendaftaran',['online','offline']);
            $table->bigInteger('poliklinik_id');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pasien');
    }
};
