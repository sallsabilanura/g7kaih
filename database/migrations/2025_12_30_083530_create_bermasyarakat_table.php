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
        // Modifikasi tabel bermasyarakat yang sudah ada
        Schema::table('bermasyarakat', function (Blueprint $table) {
            // Ubah kolom gambar_kegiatan menjadi JSON jika belum
            $table->json('gambar_kegiatan')->nullable()->change();
            
            // Tambahkan kolom sudah_ttd_ortu jika belum ada
            if (!Schema::hasColumn('bermasyarakat', 'sudah_ttd_ortu')) {
                $table->boolean('sudah_ttd_ortu')->default(false)->after('paraf_ortu');
            }
            
            // Tambahkan index untuk performa
            $table->index('siswa_id');
            $table->index('tanggal');
            $table->index(['siswa_id', 'tanggal']);
            $table->index('sudah_ttd_ortu');
            $table->index('paraf_ortu');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bermasyarakat', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['siswa_id']);
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['siswa_id', 'tanggal']);
            $table->dropIndex(['sudah_ttd_ortu']);
            $table->dropIndex(['paraf_ortu']);
            $table->dropIndex(['status']);
            
            // Drop column jika ingin rollback
            if (Schema::hasColumn('bermasyarakat', 'sudah_ttd_ortu')) {
                $table->dropColumn('sudah_ttd_ortu');
            }
        });
    }
};