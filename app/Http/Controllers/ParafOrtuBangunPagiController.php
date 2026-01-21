<?php

namespace App\Http\Controllers;

use App\Models\ParafOrtuBangunPagi;
use App\Models\BangunPagi;
use App\Models\Orangtua;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ParafOrtuBangunPagiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    try {
        $search = $request->get('search', '');
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));
        $statusFilter = $request->get('status', 'all');
        
        Log::info('Loading paraf index', [
            'search' => $search,
            'tanggal' => $selectedTanggal,
            'status' => $statusFilter
        ]);

        // Query dengan eager loading yang lengkap
        $query = BangunPagi::with([
            'siswa' => function($q) {
                $q->with(['kelas', 'orangtua']);
            },
            'parafOrtu',
            'tidurCepat'
        ])->whereDate('tanggal', $selectedTanggal);

        // Filter berdasarkan status paraf
        if ($statusFilter === 'belum') {
            $query->where('sudah_ttd_ortu', false);
        } elseif ($statusFilter === 'sudah') {
            $query->where('sudah_ttd_ortu', true);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Urutkan: belum diparaf dulu, baru yang sudah diparaf
        // Untuk yang sudah diparaf, urutkan berdasarkan waktu paraf terbaru
        $bangunPagiList = $query->orderByRaw('sudah_ttd_ortu ASC, created_at DESC')
                               ->paginate(20);

        // Hitung statistik
        $totalData = BangunPagi::whereDate('tanggal', $selectedTanggal)->count();
        $sudahDiparaf = BangunPagi::whereDate('tanggal', $selectedTanggal)
            ->where('sudah_ttd_ortu', true)->count();
        $belumDiparaf = BangunPagi::whereDate('tanggal', $selectedTanggal)
            ->where('sudah_ttd_ortu', false)->count();

        // Ambil semua kelas untuk filter (jika diperlukan)
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('paraf-ortu-bangun-pagi.index', [
            'bangunPagiList' => $bangunPagiList,
            'search' => $search,
            'selectedTanggal' => $selectedTanggal,
            'statusFilter' => $statusFilter,
            'totalData' => $totalData,
            'sudahDiparaf' => $sudahDiparaf,
            'belumDiparaf' => $belumDiparaf,
            'kelasList' => $kelasList,
        ]);

    } catch (\Exception $e) {
        Log::error('Error in index method: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $siswas = Siswa::with('orangtua')
                ->whereHas('bangunPagi', function($q) {
                    $q->whereDate('tanggal', today())
                      ->where('sudah_ttd_ortu', false);
                })->get();

            return view('paraf-ortu-bangun-pagi.create', compact('siswas'));
        } catch (\Exception $e) {
            Log::error('Error in create method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage (API/AJAX).
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'bangun_pagi_id' => 'required|exists:bangun_pagis,id',
                'nama_ortu' => 'required|string|max:255',
                'catatan' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            Log::info('Storing paraf', $request->all());

            // Cek apakah sudah ada paraf untuk bangun pagi ini
            $existingParaf = ParafOrtuBangunPagi::where('bangun_pagi_id', $request->bangun_pagi_id)->first();
            
            if ($existingParaf) {
                Log::warning('Paraf already exists for bangun pagi: ' . $request->bangun_pagi_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data paraf untuk checklist ini sudah ada!'
                ], 400);
            }

            // Buat paraf ortu
            $parafOrtu = ParafOrtuBangunPagi::create([
                'bangun_pagi_id' => $request->bangun_pagi_id,
                'nama_ortu' => $request->nama_ortu,
                'tanda_tangan' => 'Paraf Digital',
                'waktu_paraf' => now(),
                'catatan' => $request->catatan,
                'status' => 'terverifikasi',
            ]);

            // Update status di bangun pagi
            $bangunPagi = BangunPagi::find($request->bangun_pagi_id);
            $bangunPagi->update([
                'sudah_ttd_ortu' => true,
                'tanda_tangan_ortu' => 'Paraf Digital',
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('Paraf created successfully', ['paraf_id' => $parafOrtu->id]);

            return response()->json([
                'success' => true,
                'message' => 'Paraf orang tua berhasil disimpan!',
                'paraf_id' => $parafOrtu->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing paraf: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $parafOrtu = ParafOrtuBangunPagi::with([
                'bangunPagi.siswa.kelas',
                'bangunPagi.tidurCepat',
                'bangunPagi.parafOrtu'
            ])->findOrFail($id);

            return view('paraf-ortu-bangun-pagi.show', [
                'parafOrtu' => $parafOrtu,
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing paraf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $parafOrtu = ParafOrtuBangunPagi::with([
                'bangunPagi',
                'bangunPagi.siswa',
                'bangunPagi.siswa.kelas',
                'bangunPagi.siswa.orangtua'
            ])->findOrFail($id);

            return view('paraf-ortu-bangun-pagi.edit', [
                'parafOrtu' => $parafOrtu,
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing paraf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_ortu' => 'required|string|max:255',
                'tanda_tangan' => 'required|string',
                'catatan' => 'nullable|string|max:500',
                'status' => 'required|in:terverifikasi,belum_verifikasi',
            ]);

            DB::beginTransaction();

            $parafOrtu = ParafOrtuBangunPagi::findOrFail($id);

            $parafOrtu->update([
                'nama_ortu' => $request->nama_ortu,
                'tanda_tangan' => $request->tanda_tangan,
                'catatan' => $request->catatan,
                'status' => $request->status,
                'waktu_paraf' => now(),
            ]);

            // Update tanda tangan di bangun pagi jika status terverifikasi
            if ($request->status === 'terverifikasi') {
                $parafOrtu->bangunPagi->update([
                    'tanda_tangan_ortu' => $request->tanda_tangan,
                    'updated_at' => now(),
                ]);
            } else {
                // Reset jika belum diverifikasi
                $parafOrtu->bangunPagi->update([
                    'tanda_tangan_ortu' => null,
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('paraf-ortu-bangun-pagi.index')
                ->with('success', 'Data paraf orang tua berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating paraf: ' . $e->getMessage());
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
            DB::beginTransaction();

            $parafOrtu = ParafOrtuBangunPagi::findOrFail($id);
            
            // Reset status di bangun pagi
            $parafOrtu->bangunPagi->update([
                'sudah_ttd_ortu' => false,
                'tanda_tangan_ortu' => null,
                'updated_at' => now(),
            ]);

            $parafOrtu->delete();

            DB::commit();

            return redirect()->route('paraf-ortu-bangun-pagi.index')
                ->with('success', 'Data paraf orang tua berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting paraf: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========== API METHODS ==========

    /**
     * Check if paraf exists for a bangun pagi
     */
    public function checkParafExists($bangunPagiId)
    {
        try {
            $exists = ParafOrtuBangunPagi::where('bangun_pagi_id', $bangunPagiId)->exists();
            $bangunPagi = BangunPagi::with(['siswa', 'parafOrtu'])->find($bangunPagiId);
            
            $parafId = null;
            if ($exists) {
                $paraf = ParafOrtuBangunPagi::where('bangun_pagi_id', $bangunPagiId)->first();
                $parafId = $paraf->id;
            }
            
            return response()->json([
                'success' => true,
                'exists' => $exists,
                'sudah_ttd_ortu' => $bangunPagi ? $bangunPagi->sudah_ttd_ortu : false,
                'paraf_id' => $parafId
            ]);
        } catch (\Exception $e) {
            Log::error('Error checkParafExists: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistik for AJAX update
     */
    public function getStatistik(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            
            $totalData = BangunPagi::whereDate('tanggal', $tanggal)->count();
            $sudahDiparaf = BangunPagi::whereDate('tanggal', $tanggal)
                ->where('sudah_ttd_ortu', true)->count();
            $belumDiparaf = BangunPagi::whereDate('tanggal', $tanggal)
                ->where('sudah_ttd_ortu', false)->count();
                
            return response()->json([
                'success' => true,
                'totalData' => $totalData,
                'sudahDiparaf' => $sudahDiparaf,
                'belumDiparaf' => $belumDiparaf
            ]);
        } catch (\Exception $e) {
            Log::error('Error getStatistik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan detail bangun pagi untuk modal paraf
     */
    public function getDetailForParaf($id)
    {
        try {
            Log::info('Fetching detail for paraf ID: ' . $id);
            
            $bangunPagi = BangunPagi::with([
                'siswa.kelas',
                'siswa.orangtua',
                'parafOrtu'
            ])->findOrFail($id);

            // Debug informasi
            Log::info('Siswa data', [
                'siswa_id' => $bangunPagi->siswa_id,
                'siswa_nama' => $bangunPagi->siswa->nama_lengkap ?? 'N/A',
                'has_orangtua' => isset($bangunPagi->siswa->orangtua) ? 'Yes' : 'No'
            ]);

            // Ambil data orangtua
            $orangtuaOptions = [];
            
            if ($bangunPagi->siswa && $bangunPagi->siswa->orangtua) {
                $ortu = $bangunPagi->siswa->orangtua;
                
                if ($ortu->nama_ayah) {
                    $orangtuaOptions[] = [
                        'value' => $ortu->nama_ayah,
                        'label' => $ortu->nama_ayah . ' (Ayah)',
                        'type' => 'ayah'
                    ];
                }
                
                if ($ortu->nama_ibu) {
                    $orangtuaOptions[] = [
                        'value' => $ortu->nama_ibu,
                        'label' => $ortu->nama_ibu . ' (Ibu)',
                        'type' => 'ibu'
                    ];
                }
                
                Log::info('Found orangtua options', [
                    'ayah' => $ortu->nama_ayah ?? 'N/A',
                    'ibu' => $ortu->nama_ibu ?? 'N/A',
                    'options_count' => count($orangtuaOptions)
                ]);
            } else {
                Log::warning('No orangtua found for siswa: ' . $bangunPagi->siswa_id);
                
                // Ambil dari semua orangtua sebagai fallback
                $allOrangtua = Orangtua::where('is_active', true)->get();
                foreach ($allOrangtua as $ortu) {
                    if ($ortu->nama_ayah) {
                        $orangtuaOptions[] = [
                            'value' => $ortu->nama_ayah,
                            'label' => $ortu->nama_ayah . ' (Ayah) - ' . ($ortu->siswa->nama_lengkap ?? ''),
                            'type' => 'ayah'
                        ];
                    }
                    if ($ortu->nama_ibu) {
                        $orangtuaOptions[] = [
                            'value' => $ortu->nama_ibu,
                            'label' => $ortu->nama_ibu . ' (Ibu) - ' . ($ortu->siswa->nama_lengkap ?? ''),
                            'type' => 'ibu'
                        ];
                    }
                }
            }

            // Tambahkan opsi lainnya
            $orangtuaOptions[] = [
                'value' => 'orangtua_lainnya',
                'label' => 'Orang Tua/Wali Lainnya',
                'type' => 'lainnya'
            ];

            $data = [
                'id' => $bangunPagi->id,
                'siswa_nama' => $bangunPagi->siswa->nama_lengkap ?? '-',
                'siswa_nis' => $bangunPagi->siswa->nis ?? '-',
                'siswa_kelas' => $bangunPagi->siswa->kelas->nama_kelas ?? '-',
                'tanggal' => $bangunPagi->tanggal_formatted,
                'hari' => $bangunPagi->nama_hari,
                'pukul' => $bangunPagi->pukul_formatted,
                'pukul_12h' => $bangunPagi->pukul_12h,
                'status' => $bangunPagi->kategori_waktu_label,
                'nilai' => $bangunPagi->nilai,
                'sudah_diparaf' => $bangunPagi->sudah_ttd_ortu,
                'paraf_exists' => $bangunPagi->parafOrtu ? true : false,
                'orangtua_options' => $orangtuaOptions
            ];

            Log::info('Returning paraf detail data', $data);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getDetailForParaf: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get orangtua list for dropdown
     */
    public function getOrangtuaList()
    {
        try {
            $orangtuaList = Orangtua::where('is_active', true)
                ->with('siswa')
                ->get()
                ->flatMap(function ($ortu) {
                    $options = [];
                    
                    if ($ortu->nama_ayah) {
                        $options[] = [
                            'value' => $ortu->nama_ayah,
                            'label' => $ortu->nama_ayah . ' (Ayah) - ' . ($ortu->siswa->nama_lengkap ?? 'Tanpa Siswa'),
                            'type' => 'ayah'
                        ];
                    }
                    
                    if ($ortu->nama_ibu) {
                        $options[] = [
                            'value' => $ortu->nama_ibu,
                            'label' => $ortu->nama_ibu . ' (Ibu) - ' . ($ortu->siswa->nama_lengkap ?? 'Tanpa Siswa'),
                            'type' => 'ibu'
                        ];
                    }
                    
                    return $options;
                })
                ->unique('value')
                ->values()
                ->toArray();

            // Tambahkan opsi lainnya
            $orangtuaList[] = [
                'value' => 'orangtua_lainnya',
                'label' => 'Orang Tua/Wali Lainnya',
                'type' => 'lainnya'
            ];

            return response()->json([
                'success' => true,
                'data' => $orangtuaList
            ]);
        } catch (\Exception $e) {
            Log::error('Error getOrangtuaList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * Get orangtua by siswa ID
     */
    public function getOrangtuaBySiswaId($siswaId)
    {
        try {
            $siswa = Siswa::with('orangtua')->findOrFail($siswaId);
            
            $options = [];
            
            if ($siswa->orangtua) {
                if ($siswa->orangtua->nama_ayah) {
                    $options[] = [
                        'value' => $siswa->orangtua->nama_ayah,
                        'label' => $siswa->orangtua->nama_ayah . ' (Ayah)',
                        'type' => 'ayah'
                    ];
                }
                
                if ($siswa->orangtua->nama_ibu) {
                    $options[] = [
                        'value' => $siswa->orangtua->nama_ibu,
                        'label' => $siswa->orangtua->nama_ibu . ' (Ibu)',
                        'type' => 'ibu'
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $options
            ]);
        } catch (\Exception $e) {
            Log::error('Error getOrangtuaBySiswaId: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Additional routes methods
     */
    public function checklistParaf(Request $request)
    {
        return $this->store($request); // Reuse store method
    }

    public function batalkanParaf($id)
    {
        try {
            DB::beginTransaction();

            $parafOrtu = ParafOrtuBangunPagi::findOrFail($id);
            
            // Reset status di bangun pagi
            $parafOrtu->bangunPagi->update([
                'sudah_ttd_ortu' => false,
                'tanda_tangan_ortu' => null,
                'updated_at' => now(),
            ]);

            $parafOrtu->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paraf berhasil dibatalkan'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error batalkanParaf: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifikasi($id)
    {
        try {
            DB::beginTransaction();

            $parafOrtu = ParafOrtuBangunPagi::findOrFail($id);
            
            $parafOrtu->update([
                'status' => 'terverifikasi',
                'waktu_paraf' => now(),
            ]);

            $parafOrtu->bangunPagi->update([
                'tanda_tangan_ortu' => $parafOrtu->tanda_tangan,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Paraf berhasil diverifikasi!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error verifikasi: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function batalkanVerifikasi($id)
    {
        try {
            DB::beginTransaction();

            $parafOrtu = ParafOrtuBangunPagi::findOrFail($id);
            
            $parafOrtu->update([
                'status' => 'belum_verifikasi',
                'waktu_paraf' => now(),
            ]);

            $parafOrtu->bangunPagi->update([
                'tanda_tangan_ortu' => null,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Verifikasi paraf berhasil dibatalkan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error batalkanVerifikasi: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat(Request $request)
    {
        try {
            $search = $request->get('search');
            $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            
            $query = ParafOrtuBangunPagi::with([
                'bangunPagi.siswa.kelas',
                'bangunPagi.siswa.orangtua'
            ])
                ->whereBetween('waktu_paraf', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                ->latest();

            // Filter berdasarkan pencarian
            if ($search) {
                $query->whereHas('bangunPagi.siswa', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                })
                ->orWhere('nama_ortu', 'like', "%{$search}%");
            }

            $parafOrtu = $query->paginate(20);

            return view('paraf-ortu-bangun-pagi.riwayat', [
                'parafOrtu' => $parafOrtu,
                'search' => $search,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
        } catch (\Exception $e) {
            Log::error('Error riwayat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        try {
            $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $kelasId = $request->get('kelas_id');

            $query = ParafOrtuBangunPagi::with([
                'bangunPagi.siswa.kelas',
                'bangunPagi.siswa.orangtua'
            ])
            ->whereBetween('waktu_paraf', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'terverifikasi');

            if ($kelasId) {
                $query->whereHas('bangunPagi.siswa', function($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            }

            $parafOrtu = $query->get();

            $statistik = [
                'total_paraf' => $parafOrtu->count(),
                'total_siswa' => $parafOrtu->unique('bangunPagi.siswa_id')->count(),
                'paraf_hari_ini' => $parafOrtu->where('waktu_paraf', '>=', Carbon::today())->count(),
            ];

            return view('paraf-ortu-bangun-pagi.laporan', [
                'parafOrtu' => $parafOrtu,
                'statistik' => $statistik,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'kelasId' => $kelasId,
            ]);
        } catch (\Exception $e) {
            Log::error('Error laporan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}