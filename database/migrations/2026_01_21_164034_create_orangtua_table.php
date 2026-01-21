<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('orangtua')) {
            Schema::create('orangtua', function (Blueprint $table) {
                $table->id();
                $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('nama_ayah');
                $table->string('nama_ibu');
                $table->string('telepon_ayah')->nullable();
                $table->string('telepon_ibu')->nullable();
                $table->string('email_ayah')->nullable();
                $table->string('email_ibu')->nullable();
                $table->text('alamat');
                $table->string('pekerjaan_ayah')->nullable();
                $table->string('pekerjaan_ibu')->nullable();
                $table->string('pendidikan_ayah')->nullable();
                $table->string('pendidikan_ibu')->nullable();
                $table->date('tanggal_lahir_ayah')->nullable();
                $table->date('tanggal_lahir_ibu')->nullable();
                $table->string('tanda_tangan_ayah')->nullable();
                $table->string('tanda_tangan_ibu')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                // Index untuk performa
                $table->index('siswa_id');
                $table->index('user_id');
                $table->index(['email_ayah', 'email_ibu']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('orangtua');
    }
};