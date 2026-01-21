<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Siswa;
use App\Models\Orangtua;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan data orangtua contoh untuk siswa yang sudah ada
        $siswaList = Siswa::all();
        
        foreach ($siswaList as $siswa) {
            // Cek apakah siswa sudah punya orangtua
            $existingOrangtua = Orangtua::where('siswa_id', $siswa->id)->first();
            
            if (!$existingOrangtua) {
                // Buat data orangtua contoh
                Orangtua::create([
                    'siswa_id' => $siswa->id,
                    'nama_ayah' => 'Ayah ' . $siswa->nama_lengkap,
                    'nama_ibu' => 'Ibu ' . $siswa->nama_lengkap,
                    'telepon_ayah' => '08123456789',
                    'telepon_ibu' => '08198765432',
                    'alamat' => $siswa->alamat ?? 'Jl. Contoh No. 123',
                    'pekerjaan_ayah' => 'PNS',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'pendidikan_ayah' => 'S1',
                    'pendidikan_ibu' => 'SMA',
                    'tanggal_lahir_ayah' => '1980-01-01',
                    'tanggal_lahir_ibu' => '1982-01-01',
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus data orangtua yang dibuat
        Orangtua::where('nama_ayah', 'like', 'Ayah %')->delete();
        Orangtua::where('nama_ibu', 'like', 'Ibu %')->delete();
    }
};