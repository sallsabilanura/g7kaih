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
        Schema::create('gemar_belajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('judul_buku');
            $table->text('informasi_didapat');
            $table->string('gambar_buku')->nullable();
            $table->string('gambar_baca')->nullable();
            $table->integer('nilai')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gemar_belajar');
    }
};