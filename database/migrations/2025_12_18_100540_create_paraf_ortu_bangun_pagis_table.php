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
        Schema::create('paraf_ortu_bangun_pagis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bangun_pagi_id')->constrained('bangun_pagis')->onDelete('cascade');
            $table->string('nama_ortu');
            $table->text('tanda_tangan')->nullable();
            $table->timestamp('waktu_paraf')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['terverifikasi', 'belum_verifikasi'])->default('belum_verifikasi');
            $table->timestamps();

            // Index untuk performa
            $table->index('bangun_pagi_id');
            $table->index('status');
            $table->index('waktu_paraf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paraf_ortu_bangun_pagis');
    }
};