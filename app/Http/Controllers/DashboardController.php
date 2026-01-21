<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\User;
use App\Models\Kelas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Admin Dashboard - FIXED with zero division handling
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        
        // Pastikan hanya admin yang bisa akses
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        // ========== STATISTIK UTAMA ==========
        $totalUsers = User::count();
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalGuruWali = User::guruWali()->count();
        $totalAdmin = User::where('peran', 'admin')->count();
        $totalOrangtua = Orangtua::count();
        
        // ========== STATISTIK BULAN INI ==========
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $siswaBulanIni = Siswa::whereYear('created_at', $tahunIni)
            ->whereMonth('created_at', $bulanIni)
            ->count();
        
        $userBulanIni = User::whereYear('created_at', $tahunIni)
            ->whereMonth('created_at', $bulanIni)
            ->count();
        
        // ========== DISTRIBUSI GENDER ==========
        $siswaLaki = Siswa::where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = Siswa::where('jenis_kelamin', 'P')->count();
        $guruLaki = User::guruWali()->where('jenis_kelamin', 'L')->count();
        $guruPerempuan = User::guruWali()->where('jenis_kelamin', 'P')->count();
        
        // ========== STATUS ==========
        $userAktif = User::where('is_active', 1)->count();
        $userNonaktif = $totalUsers - $userAktif; // Hitung langsung
        $kelasDenganWali = Kelas::whereNotNull('guru_wali_id')->count();
        $kelasTanpaWali = Kelas::whereNull('guru_wali_id')->count();
        
        // ========== STATISTIK TAMBAHAN ==========
        $orangtuaDenganSiswa = Orangtua::whereHas('siswa')->count();
        $orangtuaTanpaSiswa = Orangtua::whereDoesntHave('siswa')->count();
        $guruTanpaKelas = User::guruWali()
            ->whereDoesntHave('kelasAsGuruWali')
            ->count();
        
        // ========== PERHITUNGAN DENGAN HANDLING ZERO DIVISION ==========
        // 1. Rata-rata siswa per kelas
        $rataSiswaPerKelas = ($totalKelas > 0 && $totalSiswa > 0) 
            ? round($totalSiswa / $totalKelas, 1) 
            : 0;
        
        // 2. 5 kelas teratas dengan siswa terbanyak
        $kelasTop5 = Kelas::withCount('siswas')
            ->with('guruWali')
            ->orderByDesc('siswas_count')
            ->limit(5)
            ->get();
        
        // Data untuk chart distribusi siswa per kelas
        $kelasChartLabels = $kelasTop5->pluck('nama_kelas')->toArray();
        $kelasChartData = $kelasTop5->pluck('siswas_count')->toArray();
        
        // ========== TREND DATA 6 BULAN TERAKHIR ==========
        $bulanLabels = [];
        $trendSiswa = [];
        $trendUser = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulanLabels[] = $date->translatedFormat('M');
            
            $trendSiswa[] = Siswa::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $trendUser[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }
        
        // ========== PERSENTASE DENGAN HANDLING ZERO DIVISION ==========
        // 1. Persentase gender siswa
        $persentaseSiswaLaki = ($totalSiswa > 0) 
            ? round(($siswaLaki / $totalSiswa) * 100, 1) 
            : 0;
        
        $persentaseSiswaPerempuan = ($totalSiswa > 0) 
            ? round(($siswaPerempuan / $totalSiswa) * 100, 1) 
            : 0;
        
        // 2. Persentase gender guru
        $persentaseGuruLaki = ($totalGuruWali > 0) 
            ? round(($guruLaki / $totalGuruWali) * 100, 1) 
            : 0;
        
        $persentaseGuruPerempuan = ($totalGuruWali > 0) 
            ? round(($guruPerempuan / $totalGuruWali) * 100, 1) 
            : 0;
        
        // ========== SUMMARY DATA ==========
        $summaryData = [
            'users' => [
                'total' => $totalUsers,
                'active' => $userAktif,
                'inactive' => $userNonaktif,
                'percentage' => ($totalUsers > 0) 
                    ? round(($userAktif / $totalUsers) * 100) 
                    : 0
            ],
            'classes' => [
                'total' => $totalKelas,
                'with_wali' => $kelasDenganWali,
                'without_wali' => $kelasTanpaWali,
                'percentage' => ($totalKelas > 0) 
                    ? round(($kelasDenganWali / $totalKelas) * 100) 
                    : 0
            ],
            'parents' => [
                'total' => $totalOrangtua,
                'with_student' => $orangtuaDenganSiswa,
                'without_student' => $orangtuaTanpaSiswa,
                'percentage' => ($totalSiswa > 0) 
                    ? min(round(($totalOrangtua / $totalSiswa) * 100), 100) 
                    : 0
            ],
            'teachers' => [
                'total' => $totalGuruWali,
                'with_class' => $totalGuruWali - $guruTanpaKelas,
                'without_class' => $guruTanpaKelas,
                'percentage' => ($totalGuruWali > 0) 
                    ? round((($totalGuruWali - $guruTanpaKelas) / $totalGuruWali) * 100) 
                    : 0
            ]
        ];
        
        // ========== PERSENTASE UNTUK KELAS TOP 5 ==========
        // Hitung persentase untuk setiap kelas di top 5
        $kelasWithPercentage = $kelasTop5->map(function($kelas) use ($totalSiswa) {
            $percentage = ($totalSiswa > 0 && $kelas->siswas_count > 0)
                ? round(($kelas->siswas_count / $totalSiswa) * 100, 1)
                : 0;
            
            return [
                'kelas' => $kelas,
                'percentage' => $percentage
            ];
        });
        
        return view('dashboard.admin', compact(
            'user',
            // Statistik utama
            'totalUsers',
            'totalSiswa',
            'totalKelas',
            'totalGuruWali',
            'totalAdmin',
            'totalOrangtua',
            
            // Statistik real-time
            'siswaBulanIni',
            'userBulanIni',
            
            // Distribusi gender
            'siswaLaki',
            'siswaPerempuan',
            'guruLaki',
            'guruPerempuan',
            
            // Status
            'userAktif',
            'userNonaktif',
            'kelasDenganWali',
            'kelasTanpaWali',
            
            // Statistik tambahan
            'orangtuaDenganSiswa',
            'orangtuaTanpaSiswa',
            'guruTanpaKelas',
            'rataSiswaPerKelas',
            
            // Data untuk charts
            'kelasTop5',
            'kelasWithPercentage', // Tambahkan ini
            'kelasChartLabels',
            'kelasChartData',
            
            // Trend data
            'bulanLabels',
            'trendSiswa',
            'trendUser',
            
            // Persentase
            'persentaseSiswaLaki',
            'persentaseSiswaPerempuan',
            'persentaseGuruLaki',
            'persentaseGuruPerempuan',
            
            // Summary data
            'summaryData'
        ));
    }
    
    /**
     * Main dashboard - redirect based on role
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        switch ($user->peran) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'guru_wali':
                return redirect()->route('dashboard.guruwali');
            case 'orangtua':
                return redirect()->route('dashboard.orangtua.admin');
            case 'siswa':
                return redirect()->route('dashboard.siswa.auth');
            default:
                return redirect()->route('dashboard.admin');
        }
    }

    /**
     * Guru Wali Dashboard
     */
    public function guruWaliDashboard()
    {
        $user = Auth::user();
        
        // Pastikan hanya guru wali yang bisa akses
        if (!$user->isGuruWali()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Ambil kelas yang diampu
        $kelas = $user->kelasWali;
        
        return view('dashboard.guruwali', compact('user', 'kelas'));
    }

    /**
     * Orangtua Dashboard - untuk orangtua yang login sebagai user
     */
    public function orangtuaDashboard()
    {
        $user = Auth::user();
        
        // Pastikan hanya orangtua yang bisa akses
        if ($user->peran !== 'orangtua') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('dashboard.orangtua', compact('user'));
    }

    /**
     * Orangtua Admin Dashboard - untuk orangtua yang login sebagai user (alias untuk orangtuaDashboard)
     */
    public function orangtuaAdminDashboard()
    {
        return $this->orangtuaDashboard();
    }

    /**
     * Siswa Auth Dashboard - untuk siswa yang login sebagai user
     */
    public function siswaAuthDashboard()
    {
        $user = Auth::user();
        
        // Pastikan hanya siswa yang bisa akses
        if (!$user->isSiswa()) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('dashboard.siswa-auth', compact('user'));
    }

    /**
     * Siswa Dashboard - untuk login siswa (session based)
     */
    public function siswaDashboard()
    {
        if (!session()->has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $siswa = Siswa::with(['kelas', 'kelas.guruWali'])->find(session()->get('siswa_id'));

        if (!$siswa) {
            session()->forget(['siswa_id', 'siswa_nis', 'siswa_nama']);
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Data siswa tidak ditemukan']);
        }

        return view('dashboard.siswa', compact('siswa'));
    }

    /**
     * Orangtua Dashboard - untuk login orangtua (session based)
     */
    public function orangtuaSessionDashboard()
    {
        if (!session()->has('orangtua_id')) {
            return redirect()->route('orangtua.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $orangtua = Orangtua::with(['siswa.kelas', 'siswa.kelas.guruWali'])
            ->find(session()->get('orangtua_id'));

        if (!$orangtua) {
            session()->flush();
            return redirect()->route('orangtua.login')
                ->withErrors(['error' => 'Data orangtua tidak ditemukan']);
        }

        return view('dashboard.orangtua-session', compact('orangtua'));
    }
}