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
        Schema::table('bangun_pagis', function (Blueprint $table) {
            // Tambahkan kolom tanda_tangan_ortu jika belum ada
            if (!Schema::hasColumn('bangun_pagis', 'tanda_tangan_ortu')) {
                $table->string('tanda_tangan_ortu')->nullable()->after('sudah_ttd_ortu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bangun_pagis', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan_ortu');
        });
    }
};