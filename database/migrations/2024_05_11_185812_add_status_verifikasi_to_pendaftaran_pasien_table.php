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
        Schema::table('pendaftaran_pasien', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['belum-verifikasi', 'sudah-verifikasi'])->default('belum-verifikasi')->after('gambar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_pasien', function (Blueprint $table) {
            //
        });
    }
};
