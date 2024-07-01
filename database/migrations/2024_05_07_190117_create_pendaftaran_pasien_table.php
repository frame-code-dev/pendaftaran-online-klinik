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
            $table->smallInteger('id',true);
            $table->string('kode_pendaftaran',50);
            $table->string('no_kartu',13)->nullable();
            $table->smallInteger('no_antrian');
            $table->enum('jenis_pembayaran',['bpjs','umum']);
            $table->smallInteger('dokter_id');
            $table->smallInteger('pasien_id');
            $table->date('tanggal_kunjungan');
            $table->string('estimasi_dilayani',5);
            $table->enum('status_pendaftaran',['proses','pending','selesai','batal']);
            $table->enum('jenis_pendaftaran',['online','offline']);
            $table->smallInteger('poliklinik_id');
            $table->string('gambar',50)->nullable();
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
