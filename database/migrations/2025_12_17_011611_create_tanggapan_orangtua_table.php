<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tanggapan_orangtua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            
            // PERBAIKI: Tambah foreign key ke orangtua jika perlu
            $table->unsignedBigInteger('orangtua_id')->nullable();
            
            // Periode tanggapan
            $table->integer('bulan'); // 1-12
            $table->integer('tahun'); // 2024, 2025, dst
            $table->date('tanggal_pengisian'); // Tanggal terakhir hari bulan (30 atau 31)
            
            // Kelas siswa (ambil dari siswa)
            $table->string('kelas'); // Kelas siswa saat ini
            
            // Tanggapan tentang 7 Kebiasaan
            $table->text('tanggapan'); // Tanggapan orangtua tentang kebiasaan anak
            
            // Nama orangtua yang mengisi (dari dropdown)
            $table->string('nama_orangtua'); // Ayah atau Ibu
            $table->enum('tipe_orangtua', ['ayah', 'ibu', 'wali']); // Tipe orangtua
            
            // Tanda tangan digital
            $table->text('tanda_tangan_digital')->nullable(); // Base64 atau path file
            
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['siswa_id', 'bulan', 'tahun']);
            $table->index('kelas'); // Index tambahan untuk query berdasarkan kelas
            
            // Constraint: hanya 1 tanggapan per siswa per bulan
            $table->unique(['siswa_id', 'bulan', 'tahun']);
            
            // Foreign key ke orangtua
            $table->foreign('orangtua_id')->references('id')->on('orangtua')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanggapan_orangtua');
    }
};