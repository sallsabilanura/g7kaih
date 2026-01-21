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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom NIS jika belum ada
            if (!Schema::hasColumn('users', 'nis')) {
                $table->string('nis')->unique()->nullable()->after('email');
            }
            
            // Tambahkan kolom kelas jika belum ada
            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas')->nullable()->after('nis');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nis', 'kelas']);
        });
    }
};