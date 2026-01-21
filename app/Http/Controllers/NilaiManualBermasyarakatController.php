<?php

namespace App\Http\Controllers;

use App\Models\Bermasyarakat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiManualBermasyarakatController extends Controller
{
    /**
     * Menampilkan halaman manajemen nilai bermasyarakat
     */
    public function index()
    {
        $data = Bermasyarakat::with(['siswa'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
           ->paginate(10); // 10 data per halaman
        
        return view('nilai-manual-bermasyarakat.index', compact('data'));
    }

    /**
     * Simpan atau update nilai ke database
     */
    public function simpanNilai(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
        ], [
            'nilai.required' => 'Nilai wajib diisi!',
            'nilai.integer' => 'Nilai harus berupa angka!',
            'nilai.min' => 'Nilai minimal 0!',
            'nilai.max' => 'Nilai maksimal 100!',
        ]);

        try {
            DB::beginTransaction();

            // Cari data bermasyarakat
            $data = Bermasyarakat::with('siswa')->findOrFail($id);
            
            // Simpan nama siswa dan kegiatan untuk pesan sukses
            $namaSiswa = $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa';
            $namaKegiatan = $data->nama_kegiatan;

            // Update nilai dan status
            $data->nilai = $request->nilai;
            $data->status = 'approved';
            
            // Set updated_by jika user login
            if (auth()->check()) {
                $data->updated_by = auth()->id();
            }
            
            $data->save();

            DB::commit();

            return redirect()->route('nilai-manual-bermasyarakat.index')
                ->with('success', "Nilai {$request->nilai} berhasil disimpan untuk {$namaSiswa} - \"{$namaKegiatan}\"!");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan nilai bermasyarakat: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-bermasyarakat.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai. Silakan coba lagi.');
        }
    }
}