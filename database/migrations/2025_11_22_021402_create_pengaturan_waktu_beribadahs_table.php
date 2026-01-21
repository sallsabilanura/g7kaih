<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_waktu_beribadahs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_sholat'); // subuh, dzuhur, ashar, maghrib, isya
            
            // Kategori 1: Tepat Waktu
            $table->time('waktu_tepat_start');
            $table->time('waktu_tepat_end');
            $table->integer('nilai_tepat')->default(100);
            
            // Kategori 2: Terlambat
            $table->time('waktu_terlambat_start');
            $table->time('waktu_terlambat_end');
            $table->integer('nilai_terlambat')->default(70);
            
            // Tidak Sholat
            $table->integer('nilai_tidak_sholat')->default(0);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('jenis_sholat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_waktu_beribadahs');
    }
};