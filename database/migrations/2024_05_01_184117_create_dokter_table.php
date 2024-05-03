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
        Schema::create('dokter', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('poliklinik_id');
            $table->bigInteger('user_id');
            $table->string('name');
            $table->string('gambar');
            $table->date('tanggal');
            $table->string('jam_praktek');
            $table->bigInteger('kuota')->default(0);
            $table->bigInteger('kuota_terisi')->default(0);
            $table->enum('status',['aktif','non-aktif'])->default('aktif');
            $table->enum('jenis_kelamin',['l','p']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
