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
        Schema::create('bangun_pagis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('pukul');
            $table->string('bulan', 20);
            $table->integer('tahun');
            $table->integer('nilai')->default(0);
            $table->string('kategori_waktu')->nullable();
            $table->boolean('sudah_bangun')->default(false);
            $table->boolean('sudah_tidur_cepat')->default(false);
            $table->boolean('sudah_ttd_ortu')->default(false);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']);
            $table->index(['siswa_id', 'bulan', 'tahun']);
            $table->index('sudah_bangun');
            $table->index('sudah_tidur_cepat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangun_pagis');
    }
};