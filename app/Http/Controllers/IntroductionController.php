<?php

namespace App\Http\Controllers;

use App\Models\Introduction;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IntroductionController extends Controller
{
    public function index()
    {
        // Cek apakah user adalah siswa yang login
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
            $siswa = Siswa::find($siswaId);
            
            // Jika siswa sudah memiliki introduction, redirect ke show
            if ($siswa && $siswa->introduction) {
                return redirect()->route('introductions.show', $siswa->introduction->id);
            }
            
            // Jika belum punya introduction, redirect ke create
            return redirect()->route('introductions.create');
        } else {
            // Untuk admin/guru, tampilkan semua
            $introductions = Introduction::with('siswa')->get();
            return view('introductions.index', compact('introductions'));
        }
    }

   
    public function create()
    {
        // Cek apakah siswa sudah login
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        // Ambil siswa yang sedang login dari session
        $siswaId = Session::get('siswa_id');
        $siswa = Siswa::find($siswaId);

        if (!$siswa) {
            Session::flush();
            return redirect()->route('siswa.login')
                ->with('error', 'Data siswa tidak ditemukan. Silakan login kembali!');
        }
        
        // Cek apakah siswa sudah memiliki introduction
        if ($siswa->introduction) {
            return redirect()->route('introductions.show', $siswa->introduction->id)
                ->with('info', 'Anda sudah mengisi data pengenalan diri. Klik tombol Edit jika ingin mengubah data.');
        }
        
        $hasIntroduction = false;
        
        return view('introductions.create', compact('siswa', 'hasIntroduction'));
    }

    public function store(Request $request)
    {
        // Cek apakah siswa sudah login
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        // Ambil siswa yang sedang login
        $siswaId = Session::get('siswa_id');
        $siswa = Siswa::find($siswaId);

        if (!$siswa) {
            Session::flush();
            return redirect()->route('siswa.login')
                ->with('error', 'Data siswa tidak ditemukan. Silakan login kembali!');
        }
        
        // Validasi bahwa siswa belum memiliki introduction
        if ($siswa->introduction) {
            return redirect()->route('introductions.show', $siswa->introduction->id)
                ->with('warning', 'Anda sudah memiliki data introduction!');
        }
        
        $validated = $request->validate([
            'hobi' => 'required|string|max:255',
            'cita_cita' => 'required|string|max:100',
            'olahraga_kesukaan' => 'required|string|max:100',
            'makanan_kesukaan' => 'required|string|max:100',
            'buah_kesukaan' => 'required|string|max:100',
            'pelajaran_kesukaan' => 'required|string|max:100',
            'warna_kesukaan' => 'required|array|size:3',
            'warna_kesukaan.*' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'warna_kesukaan.size' => 'Harus memilih tepat 3 warna',
        ]);

        // Tambahkan siswa_id dari siswa yang login
        $validated['siswa_id'] = $siswa->id;

        // Simpan data
        $introduction = Introduction::create($validated);

        // Redirect ke halaman show setelah berhasil create
        return redirect()->route('introductions.show', $introduction->id)
            ->with('success', 'Data pengenalan diri berhasil disimpan!');
    }

    public function show(Introduction $introduction)
    {
        // Cek akses siswa
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
            if ($introduction->siswa_id !== $siswaId) {
                return redirect()->route('introductions.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat data ini!');
            }
        }

        $introduction->load('siswa');
        return view('introductions.show', compact('introduction'));
    }

    public function edit(Introduction $introduction)
    {
        // Cek apakah siswa sudah login
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        // Cek apakah introduction milik siswa yang login
        $siswaId = Session::get('siswa_id');
        
        if ($introduction->siswa_id !== $siswaId) {
            return redirect()->route('introductions.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini!');
        }
        
        $introduction->load('siswa');
        $siswa = $introduction->siswa;
        $hasIntroduction = true;
        
        return view('introductions.create', compact('introduction', 'siswa', 'hasIntroduction'));
    }

    public function update(Request $request, Introduction $introduction)
    {
        // Cek apakah siswa sudah login
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        // Cek apakah introduction milik siswa yang login
        $siswaId = Session::get('siswa_id');
        
        if ($introduction->siswa_id !== $siswaId) {
            return redirect()->route('introductions.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate data ini!');
        }
        
        $validated = $request->validate([
            'hobi' => 'required|string|max:255',
            'cita_cita' => 'required|string|max:100',
            'olahraga_kesukaan' => 'required|string|max:100',
            'makanan_kesukaan' => 'required|string|max:100',
            'buah_kesukaan' => 'required|string|max:100',
            'pelajaran_kesukaan' => 'required|string|max:100',
            'warna_kesukaan' => 'required|array|size:3',
            'warna_kesukaan.*' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $introduction->update($validated);

        // Redirect ke halaman show setelah berhasil update
        return redirect()->route('introductions.show', $introduction->id)
            ->with('success', 'Data pengenalan diri berhasil diperbarui!');
    }

    public function destroy(Introduction $introduction)
    {
        // Cek apakah siswa sudah login
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        // Cek apakah introduction milik siswa yang login
        $siswaId = Session::get('siswa_id');
        
        if ($introduction->siswa_id !== $siswaId) {
            return redirect()->route('introductions.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini!');
        }
        
        $introduction->delete();

        return redirect()->route('introductions.create')
            ->with('success', 'Data pengenalan diri berhasil dihapus!');
    }
}