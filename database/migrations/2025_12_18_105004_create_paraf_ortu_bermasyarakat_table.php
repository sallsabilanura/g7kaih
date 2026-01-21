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
        Schema::create('paraf_ortu_bermasyarakat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bermasyarakat_id')->constrained('bermasyarakat')->onDelete('cascade');
            $table->string('nama_ortu');
            $table->text('tanda_tangan')->nullable();
            $table->timestamp('waktu_paraf')->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['terverifikasi', 'belum_verifikasi'])->default('belum_verifikasi');
            $table->foreignId('ortu_id')->nullable()->constrained('orangtua')->onDelete('set null');
            $table->timestamps();

            // Index untuk performa
            $table->index('bermasyarakat_id');
            $table->index('ortu_id');
            $table->index('status');
            $table->index('waktu_paraf');
        });

        // Update tabel bermasyarakat untuk relasi paraf
        Schema::table('bermasyarakat', function (Blueprint $table) {
            $table->boolean('sudah_ttd_ortu')->default(false)->after('paraf_ortu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paraf_ortu_bermasyarakat');
        
        Schema::table('bermasyarakat', function (Blueprint $table) {
            $table->dropColumn('sudah_ttd_ortu');
        });
    }
};