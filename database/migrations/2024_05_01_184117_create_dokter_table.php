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
            $table->smallInteger('id',true);
            $table->smallInteger('poliklinik_id');
            $table->smallInteger('user_id');
            $table->string('name',50);
            $table->string('gambar',50);
            $table->date('tanggal');
            $table->string('jam_praktek',50);
            $table->smallInteger('kuota')->default(0);
            $table->smallInteger('kuota_terisi')->default(0);
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
