<?php

namespace App\Http\Controllers;

use App\Models\GemarBelajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NilaiManualGemarBelajarController extends Controller
{
    /**
     * Menampilkan halaman manajemen nilai gemar belajar
     */
    public function index(Request $request)
    {
        try {
            $query = GemarBelajar::with(['siswa' => function($q) {
                $q->select('id', 'nama_lengkap', 'nama', 'nis');
            }])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

            // Filter berdasarkan status jika ada
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan bulan jika ada
            if ($request->has('bulan') && $request->bulan !== 'all') {
                $query->where('bulan', $request->bulan);
            }

            // Filter berdasarkan tahun jika ada
            if ($request->has('tahun') && $request->tahun !== 'all') {
                $query->where('tahun', $request->tahun);
            }

            // Search jika ada
            if ($request->has('search')) {
                $search = $request->search;
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nama', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                })->orWhere('judul_buku', 'like', "%{$search}%");
            }

            $data = $query->paginate(10);

            $pendingCount = GemarBelajar::where('status', 'pending')->count();
            $approvedCount = GemarBelajar::where('status', 'approved')->count();
            $totalCount = GemarBelajar::count();

            // Data untuk filter dropdown
            $bulanList = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $tahunList = GemarBelajar::select('tahun')
                ->distinct()
                ->orderBy('tahun', 'desc')
                ->pluck('tahun');

            return view('nilai-manual-gemar-belajar.index', compact(
                'data', 
                'pendingCount', 
                'approvedCount', 
                'totalCount',
                'bulanList',
                'tahunList'
            ));

        } catch (\Exception $e) {
            Log::error('Error di index NilaiManualGemarBelajarController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
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

            // Cari data gemar belajar
            $data = GemarBelajar::with(['siswa' => function($q) {
                $q->select('id', 'nama_lengkap', 'nama');
            }])->findOrFail($id);
            
            // Simpan nama siswa dan buku untuk pesan sukses
            $namaSiswa = $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa';
            $judulBuku = $data->judul_buku;

            // Update nilai dan status
            $data->nilai = $request->nilai;
            $data->status = 'approved';
            
            // Set updated_by jika user login
            if (auth()->check()) {
                $data->updated_by = auth()->id();
            }
            
            $data->save();

            DB::commit();

            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('success', "Nilai {$request->nilai} berhasil disimpan untuk {$namaSiswa} - \"{$judulBuku}\"!");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Data gemar belajar tidak ditemukan: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('error', 'Data tidak ditemukan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error simpan nilai gemar belajar: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai. Silakan coba lagi.');
        }
    }

    /**
     * Reset nilai ke status pending
     */
    public function resetNilai($id)
    {
        try {
            DB::beginTransaction();

            $data = GemarBelajar::with(['siswa' => function($q) {
                $q->select('id', 'nama_lengkap', 'nama');
            }])->findOrFail($id);

            $namaSiswa = $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa';
            $judulBuku = $data->judul_buku;

            // Reset ke pending
            $data->status = 'pending';
            $data->nilai = null;
            $data->save();

            DB::commit();

            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('success', "Nilai berhasil direset untuk {$namaSiswa} - \"{$judulBuku}\"!");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Data gemar belajar tidak ditemukan saat reset: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('error', 'Data tidak ditemukan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error reset nilai gemar belajar: ' . $e->getMessage());
            
            return redirect()->route('nilai-manual-gemar-belajar.index')
                ->with('error', 'Terjadi kesalahan saat mereset nilai. Silakan coba lagi.');
        }
    }
}