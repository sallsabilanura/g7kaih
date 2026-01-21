<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_waktu_tidur_cepats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengaturan')->nullable()->default('Pengaturan Waktu Tidur');
            $table->text('deskripsi')->nullable();
            $table->json('kategori_waktu')->nullable(); // JSON untuk kategori fleksibel
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_waktu_tidur_cepats');
    }
};