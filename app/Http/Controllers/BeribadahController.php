<?php

namespace App\Http\Controllers;

use App\Models\Beribadah;
use App\Models\Siswa;
use App\Models\PengaturanWaktuBeribadah;
use App\Models\ParafOrtuBeribadah;
use App\Models\Orangtua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BeribadahController extends Controller
{
    protected $bulanList = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    /**
     * Display a listing of the resource with checklist system
     */
    public function index(Request $request)
    {
        $selectedTanggal = $request->get('tanggal', date('Y-m-d'));
        $search = $request->get('search');
        
        // Cek apakah user login sebagai siswa
        $siswaId = null;
        if (Auth::check() && Auth::user()->isSiswa()) {
            // Jika login sebagai siswa, dapatkan siswa_id dari user
            $user = Auth::user();
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $siswaId = $siswa->id;
            }
        } elseif (Session::has('siswa_id')) {
            // Jika login via session (login siswa biasa)
            $siswaId = Session::get('siswa_id');
        }
        
        // Query siswa berdasarkan siapa yang mengakses
        if ($siswaId) {
            // Jika siswa yang login, hanya tampilkan data diri sendiri
            $query = Siswa::with(['orangtua'])
                ->where('id', $siswaId)
                ->orderBy('nama_lengkap', 'asc');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            $siswaList = $query->get();
            
            // Pastikan hanya 1 siswa yang ditampilkan
            if ($siswaList->count() === 0) {
                abort(404, 'Data siswa tidak ditemukan');
            }
            
            // Ambil siswa yang sedang login
            $currentSiswa = $siswaList->first();
            
        } else {
            // Jika admin/guru yang login, tampilkan semua siswa
            $query = Siswa::with(['orangtua'])->orderBy('nama_lengkap', 'asc');
            
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            $siswaList = $query->get();
            $currentSiswa = null;
        }
        
        // Ambil data beribadah untuk tanggal yang dipilih
        $beribadahs = Beribadah::with(['parafOrtu', 'siswa.orangtua'])
            ->whereDate('tanggal', $selectedTanggal)
            ->orderBy('created_at', 'desc')
            ->get()
            ->keyBy('siswa_id');
        
        // Ambil pengaturan waktu sholat
        $jenisSholat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $sholatLabels = [
            'subuh' => 'Subuh',
            'dzuhur' => 'Dzuhur',
            'ashar' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya'
        ];
        
        $pengaturan = PengaturanWaktuBeribadah::orderByRaw(
            "FIELD(jenis_sholat, 'subuh', 'dzuhur', 'ashar', 'maghrib', 'isya')"
        )->get()->keyBy('jenis_sholat');
        
        return view('beribadah.index', compact(
            'siswaList',
            'currentSiswa',
            'beribadahs',
            'selectedTanggal',
            'search',
            'pengaturan',
            'sholatLabels',
            'jenisSholat'
        ));
    }

    /**
     * Store checklist data dengan notifikasi ke orangtua
     */
    public function storeChecklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'sholat' => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
            'waktu' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $tanggal = Carbon::parse($request->tanggal);
            $today = Carbon::today();
            
            // Validasi: Hanya bisa checklist untuk hari ini
            if (!$tanggal->isSameDay($today)) {
                throw new \Exception('Checklist sholat hanya bisa diisi untuk hari ini.');
            }
            
            // Validasi: Cek apakah siswa yang login sesuai dengan yang dicentang
            $siswaId = null;
            if (Auth::check() && Auth::user()->isSiswa()) {
                $user = Auth::user();
                $siswa = Siswa::where('user_id', $user->id)->first();
                if ($siswa && $siswa->id != $request->siswa_id) {
                    throw new \Exception('Anda hanya bisa mengisi checklist untuk diri sendiri.');
                }
                $siswaId = $siswa->id;
            } elseif (Session::has('siswa_id')) {
                if (Session::get('siswa_id') != $request->siswa_id) {
                    throw new \Exception('Anda hanya bisa mengisi checklist untuk diri sendiri.');
                }
                $siswaId = Session::get('siswa_id');
            }
            
            // Cek apakah sudah ada data untuk siswa di tanggal tersebut
            $beribadah = Beribadah::where('siswa_id', $request->siswa_id)
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if (!$beribadah) {
                // Buat data baru
                $beribadah = Beribadah::create([
                    'siswa_id' => $request->siswa_id,
                    'tanggal' => $tanggal,
                    'bulan' => $this->bulanList[$tanggal->format('m')],
                    'tahun' => $tanggal->year,
                    'created_by' => Auth::check() ? Auth::id() : null,
                ]);
            } else {
                // Cek apakah sholat sudah dicentang sebelumnya
                if ($beribadah->{$request->sholat . '_waktu'}) {
                    throw new \Exception('Sholat ' . $request->sholat . ' sudah dicentang sebelumnya.');
                }
            }

            // Hitung nilai untuk sholat yang dipilih
            $hasil = Beribadah::hitungNilaiSholat($request->sholat, $request->waktu);
            
            // Update data sholat
            $beribadah->update([
                $request->sholat . '_waktu' => $request->waktu . ':00',
                $request->sholat . '_nilai' => $hasil['nilai'],
                $request->sholat . '_kategori' => $hasil['kategori'],
                'updated_by' => Auth::check() ? Auth::id() : null,
            ]);

            // Hitung ulang total
            $beribadah->hitungTotal();
            $beribadah->save();

            // Kirim notifikasi ke orangtua
            $notificationSent = $beribadah->sendParafNotificationToOrangtua($request->sholat);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checklist ibadah berhasil disimpan!' . ($notificationSent ? ' Notifikasi telah dikirim ke orangtua.' : ''),
                'data' => [
                    'id' => $beribadah->id,
                    'nilai' => $hasil['nilai'],
                    'kategori' => $hasil['kategori'],
                    'waktu_formatted' => Carbon::parse($request->waktu)->format('H:i'),
                    'total_nilai' => $beribadah->total_nilai,
                    'total_sholat' => $beribadah->total_sholat,
                    'paraf' => $beribadah->{$request->sholat . '_paraf'},
                    'need_paraf' => true,
                    'notification_sent' => $notificationSent
                ]
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
     * Hapus checklist sholat
     */
    public function deleteChecklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'sholat' => 'required|in:subuh,dzuhur,ashar,maghrib,isya'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid'
            ], 422);
        }

        try {
            // Validasi: Cek apakah siswa yang login sesuai
            $siswaId = null;
            if (Auth::check() && Auth::user()->isSiswa()) {
                $user = Auth::user();
                $siswa = Siswa::where('user_id', $user->id)->first();
                if ($siswa && $siswa->id != $request->siswa_id) {
                    throw new \Exception('Anda hanya bisa menghapus checklist untuk diri sendiri.');
                }
            } elseif (Session::has('siswa_id')) {
                if (Session::get('siswa_id') != $request->siswa_id) {
                    throw new \Exception('Anda hanya bisa menghapus checklist untuk diri sendiri.');
                }
            }
            
            // Cari data beribadah berdasarkan siswa_id dan tanggal
            $beribadah = Beribadah::where('siswa_id', $request->siswa_id)
                ->whereDate('tanggal', $request->tanggal)
                ->first();

            if (!$beribadah) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            // Batalkan paraf jika sudah ada
            if ($beribadah->isSholatParafed($request->sholat)) {
                $beribadah->batalkanParaf($request->sholat);
            }

            // Set kolom yang sesuai menjadi null
            $beribadah->update([
                $request->sholat . '_waktu' => null,
                $request->sholat . '_nilai' => 0,
                $request->sholat . '_kategori' => null,
                $request->sholat . '_paraf' => false,
                $request->sholat . '_paraf_nama' => null,
                $request->sholat . '_paraf_waktu' => null,
                'updated_by' => Auth::check() ? Auth::id() : null,
            ]);

            // Recalculate total
            $beribadah->hitungTotal();
            $beribadah->save();

            return response()->json([
                'success' => true,
                'message' => 'Checklist sholat berhasil dihapus',
                'data' => $beribadah
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman khusus orangtua untuk memparaf sholat anak
     */
    public function parafOrtuView(Request $request, $beribadahId, $token = null)
    {
        try {
            $beribadah = Beribadah::with(['siswa', 'orangtua'])->findOrFail($beribadahId);
            
            // Validasi token jika ada
            if ($token) {
                $cachedToken = \Cache::get("paraf_token_{$beribadahId}");
                if ($token !== $cachedToken) {
                    return view('errors.403')->with('message', 'Token tidak valid atau sudah kadaluarsa.');
                }
            }
            
            // Ambil sholat yang belum diparaf
            $sholatBelumParaf = $beribadah->getUnparafedSholatList();
            
            // Ambil sholat yang sudah diparaf
            $sholatSudahParaf = $beribadah->getParafedSholatList();
            
            // Ambil data orangtua
            $orangtua = $beribadah->orangtua;
            
            if (!$orangtua) {
                return view('errors.404')->with('message', 'Data orangtua tidak ditemukan.');
            }
            
            return view('beribadah.paraf-ortu', compact(
                'beribadah',
                'orangtua',
                'sholatBelumParaf',
                'sholatSudahParaf'
            ));
            
        } catch (\Exception $e) {
            return view('errors.404')->with('message', 'Data tidak ditemukan.');
        }
    }

    /**
     * Proses paraf dari orangtua
     */
    public function prosesParafOrtu(Request $request, $beribadahId)
    {
        $validator = Validator::make($request->all(), [
            'jenis_sholat' => 'required|array',
            'jenis_sholat.*' => 'in:subuh,dzuhur,ashar,maghrib,isya',
            'nama_ortu' => 'required|string|max:100',
            'ortu_id' => 'required|exists:orangtua,id',
            'catatan' => 'nullable|string|max:500',
            'tanda_tangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $beribadah = Beribadah::findOrFail($beribadahId);
            $orangtua = Orangtua::findOrFail($request->ortu_id);
            
            // Verifikasi bahwa orangtua adalah orangtua dari siswa tersebut
            if ($orangtua->siswa_id !== $beribadah->siswa_id) {
                throw new \Exception('Orangtua tidak terdaftar untuk siswa ini.');
            }
            
            $results = [];
            $parafList = [];
            
            foreach ($request->jenis_sholat as $jenisSholat) {
                // Cek apakah sholat sudah dilaksanakan
                if (!$beribadah->{$jenisSholat . '_waktu'}) {
                    $results[$jenisSholat] = [
                        'success' => false,
                        'message' => 'Sholat belum dilaksanakan'
                    ];
                    continue;
                }
                
                // Cek apakah sudah ada paraf
                if ($beribadah->isSholatParafed($jenisSholat)) {
                    $results[$jenisSholat] = [
                        'success' => false,
                        'message' => 'Sudah diparaf sebelumnya'
                    ];
                    continue;
                }
                
                // Buat paraf
                $paraf = ParafOrtuBeribadah::create([
                    'beribadah_id' => $beribadahId,
                    'siswa_id' => $beribadah->siswa_id,
                    'ortu_id' => $orangtua->id,
                    'jenis_sholat' => $jenisSholat,
                    'nama_ortu' => $request->nama_ortu,
                    'tanda_tangan' => $request->tanda_tangan ?? ($orangtua->tanda_tangan_ayah ?? $orangtua->tanda_tangan_ibu),
                    'waktu_paraf' => now(),
                    'catatan' => $request->catatan,
                    'status' => 'terverifikasi',
                ]);
                
                // Update flag paraf di tabel beribadahs
                $beribadah->update([
                    $jenisSholat . '_paraf' => true,
                    $jenisSholat . '_paraf_nama' => $request->nama_ortu,
                    $jenisSholat . '_paraf_waktu' => now(),
                ]);
                
                $parafList[] = $paraf;
                $results[$jenisSholat] = [
                    'success' => true,
                    'message' => 'Berhasil diparaf',
                    'paraf_id' => $paraf->id
                ];
            }
            
            // Update status paraf keseluruhan
            $beribadah->updateParafStatus();
            $beribadah->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Paraf berhasil diberikan',
                'results' => $results,
                'data' => [
                    'beribadah' => $beribadah,
                    'paraf_list' => $parafList
                ]
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
     * Get sholat yang perlu paraf untuk orangtua
     */
    public function getSholatNeedParaf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal'
            ], 422);
        }

        try {
            $query = Beribadah::with(['siswa'])
                ->where('siswa_id', $request->siswa_id)
                ->where(function($q) {
                    foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                        $q->orWhere(function($q2) use ($sholat) {
                            $q2->whereNotNull($sholat . '_waktu')
                               ->where($sholat . '_paraf', false);
                        });
                    }
                });

            if ($request->tanggal) {
                $query->whereDate('tanggal', $request->tanggal);
            } else {
                $query->whereDate('tanggal', '>=', Carbon::today()->subDays(7));
            }

            $beribadahs = $query->orderBy('tanggal', 'desc')->get();

            $result = [];
            foreach ($beribadahs as $beribadah) {
                $sholatNeedParaf = $beribadah->getUnparafedSholatList();
                if (!empty($sholatNeedParaf)) {
                    $result[] = [
                        'beribadah_id' => $beribadah->id,
                        'tanggal' => $beribadah->tanggal_formatted,
                        'hari' => $beribadah->nama_hari,
                        'sholat_need_paraf' => $sholatNeedParaf,
                        'total_need_paraf' => count($sholatNeedParaf)
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update waktu checklist
     */
    public function updateWaktuChecklist(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sholat' => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
            'waktu' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid'
            ], 422);
        }

        try {
            $beribadah = Beribadah::findOrFail($id);
            $sholat = $request->sholat;

            // Cek apakah sholat sudah diparaf
            if ($beribadah->isSholatParafed($sholat)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sholat sudah diparaf, tidak bisa diubah'
                ], 422);
            }

            // Hitung nilai baru
            $hasil = Beribadah::hitungNilaiSholat($sholat, $request->waktu);
            
            // Update data sholat
            $beribadah->update([
                $sholat . '_waktu' => $request->waktu . ':00',
                $sholat . '_nilai' => $hasil['nilai'],
                $sholat . '_kategori' => $hasil['kategori'],
                'updated_by' => Auth::check() ? Auth::id() : null,
            ]);

            // Hitung ulang total
            $beribadah->hitungTotal();
            $beribadah->save();

            return response()->json([
                'success' => true,
                'message' => 'Waktu ibadah berhasil diperbarui!',
                'data' => [
                    'nilai' => $hasil['nilai'],
                    'kategori' => $hasil['kategori'],
                    'waktu_formatted' => Carbon::parse($request->waktu)->format('H:i'),
                    'total_nilai' => $beribadah->total_nilai,
                    'total_sholat' => $beribadah->total_sholat,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus checklist sholat
     */
    public function hapusChecklist(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sholat' => 'required|in:subuh,dzuhur,ashar,maghrib,isya',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid'
            ], 422);
        }

        try {
            $beribadah = Beribadah::findOrFail($id);
            $sholat = $request->sholat;

            // Batalkan paraf jika sudah ada
            if ($beribadah->isSholatParafed($sholat)) {
                $beribadah->batalkanParaf($sholat);
            }

            // Reset data sholat
            $beribadah->update([
                $sholat . '_waktu' => null,
                $sholat . '_nilai' => 0,
                $sholat . '_kategori' => null,
                $sholat . '_paraf' => false,
                $sholat . '_paraf_nama' => null,
                $sholat . '_paraf_waktu' => null,
                'updated_by' => Auth::check() ? Auth::id() : null,
            ]);

            // Hitung ulang total
            $beribadah->hitungTotal();
            $beribadah->save();

            return response()->json([
                'success' => true,
                'message' => 'Checklist ibadah berhasil dihapus!',
                'data' => [
                    'total_nilai' => $beribadah->total_nilai,
                    'total_sholat' => $beribadah->total_sholat,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paraf semua sholat untuk siswa
     */
    public function parafSemua(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_ortu' => 'required|string|max:100',
            'ortu_id' => 'required|exists:orangtua,id',
        ], [
            'nama_ortu.required' => 'Nama orang tua harus diisi',
            'ortu_id.required' => 'Orang tua harus dipilih',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $beribadah = Beribadah::findOrFail($id);
            $orangtua = Orangtua::findOrFail($request->ortu_id);
            
            // Verifikasi bahwa orangtua adalah orangtua dari siswa tersebut
            if ($orangtua->siswa_id !== $beribadah->siswa_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orangtua tidak terdaftar untuk siswa ini'
                ], 422);
            }
            
            $results = [];
            foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                if ($beribadah->{$sholat . '_waktu'} && !$beribadah->isSholatParafed($sholat)) {
                    try {
                        $paraf = $beribadah->beriParaf($sholat, $request->nama_ortu, $orangtua->id);
                        $results[$sholat] = [
                            'success' => true,
                            'message' => "Berhasil diparaf"
                        ];
                    } catch (\Exception $e) {
                        $results[$sholat] = [
                            'success' => false,
                            'message' => $e->getMessage()
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Paraf orang tua berhasil disimpan!',
                'results' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan paraf: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Paraf Orang Tua untuk sholat tertentu
     */
    public function parafOrtuSingle(Request $request, $id, $sholat)
    {
        if (!in_array($sholat, Beribadah::JENIS_SHOLAT)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis sholat tidak valid.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'nama_ortu' => 'required|string|max:100',
            'ortu_id' => 'required|exists:orangtua,id',
            'catatan' => 'nullable|string|max:500',
        ], [
            'nama_ortu.required' => 'Nama orang tua harus diisi',
            'ortu_id.required' => 'Orang tua harus dipilih',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $beribadah = Beribadah::findOrFail($id);
            $orangtua = Orangtua::findOrFail($request->ortu_id);
            
            // Verifikasi bahwa orangtua adalah orangtua dari siswa tersebut
            if ($orangtua->siswa_id !== $beribadah->siswa_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orangtua tidak terdaftar untuk siswa ini'
                ], 422);
            }
            
            // Beri paraf
            $paraf = $beribadah->beriParaf(
                $sholat, 
                $request->nama_ortu, 
                $orangtua->id, 
                $request->catatan
            );

            return response()->json([
                'success' => true,
                'message' => "Paraf orang tua untuk sholat {$sholat} berhasil disimpan!",
                'data' => $paraf
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan paraf: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Batalkan paraf
     */
    public function batalParaf($id, $sholat)
    {
        if (!in_array($sholat, Beribadah::JENIS_SHOLAT)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis sholat tidak valid.'
            ], 422);
        }

        try {
            $beribadah = Beribadah::findOrFail($id);
            
            $success = $beribadah->batalkanParaf($sholat);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => "Paraf orang tua untuk sholat {$sholat} berhasil dibatalkan!"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Paraf untuk sholat {$sholat} tidak ditemukan"
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan paraf: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard orangtua - lihat checklist anak yang perlu paraf
     */
    public function dashboardOrangtua(Request $request)
    {
        // Untuk akses ini, orangtua perlu login atau menggunakan token
        $ortuId = $request->get('ortu_id');
        $token = $request->get('token');
        
        if (!$ortuId && !$token) {
            // Jika tidak ada parameter, redirect ke login
            return redirect()->route('login');
        }
        
        try {
            if ($ortuId) {
                $orangtua = Orangtua::with(['siswa'])->findOrFail($ortuId);
            } elseif ($token) {
                // Decode token untuk mendapatkan ortu_id
                $decoded = decrypt($token);
                $orangtua = Orangtua::with(['siswa'])->findOrFail($decoded['ortu_id']);
            }
            
            // Ambil data beribadah yang perlu paraf
            $beribadahs = Beribadah::with(['parafOrtu'])
                ->where('siswa_id', $orangtua->siswa_id)
                ->where(function($q) {
                    foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                        $q->orWhere(function($q2) use ($sholat) {
                            $q2->whereNotNull($sholat . '_waktu')
                               ->where($sholat . '_paraf', false);
                        });
                    }
                })
                ->whereDate('tanggal', '>=', Carbon::today()->subDays(7))
                ->orderBy('tanggal', 'desc')
                ->get();
            
            // Kelompokkan berdasarkan tanggal
            $groupedBeribadahs = [];
            foreach ($beribadahs as $beribadah) {
                $tanggal = $beribadah->tanggal->format('Y-m-d');
                if (!isset($groupedBeribadahs[$tanggal])) {
                    $groupedBeribadahs[$tanggal] = [
                        'tanggal' => $beribadah->tanggal_formatted,
                        'hari' => $beribadah->nama_hari,
                        'beribadahs' => []
                    ];
                }
                
                $sholatNeedParaf = $beribadah->getUnparafedSholatList();
                if (!empty($sholatNeedParaf)) {
                    $groupedBeribadahs[$tanggal]['beribadahs'][] = [
                        'id' => $beribadah->id,
                        'sholat_need_paraf' => $sholatNeedParaf
                    ];
                }
            }
            
            return view('beribadah.dashboard-ortu', compact(
                'orangtua',
                'groupedBeribadahs'
            ));
            
        } catch (\Exception $e) {
            return view('errors.404')->with('message', 'Data tidak ditemukan.');
        }
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $siswaList = Siswa::orderBy('nama_lengkap')->get();
        $pengaturan = PengaturanWaktuBeribadah::getOrCreateDefaults();

        return view('beribadah.create', [
            'siswaList' => $siswaList,
            'pengaturan' => $pengaturan,
            'bulanList' => $this->bulanList,
            'sholatLabels' => Beribadah::SHOLAT_LABELS,
            'jenisSholat' => Beribadah::JENIS_SHOLAT,
            'isSiswa' => false,
        ]);
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
        ], [
            'siswa_id.required' => 'Siswa harus dipilih',
            'tanggal.required' => 'Tanggal harus diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $existing = Beribadah::where('siswa_id', $request->siswa_id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return back()->with('error', 'Data ibadah untuk siswa ini pada tanggal tersebut sudah ada.')
                        ->withInput();
        }

        try {
            $tanggal = Carbon::parse($request->tanggal);
            
            $data = [
                'siswa_id' => $request->siswa_id,
                'tanggal' => $tanggal,
                'bulan' => $this->bulanList[$tanggal->format('m')],
                'tahun' => $tanggal->year,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::check() ? Auth::id() : null,
            ];

            foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                $waktuField = $sholat . '_waktu';
                if ($request->filled($waktuField)) {
                    $waktu = $request->$waktuField;
                    $hasil = Beribadah::hitungNilaiSholat($sholat, $waktu);
                    
                    $data[$sholat . '_waktu'] = $waktu . ':00';
                    $data[$sholat . '_nilai'] = $hasil['nilai'];
                    $data[$sholat . '_kategori'] = $hasil['kategori'];
                }
            }

            $beribadah = Beribadah::create($data);
            $beribadah->hitungTotal();
            $beribadah->save();

            // Kirim notifikasi untuk sholat yang dicentang
            foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                if ($request->filled($sholat . '_waktu')) {
                    $beribadah->sendParafNotificationToOrangtua($sholat);
                }
            }

            return redirect()->route('beribadah.index')
                           ->with('success', 'Data ibadah berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $beribadah = Beribadah::with(['siswa', 'createdBy', 'updatedBy', 'parafOrtu.orangtua'])->findOrFail($id);
        $pengaturan = PengaturanWaktuBeribadah::getOrCreateDefaults()->keyBy('jenis_sholat');

        return view('beribadah.show', [
            'beribadah' => $beribadah,
            'pengaturan' => $pengaturan,
            'sholatLabels' => Beribadah::SHOLAT_LABELS,
            'jenisSholat' => Beribadah::JENIS_SHOLAT,
        ]);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $beribadah = Beribadah::with('siswa')->findOrFail($id);
        $siswaList = Siswa::orderBy('nama_lengkap')->get();
        $pengaturan = PengaturanWaktuBeribadah::getOrCreateDefaults()->keyBy('jenis_sholat');

        return view('beribadah.edit', [
            'beribadah' => $beribadah,
            'siswaList' => $siswaList,
            'pengaturan' => $pengaturan,
            'bulanList' => $this->bulanList,
            'sholatLabels' => Beribadah::SHOLAT_LABELS,
            'jenisSholat' => Beribadah::JENIS_SHOLAT,
        ]);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $beribadah = Beribadah::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $existing = Beribadah::where('siswa_id', $beribadah->siswa_id)
            ->whereDate('tanggal', $request->tanggal)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Data ibadah untuk tanggal tersebut sudah ada.')
                        ->withInput();
        }

        try {
            $tanggal = Carbon::parse($request->tanggal);
            
            $data = [
                'tanggal' => $tanggal,
                'bulan' => $this->bulanList[$tanggal->format('m')],
                'tahun' => $tanggal->year,
                'keterangan' => $request->keterangan,
            ];

            if (Auth::check()) {
                $data['updated_by'] = Auth::id();
            }

            foreach (Beribadah::JENIS_SHOLAT as $sholat) {
                $waktuField = $sholat . '_waktu';
                if ($request->filled($waktuField)) {
                    $waktu = $request->$waktuField;
                    $hasil = Beribadah::hitungNilaiSholat($sholat, $waktu);
                    
                    $data[$sholat . '_waktu'] = $waktu . ':00';
                    $data[$sholat . '_nilai'] = $hasil['nilai'];
                    $data[$sholat . '_kategori'] = $hasil['kategori'];
                    
                    // Jika sebelumnya kosong, kirim notifikasi
                    if (!$beribadah->$waktuField) {
                        $beribadah->sendParafNotificationToOrangtua($sholat);
                    }
                } else {
                    $data[$sholat . '_waktu'] = null;
                    $data[$sholat . '_nilai'] = 0;
                    $data[$sholat . '_kategori'] = null;
                }
            }

            $beribadah->update($data);
            $beribadah->hitungTotal();
            $beribadah->save();

            return redirect()->route('beribadah.index')
                           ->with('success', 'Data ibadah berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        $beribadah = Beribadah::findOrFail($id);

        try {
            $beribadah->delete();
            
            return redirect()->route('beribadah.index')
                           ->with('success', 'Data ibadah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('beribadah.index')
                           ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Display laporan for specific siswa
     */
    public function laporanSiswa(Request $request, $siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        $records = Beribadah::with(['parafOrtu'])
            ->where('siswa_id', $siswaId)
            ->where('bulan', $this->bulanList[$bulan] ?? $bulan)
            ->where('tahun', $tahun)
            ->orderBy('tanggal')
            ->get();

        $statistik = $this->getStatistikBulanan($siswaId, $this->bulanList[$bulan] ?? $bulan, $tahun);

        return view('beribadah.laporan-siswa', [
            'siswa' => $siswa,
            'records' => $records,
            'statistik' => $statistik,
            'bulanList' => $this->bulanList,
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
            'sholatLabels' => Beribadah::SHOLAT_LABELS,
            'jenisSholat' => Beribadah::JENIS_SHOLAT,
        ]);
    }

    /**
     * Get statistik bulanan
     */
    private function getStatistikBulanan($siswaId, $bulan, $tahun)
    {
        $records = Beribadah::where('siswa_id', $siswaId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $totalSholat = 0;
        $totalNilai = 0;
        $totalParaf = 0;
        $totalNeedParaf = 0;

        foreach ($records as $record) {
            $totalSholat += $record->total_sholat;
            $totalNilai += $record->total_nilai;
            
            $parafCount = $record->getParafCount();
            $totalParaf += $parafCount['parafed'];
            $totalNeedParaf += $parafCount['unparafed'];
        }

        $days = count($records);
        $avgNilai = $days > 0 ? round($totalNilai / $days, 2) : 0;

        return [
            'total_days' => $days,
            'total_sholat' => $totalSholat,
            'total_nilai' => $totalNilai,
            'avg_nilai' => $avgNilai,
            'total_paraf' => $totalParaf,
            'total_need_paraf' => $totalNeedParaf,
            'paraf_percentage' => $totalSholat > 0 ? round(($totalParaf / $totalSholat) * 100) : 0
        ];
    }
}