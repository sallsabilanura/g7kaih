waktu bangun pagi

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
        Schema::create('pengaturan_waktu_bangun_pagis', function (Blueprint $table) {
            $table->id();
            
            // Kategori 1: Tepat Waktu (Nilai Tertinggi)
            $table->time('waktu_100_start'); // Misal: 04:00
            $table->time('waktu_100_end');   // Misal: 05:00
            $table->integer('nilai_100')->default(100);
            
            // Kategori 2: Sedikit Terlambat
            $table->time('waktu_70_start');  // Misal: 05:00
            $table->time('waktu_70_end');    // Misal: 06:00
            $table->integer('nilai_70')->default(70);
            
            // Kategori 3: Terlambat (Di luar rentang)
            $table->integer('nilai_terlambat')->default(50);
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_waktu_bangun_pagis');
    }
};