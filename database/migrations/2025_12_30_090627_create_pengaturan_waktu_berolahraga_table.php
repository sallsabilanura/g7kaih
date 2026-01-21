<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_waktu_olahraga', function (Blueprint $table) { // tanpa 's'
            $table->id();
            
            // Kategori Waktu 1: Sangat Baik (Nilai Tertinggi)
            $table->time('waktu_sangat_baik_start'); // Contoh: 05:00
            $table->time('waktu_sangat_baik_end');   // Contoh: 06:00
            $table->integer('nilai_sangat_baik')->default(100); // Nilai 100
            
            // Kategori Waktu 2: Baik (Nilai Menengah Atas)
            $table->time('waktu_baik_start');        // Contoh: 06:01
            $table->time('waktu_baik_end');          // Contoh: 07:00
            $table->integer('nilai_baik')->default(85); // Nilai 85
            
            // Kategori Waktu 3: Cukup (Nilai Menengah)
            $table->time('waktu_cukup_start');       // Contoh: 07:01
            $table->time('waktu_cukup_end');         // Contoh: 08:00
            $table->integer('nilai_cukup')->default(70); // Nilai 70
            
            // Kategori Waktu 4: Kurang (Nilai Rendah)
            // Di luar semua rentang waktu di atas
            $table->integer('nilai_kurang')->default(50); // Nilai 50
            
            // Durasi minimal olahraga (dalam menit)
            $table->integer('durasi_minimal')->default(30); // Minimal 30 menit
            $table->integer('durasi_maksimal')->default(120); // Maksimal 120 menit (2 jam)
            
            // Status aktif
            $table->boolean('is_active')->default(true);
            
            // Keterangan
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_waktu_olahraga'); // tanpa 's'
    }
};