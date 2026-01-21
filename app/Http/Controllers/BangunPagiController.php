<?php

namespace App\Http\Controllers;

use App\Models\BangunPagi;
use App\Models\TidurCepat;
use App\Models\PengaturanWaktuBangunPagi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BangunPagiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Tambahkan ini di awal method
        $search = $request->get('search', '');
        
        $query = BangunPagi::with(['siswa', 'tidurCepat']);
        
        // Jika user login sebagai siswa, hanya tampilkan data siswa tersebut
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
            $query->where('siswa_id', $siswaId);
            
            // Ambil data siswa untuk judul
            $currentSiswa = Siswa::find($siswaId);
            $siswas = collect([$currentSiswa]); // Hanya satu siswa
        } 
        // Jika user login sebagai orangtua
        elseif (Session::has('orangtua_id')) {
            $orangtuaId = Session::get('orangtua_id');
            // Ambil siswa berdasarkan orangtua_id
            $currentSiswa = Siswa::whereHas('orangtua', function($q) use ($orangtuaId) {
                $q->where('id', $orangtuaId);
            })->first();
            
            if ($currentSiswa) {
                $query->where('siswa_id', $currentSiswa->id);
                $siswas = collect([$currentSiswa]);
            } else {
                $siswas = collect();
            }
        }
        // Jika admin/guru wali
        else {
            // Filter berdasarkan siswa jika ada
            if ($request->has('siswa_id') && !empty($request->siswa_id) && $request->siswa_id !== 'null') {
                $query->where('siswa_id', $request->siswa_id);
            }
            
            // Tambahkan filter search untuk admin/guru
            if ($search) {
                $siswas = Siswa::where('nama_lengkap', 'like', "%$search%")
                    ->orWhere('nis', 'like', "%$search%")
                    ->orderBy('nama_lengkap')
                    ->get();
            } else {
                $siswas = Siswa::orderBy('nama_lengkap')->get();
            }
        }
        
        // Filter berdasarkan bulan
        if ($request->has('bulan') && !empty($request->bulan) && $request->bulan !== 'null') {
            $query->where('bulan', $request->bulan);
        }
        
        // Filter berdasarkan tahun
        if ($request->has('tahun') && !empty($request->tahun) && $request->tahun !== 'null') {
            $query->where('tahun', $request->tahun);
        }
        
        // Filter berdasarkan status bangun
        if ($request->has('sudah_bangun') && $request->sudah_bangun !== '' && $request->sudah_bangun !== 'null') {
            $query->where('sudah_bangun', $request->sudah_bangun == '1');
        }
        
        $bangunPagis = $query->orderBy('tanggal', 'desc')->paginate(20);
        
        // Cek apakah user sudah login sebagai siswa/orangtua
        $isSiswaLogin = Session::has('siswa_id');
        $isOrangtuaLogin = Session::has('orangtua_id');
        
        // Data untuk UI baru (checklist cepat)
        $today = now()->toDateString();
        $selectedTanggal = $request->get('tanggal', $today);
        
        // Ambil data bangun pagi untuk tanggal yang dipilih
        $bangunPagiData = BangunPagi::where('tanggal', $selectedTanggal);
        
        // Jika login sebagai siswa/orangtua, filter berdasarkan siswa
        if ($isSiswaLogin) {
            $bangunPagiData->where('siswa_id', Session::get('siswa_id'));
        } elseif ($isOrangtuaLogin && isset($currentSiswa)) {
            $bangunPagiData->where('siswa_id', $currentSiswa->id);
        }
        
        $bangunPagiData = $bangunPagiData->get()->keyBy('siswa_id');
        
        // Hitung statistik untuk hari ini
        $totalStudents = $siswas->count();
        $totalCheckedToday = 0;
        $onTimeCount = 0;
        $lateCount = 0;
        
        foreach ($siswas as $siswa) {
            $bangunPagi = $bangunPagiData->get($siswa->id);
            if ($bangunPagi && $bangunPagi->sudah_bangun) {
                $totalCheckedToday++;
                if ($bangunPagi->kategori_waktu == 'tepat_waktu') {
                    $onTimeCount++;
                } else {
                    $lateCount++;
                }
            }
        }
        
        // Data checklist terkini untuk tabel
        $recentChecklists = $bangunPagis;
        
        // Ambil pengaturan waktu jika ada
        $pengaturan = PengaturanWaktuBangunPagi::first();
        
        // Tambahkan variabel $search ke compact
        return view('bangun-pagi.index', compact(
            'bangunPagis',
            'siswas',
            'isSiswaLogin',
            'isOrangtuaLogin',
            'selectedTanggal',
            'bangunPagiData',
            'totalStudents',
            'totalCheckedToday',
            'onTimeCount',
            'lateCount',
            'recentChecklists',
            'pengaturan',
            'search'
        ));
    }

    /**
     * Checklist bangun pagi (untuk siswa langsung tanpa modal)
     */
    public function checklistSiswa(Request $request)
    {
        // Validasi untuk siswa login
        $siswaId = Session::get('siswa_id');
        
        if (!$siswaId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi siswa tidak ditemukan. Silakan login ulang.'
            ], 401);
        }

        $request->validate([
            'pukul' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = now()->toDateString();
            $pukul = $request->pukul ?: now()->format('H:i');
            
            // Cek apakah sudah ada data untuk hari ini
            $existing = BangunPagi::where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->first();

            if ($existing) {
                // Update data existing
                $existing->update([
                    'pukul' => $pukul,
                    'sudah_bangun' => true,
                ]);
                
                $bangunPagi = $existing;
            } else {
                // Buat data baru
                $bangunPagi = BangunPagi::create([
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                    'pukul' => $pukul,
                    'bulan' => now()->translatedFormat('F'),
                    'tahun' => now()->year,
                    'sudah_bangun' => true,
                ]);
            }

            // Hitung nilai otomatis
            $bangunPagi->hitungNilai();
            $bangunPagi->save();

            // Cek apakah ada data tidur cepat untuk tanggal yang sama
            $tidurCepat = TidurCepat::where('siswa_id', $siswaId)
                ->where('tanggal', $tanggal)
                ->first();

            if ($tidurCepat) {
                // Update status bangun pagi di tidur cepat
                $tidurCepat->update([
                    'bangun_pagi_id' => $bangunPagi->id,
                    'status_bangun_pagi' => 'sudah_bangun'
                ]);
                
                // Update status sudah tidur cepat di bangun pagi
                $bangunPagi->update(['sudah_tidur_cepat' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bangun pagi berhasil dicatat!',
                'data' => $bangunPagi->load('siswa', 'tidurCepat')
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
     * Checklist bangun pagi (untuk admin/guru)
     */
    public function checklist(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'pukul' => 'required',
        ]);

        DB::beginTransaction();
        try {
            // Cek apakah sudah ada data untuk tanggal tersebut
            $existing = BangunPagi::where('siswa_id', $request->siswa_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($existing) {
                // Update data existing
                $existing->update([
                    'pukul' => $request->pukul,
                    'sudah_bangun' => true,
                    'updated_by' => Auth::id(),
                ]);
                
                $bangunPagi = $existing;
            } else {
                // Buat data baru
                $tanggal = \Carbon\Carbon::parse($request->tanggal);
                
                $bangunPagi = BangunPagi::create([
                    'siswa_id' => $request->siswa_id,
                    'tanggal' => $request->tanggal,
                    'pukul' => $request->pukul,
                    'bulan' => $tanggal->translatedFormat('F'),
                    'tahun' => $tanggal->year,
                    'sudah_bangun' => true,
                    'created_by' => Auth::id(),
                ]);
            }

            // Hitung nilai otomatis
            $bangunPagi->hitungNilai();
            $bangunPagi->save();

            // Cek apakah ada data tidur cepat untuk tanggal yang sama
            $tidurCepat = TidurCepat::where('siswa_id', $request->siswa_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($tidurCepat) {
                // Update status bangun pagi di tidur cepat
                $tidurCepat->update([
                    'bangun_pagi_id' => $bangunPagi->id,
                    'status_bangun_pagi' => 'sudah_bangun'
                ]);
                
                // Update status sudah tidur cepat di bangun pagi
                $bangunPagi->update(['sudah_tidur_cepat' => true]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bangun pagi berhasil dicatat',
                'data' => $bangunPagi->load('siswa', 'tidurCepat')
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
        
        $data = BangunPagi::where('siswa_id', $siswaId)
            ->where('tanggal', $tanggal)
            ->with(['siswa', 'tidurCepat'])
            ->first();
            
        return response()->json([
            'success' => true,
            'data' => $data,
            'sudah_checklist' => $data && $data->sudah_bangun
        ]);
    }

    /**
     * Uncheck bangun pagi
     */
    public function uncheck(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bangun_pagis,id'
        ]);

        DB::beginTransaction();
        try {
            $bangunPagi = BangunPagi::findOrFail($request->id);
            
            // Update status bangun pagi
            $bangunPagi->update([
                'sudah_bangun' => false,
                'updated_by' => Auth::id(),
            ]);

            // Update status di tidur cepat jika ada
            if ($bangunPagi->tidurCepat) {
                $bangunPagi->tidurCepat->update([
                    'status_bangun_pagi' => 'belum_bangun'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status bangun pagi berhasil diubah'
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
        
        $data = BangunPagi::where('siswa_id', $siswaId)
            ->where('tanggal', $tanggal)
            ->with(['siswa', 'tidurCepat'])
            ->first();
            
        return response()->json($data);
    }

    /**
     * Get data untuk edit
     */
    public function getData($id)
    {
        try {
            $bangunPagi = BangunPagi::with('siswa')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $bangunPagi->id,
                    'siswa_id' => $bangunPagi->siswa_id,
                    'siswa_nama' => $bangunPagi->siswa->nama_lengkap ?? $bangunPagi->siswa->nama,
                    'pukul' => $bangunPagi->pukul,
                    'tanggal' => $bangunPagi->tanggal,
                    'nilai' => $bangunPagi->nilai,
                    'kategori_waktu' => $bangunPagi->kategori_waktu,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update waktu bangun pagi
     */
    public function updateWaktu(Request $request, $id)
    {
        $request->validate([
            'pukul' => 'required|date_format:H:i',
        ]);

        try {
            $bangunPagi = BangunPagi::findOrFail($id);
            
            $bangunPagi->update([
                'pukul' => $request->pukul,
                'updated_by' => Auth::id(),
            ]);

            // Hitung ulang nilai
            $bangunPagi->hitungNilai();
            $bangunPagi->save();

            return response()->json([
                'success' => true,
                'message' => 'Waktu bangun berhasil diperbarui',
                'data' => $bangunPagi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus checklist bangun pagi
     */
    public function hapusChecklist($id)
    {
        try {
            $bangunPagi = BangunPagi::findOrFail($id);
            
            // Update status di tidur cepat jika ada
            if ($bangunPagi->tidurCepat) {
                $bangunPagi->tidurCepat->update([
                    'bangun_pagi_id' => null,
                    'status_bangun_pagi' => 'belum_bangun'
                ]);
            }
            
            $bangunPagi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Checklist berhasil dihapus'
            ]);
        } catch (\Exception $e) {
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
        return view('bangun-pagi.create', compact('siswas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $tanggal = \Carbon\Carbon::parse($request->tanggal);
            
            $bangunPagi = BangunPagi::create([
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
                'pukul' => $request->pukul,
                'bulan' => $tanggal->translatedFormat('F'),
                'tahun' => $tanggal->year,
                'sudah_bangun' => $request->has('sudah_bangun'),
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            // Hitung nilai otomatis
            $bangunPagi->hitungNilai();
            $bangunPagi->save();

            DB::commit();

            return redirect()->route('bangun-pagi.index')
                ->with('success', 'Data bangun pagi berhasil disimpan');

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
        $bangunPagi = BangunPagi::with(['siswa', 'tidurCepat', 'parafOrtu', 'creator', 'updater'])
            ->findOrFail($id);
        
        return view('bangun-pagi.show', compact('bangunPagi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bangunPagi = BangunPagi::findOrFail($id);
        $siswas = Siswa::orderBy('nama_lengkap')->get();
        
        return view('bangun-pagi.edit', compact('bangunPagi', 'siswas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            $bangunPagi = BangunPagi::findOrFail($id);
            
            $tanggal = \Carbon\Carbon::parse($request->tanggal);
            
            $bangunPagi->update([
                'siswa_id' => $request->siswa_id,
                'tanggal' => $request->tanggal,
                'pukul' => $request->pukul,
                'bulan' => $tanggal->translatedFormat('F'),
                'tahun' => $tanggal->year,
                'sudah_bangun' => $request->has('sudah_bangun'),
                'keterangan' => $request->keterangan,
                'updated_by' => Auth::id(),
            ]);

            // Hitung ulang nilai
            $bangunPagi->hitungNilai();
            $bangunPagi->save();

            // Update status di tidur cepat jika ada
            if ($bangunPagi->tidurCepat) {
                $bangunPagi->tidurCepat->update([
                    'status_bangun_pagi' => $bangunPagi->sudah_bangun ? 'sudah_bangun' : 'belum_bangun'
                ]);
            }

            DB::commit();

            return redirect()->route('bangun-pagi.index')
                ->with('success', 'Data bangun pagi berhasil diperbarui');

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
            $bangunPagi = BangunPagi::findOrFail($id);
            
            // Update status di tidur cepat jika ada
            if ($bangunPagi->tidurCepat) {
                $bangunPagi->tidurCepat->update([
                    'bangun_pagi_id' => null,
                    'status_bangun_pagi' => 'belum_bangun'
                ]);
            }
            
            $bangunPagi->delete();

            DB::commit();

            return redirect()->route('bangun-pagi.index')
                ->with('success', 'Data bangun pagi berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}