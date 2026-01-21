<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiswaDashboardController extends Controller
{
    /**
     * Tampilkan dashboard siswa
     */
    public function index()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        // Ganti dari 'siswa.dashboard' menjadi 'dashboard.siswa'
        return view('dashboard.siswa');
    }

    /**
     * Tampilkan profil siswa
     */
    public function profile()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        // Jika ada view profile khusus siswa
        return view('siswa.profile'); // atau sesuaikan dengan struktur view Anda
    }

    /**
     * Tampilkan jadwal siswa
     */
    public function jadwal()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        return view('siswa.jadwal');
    }

    /**
     * Tampilkan nilai siswa
     */
    public function nilai()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        return view('siswa.nilai');
    }

    /**
     * Tampilkan absensi siswa
     */
    public function absensi()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        return view('siswa.absensi');
    }

    /**
     * Logout siswa
     */
    public function logout(Request $request)
    {
        // Hapus semua session siswa
        Session::forget(['siswa_id', 'siswa_nis', 'siswa_nama']);
        
        return redirect()->route('siswa.login')
            ->with('success', 'Anda telah berhasil logout');
    }
}