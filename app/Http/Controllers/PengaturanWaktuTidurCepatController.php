<?php

namespace App\Http\Controllers;

use App\Models\PengaturanWaktuTidurCepat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PengaturanWaktuTidurCepatController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanWaktuTidurCepat::first();
        $sudahAda = !is_null($pengaturan);

        return view('pengaturan-waktu-tidur-cepat.index', [
            'pengaturan' => $pengaturan,
            'sudahAda' => $sudahAda,
        ]);
    }

    public function create()
    {
        if (PengaturanWaktuTidurCepat::first()) {
            return redirect()->route('pengaturan-waktu-tidur-cepat.edit')
                           ->with('info', 'Pengaturan sudah ada. Silakan edit pengaturan yang sudah ada.');
        }

        return view('pengaturan-waktu-tidur-cepat.create');
    }

    public function store(Request $request)
    {
        if (PengaturanWaktuTidurCepat::first()) {
            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('error', 'Pengaturan sudah ada. Silakan edit pengaturan yang sudah ada.');
        }

        try {
            // Validasi minimal (hanya required saja)
            $request->validate([
                'nama_pengaturan' => 'required|string|max:255',
                'kategori.*.nama' => 'required|string|max:255',
                'kategori.*.waktu_start' => 'required|date_format:H:i',
                'kategori.*.waktu_end' => 'required|date_format:H:i',
                'kategori.*.nilai' => 'required|integer|min:0|max:100',
                'kategori.*.urutan' => 'required|integer|min:1',
            ], [
                'kategori.*.nama.required' => 'Nama kategori harus diisi',
                'kategori.*.waktu_start.required' => 'Waktu mulai harus diisi',
                'kategori.*.waktu_end.required' => 'Waktu selesai harus diisi',
                'kategori.*.nilai.required' => 'Nilai harus diisi',
                'kategori.*.urutan.required' => 'Urutan harus diisi',
            ]);

            // Format kategori
            $kategoriData = [];
            $index = 0;
            if ($request->kategori) {
                foreach ($request->kategori as $kat) {
                    $kategoriData[] = [
                        'id' => $index + 1,
                        'nama' => $kat['nama'],
                        'waktu_start' => $kat['waktu_start'],
                        'waktu_end' => $kat['waktu_end'],
                        'nilai' => $kat['nilai'],
                        'warna' => $kat['warna'] ?? '#10B981',
                        'urutan' => $kat['urutan'],
                        'is_active' => isset($kat['is_active']) ? true : false,
                    ];
                    $index++;
                }
            }

            // Simpan ke database
            $pengaturan = PengaturanWaktuTidurCepat::create([
                'nama_pengaturan' => $request->nama_pengaturan,
                'deskripsi' => $request->deskripsi,
                'kategori_waktu' => $kategoriData,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('success', 'Pengaturan waktu tidur berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Error create pengaturan:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit()
    {
        $pengaturan = PengaturanWaktuTidurCepat::first();
        
        if (!$pengaturan) {
            return redirect()->route('pengaturan-waktu-tidur-cepat.create')
                           ->with('info', 'Belum ada pengaturan. Silakan buat pengaturan baru.');
        }

        return view('pengaturan-waktu-tidur-cepat.edit', [
            'pengaturan' => $pengaturan,
        ]);
    }

   public function update(Request $request)
{
    $pengaturan = PengaturanWaktuTidurCepat::first();

    if (!$pengaturan) {
        return redirect()->route('pengaturan-waktu-tidur-cepat.create')
                       ->with('error', 'Pengaturan tidak ditemukan. Silakan buat baru.');
    }

    try {
        // Validasi minimal
        $request->validate([
            'nama_pengaturan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'kategori.*.nama' => 'required|string|max:255',
            'kategori.*.waktu_start' => 'required|date_format:H:i',
            'kategori.*.waktu_end' => 'required|date_format:H:i',
            'kategori.*.nilai' => 'required|integer|min:0|max:100',
            'kategori.*.urutan' => 'required|integer|min:1',
        ]);

        // Format kategori
        $kategoriData = [];
        $index = 0;
        
        if ($request->kategori) {
            foreach ($request->kategori as $kat) {
                // Skip kategori yang dihapus (ditandai dengan disabled)
                if (isset($kat['deleted']) && $kat['deleted'] == '1') {
                    continue;
                }
                
                $kategoriData[] = [
                    'id' => $kat['id'] ?? ($index + 1),
                    'nama' => $kat['nama'],
                    'waktu_start' => $kat['waktu_start'],
                    'waktu_end' => $kat['waktu_end'],
                    'nilai' => $kat['nilai'],
                    'warna' => $kat['warna'] ?? '#10B981',
                    'urutan' => $kat['urutan'],
                    'is_active' => isset($kat['is_active']) ? true : false,
                ];
                $index++;
            }
        }

        // Update database - Hanya update deskripsi dan is_active
        // Nama pengaturan tetap tidak diubah
        $pengaturan->update([
            'deskripsi' => $request->deskripsi,
            'kategori_waktu' => $kategoriData,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                       ->with('success', 'Pengaturan waktu tidur berhasil diperbarui!');
    } catch (\Exception $e) {
        Log::error('Error update pengaturan:', ['error' => $e->getMessage()]);
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}
    public function destroy()
    {
        $pengaturan = PengaturanWaktuTidurCepat::first();

        if (!$pengaturan) {
            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('error', 'Pengaturan tidak ditemukan.');
        }

        try {
            $pengaturan->delete();
            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('success', 'Pengaturan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error delete pengaturan:', ['error' => $e->getMessage()]);
            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('error', 'Gagal menghapus pengaturan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            $pengaturan = PengaturanWaktuTidurCepat::first();
            
            if (!$pengaturan) {
                $pengaturan = new PengaturanWaktuTidurCepat();
                $pengaturan->save();
            }

            $pengaturan->resetToDefault();

            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('success', 'Pengaturan berhasil direset ke default!');
        } catch (\Exception $e) {
            Log::error('Error reset pengaturan:', ['error' => $e->getMessage()]);
            return redirect()->route('pengaturan-waktu-tidur-cepat.index')
                           ->with('error', 'Gagal mereset pengaturan: ' . $e->getMessage());
        }
    }
    
    // Helper method untuk mendapatkan kategori dari JSON
    private function getKategoriArray($pengaturan)
    {
        if (!$pengaturan || !isset($pengaturan->kategori_waktu)) {
            return [];
        }
        
        // Jika kategori_waktu adalah JSON string, decode
        if (is_string($pengaturan->kategori_waktu)) {
            return json_decode($pengaturan->kategori_waktu, true) ?? [];
        }
        
        // Jika sudah array, return langsung
        return $pengaturan->kategori_waktu ?? [];
    }
}