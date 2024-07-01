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
            $table->string('estimasi_dilayani',5)->nullable()->change();
            $table->date('tanggal_kunjungan')->nullable()->change();
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
