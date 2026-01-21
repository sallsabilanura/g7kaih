<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tidur_cepats', function (Blueprint $table) {
            // Tambahkan kolom bangun_pagi_id SETELAH tabel bangun_pagis dibuat
            $table->foreignId('bangun_pagi_id')->nullable()->after('pukul_bangun');
            
            // Tambahkan foreign key constraint
            $table->foreign('bangun_pagi_id')
                  ->references('id')
                  ->on('bangun_pagis')
                  ->onDelete('set null');
                  
            // Tambahkan index
            $table->index('bangun_pagi_id');
        });
    }

    public function down(): void
    {
        Schema::table('tidur_cepats', function (Blueprint $table) {
            $table->dropForeign(['bangun_pagi_id']);
            $table->dropColumn('bangun_pagi_id');
        });
    }
};