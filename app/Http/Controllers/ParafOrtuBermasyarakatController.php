<?php

namespace App\Http\Controllers;

use App\Models\ParafOrtuBermasyarakat;
use App\Models\Bermasyarakat;
use App\Models\Orangtua;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ParafOrtuBermasyarakatController extends Controller
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
            
            Log::info('Loading paraf bermasyarakat index', [
                'search' => $search,
                'tanggal' => $selectedTanggal,
                'status' => $statusFilter
            ]);

            // Query dengan eager loading
            $query = Bermasyarakat::with([
                'siswa' => function($q) {
                    $q->with(['kelas', 'orangtua']);
                },
                'parafOrtu'
            ]);
            
            // Filter berdasarkan user role
            if (Session::has('orangtua_id')) {
                // Orangtua hanya lihat data anaknya
                $orangtuaId = Session::get('orangtua_id');
                $siswa = Siswa::whereHas('orangtua', function($q) use ($orangtuaId) {
                    $q->where('id', $orangtuaId);
                })->first();
                
                if ($siswa) {
                    $query->where('siswa_id', $siswa->id);
                } else {
                    // Jika tidak ada siswa, return empty
                    return view('paraf-ortu-bermasyarakat.index', [
                        'bermasyarakatList' => collect()->paginate(20),
                        'search' => $search,
                        'selectedTanggal' => $selectedTanggal,
                        'statusFilter' => $statusFilter,
                        'totalData' => 0,
                        'sudahDiparaf' => 0,
                        'belumDiparaf' => 0,
                        'kelasList' => Kelas::orderBy('nama_kelas')->get(),
                    ])->with('info', 'Data siswa tidak ditemukan');
                }
            }
            
            $query->whereDate('tanggal', $selectedTanggal);

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
                })->orWhere('nama_kegiatan', 'like', "%{$search}%");
            }

            $bermasyarakatList = $query->orderBy('tanggal', 'desc')
                                       ->orderBy('created_at', 'desc')
                                       ->paginate(20);

            // Hitung statistik
            $statsQuery = Bermasyarakat::whereDate('tanggal', $selectedTanggal);
            
            if (Session::has('orangtua_id') && isset($siswa)) {
                $statsQuery->where('siswa_id', $siswa->id);
            }
            
            $totalData = (clone $statsQuery)->count();
            $sudahDiparaf = (clone $statsQuery)->where('sudah_ttd_ortu', true)->count();
            $belumDiparaf = (clone $statsQuery)->where('sudah_ttd_ortu', false)->count();

            // Ambil semua kelas untuk filter
            $kelasList = Kelas::orderBy('nama_kelas')->get();

            return view('paraf-ortu-bermasyarakat.index', [
                'bermasyarakatList' => $bermasyarakatList,
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
     * Store a newly created resource in storage (API/AJAX).
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'bermasyarakat_id' => 'required|exists:bermasyarakat,id',
                'nama_ortu' => 'required|string|max:255',
                'catatan' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            Log::info('Storing paraf bermasyarakat', $request->all());

            // Cek apakah sudah ada paraf untuk kegiatan ini
            $existingParaf = ParafOrtuBermasyarakat::where('bermasyarakat_id', $request->bermasyarakat_id)->first();
            
            if ($existingParaf) {
                Log::warning('Paraf already exists for bermasyarakat: ' . $request->bermasyarakat_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Data paraf untuk kegiatan ini sudah ada!'
                ], 400);
            }

            // Cari orangtua berdasarkan nama (optional)
            $ortu = null;
            if ($request->nama_ortu !== 'orangtua_lainnya') {
                $ortu = Orangtua::where('nama_ayah', $request->nama_ortu)
                    ->orWhere('nama_ibu', $request->nama_ortu)
                    ->first();
            }

            // Buat paraf ortu
            $parafOrtu = ParafOrtuBermasyarakat::create([
                'bermasyarakat_id' => $request->bermasyarakat_id,
                'nama_ortu' => $request->nama_ortu,
                'tanda_tangan' => 'Paraf Digital',
                'waktu_paraf' => now(),
                'catatan' => $request->catatan,
                'status' => 'terverifikasi',
                'ortu_id' => $ortu ? $ortu->id : null,
            ]);

            // Update status di bermasyarakat
            $bermasyarakat = Bermasyarakat::find($request->bermasyarakat_id);
            $bermasyarakat->update([
                'paraf_ortu' => true,
                'sudah_ttd_ortu' => true,
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('Paraf bermasyarakat created successfully', ['paraf_id' => $parafOrtu->id]);

            return response()->json([
                'success' => true,
                'message' => 'Paraf orang tua berhasil disimpan!',
                'paraf_id' => $parafOrtu->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing paraf bermasyarakat: ' . $e->getMessage());
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
            $parafOrtu = ParafOrtuBermasyarakat::with([
                'bermasyarakat.siswa.kelas',
                'bermasyarakat.parafOrtu',
                'ortu'
            ])->findOrFail($id);

            return view('paraf-ortu-bermasyarakat.show', [
                'parafOrtu' => $parafOrtu,
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing paraf bermasyarakat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Check if paraf exists for a bermasyarakat
     */
    public function checkParafExists($bermasyarakatId)
    {
        try {
            $exists = ParafOrtuBermasyarakat::where('bermasyarakat_id', $bermasyarakatId)->exists();
            $bermasyarakat = Bermasyarakat::with(['siswa', 'parafOrtu'])->find($bermasyarakatId);
            
            $parafId = null;
            if ($exists) {
                $paraf = ParafOrtuBermasyarakat::where('bermasyarakat_id', $bermasyarakatId)->first();
                $parafId = $paraf->id;
            }
            
            return response()->json([
                'success' => true,
                'exists' => $exists,
                'sudah_ttd_ortu' => $bermasyarakat ? $bermasyarakat->sudah_ttd_ortu : false,
                'paraf_id' => $parafId
            ]);
        } catch (\Exception $e) {
            Log::error('Error checkParafExists bermasyarakat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan detail bermasyarakat untuk modal paraf
     */
    public function getDetailForParaf($id)
    {
        try {
            Log::info('Fetching detail for paraf bermasyarakat ID: ' . $id);
            
            $bermasyarakat = Bermasyarakat::with([
                'siswa.kelas',
                'siswa.orangtua',
                'parafOrtu'
            ])->findOrFail($id);

            // Ambil data orangtua
            $orangtuaOptions = [];
            
            if ($bermasyarakat->siswa && $bermasyarakat->siswa->orangtua) {
                $ortu = $bermasyarakat->siswa->orangtua;
                
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
            }

            // Tambahkan opsi lainnya
            $orangtuaOptions[] = [
                'value' => 'orangtua_lainnya',
                'label' => 'Orang Tua/Wali Lainnya',
                'type' => 'lainnya'
            ];

            $data = [
                'id' => $bermasyarakat->id,
                'siswa_nama' => $bermasyarakat->siswa->nama_lengkap ?? '-',
                'siswa_nis' => $bermasyarakat->siswa->nis ?? '-',
                'siswa_kelas' => $bermasyarakat->siswa->kelas->nama_kelas ?? '-',
                'tanggal' => $bermasyarakat->tanggal_formatted,
                'hari' => $bermasyarakat->nama_hari,
                'nama_kegiatan' => $bermasyarakat->nama_kegiatan,
                'pesan_kesan' => $bermasyarakat->pesan_kesan,
                'thumbnail' => $bermasyarakat->thumbnail ? asset('storage/' . $bermasyarakat->thumbnail) : null,
                'status' => $bermasyarakat->status_label,
                'nilai' => $bermasyarakat->nilai,
                'sudah_diparaf' => $bermasyarakat->sudah_ttd_ortu,
                'paraf_exists' => $bermasyarakat->parafOrtu ? true : false,
                'orangtua_options' => $orangtuaOptions
            ];

            Log::info('Returning paraf bermasyarakat detail data', $data);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getDetailForParaf bermasyarakat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get statistik for AJAX update
     */
    public function getStatistik(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', date('Y-m-d'));
            
            $query = Bermasyarakat::whereDate('tanggal', $tanggal);
            
            // Filter by orangtua if logged in
            if (Session::has('orangtua_id')) {
                $orangtuaId = Session::get('orangtua_id');
                $siswa = Siswa::whereHas('orangtua', function($q) use ($orangtuaId) {
                    $q->where('id', $orangtuaId);
                })->first();
                
                if ($siswa) {
                    $query->where('siswa_id', $siswa->id);
                }
            }
            
            $totalData = (clone $query)->count();
            $sudahDiparaf = (clone $query)->where('sudah_ttd_ortu', true)->count();
            $belumDiparaf = (clone $query)->where('sudah_ttd_ortu', false)->count();
                
            return response()->json([
                'success' => true,
                'totalData' => $totalData,
                'sudahDiparaf' => $sudahDiparaf,
                'belumDiparaf' => $belumDiparaf
            ]);
        } catch (\Exception $e) {
            Log::error('Error getStatistik bermasyarakat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * Batalkan paraf
     */
    public function batalkanParaf($id)
    {
        try {
            DB::beginTransaction();

            $parafOrtu = ParafOrtuBermasyarakat::findOrFail($id);
            
            // Reset status di bermasyarakat
            $parafOrtu->bermasyarakat->update([
                'paraf_ortu' => false,
                'sudah_ttd_ortu' => false,
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
            Log::error('Error batalkanParaf bermasyarakat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list orangtua for dropdown
     */
    public function getOrangtuaList()
    {
        try {
            $orangtuaList = [];
            
            // Jika login sebagai orangtua, hanya ambil data orangtua tersebut
            if (Session::has('orangtua_id')) {
                $ortu = Orangtua::find(Session::get('orangtua_id'));
                
                if ($ortu) {
                    if ($ortu->nama_ayah) {
                        $orangtuaList[] = [
                            'value' => $ortu->nama_ayah,
                            'label' => $ortu->nama_ayah . ' (Ayah)'
                        ];
                    }
                    if ($ortu->nama_ibu) {
                        $orangtuaList[] = [
                            'value' => $ortu->nama_ibu,
                            'label' => $ortu->nama_ibu . ' (Ibu)'
                        ];
                    }
                }
            } else {
                // Ambil semua orangtua untuk admin/guru
                $allOrangtua = Orangtua::all();
                
                foreach ($allOrangtua as $ortu) {
                    if ($ortu->nama_ayah) {
                        $orangtuaList[] = [
                            'value' => $ortu->nama_ayah,
                            'label' => $ortu->nama_ayah . ' (Ayah)'
                        ];
                    }
                    if ($ortu->nama_ibu) {
                        $orangtuaList[] = [
                            'value' => $ortu->nama_ibu,
                            'label' => $ortu->nama_ibu . ' (Ibu)'
                        ];
                    }
                }
            }

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
}