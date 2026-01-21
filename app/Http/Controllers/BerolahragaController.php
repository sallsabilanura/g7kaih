<?php

namespace App\Http\Controllers;

use App\Models\Berolahraga;
use App\Models\Siswa;
use App\Models\PengaturanWaktuOlahraga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BerolahragaController extends Controller
{
    /**
     * Pastikan pengaturan olahraga tersedia
     * Jika tidak ada, buat otomatis
     */
    private function ensurePengaturanExists()
    {
        $pengaturan = PengaturanWaktuOlahraga::where('is_active', true)->first();
        
        if (!$pengaturan) {
            // Buat pengaturan default otomatis
            $pengaturan = PengaturanWaktuOlahraga::create([
                'waktu_sangat_baik_start' => '05:00:00',
                'waktu_sangat_baik_end' => '06:00:00',
                'nilai_sangat_baik' => 100,
                
                'waktu_baik_start' => '06:01:00',
                'waktu_baik_end' => '07:00:00',
                'nilai_baik' => 85,
                
                'waktu_cukup_start' => '07:01:00',
                'waktu_cukup_end' => '08:00:00',
                'nilai_cukup' => 70,
                
                'nilai_kurang' => 50,
                
                'durasi_minimal' => 30,
                'durasi_maksimal' => 120,
                
                'is_active' => true,
                'keterangan' => 'Pengaturan default sistem - dibuat otomatis'
            ]);
        }
        
        return $pengaturan;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Pastikan pengaturan ada
        $this->ensurePengaturanExists();

        $search = $request->get('search');
        $selectedBulan = $request->get('bulan', date('Y-m'));
        
        // Tentukan siapa yang login
        $userType = null;
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            // Login sebagai siswa
            $userType = 'siswa';
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            // Login sebagai orangtua
            $userType = 'orangtua';
            $siswaId = Session::get('siswa_anak_id'); // Ambil siswa_id dari anak orangtua
        }

        $query = Berolahraga::with(['siswa', 'siswa.kelas'])
            ->whereYear('tanggal', substr($selectedBulan, 0, 4))
            ->whereMonth('tanggal', substr($selectedBulan, 5, 2));

        // Filter berdasarkan login
        if ($userType && $siswaId) {
            // Jika login sebagai siswa atau orangtua, hanya tampilkan data siswa tersebut
            $query->where('siswa_id', $siswaId);
        } else {
            // Jika admin/guru wali, bisa filter berdasarkan search
            if ($search) {
                $query->whereHas('siswa', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%")
                      ->orWhere('nama', 'like', "%{$search}%");
                });
            }
        }

        $berolahragaList = $query->orderBy('tanggal', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);

        // Statistik
        $stats = [
            'total' => Berolahraga::whereYear('tanggal', substr($selectedBulan, 0, 4))
                                  ->whereMonth('tanggal', substr($selectedBulan, 5, 2))
                                  ->when($siswaId, function ($q) use ($siswaId) {
                                      return $q->where('siswa_id', $siswaId);
                                  })
                                  ->count(),
            'rata_rata_nilai' => round(
                Berolahraga::whereYear('tanggal', substr($selectedBulan, 0, 4))
                           ->whereMonth('tanggal', substr($selectedBulan, 5, 2))
                           ->whereNotNull('nilai')
                           ->when($siswaId, function ($q) use ($siswaId) {
                               return $q->where('siswa_id', $siswaId);
                           })
                           ->avg('nilai') ?? 0, 1
            ),
            'sudah_dinilai' => Berolahraga::whereYear('tanggal', substr($selectedBulan, 0, 4))
                                          ->whereMonth('tanggal', substr($selectedBulan, 5, 2))
                                          ->whereNotNull('nilai')
                                          ->when($siswaId, function ($q) use ($siswaId) {
                                              return $q->where('siswa_id', $siswaId);
                                          })
                                          ->count(),
        ];

        // Ambil pengaturan aktif
        $pengaturan = PengaturanWaktuOlahraga::where('is_active', true)->first();
        
        // Ambil data siswa untuk header jika login sebagai siswa/orangtua
        $currentSiswa = null;
        if ($siswaId) {
            $currentSiswa = Siswa::find($siswaId);
        }

        return view('berolahraga.index', compact(
            'berolahragaList',
            'search',
            'selectedBulan',
            'stats',
            'pengaturan',
            'currentSiswa',
            'userType'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Pastikan pengaturan ada, jika tidak buat otomatis
        $pengaturan = $this->ensurePengaturanExists();
        
        // Cek siapa yang login
        $userType = null;
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            // Login sebagai siswa
            $userType = 'siswa';
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            // Login sebagai orangtua
            $userType = 'orangtua';
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, set siswa otomatis
        $currentSiswa = null;
        if ($siswaId) {
            $currentSiswa = Siswa::find($siswaId);
        }
        
        return view('berolahraga.create', compact('pengaturan', 'currentSiswa'));
    }

    /**
     * Store a newly created resource in storage.
     * Siswa hanya input data, NILAI BELUM DIHITUNG
     */
    public function store(Request $request)
    {
        // Pastikan pengaturan ada
        $pengaturan = $this->ensurePengaturanExists();

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'video' => 'required|mimes:mp4,mov,avi,wmv|max:5120', // 5MB
        ], [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'video.required' => 'Video olahraga wajib diupload',
            'video.mimes' => 'Format video harus MP4, MOV, AVI, atau WMV',
            'video.max' => 'Ukuran video maksimal 5MB',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
        ]);

        try {
            // Hitung durasi
            $mulai = Carbon::parse($request->waktu_mulai);
            $selesai = Carbon::parse($request->waktu_selesai);
            $durasiMenit = $selesai->diffInMinutes($mulai);

            // Validasi durasi minimal
            if ($durasiMenit < $pengaturan->durasi_minimal) {
                return redirect()->back()
                    ->with('error', "Durasi olahraga minimal {$pengaturan->durasi_minimal} menit!")
                    ->withInput();
            }

            // Upload video
            $video = $request->file('video');
            $filename = time() . '_' . $video->getClientOriginalName();
            $path = $video->storeAs('videos/berolahraga', $filename, 'public');

            // Tentukan siswa_id berdasarkan login
            $siswaId = null;
            
            if (Session::has('siswa_id')) {
                // Login sebagai siswa
                $siswaId = Session::get('siswa_id');
            } elseif (Session::has('orangtua_id')) {
                // Login sebagai orangtua
                $siswaId = Session::get('siswa_anak_id');
            } else {
                // Admin/guru wali memilih siswa
                $siswaId = $request->siswa_id;
            }

            // Validasi siswa_id harus ada
            if (!$siswaId) {
                return redirect()->back()
                    ->with('error', 'Data siswa tidak ditemukan. Silakan login ulang.')
                    ->withInput();
            }

            // BUAT DATA TANPA NILAI (nilai akan diisi oleh admin manual)
            $berolahraga = Berolahraga::create([
                'siswa_id' => $siswaId,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'video_path' => $path,
                'video_filename' => $video->getClientOriginalName(),
                'video_size' => round($video->getSize() / 1024),
                'durasi_menit' => $durasiMenit,
                // NILAI TIDAK DI-SET, BIARKAN NULL
                'nilai' => null,
                'kategori_nilai' => null,
            ]);

            $message = 'Data olahraga berhasil diupload! Menunggu penilaian dari admin.';

            return redirect()->route('berolahraga.index')->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $berolahraga = Berolahraga::with(['siswa'])->findOrFail($id);
        $pengaturan = $this->ensurePengaturanExists();
        
        // Cek apakah user berhak mengedit data ini
        $userType = null;
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $berolahraga->siswa_id != $siswaId) {
            return redirect()->route('berolahraga.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }
        
        return view('berolahraga.edit', compact('berolahraga', 'pengaturan'));
    }

    /**
     * Update the specified resource in storage.
     * Siswa update data, NILAI TETAP NULL (akan dinilai admin)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:5120', // 5MB
        ]);

        try {
            $berolahraga = Berolahraga::findOrFail($id);
            
            // Cek apakah user berhak mengedit data ini
            $siswaId = null;
            
            if (Session::has('siswa_id')) {
                $siswaId = Session::get('siswa_id');
            } elseif (Session::has('orangtua_id')) {
                $siswaId = Session::get('siswa_anak_id');
            }
            
            // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
            if ($siswaId && $berolahraga->siswa_id != $siswaId) {
                return redirect()->route('berolahraga.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
            }
            
            // Hitung durasi baru
            $mulai = Carbon::parse($request->waktu_mulai);
            $selesai = Carbon::parse($request->waktu_selesai);
            $durasiMenit = $selesai->diffInMinutes($mulai);
            
            // Validasi durasi minimal
            $pengaturan = $this->ensurePengaturanExists();
            if ($durasiMenit < $pengaturan->durasi_minimal) {
                return redirect()->back()
                    ->with('error', "Durasi olahraga minimal {$pengaturan->durasi_minimal} menit!")
                    ->withInput();
            }

            $data = [
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'durasi_menit' => $durasiMenit,
            ];

            // Jika ada video baru
            if ($request->hasFile('video')) {
                if ($berolahraga->video_path) {
                    Storage::disk('public')->delete($berolahraga->video_path);
                }

                $video = $request->file('video');
                $filename = time() . '_' . $video->getClientOriginalName();
                $path = $video->storeAs('videos/berolahraga', $filename, 'public');

                $data['video_path'] = $path;
                $data['video_filename'] = $video->getClientOriginalName();
                $data['video_size'] = round($video->getSize() / 1024);
            }

            // UPDATE - NILAI TETAP NULL (tidak diubah)
            $berolahraga->update($data);

            $message = 'Data olahraga berhasil diperbarui!';

            return redirect()->route('berolahraga.index')->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $berolahraga = Berolahraga::findOrFail($id);
            
            // Cek apakah user berhak menghapus data ini
            $siswaId = null;
            
            if (Session::has('siswa_id')) {
                $siswaId = Session::get('siswa_id');
            } elseif (Session::has('orangtua_id')) {
                $siswaId = Session::get('siswa_anak_id');
            }
            
            // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
            if ($siswaId && $berolahraga->siswa_id != $siswaId) {
                return redirect()->route('berolahraga.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }
            
            if ($berolahraga->video_path) {
                Storage::disk('public')->delete($berolahraga->video_path);
            }

            $berolahraga->delete();

            return redirect()->route('berolahraga.index')
                ->with('success', 'Data olahraga berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download video
     */
    public function downloadVideo($id)
    {
        $berolahraga = Berolahraga::findOrFail($id);
        
        // Cek apakah user berhak mengakses video ini
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $berolahraga->siswa_id != $siswaId) {
            return redirect()->route('berolahraga.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengunduh video ini.');
        }
        
        if (!$berolahraga->video_path) {
            return redirect()->back()->with('error', 'Video tidak ditemukan!');
        }

        return Storage::disk('public')->download($berolahraga->video_path, $berolahraga->video_filename);
    }
}