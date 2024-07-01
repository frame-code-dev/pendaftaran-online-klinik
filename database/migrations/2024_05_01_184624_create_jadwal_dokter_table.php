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
        Schema::create('jadwal_dokter', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->bigInteger('dokter_id')->unsigned();
            $table->enum('status',['bpjs','umum']);
            $table->string('senin',50)->nullable();
            $table->string('selasa',50)->nullable();
            $table->string('rabu',50)->nullable();
            $table->string('kamis',50)->nullable();
            $table->string('jumaat',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokter');
    }
};
