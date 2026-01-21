<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasSayaController extends Controller
{
    /**
     * Menampilkan halaman utama kelas saya
     */
    public function index()
    {
        try {
            // Ambil user yang sedang login
            $user = Auth::user();
            
            // Debug: cek data user
            \Log::info('User yang login:', ['user_id' => $user->id, 'nama' => $user->nama_lengkap]);
            
            // Cari kelas dimana guru_wali_id sama dengan ID user yang login
            $kelas = Kelas::with(['siswas' => function($query) {
                $query->orderBy('nama_lengkap');
            }])
            ->with('guruWali')
            ->where('guru_wali_id', $user->id)
            ->first();

            // Debug: cek data kelas
            \Log::info('Data kelas ditemukan:', ['kelas' => $kelas ? $kelas->toArray() : 'Tidak ada kelas']);

            // ✅ PERBAIKAN: Jika tidak ada kelas, return view dengan pesan
            if (!$kelas) {
                return view('kelas-saya.index', compact('kelas'))
                    ->with('info', 'Anda belum memiliki kelas. Silakan hubungi administrator untuk penugasan kelas.');
            }

            return view('kelas-saya.index', compact('kelas'));

        } catch (\Exception $e) {
            \Log::error('Error di KelasSayaController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ... method lainnya tetap sama ...

    /**
     * Dashboard cepat untuk guru wali
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            $kelas = Kelas::withCount('siswas')
                ->where('guru_wali_id', $user->id)
                ->first();

            $recentActivities = []; // Bisa diisi dengan aktivitas terbaru

            return view('kelas-saya.dashboard', compact('kelas', 'recentActivities'));

        } catch (\Exception $e) {
            \Log::error('Error di KelasSayaController@dashboard: ' . $e->getMessage());
            // ✅ PERBAIKAN: Redirect ke dashboard.guruwali jika error
            return redirect()->route('dashboard.guruwali')->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
}