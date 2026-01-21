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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            
            // Data identitas (Wajib)
            $table->string('nisn', 20)->unique();
            $table->string('nis', 20)->unique();
            
            // Data pribadi
            $table->string('nama')->nullable(); // Dibuat nullable karena sudah ada nama_lengkap
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir');
            
            // Kontak
            $table->text('alamat');
            $table->string('telepon', 20)->nullable();
            $table->string('email')->nullable();
            
            // Relasi
            $table->foreignId('orangtua_id')->nullable()->constrained('orangtua')->onDelete('set null');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
            
            // Data tambahan
            $table->year('tahun_masuk')->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'dropout'])->default('aktif');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa
            $table->index('nis');
            $table->index('nisn');
            $table->index('kelas_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};