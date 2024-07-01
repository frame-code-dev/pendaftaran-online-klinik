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
        Schema::create('pasien', function (Blueprint $table) {
            $table->smallInteger('id',true);
            $table->string('name',50);
            $table->string('nik',16);
            $table->enum('jenis_kelamin',['l','p']);
            $table->string('alamat',50)->nullable();
            $table->string('rt',20)->nullable();
            $table->string('rw',20)->nullable();
            $table->string('tempat_lahir',20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->bigInteger('provinsi_id')->nullable();
            $table->bigInteger('kabupaten_id')->nullable();
            $table->bigInteger('kecamatan_id')->nullable();
            $table->bigInteger('desa_id')->nullable();
            $table->string('agama',10)->nullable();
            $table->string('status_kawin',10)->nullable();
            $table->string('pendidikan',20)->nullable();
            $table->string('pekerjaan',20)->nullable();
            $table->string('suku',20)->nullable();
            $table->string('bahasa',20)->nullable();
            $table->string('no_hp',14)->nullable();
            $table->string('nama_ortu',50)->nullable();
            $table->smallInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
