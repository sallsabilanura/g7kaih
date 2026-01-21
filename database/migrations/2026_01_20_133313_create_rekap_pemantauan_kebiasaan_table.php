<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekap_pemantauan_kebiasaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('nama_lengkap', 100);
            $table->string('kelas', 10); // Ubah dari enum ke string untuk fleksibilitas
            $table->string('bulan', 20);
            $table->integer('tahun');
            $table->enum('bangun_pagi_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('beribadah_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('berolahraga_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('makan_sehat_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('gemar_belajar_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('bermasyarakat_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->enum('tidur_cepat_status', ['belum_terbiasa', 'sudah_terbiasa'])->default('belum_terbiasa');
            $table->string('guru_kelas', 100)->nullable();
            $table->string('orangtua_siswa', 100)->nullable();
            $table->date('tanggal_persetujuan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['siswa_id', 'bulan', 'tahun']);
            $table->index('kelas');
            $table->index('tahun');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekap_pemantauan_kebiasaan');
    }
};