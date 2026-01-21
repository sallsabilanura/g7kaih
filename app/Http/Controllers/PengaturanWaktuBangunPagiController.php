<?php

namespace App\Http\Controllers;

use App\Models\PengaturanWaktuBangunPagi;
use Illuminate\Http\Request;

class PengaturanWaktuBangunPagiController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanWaktuBangunPagi::first();
        $sudahAda = !is_null($pengaturan);
        
        return view('pengaturan-waktu-bangun-pagi.index', compact('pengaturan', 'sudahAda'));
    }

    public function create()
    {
        $pengaturan = PengaturanWaktuBangunPagi::first();
        if ($pengaturan) {
            return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                ->with('info', 'Pengaturan sudah ada. Anda dapat mengeditnya.');
        }
        
        return view('pengaturan-waktu-bangun-pagi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'waktu_100_start' => 'required',
            'waktu_100_end' => 'required',
            'waktu_70_start' => 'required',
            'waktu_70_end' => 'required',
            'nilai_100' => 'required|integer|min:0|max:100',
            'nilai_70' => 'required|integer|min:0|max:100',
            'nilai_terlambat' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        // Custom validation untuk waktu
        $errors = [];
        
        // Cek format waktu
        $timeFields = ['waktu_100_start', 'waktu_100_end', 'waktu_70_start', 'waktu_70_end'];
        foreach ($timeFields as $field) {
            if (!empty($validated[$field])) {
                // Cek apakah format valid (H:i atau H:i:s)
                if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $validated[$field])) {
                    $errors[$field] = 'Format waktu tidak valid. Gunakan format HH:MM';
                }
            }
        }
        
        // Cek waktu 100_start < waktu_100_end
        if (strtotime($validated['waktu_100_start']) >= strtotime($validated['waktu_100_end'])) {
            $errors['waktu_100_end'] = 'Waktu selesai harus setelah waktu mulai (Kategori 1)';
        }
        
        // Cek waktu 70_start < waktu_70_end
        if (strtotime($validated['waktu_70_start']) >= strtotime($validated['waktu_70_end'])) {
            $errors['waktu_70_end'] = 'Waktu selesai harus setelah waktu mulai (Kategori 2)';
        }
        
        // Cek gap antara kategori
        if (strtotime($validated['waktu_100_end']) > strtotime($validated['waktu_70_start'])) {
            $errors['waktu_70_start'] = 'Waktu mulai Kategori 2 harus setelah atau sama dengan waktu akhir Kategori 1';
        }
        
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        try {
            // Cek apakah sudah ada pengaturan
            $existing = PengaturanWaktuBangunPagi::first();
            if ($existing) {
                return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                    ->with('error', 'Pengaturan sudah ada. Gunakan menu edit untuk mengubah.');
            }

            // Format waktu ke H:i:s
            $validated['waktu_100_start'] = $this->formatTimeForDb($validated['waktu_100_start']);
            $validated['waktu_100_end'] = $this->formatTimeForDb($validated['waktu_100_end']);
            $validated['waktu_70_start'] = $this->formatTimeForDb($validated['waktu_70_start']);
            $validated['waktu_70_end'] = $this->formatTimeForDb($validated['waktu_70_end']);

            // Create new setting
            $pengaturan = PengaturanWaktuBangunPagi::create($validated);

            return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                ->with('success', 'Pengaturan waktu berhasil dibuat!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        $pengaturan = PengaturanWaktuBangunPagi::first();
        
        if (!$pengaturan) {
            return redirect()->route('pengaturan-waktu-bangun-pagi.create')
                ->with('info', 'Belum ada pengaturan. Silakan buat pengaturan baru.');
        }
        
        return view('pengaturan-waktu-bangun-pagi.edit', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $pengaturan = PengaturanWaktuBangunPagi::first();
        
        if (!$pengaturan) {
            return redirect()->route('pengaturan-waktu-bangun-pagi.create')
                ->with('error', 'Belum ada pengaturan. Silakan buat pengaturan baru.');
        }

        $validated = $request->validate([
            'waktu_100_start' => 'required',
            'waktu_100_end' => 'required',
            'waktu_70_start' => 'required',
            'waktu_70_end' => 'required',
            'nilai_100' => 'required|integer|min:0|max:100',
            'nilai_70' => 'required|integer|min:0|max:100',
            'nilai_terlambat' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        // Custom validation untuk waktu
        $errors = [];
        
        // Cek format waktu
        $timeFields = ['waktu_100_start', 'waktu_100_end', 'waktu_70_start', 'waktu_70_end'];
        foreach ($timeFields as $field) {
            if (!empty($validated[$field])) {
                // Cek apakah format valid (H:i atau H:i:s)
                if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $validated[$field])) {
                    $errors[$field] = 'Format waktu tidak valid. Gunakan format HH:MM';
                }
            }
        }
        
        // Cek waktu 100_start < waktu_100_end
        if (strtotime($validated['waktu_100_start']) >= strtotime($validated['waktu_100_end'])) {
            $errors['waktu_100_end'] = 'Waktu selesai harus setelah waktu mulai (Kategori 1)';
        }
        
        // Cek waktu 70_start < waktu_70_end
        if (strtotime($validated['waktu_70_start']) >= strtotime($validated['waktu_70_end'])) {
            $errors['waktu_70_end'] = 'Waktu selesai harus setelah waktu mulai (Kategori 2)';
        }
        
        // Cek gap antara kategori
        if (strtotime($validated['waktu_100_end']) > strtotime($validated['waktu_70_start'])) {
            $errors['waktu_70_start'] = 'Waktu mulai Kategori 2 harus setelah atau sama dengan waktu akhir Kategori 1';
        }
        
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        try {
            // Format waktu ke H:i:s
            $validated['waktu_100_start'] = $this->formatTimeForDb($validated['waktu_100_start']);
            $validated['waktu_100_end'] = $this->formatTimeForDb($validated['waktu_100_end']);
            $validated['waktu_70_start'] = $this->formatTimeForDb($validated['waktu_70_start']);
            $validated['waktu_70_end'] = $this->formatTimeForDb($validated['waktu_70_end']);

            $pengaturan->update($validated);

            return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                ->with('success', 'Pengaturan waktu berhasil diperbarui!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy()
    {
        try {
            $pengaturan = PengaturanWaktuBangunPagi::first();
            
            if (!$pengaturan) {
                return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                    ->with('error', 'Tidak ada pengaturan untuk dihapus.');
            }
            
            $pengaturan->delete();

            return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                ->with('success', 'Pengaturan waktu berhasil dihapus!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reset()
    {
        try {
            $pengaturan = PengaturanWaktuBangunPagi::first();
            
            if (!$pengaturan) {
                return redirect()->route('pengaturan-waktu-bangun-pagi.create')
                    ->with('error', 'Belum ada pengaturan. Silakan buat pengaturan baru.');
            }
            
            // Reset ke nilai default
            $pengaturan->update([
                'waktu_100_start' => '04:00:00',
                'waktu_100_end' => '05:00:00',
                'nilai_100' => 100,
                'waktu_70_start' => '05:00:00',
                'waktu_70_end' => '06:00:00',
                'nilai_70' => 70,
                'nilai_terlambat' => 50,
                'is_active' => true
            ]);

            return redirect()->route('pengaturan-waktu-bangun-pagi.index')
                ->with('success', 'Pengaturan berhasil direset ke default!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk format waktu ke database
     */
    private function formatTimeForDb($time)
    {
        if (empty($time)) {
            return '00:00:00';
        }
        
        // Jika sudah format H:i:s, return langsung
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
            return $time;
        }
        
        // Jika format H:i, tambahkan :00
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time . ':00';
        }
        
        // Coba parse string time
        $timestamp = strtotime($time);
        if ($timestamp !== false) {
            return date('H:i:s', $timestamp);
        }
        
        return $time;
    }
}