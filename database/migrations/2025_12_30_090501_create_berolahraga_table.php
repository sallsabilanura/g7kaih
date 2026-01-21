<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('berolahraga', function (Blueprint $table) { // tanpa 's'
            $table->id();
            
            // Foreign key ke tabel siswa
            $table->foreignId('siswa_id')->constrained()->onDelete('cascade');
            
            // Tanggal dan waktu olahraga
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('durasi_menit');
            
            // Data video
            $table->string('video_path')->nullable();
            $table->string('video_filename')->nullable();
            $table->integer('video_size')->nullable(); // dalam KB
            
            // Nilai otomatis
            $table->integer('nilai')->nullable();
            $table->enum('kategori_nilai', ['sangat_baik', 'baik', 'cukup', 'kurang'])->nullable();
            
            // Verifikasi oleh admin
            $table->text('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            
            // Koreksi waktu (jika ada)
            $table->time('waktu_mulai_koreksi')->nullable();
            $table->time('waktu_selesai_koreksi')->nullable();
            $table->boolean('ada_koreksi_waktu')->default(false);
            
            $table->timestamps();

            // Indexes untuk performa
            $table->index(['siswa_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('nilai');
            $table->index('kategori_nilai');
        });
    }

    public function down()
    {
        Schema::dropIfExists('berolahraga'); // tanpa 's'
    }
};