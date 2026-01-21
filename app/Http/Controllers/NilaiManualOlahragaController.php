<?php

namespace App\Http\Controllers;

use App\Models\Berolahraga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiManualOlahragaController extends Controller
{
    /**
     * Menampilkan halaman manajemen nilai olahraga
     */
   public function index()
    {
        $data = Berolahraga::with(['siswa'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // 10 data per halaman
        
        return view('nilai-manual-olahraga.index', compact('data'));
    }

    /**
     * Simpan atau update nilai ke database
     */
    public function simpanNilai(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'catatan_admin' => 'nullable|string|max:500',
        ], [
            'nilai.required' => 'Nilai wajib diisi!',
            'nilai.integer' => 'Nilai harus berupa angka!',
            'nilai.min' => 'Nilai minimal 0!',
            'nilai.max' => 'Nilai maksimal 100!',
        ]);

        try {
            DB::beginTransaction();

            // Cari data olahraga
            $data = Berolahraga::with('siswa')->findOrFail($id);
            
            // Simpan nama siswa untuk pesan sukses
            $namaSiswa = $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa';
            $tanggal = $data->tanggal->format('d/m/Y');

            // Tentukan kategori nilai berdasarkan nilai
            $kategori = $this->tentukanKategoriNilai($request->nilai);

            // Update nilai dan informasi verifikasi
            $data->nilai = $request->nilai;
            $data->kategori_nilai = $kategori;
            $data->catatan_admin = $request->catatan_admin;
            $data->verified_by = auth()->id();
            $data->verified_at = now();
            
            $data->save();

            DB::commit();

            return redirect()->route('nilai-manual-olahraga.index')
                ->with('success', "Nilai {$request->nilai} ({$kategori}) berhasil disimpan untuk {$namaSiswa} - {$tanggal}!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan nilai olahraga: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-olahraga.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai. Silakan coba lagi.');
        }
    }

    /**
     * Tentukan kategori nilai berdasarkan nilai numerik
     */
    private function tentukanKategoriNilai($nilai)
    {
        if ($nilai >= 90) return 'sangat_baik';
        if ($nilai >= 80) return 'baik';
        if ($nilai >= 70) return 'cukup';
        return 'kurang';
    }

    /**
     * Reset nilai ke null (hapus nilai)
     */
    public function resetNilai($id)
    {
        try {
            DB::beginTransaction();

            $data = Berolahraga::with('siswa')->findOrFail($id);
            $namaSiswa = $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa';
            $tanggal = $data->tanggal->format('d/m/Y');

            // Reset nilai
            $data->nilai = null;
            $data->kategori_nilai = null;
            $data->catatan_admin = null;
            $data->verified_by = null;
            $data->verified_at = null;
            
            $data->save();

            DB::commit();

            return redirect()->route('nilai-manual-olahraga.index')
                ->with('success', "Nilai berhasil direset untuk {$namaSiswa} - {$tanggal}!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reset nilai olahraga: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-olahraga.index')
                ->with('error', 'Terjadi kesalahan saat mereset nilai.');
        }
    }
}