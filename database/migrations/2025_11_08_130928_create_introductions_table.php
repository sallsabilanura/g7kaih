<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntroductionsTable extends Migration
{
    public function up()
    {
        Schema::create('introductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade'); // Perbaiki ke 'siswas'
            $table->text('hobi');
            $table->string('cita_cita');
            $table->string('olahraga_kesukaan');
            $table->string('makanan_kesukaan');
            $table->string('buah_kesukaan');
            $table->string('pelajaran_kesukaan');
            $table->json('warna_kesukaan'); // Ubah ke JSON untuk 3 warna
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('introductions');
    }
}