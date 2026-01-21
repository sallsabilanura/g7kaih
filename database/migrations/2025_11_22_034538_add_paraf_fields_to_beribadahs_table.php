<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beribadahs', function (Blueprint $table) {
            // Untuk setiap sholat, tambahkan field paraf
            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            
            foreach ($sholats as $sholat) {
                if (!Schema::hasColumn('beribadahs', $sholat . '_paraf')) {
                    $table->boolean($sholat . '_paraf')->default(false)->after($sholat . '_kategori');
                }
                if (!Schema::hasColumn('beribadahs', $sholat . '_paraf_nama')) {
                    $table->string($sholat . '_paraf_nama')->nullable()->after($sholat . '_paraf');
                }
                if (!Schema::hasColumn('beribadahs', $sholat . '_paraf_waktu')) {
                    $table->timestamp($sholat . '_paraf_waktu')->nullable()->after($sholat . '_paraf_nama');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('beribadahs', function (Blueprint $table) {
            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            
            foreach ($sholats as $sholat) {
                $table->dropColumn([
                    $sholat . '_paraf',
                    $sholat . '_paraf_nama', 
                    $sholat . '_paraf_waktu'
                ]);
            }
        });
    }
};