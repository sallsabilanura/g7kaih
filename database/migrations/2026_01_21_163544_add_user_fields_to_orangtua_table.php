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
        // Cek apakah tabel orangtua ada
        if (Schema::hasTable('orangtua')) {
            Schema::table('orangtua', function (Blueprint $table) {
                // Tambahkan user_id jika belum ada
                if (!Schema::hasColumn('orangtua', 'user_id')) {
                    $table->foreignId('user_id')
                          ->nullable()
                          ->after('id')
                          ->constrained('users')
                          ->onDelete('set null');
                }
                
                // Tambahkan email_ayah jika belum ada
                if (!Schema::hasColumn('orangtua', 'email_ayah')) {
                    $table->string('email_ayah')->nullable()->after('telepon_ayah');
                }
                
                // Tambahkan email_ibu jika belum ada
                if (!Schema::hasColumn('orangtua', 'email_ibu')) {
                    $table->string('email_ibu')->nullable()->after('telepon_ibu');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orangtua')) {
            Schema::table('orangtua', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn(['user_id', 'email_ayah', 'email_ibu']);
            });
        }
    }
};