<?php

namespace App\Http\Controllers;

use App\Models\TidurCepat;
use App\Models\BangunPagi;
use App\Models\PengaturanWaktuTidurCepat;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class TidurCepatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $today = now()->toDateString();
        $selectedTanggal = $request->get('tanggal', $today);
        
        // Deteksi tipe user login
        $isSiswaLogin = Session::has('siswa_id');
        $isOrangtuaLogin = Session::has('orangtua_id');
        
        // Query untuk data siswa
        $querySiswa = Siswa::query();
        
        // Jika user login sebagai siswa
        if ($isSiswaLogin) {
            $siswaId = Session::get('siswa_id');
            $querySiswa->where('id', $siswaId);
            $currentSiswa = Siswa::find($siswaId);
            $siswas = collect([$currentSiswa]);
        } 
        // Jika user login sebagai orangtua
        elseif ($isOrangtuaLogin) {
            $orangtuaId = Session::get('orangtua_id');
            $currentSiswa = Siswa::where('orangtua_id', $orangtuaId)->first();
            
            if ($currentSiswa) {
                $querySiswa->where('id', $currentSiswa->id);
                $siswas = collect([$currentSiswa]);
            } else {
                $siswas = collect();
            }
        }
        // Jika admin/guru wali
        else {
            if ($search) {
                $querySiswa->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            $siswas = $querySiswa->orderBy('nama_lengkap')->get();
        }
        
        // Ambil data tidur cepat untuk tanggal yang dipilih
        $tidurCepatData = TidurCepat::whereDate('tanggal', $selectedTanggal)
            ->when($isSiswaLogin, function($q) use ($siswaId) {
                $q->where('siswa_id', $siswaId);
            })
            ->when($isOrangtuaLogin && isset($currentSiswa), function($q) use ($currentSiswa) {
                $q->where('siswa_id', $currentSiswa->id);
            })
            ->get()
            ->keyBy('siswa_id');
        
        // Hitung statistik untuk hari ini
        $totalCheckedToday = 0;
        $onTimeCount = 0;
        $lateCount = 0;
        
        foreach ($siswas as $siswa) {
            $tidurCepat = $tidurCepatData->get($siswa->id);
            if ($tidurCepat) {
                $totalCheckedToday++;
                if ($tidurCepat->kategori_waktu == 'Sangat Baik' || $tidurCepat->nilai >= 90) {
                    $onTimeCount++;
                } else {
                    $lateCount++;
                }
            }
        }
        
        return view('tidur-cepat.index', compact(
            'siswas',
            'isSiswaLogin',
            'isOrangtuaLogin',
            'selectedTanggal',
            'tidurCepatData',
            'totalCheckedToday',
            'onTimeCount',
            'lateCount',
            'search'
        ));
    }

    /**
     * Checklist tidur cepat (untuk admin/guru)
     */
    public function checklist(Request $request)
    {
        try {
            $request->validate([
                'siswa_id' => 'required|exists:siswas,id',
                'pukul_tidur' => 'required|date_format:H:i',
            ]);

            DB::beginTransaction();

            $siswaId = $request->siswa_id;
            $tanggal = now()->toDateString(); // Selalu tanggal hari ini
            $pukulTidur = $request->pukul_tidur;
            
            // Cek apakah sudah ada data untuk tanggal tersebut
            $existing = TidurCepat::where('siswa_id', $siswaId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($existing) {
                // Update data existing
                $existing->update([
                    'pukul_tidur' => $pukulTidur,
                    'updated_by' => Auth::id(),
                ]);
                
                $tidurCepat = $existing;
            } else {
                // Buat data baru
                $tidurCepat = TidurCepat::create([
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                    'pukul_tidur' => $pukulTidur,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tidur cepat berhasil dicatat',
                'data' => $tidurCepat->load('siswa')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checklist tidur cepat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get data untuk modal edit
     */
    public function getData($id)
    {
        try {
            $tidurCepat = TidurCepat::find($id);
            
            if (!$tidurCepat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tidurCepat->id,
                    'siswa_id' => $tidurCepat->siswa_id,
                    'pukul_tidur' => $tidurCepat->pukul_tidur,
                    'tanggal' => $tidurCepat->tanggal,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get data tidur cepat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update waktu tidur
     */
     public function updateWaktu(Request $request, $id)
    {
        try {
            $request->validate([
                'pukul_tidur' => 'required|date_format:H:i',
            ]);

            DB::beginTransaction();

            $tidurCepat = TidurCepat::find($id);
            
            if (!$tidurCepat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            // Cek apakah data untuk hari ini
            $isToday = Carbon::parse($tidurCepat->tanggal)->isToday();
            
            if (!$isToday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa edit data untuk hari ini'
                ], 400);
            }

            $tidurCepat->update([
                'pukul_tidur' => $request->pukul_tidur,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Waktu tidur berhasil diperbarui',
                'data' => $tidurCepat->load('siswa')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update waktu tidur error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus checklist tidur cepat
     */
      public function hapusChecklist($id)
    {
        try {
            DB::beginTransaction();

            $tidurCepat = TidurCepat::find($id);
            
            if (!$tidurCepat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
            
            // Cek apakah data untuk hari ini
            $isToday = Carbon::parse($tidurCepat->tanggal)->isToday();
            
            if (!$isToday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya bisa menghapus data untuk hari ini'
                ], 400);
            }

            // Hapus data
            $tidurCepat->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checklist berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hapus checklist error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswas = Siswa::orderBy('nama_lengkap')->get();
        return view('tidur-cepat.create', compact('siswas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'pukul_tidur' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $tidurCepat = TidurCepat::create([
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
                'pukul_tidur' => $request->pukul_tidur,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('tidur-cepat.index')
                ->with('success', 'Data tidur cepat berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tidurCepat = TidurCepat::with(['siswa', 'bangunPagi', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
        
        return view('tidur-cepat.show', compact('tidurCepat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tidurCepat = TidurCepat::findOrFail($id);
        $siswas = Siswa::orderBy('nama_lengkap')->get();
        
        return view('tidur-cepat.edit', compact('tidurCepat', 'siswas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'pukul_tidur' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $tidurCepat = TidurCepat::findOrFail($id);
            
            $tidurCepat->update([
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
                'pukul_tidur' => $request->pukul_tidur,
                'keterangan' => $request->keterangan,
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('tidur-cepat.index')
                ->with('success', 'Data tidur cepat berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $tidurCepat = TidurCepat::findOrFail($id);
            
            // Update status di bangun pagi jika ada
            if ($tidurCepat->bangunPagi) {
                $tidurCepat->bangunPagi->update(['sudah_tidur_cepat' => false]);
            }
            
            $tidurCepat->delete();

            DB::commit();

            return redirect()->route('tidur-cepat.index')
                ->with('success', 'Data tidur cepat berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Checklist tidur cepat untuk siswa (tanpa modal, langsung submit)
     */
   public function checklistSiswa(Request $request)
    {
        $siswaId = Session::get('siswa_id');
        
        if (!$siswaId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi siswa tidak ditemukan. Silakan login ulang.'
            ], 401);
        }

        try {
            $request->validate([
                'pukul_tidur' => 'required|date_format:H:i',
            ]);

            DB::beginTransaction();

            $tanggal = now()->toDateString();
            $pukulTidur = $request->pukul_tidur;
            
            // Cek apakah sudah ada data untuk hari ini
            $existing = TidurCepat::where('siswa_id', $siswaId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            if ($existing) {
                // Update data existing
                $existing->update([
                    'pukul_tidur' => $pukulTidur,
                ]);
                
                $tidurCepat = $existing;
            } else {
                // Buat data baru
                $tidurCepat = TidurCepat::create([
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                    'pukul_tidur' => $pukulTidur,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tidur cepat berhasil dicatat! Selamat tidur 😴',
                'data' => $tidurCepat->load('siswa')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checklist siswa tidur cepat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Uncheck tidur cepat
     */
    public function uncheck(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:tidur_cepats,id'
        ]);

        DB::beginTransaction();
        try {
            $tidurCepat = TidurCepat::findOrFail($request->id);
            
            // Update status di bangun pagi jika ada
            if ($tidurCepat->bangunPagi) {
                $tidurCepat->bangunPagi->update(['sudah_tidur_cepat' => false]);
            }

            // Hapus relasi dengan bangun pagi
            $tidurCepat->update([
                'bangun_pagi_id' => null,
                'status_bangun_pagi' => 'belum_bangun',
                'updated_by' => Auth::id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status tidur cepat berhasil diubah'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cek apakah sudah checklist hari ini (untuk siswa)
     */
    public function cekHariIni()
    {
        $siswaId = Session::get('siswa_id');
        
        if (!$siswaId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi siswa tidak ditemukan'
            ]);
        }

        $tanggal = now()->toDateString();
        
        $data = TidurCepat::where('siswa_id', $siswaId)
            ->whereDate('tanggal', $tanggal)
            ->with(['siswa'])
            ->first();
            
        return response()->json([
            'success' => true,
            'data' => $data,
            'sudah_checklist' => $data ? true : false
        ]);
    }

    /**
     * Get data untuk hari ini
     */
    public function getToday(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
        ]);

        $siswaId = $request->siswa_id;
        $tanggal = $request->tanggal;
        
        $data = TidurCepat::where('siswa_id', $siswaId)
            ->whereDate('tanggal', $tanggal)
            ->with(['siswa', 'bangunPagi'])
            ->first();
            
        return response()->json($data);
    }

    /**
     * View checklist untuk siswa
     */
    public function checklistSiswaView()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('login.siswa')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        return view('tidur-cepat.checklist-siswa');
    }

    /**
     * Get riwayat tidur cepat untuk siswa
     */
    public function riwayat(Request $request)
    {
        $siswaId = Session::get('siswa_id');
        
        if (!$siswaId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi siswa tidak ditemukan'
            ]);
        }
        
        $limit = $request->limit ?? 7;
        
        $data = TidurCepat::where('siswa_id', $siswaId)
            ->orderBy('tanggal', 'desc')
            ->limit($limit)
            ->get(['id', 'tanggal', 'pukul_tidur', 'nilai', 'status_bangun_pagi']);
            
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get statistik tidur cepat
     */
    public function statistik(Request $request)
    {
        $siswaId = $request->siswa_id;
        $bulan = $request->bulan ?? now()->format('F');
        $tahun = $request->tahun ?? now()->year;
        
        $query = TidurCepat::where('bulan', $bulan)
            ->where('tahun', $tahun);
            
        if ($siswaId) {
            $query->where('siswa_id', $siswaId);
        }
        
        $data = $query->get();
        
        $statistik = [
            'total_hari' => $data->count(),
            'rata_rata_nilai' => $data->count() > 0 ? round($data->avg('nilai')) : 0,
            'tepat_waktu' => $data->where('kategori_waktu', 'Sangat Baik')->count(),
            'sedikit_terlambat' => $data->where('kategori_waktu', 'Baik')->count(),
            'terlambat' => $data->where('kategori_waktu', 'Cukup')->count() + $data->where('kategori_waktu', 'Kurang')->count(),
            'sudah_bangun_pagi' => $data->where('status_bangun_pagi', 'sudah_bangun')->count(),
            'belum_bangun_pagi' => $data->where('status_bangun_pagi', 'belum_bangun')->count(),
        ];
        
        return response()->json($statistik);
    }
}