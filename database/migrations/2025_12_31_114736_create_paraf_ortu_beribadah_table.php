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
        Schema::create('paraf_ortu_beribadah', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('beribadah_id')
                  ->constrained('beribadahs')
                  ->onDelete('cascade')
                  ->comment('ID dari tabel beribadahs');
            
            $table->foreignId('siswa_id')
                  ->constrained('siswas')
                  ->onDelete('cascade')
                  ->comment('ID siswa yang melakukan ibadah');
            
            $table->foreignId('ortu_id')
                  ->nullable()
                  ->constrained('orangtua')
                  ->onDelete('set null')
                  ->comment('ID orangtua yang memberikan paraf (jika ada di database)');
            
            // Jenis sholat yang diparaf
            $table->enum('jenis_sholat', ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya', 'semua'])
                  ->default('semua')
                  ->comment('Jenis sholat yang diparaf');
            
            // Data paraf
            $table->string('nama_ortu', 100)
                  ->comment('Nama orang tua yang memberikan paraf');
            
            $table->text('tanda_tangan')
                  ->nullable()
                  ->comment('Tanda tangan digital (opsional)');
            
            $table->timestamp('waktu_paraf')
                  ->useCurrent()
                  ->comment('Waktu paraf diberikan');
            
            $table->text('catatan')
                  ->nullable()
                  ->comment('Catatan dari orang tua (opsional)');
            
            // Status verifikasi
            $table->enum('status', ['pending', 'terverifikasi', 'ditolak'])
                  ->default('terverifikasi')
                  ->comment('Status verifikasi paraf');
            
            $table->text('alasan_tolak')
                  ->nullable()
                  ->comment('Alasan jika paraf ditolak (opsional)');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes untuk performa query
            $table->index(['beribadah_id', 'jenis_sholat']);
            $table->index('siswa_id');
            $table->index('ortu_id');
            $table->index('waktu_paraf');
            $table->index('status');
            
            // Unique constraint: satu jenis sholat hanya bisa diparaf sekali per beribadah
            // Kecuali untuk jenis 'semua' yang bisa ada bersama jenis lainnya
            $table->unique(['beribadah_id', 'jenis_sholat'], 'unique_paraf_per_sholat');
            
            // Comment untuk tabel
            $table->comment('Tabel untuk menyimpan data paraf orang tua pada ibadah siswa');
        });
        
        // Tambahkan constraint untuk memastikan jenis_sholat tidak kosong
        Schema::table('paraf_ortu_beribadah', function (Blueprint $table) {
            DB::statement('ALTER TABLE paraf_ortu_beribadah ADD CONSTRAINT chk_jenis_sholat CHECK (jenis_sholat IN ("subuh", "dzuhur", "ashar", "maghrib", "isya", "semua"))');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paraf_ortu_beribadah');
    }
};