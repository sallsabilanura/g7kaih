<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beribadahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('bulan', 20);
            $table->integer('tahun');
            
            // Sholat Subuh
            $table->time('subuh_waktu')->nullable();
            $table->integer('subuh_nilai')->default(0);
            $table->string('subuh_kategori')->nullable();
            $table->boolean('subuh_paraf')->default(false);
            $table->string('subuh_paraf_nama')->nullable();
            $table->timestamp('subuh_paraf_waktu')->nullable();
            
            // Sholat Dzuhur
            $table->time('dzuhur_waktu')->nullable();
            $table->integer('dzuhur_nilai')->default(0);
            $table->string('dzuhur_kategori')->nullable();
            $table->boolean('dzuhur_paraf')->default(false);
            $table->string('dzuhur_paraf_nama')->nullable();
            $table->timestamp('dzuhur_paraf_waktu')->nullable();
            
            // Sholat Ashar
            $table->time('ashar_waktu')->nullable();
            $table->integer('ashar_nilai')->default(0);
            $table->string('ashar_kategori')->nullable();
            $table->boolean('ashar_paraf')->default(false);
            $table->string('ashar_paraf_nama')->nullable();
            $table->timestamp('ashar_paraf_waktu')->nullable();
            
            // Sholat Maghrib
            $table->time('maghrib_waktu')->nullable();
            $table->integer('maghrib_nilai')->default(0);
            $table->string('maghrib_kategori')->nullable();
            $table->boolean('maghrib_paraf')->default(false);
            $table->string('maghrib_paraf_nama')->nullable();
            $table->timestamp('maghrib_paraf_waktu')->nullable();
            
            // Sholat Isya
            $table->time('isya_waktu')->nullable();
            $table->integer('isya_nilai')->default(0);
            $table->string('isya_kategori')->nullable();
            $table->boolean('isya_paraf')->default(false);
            $table->string('isya_paraf_nama')->nullable();
            $table->timestamp('isya_paraf_waktu')->nullable();
            
            // Total
            $table->integer('total_nilai')->default(0);
            $table->integer('total_sholat')->default(0);
            
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // ✅ INDEXES PENTING untuk performa
            $table->index('siswa_id', 'idx_siswa_id');
            $table->index('tanggal', 'idx_tanggal');
            $table->index(['siswa_id', 'tanggal'], 'idx_siswa_tanggal');
            $table->index(['siswa_id', 'bulan', 'tahun'], 'idx_siswa_bulan_tahun');
            
            // Index untuk setiap kolom paraf
            $table->index('subuh_paraf', 'idx_subuh_paraf');
            $table->index('dzuhur_paraf', 'idx_dzuhur_paraf');
            $table->index('ashar_paraf', 'idx_ashar_paraf');
            $table->index('maghrib_paraf', 'idx_maghrib_paraf');
            $table->index('isya_paraf', 'idx_isya_paraf');
            
            $table->unique(['siswa_id', 'tanggal'], 'unique_siswa_tanggal');
        });
        
        // ✅ PENTING: Set default value untuk kolom boolean
        DB::statement("ALTER TABLE beribadahs 
            MODIFY COLUMN subuh_paraf TINYINT(1) NOT NULL DEFAULT 0,
            MODIFY COLUMN dzuhur_paraf TINYINT(1) NOT NULL DEFAULT 0,
            MODIFY COLUMN ashar_paraf TINYINT(1) NOT NULL DEFAULT 0,
            MODIFY COLUMN maghrib_paraf TINYINT(1) NOT NULL DEFAULT 0,
            MODIFY COLUMN isya_paraf TINYINT(1) NOT NULL DEFAULT 0
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beribadahs');
    }
};