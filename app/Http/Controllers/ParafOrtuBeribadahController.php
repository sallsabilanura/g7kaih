<?php

namespace App\Http\Controllers;

use App\Models\Beribadah;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParafOrtuBeribadahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Dapatkan user info berdasarkan session
            $userInfo = $this->getUserInfo();
            
            // Debug
            Log::info('ParafOrtuBeribadahController@index', [
                'userType' => $userInfo['userType'] ?? 'unknown',
                'isOrangtua' => $userInfo['isOrangtua'],
                'isSiswa' => $userInfo['isSiswa'],
                'isAdmin' => $userInfo['isAdmin'],
                'isGuruWali' => $userInfo['isGuruWali'],
                'siswaAnakId' => $userInfo['siswaAnak']->id ?? null
            ]);
            
            // Set tanggal default hari ini
            $selectedTanggal = $request->get('tanggal', date('Y-m-d'));
            $search = $request->get('search', '');
            $status = $request->get('status', '');
            
            // Query beribadah
            $query = Beribadah::query()
                ->with(['siswa' => function($q) {
                    $q->with('kelas');
                }])
                ->whereDate('tanggal', $selectedTanggal)
                ->orderBy('tanggal', 'desc')
                ->orderBy('siswa_id');
            
            // Filter berdasarkan user role
            if ($userInfo['isOrangtua'] && isset($userInfo['siswaAnak']->id)) {
                $query->where('siswa_id', $userInfo['siswaAnak']->id);
            } elseif ($userInfo['isSiswa'] && isset($userInfo['siswaAnak']->id)) {
                $query->where('siswa_id', $userInfo['siswaAnak']->id);
            }
            
            // Filter search (hanya untuk admin/guru wali)
            if ($search && ($userInfo['isAdmin'] || $userInfo['isGuruWali'])) {
                $query->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            // Get all data
            $beribadahs = $query->get();
            
            // Filter berdasarkan status jika dipilih
            if ($status && in_array($status, ['belum', 'sebagian', 'sudah'])) {
                $beribadahs = $beribadahs->filter(function($item) use ($status) {
                    return $this->checkStatus($item, $status);
                })->values();
            }
            
            // Hitung statistik
            $statistics = $this->calculateStatistics($beribadahs);
            
            // Label sholat
            $sholatLabels = [
                'subuh' => 'Subuh',
                'dzuhur' => 'Dzuhur',
                'ashar' => 'Ashar',
                'maghrib' => 'Maghrib',
                'isya' => 'Isya'
            ];
            
            return view('paraf-ortu-beribadah.index', [
                'beribadahs' => $beribadahs,
                'selectedTanggal' => $selectedTanggal,
                'search' => $search,
                'status' => $status,
                'userInfo' => $userInfo,
                'totalData' => $statistics['totalData'],
                'belumParaf' => $statistics['belumParaf'],
                'sebagianParaf' => $statistics['sebagianParaf'],
                'semuaParaf' => $statistics['semuaParaf'],
                'sholatLabels' => $sholatLabels,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get detail beribadah untuk modal paraf
     */
    public function getDetail($id)
    {
        try {
            Log::info('getDetail called for beribadah ID: ' . $id);
            
            $userInfo = $this->getUserInfo();
            
            $beribadah = Beribadah::with([
                'siswa' => function($q) {
                    $q->with('kelas');
                }
            ])->find($id);
            
            if (!$beribadah) {
                Log::warning('Beribadah not found: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data beribadah tidak ditemukan'
                ], 404);
            }
            
            // Validasi akses
            if ($userInfo['isOrangtua'] || $userInfo['isSiswa']) {
                if (!isset($userInfo['siswaAnak']->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data siswa tidak ditemukan dalam session'
                    ], 403);
                }
                
                if ($beribadah->siswa_id != $userInfo['siswaAnak']->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengakses data anak Anda sendiri'
                    ], 403);
                }
            }
            
            // Data sholat
            $sholatData = [];
            $totalSholat = 0;
            $totalNilai = 0;
            
            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            foreach ($sholats as $sholat) {
                $waktuField = $sholat . '_waktu';
                $nilaiField = $sholat . '_nilai';
                $kategoriField = $sholat . '_kategori';
                $parafField = $sholat . '_paraf';
                $parafNamaField = $sholat . '_paraf_nama';
                $parafWaktuField = $sholat . '_paraf_waktu';
                
                $waktu = $beribadah->$waktuField;
                $formattedWaktu = '-';
                
                if ($waktu) {
                    try {
                        $formattedWaktu = Carbon::parse($waktu)->format('H:i');
                        $totalSholat++;
                        $totalNilai += (int)($beribadah->$nilaiField ?? 0);
                    } catch (\Exception $e) {
                        if (is_string($waktu) && strlen($waktu) >= 5) {
                            $formattedWaktu = substr($waktu, 0, 5);
                        }
                        if ($formattedWaktu !== '-') {
                            $totalSholat++;
                            $totalNilai += (int)($beribadah->$nilaiField ?? 0);
                        }
                    }
                }
                
                $parafWaktu = null;
                if ($beribadah->$parafWaktuField) {
                    try {
                        $parafWaktu = Carbon::parse($beribadah->$parafWaktuField)->format('d/m/Y H:i');
                    } catch (\Exception $e) {
                        $parafWaktu = $beribadah->$parafWaktuField;
                    }
                }
                
                $sholatData[$sholat] = [
                    'label' => $this->getSholatLabel($sholat),
                    'waktu' => $formattedWaktu,
                    'nilai' => $beribadah->$nilaiField ?? '0',
                    'kategori' => $beribadah->$kategoriField ?? '-',
                    'sudah_paraf' => (bool)($beribadah->$parafField ?? false),
                    'paraf_nama' => $beribadah->$parafNamaField ?? null,
                    'paraf_waktu' => $parafWaktu,
                ];
            }
            
            // Opsi nama orangtua
            $orangtuaOptions = [];
            
            if ($userInfo['isOrangtua']) {
                // Jika login sebagai orangtua, gunakan nama dari session
                $namaOrangtua = Session::get('orangtua_nama', 'Orangtua');
                $orangtuaOptions[] = [
                    'value' => $namaOrangtua,
                    'label' => $namaOrangtua . ' (Orangtua)',
                    'type' => 'orangtua'
                ];
            } 
            elseif ($userInfo['isAdmin'] || $userInfo['isGuruWali']) {
                // Jika admin/guru, ambil data orangtua dari database
                $orangtua = Orangtua::where('siswa_id', $beribadah->siswa_id)->first();
                
                if ($orangtua) {
                    if ($orangtua->nama_ayah) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_ayah,
                            'label' => $orangtua->nama_ayah . ' (Ayah)',
                            'type' => 'ayah'
                        ];
                    }
                    if ($orangtua->nama_ibu) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_ibu,
                            'label' => $orangtua->nama_ibu . ' (Ibu)',
                            'type' => 'ibu'
                        ];
                    }
                    if ($orangtua->nama_wali) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_wali,
                            'label' => $orangtua->nama_wali . ' (Wali)',
                            'type' => 'wali'
                        ];
                    }
                }
                
                // Tambahkan opsi lainnya
                $orangtuaOptions[] = [
                    'value' => 'orangtua_lainnya',
                    'label' => 'Orang Tua/Wali Lainnya',
                    'type' => 'lainnya'
                ];
            }
            elseif ($userInfo['isSiswa']) {
                // Siswa bisa pilih nama orangtua
                $orangtua = Orangtua::where('siswa_id', $userInfo['siswaAnak']->id ?? null)->first();
                
                if ($orangtua) {
                    if ($orangtua->nama_ayah) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_ayah,
                            'label' => $orangtua->nama_ayah . ' (Ayah)',
                            'type' => 'ayah'
                        ];
                    }
                    if ($orangtua->nama_ibu) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_ibu,
                            'label' => $orangtua->nama_ibu . ' (Ibu)',
                            'type' => 'ibu'
                        ];
                    }
                    if ($orangtua->nama_wali) {
                        $orangtuaOptions[] = [
                            'value' => $orangtua->nama_wali,
                            'label' => $orangtua->nama_wali . ' (Wali)',
                            'type' => 'wali'
                        ];
                    }
                }
                
                // Default option
                $orangtuaOptions[] = [
                    'value' => 'orangtua_lainnya',
                    'label' => 'Orang Tua/Wali',
                    'type' => 'lainnya'
                ];
            }
            
            // Jika tidak ada opsi, tambahkan default
            if (empty($orangtuaOptions)) {
                $orangtuaOptions[] = [
                    'value' => 'orangtua_lainnya',
                    'label' => 'Orang Tua/Wali',
                    'type' => 'lainnya'
                ];
            }
            
            // Format hari
            $hari = '-';
            try {
                $hari = Carbon::parse($beribadah->tanggal)->locale('id')->dayName;
            } catch (\Exception $e) {
                // Do nothing
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $beribadah->id,
                    'siswa_nama' => $beribadah->siswa->nama_lengkap ?? '-',
                    'siswa_nis' => $beribadah->siswa->nis ?? '-',
                    'siswa_kelas' => $beribadah->siswa->kelas->nama_kelas ?? '-',
                    'tanggal' => Carbon::parse($beribadah->tanggal)->format('d/m/Y'),
                    'hari' => $hari,
                    'sholat_data' => $sholatData,
                    'total_sholat' => $totalSholat,
                    'total_nilai' => $totalNilai,
                    'orangtua_options' => $orangtuaOptions,
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getDetail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Store paraf called', $request->all());
            
            // Validasi
            $validatedData = $request->validate([
                'beribadahs_id' => 'required|exists:beribadahs,id',
                'nama_ortu' => 'required|string|max:255',
                'jenis_sholat' => 'required|array|min:1',
                'jenis_sholat.*' => 'in:subuh,dzuhur,ashar,maghrib,isya',
                'catatan' => 'nullable|string|max:500',
            ], [
                'beribadahs_id.required' => 'ID beribadah diperlukan',
                'beribadahs_id.exists' => 'Data beribadah tidak ditemukan',
                'nama_ortu.required' => 'Nama orangtua harus diisi',
                'jenis_sholat.required' => 'Pilih minimal satu sholat',
                'jenis_sholat.min' => 'Pilih minimal satu sholat',
            ]);
            
            $userInfo = $this->getUserInfo();
            $beribadah = Beribadah::with('siswa')->findOrFail($request->beribadahs_id);
            
            // Validasi akses
            if ($userInfo['isOrangtua'] || $userInfo['isSiswa']) {
                if (!isset($userInfo['siswaAnak']->id)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data siswa tidak ditemukan dalam session'
                    ], 403);
                }
                
                if ($beribadah->siswa_id != $userInfo['siswaAnak']->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat memberikan paraf untuk data anak Anda sendiri'
                    ], 403);
                }
            }
            
            // Proses paraf untuk setiap sholat yang dipilih
            $parafedCount = 0;
            $currentTime = Carbon::now();
            $sholatDiparaf = [];
            
            foreach ($request->jenis_sholat as $sholat) {
                $waktuField = $sholat . '_waktu';
                $parafField = $sholat . '_paraf';
                $parafNamaField = $sholat . '_paraf_nama';
                $parafWaktuField = $sholat . '_paraf_waktu';
                
                // Cek apakah sholat sudah dilaksanakan
                if (!$beribadah->$waktuField) {
                    Log::info("Sholat {$sholat} tidak dilaksanakan, skipping");
                    continue;
                }
                
                // Cek apakah sudah diparaf
                if ($beribadah->$parafField) {
                    Log::info("Sholat {$sholat} sudah diparaf sebelumnya, skipping");
                    continue;
                }
                
                // Berikan paraf
                $beribadah->$parafField = true;
                $beribadah->$parafNamaField = $request->nama_ortu;
                $beribadah->$parafWaktuField = $currentTime;
                $parafedCount++;
                $sholatDiparaf[] = $this->getSholatLabel($sholat);
                
                Log::info("Sholat {$sholat} diparaf oleh {$request->nama_ortu}");
            }
            
            if ($parafedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada sholat yang berhasil diparaf. Mungkin sudah diparaf sebelumnya atau belum dilaksanakan.'
                ], 400);
            }
            
            // Tambahkan catatan jika ada
            if ($request->catatan) {
                $currentCatatan = $beribadah->catatan_ortu ? $beribadah->catatan_ortu . "\n" : '';
                $beribadah->catatan_ortu = $currentCatatan . 
                    "[{$currentTime->format('d/m/Y H:i')}] {$request->nama_ortu}: {$request->catatan}";
            }
            
            $beribadah->save();
            
            Log::info('Paraf berhasil diberikan', [
                'beribadah_id' => $beribadah->id,
                'siswa_id' => $beribadah->siswa_id,
                'paraf_oleh' => $request->nama_ortu,
                'sholat_diparaf' => $sholatDiparaf,
                'parafed_count' => $parafedCount,
                'user_type' => $userInfo['userType'] ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => '✅ Paraf berhasil disimpan untuk ' . $parafedCount . ' sholat: ' . implode(', ', $sholatDiparaf),
                'reload' => true
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in store paraf: ' . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => '❌ Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error in store paraf: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => '❌ Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batalkan paraf untuk semua sholat
     */
    public function batalkanParaf($id)
    {
        try {
            $userInfo = $this->getUserInfo();
            
            // Hanya admin/guru wali yang bisa membatalkan paraf
            if (!$userInfo['isAdmin'] && !$userInfo['isGuruWali']) {
                return redirect()->back()->with('error', 'Anda tidak berhak membatalkan paraf');
            }
            
            $beribadah = Beribadah::findOrFail($id);
            
            // Reset semua paraf
            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            $dibatalkan = [];
            
            foreach ($sholats as $sholat) {
                $parafField = $sholat . '_paraf';
                $parafNamaField = $sholat . '_paraf_nama';
                $parafWaktuField = $sholat . '_paraf_waktu';
                
                if ($beribadah->$parafField) {
                    $beribadah->$parafField = false;
                    $beribadah->$parafNamaField = null;
                    $beribadah->$parafWaktuField = null;
                    $dibatalkan[] = $this->getSholatLabel($sholat);
                }
            }
            
            $beribadah->save();
            
            Log::info('Paraf dibatalkan', [
                'beribadah_id' => $beribadah->id,
                'sholat_dibatalkan' => $dibatalkan
            ]);
            
            return redirect()->back()->with('success', 'Paraf berhasil dibatalkan untuk: ' . implode(', ', $dibatalkan));
            
        } catch (\Exception $e) {
            Log::error('Error in batalkanParaf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * ==================== HELPER METHODS ====================
     */
    
    /**
     * Get user info based on session
     */
    private function getUserInfo()
    {
        $userInfo = [
            'isOrangtua' => false,
            'isSiswa' => false,
            'isAdmin' => false,
            'isGuruWali' => false,
            'user' => null,
            'orangtua' => null,
            'siswaAnak' => null,
            'userType' => Session::get('user_type'),
        ];
        
        // Check session type
        $userType = Session::get('user_type');
        
        if ($userType === 'orangtua') {
            $userInfo['isOrangtua'] = true;
            
            // Get orangtua from session
            $orangtuaId = Session::get('orangtua_id');
            if ($orangtuaId) {
                $userInfo['orangtua'] = Orangtua::find($orangtuaId);
            }
            
            // Get siswa anak
            $siswaId = Session::get('siswa_anak_id');
            if ($siswaId) {
                $userInfo['siswaAnak'] = Siswa::with('kelas')->find($siswaId);
            }
            
        } elseif ($userType === 'siswa') {
            $userInfo['isSiswa'] = true;
            
            // Get siswa from session
            $siswaId = Session::get('siswa_id');
            if ($siswaId) {
                $userInfo['siswaAnak'] = Siswa::with('kelas')->find($siswaId);
            }
            
        } else {
            // Check Laravel auth (for admin/guru)
            if (Auth::check()) {
                $user = Auth::user();
                $userInfo['user'] = $user;
                
                if ($user->peran === 'admin') {
                    $userInfo['isAdmin'] = true;
                } elseif ($user->peran === 'guru_wali') {
                    $userInfo['isGuruWali'] = true;
                }
            }
        }
        
        return $userInfo;
    }

    /**
     * Calculate statistics
     */
    private function calculateStatistics($beribadahs)
    {
        $belumParaf = 0;
        $sebagianParaf = 0;
        $semuaParaf = 0;
        $totalData = $beribadahs->count();
        
        foreach ($beribadahs as $beribadah) {
            $totalSholat = 0;
            $parafedSholat = 0;
            
            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            foreach ($sholats as $sholat) {
                $waktuField = $sholat . '_waktu';
                $parafField = $sholat . '_paraf';
                
                if ($beribadah->$waktuField) {
                    $totalSholat++;
                    if ($beribadah->$parafField) {
                        $parafedSholat++;
                    }
                }
            }
            
            if ($totalSholat === 0) {
                continue;
            } elseif ($parafedSholat === 0) {
                $belumParaf++;
            } elseif ($parafedSholat === $totalSholat) {
                $semuaParaf++;
            } else {
                $sebagianParaf++;
            }
        }
        
        return [
            'totalData' => $totalData,
            'belumParaf' => $belumParaf,
            'sebagianParaf' => $sebagianParaf,
            'semuaParaf' => $semuaParaf,
        ];
    }

    /**
     * Check status of beribadah
     */
    private function checkStatus($beribadah, $status)
    {
        $totalSholat = 0;
        $parafedSholat = 0;
        
        $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        foreach ($sholats as $sholat) {
            $waktuField = $sholat . '_waktu';
            $parafField = $sholat . '_paraf';
            
            if ($beribadah->$waktuField) {
                $totalSholat++;
                if ($beribadah->$parafField) {
                    $parafedSholat++;
                }
            }
        }
        
        if ($totalSholat === 0) return false;
        
        switch ($status) {
            case 'belum':
                return $parafedSholat === 0;
            case 'sebagian':
                return $parafedSholat > 0 && $parafedSholat < $totalSholat;
            case 'sudah':
                return $parafedSholat === $totalSholat;
            default:
                return true;
        }
    }

    /**
     * Get sholat label
     */
    private function getSholatLabel($sholat)
    {
        $labels = [
            'subuh' => 'Subuh',
            'dzuhur' => 'Dzuhur',
            'ashar' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya'
        ];
        
        return $labels[$sholat] ?? ucfirst($sholat);
    }
}