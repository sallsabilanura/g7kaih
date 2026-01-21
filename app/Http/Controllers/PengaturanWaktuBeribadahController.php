<?php

namespace App\Http\Controllers;

use App\Models\PengaturanWaktuBeribadah;
use Illuminate\Http\Request;

class PengaturanWaktuBeribadahController extends Controller
{
    protected $sholatLabels = [
        'subuh' => 'Subuh',
        'dzuhur' => 'Dzuhur',
        'ashar' => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya' => 'Isya'
    ];

    protected $sholatColors = [
        'subuh' => ['bg' => 'from-blue-400 to-indigo-500', 'border' => 'border-blue-300', 'light' => 'bg-blue-50'],
        'dzuhur' => ['bg' => 'from-yellow-400 to-orange-500', 'border' => 'border-yellow-300', 'light' => 'bg-yellow-50'],
        'ashar' => ['bg' => 'from-orange-400 to-red-500', 'border' => 'border-orange-300', 'light' => 'bg-orange-50'],
        'maghrib' => ['bg' => 'from-purple-400 to-pink-500', 'border' => 'border-purple-300', 'light' => 'bg-purple-50'],
        'isya' => ['bg' => 'from-gray-600 to-gray-800', 'border' => 'border-gray-400', 'light' => 'bg-gray-100'],
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pengaturan waktu beribadah
        $pengaturanList = PengaturanWaktuBeribadah::orderByRaw(
            "FIELD(jenis_sholat, 'subuh', 'dzuhur', 'ashar', 'maghrib', 'isya')"
        )->get();

        return view('pengaturan-waktu-beribadah.index', [
            'pengaturanList' => $pengaturanList,
            'sholatLabels' => $this->sholatLabels,
            'sholatColors' => $this->sholatColors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar sholat yang sudah ada pengaturan
        $existingSholat = PengaturanWaktuBeribadah::pluck('jenis_sholat')->toArray();
        
        // Sholat yang tersedia untuk ditambahkan
        $availableSholat = array_diff(array_keys($this->sholatLabels), $existingSholat);

        // Jika semua sholat sudah ada pengaturan, redirect ke index
        if (empty($availableSholat)) {
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('info', 'Semua sholat sudah memiliki pengaturan.');
        }

        return view('pengaturan-waktu-beribadah.create', [
            'sholatLabels' => $this->sholatLabels,
            'sholatColors' => $this->sholatColors,
            'availableSholat' => $availableSholat,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sholat' => 'required|string|in:subuh,dzuhur,ashar,maghrib,isya',
            'waktu_tepat_start' => 'required|date_format:H:i',
            'waktu_tepat_end' => 'required|date_format:H:i',
            'nilai_tepat' => 'required|integer|min:0|max:100',
            'waktu_terlambat_start' => 'required|date_format:H:i',
            'waktu_terlambat_end' => 'required|date_format:H:i',
            'nilai_terlambat' => 'required|integer|min:0|max:100',
            'nilai_tidak_sholat' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            // Cek apakah sudah ada pengaturan untuk sholat ini
            $existing = PengaturanWaktuBeribadah::where('jenis_sholat', $validated['jenis_sholat'])->first();
            
            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Pengaturan untuk sholat ' . $this->sholatLabels[$validated['jenis_sholat']] . ' sudah ada!')
                    ->withInput();
            }

            // Buat pengaturan baru
            PengaturanWaktuBeribadah::create([
                'jenis_sholat' => $validated['jenis_sholat'],
                'waktu_tepat_start' => $validated['waktu_tepat_start'] . ':00',
                'waktu_tepat_end' => $validated['waktu_tepat_end'] . ':00',
                'nilai_tepat' => $validated['nilai_tepat'],
                'waktu_terlambat_start' => $validated['waktu_terlambat_start'] . ':00',
                'waktu_terlambat_end' => $validated['waktu_terlambat_end'] . ':00',
                'nilai_terlambat' => $validated['nilai_terlambat'],
                'nilai_tidak_sholat' => $validated['nilai_tidak_sholat'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', 'Pengaturan waktu beribadah berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($jenisSholat)
    {
        $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)->firstOrFail();
        
        return view('pengaturan-waktu-beribadah.edit', [
            'pengaturan' => $pengaturan,
            'sholatLabels' => $this->sholatLabels,
            'sholatColors' => $this->sholatColors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $jenisSholat)
    {
        $validated = $request->validate([
            'waktu_tepat_start' => 'required|date_format:H:i',
            'waktu_tepat_end' => 'required|date_format:H:i',
            'nilai_tepat' => 'required|integer|min:0|max:100',
            'waktu_terlambat_start' => 'required|date_format:H:i',
            'waktu_terlambat_end' => 'required|date_format:H:i',
            'nilai_terlambat' => 'required|integer|min:0|max:100',
            'nilai_tidak_sholat' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)->firstOrFail();
            
            $pengaturan->update([
                'waktu_tepat_start' => $validated['waktu_tepat_start'] . ':00',
                'waktu_tepat_end' => $validated['waktu_tepat_end'] . ':00',
                'nilai_tepat' => $validated['nilai_tepat'],
                'waktu_terlambat_start' => $validated['waktu_terlambat_start'] . ':00',
                'waktu_terlambat_end' => $validated['waktu_terlambat_end'] . ':00',
                'nilai_terlambat' => $validated['nilai_terlambat'],
                'nilai_tidak_sholat' => $validated['nilai_tidak_sholat'],
                'is_active' => $validated['is_active'] ?? $pengaturan->is_active,
            ]);

            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', 'Pengaturan waktu beribadah berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($jenisSholat)
    {
        try {
            $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)->firstOrFail();
            $pengaturan->delete();

            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', 'Pengaturan waktu beribadah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('error', 'Gagal menghapus pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Reset pengaturan ke default.
     */
    public function reset($jenisSholat)
    {
        try {
            $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)->firstOrFail();
            
            $defaults = PengaturanWaktuBeribadah::getDefaultSettings();
            
            if (isset($defaults[$jenisSholat])) {
                $pengaturan->update($defaults[$jenisSholat]);
            }

            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', 'Pengaturan waktu beribadah berhasil direset ke default!');
        } catch (\Exception $e) {
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('error', 'Gagal mereset pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Reset semua pengaturan ke default.
     */
    public function resetAll()
    {
        try {
            $defaults = PengaturanWaktuBeribadah::getDefaultSettings();
            
            foreach ($defaults as $jenis => $setting) {
                $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenis)->first();
                
                if ($pengaturan) {
                    $pengaturan->update($setting);
                } else {
                    // Jika belum ada, buat baru
                    PengaturanWaktuBeribadah::create(array_merge(['jenis_sholat' => $jenis], $setting));
                }
            }

            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', 'Semua pengaturan waktu beribadah berhasil direset ke default!');
        } catch (\Exception $e) {
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('error', 'Gagal mereset semua pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Aktifkan atau nonaktifkan pengaturan
     */
    public function toggleStatus($jenisSholat)
    {
        try {
            $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)->firstOrFail();
            
            $pengaturan->update([
                'is_active' => !$pengaturan->is_active
            ]);

            $status = $pengaturan->is_active ? 'diaktifkan' : 'dinonaktifkan';
            
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('success', "Pengaturan {$this->sholatLabels[$jenisSholat]} berhasil $status!");
        } catch (\Exception $e) {
            return redirect()->route('pengaturan-waktu-beribadah.index')
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}