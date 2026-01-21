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
        Schema::table('bermasyarakat', function (Blueprint $table) {
            // Tambahkan kolom sudah_ttd_ortu jika belum ada
            if (!Schema::hasColumn('bermasyarakat', 'sudah_ttd_ortu')) {
                $table->boolean('sudah_ttd_ortu')->default(false)->after('paraf_ortu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bermasyarakat', function (Blueprint $table) {
            $table->dropColumn('sudah_ttd_ortu');
        });
    }
};