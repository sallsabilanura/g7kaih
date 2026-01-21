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
        Schema::create('tidur_cepats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('bangun_pagi_id')->nullable()->constrained('bangun_pagis')->onDelete('set null');
            $table->date('tanggal');
            $table->time('pukul_tidur')->nullable();
            $table->time('pukul_bangun')->nullable();
            $table->string('bulan', 20);
            $table->integer('tahun');
            $table->integer('nilai')->default(0);
            $table->string('kategori_waktu')->nullable();
            $table->enum('status_bangun_pagi', ['belum_bangun', 'sudah_bangun'])->default('belum_bangun');
            $table->boolean('sudah_ttd_ortu')->default(false);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']);
            $table->index(['siswa_id', 'bulan', 'tahun']);
            $table->index('bangun_pagi_id');
            $table->index('status_bangun_pagi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tidur_cepats');
    }
};