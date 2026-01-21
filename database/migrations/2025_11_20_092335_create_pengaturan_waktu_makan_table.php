<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_waktu_makan', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_makanan', ['sarapan', 'makan_siang', 'makan_malam'])->unique();
            $table->time('waktu_100_start');
            $table->time('waktu_100_end');
            $table->time('waktu_70_start');
            $table->time('waktu_70_end');
            $table->integer('nilai_100')->default(100);
            $table->integer('nilai_70')->default(70);
            $table->integer('nilai_terlambat')->default(50);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default data
        DB::table('pengaturan_waktu_makan')->insert([
            [
                'jenis_makanan' => 'sarapan',
                'waktu_100_start' => '06:00:00',
                'waktu_100_end' => '06:30:00',
                'waktu_70_start' => '06:31:00',
                'waktu_70_end' => '07:00:00',
                'nilai_100' => 100,
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_makanan' => 'makan_siang',
                'waktu_100_start' => '12:00:00',
                'waktu_100_end' => '12:30:00',
                'waktu_70_start' => '12:31:00',
                'waktu_70_end' => '13:00:00',
                'nilai_100' => 100,
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_makanan' => 'makan_malam',
                'waktu_100_start' => '15:00:00',
                'waktu_100_end' => '15:30:00',
                'waktu_70_start' => '15:31:00',
                'waktu_70_end' => '16:00:00',
                'nilai_100' => 100,
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_waktu_makan');
    }
};