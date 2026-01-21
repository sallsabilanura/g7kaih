<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('makan_sehat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('bulan', 20);
            
            // Jenis waktu makan (sarapan, makan_siang, makan_malam)
            $table->enum('jenis_makanan', ['sarapan', 'makan_siang', 'makan_malam']);
            
            // Waktu dan menu
            $table->time('waktu_makan');
            $table->text('menu_makanan');
            $table->integer('nilai')->default(0);
            
            // Dokumentasi per waktu makan
            $table->string('dokumentasi_foto')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
            
            $table->index(['tanggal', 'bulan']);
            $table->index(['siswa_id', 'tanggal']);
            $table->index(['jenis_makanan', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('makan_sehat');
    }
};