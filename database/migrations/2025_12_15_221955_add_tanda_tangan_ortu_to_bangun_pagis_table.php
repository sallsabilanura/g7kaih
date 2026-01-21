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
            if (Schema::hasColumn('bangun_pagis', 'tanda_tangan_ortu')) {
                $table->dropColumn('tanda_tangan_ortu');
            }
        });
    }
};