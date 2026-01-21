<?php

namespace App\Http\Controllers\Orangtua;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\Bermasyarakat;
use App\Models\Beribadah;
use App\Models\BangunPagi;
use Carbon\Carbon;

class OrangtuaDashboardController extends Controller
{
    public function index()
    {
        if (!Session::has('orangtua_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $orangtua = Orangtua::with(['siswa.kelas', 'siswa.kelas.guruWali'])
            ->find(Session::get('orangtua_id'));

        if (!$orangtua) {
            Session::flush();
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Data orangtua tidak ditemukan']);
        }

        $siswaId = $orangtua->siswa_id;

        // ========== BERMASYARAKAT ==========
        $bermasyarakatMenunggu = Bermasyarakat::where('siswa_id', $siswaId)
            ->where(function($q) {
                $q->where('sudah_ttd_ortu', false)
                  ->orWhere('sudah_ttd_ortu', 0)
                  ->orWhereNull('sudah_ttd_ortu');
            })
            ->count();
        
        $bermasyarakatSudah = Bermasyarakat::where('siswa_id', $siswaId)
            ->where('sudah_ttd_ortu', true)
            ->count();

        $bermasyarakatBelumParaf = Bermasyarakat::where('siswa_id', $siswaId)
            ->where(function($q) {
                $q->where('sudah_ttd_ortu', false)
                  ->orWhere('sudah_ttd_ortu', 0)
                  ->orWhereNull('sudah_ttd_ortu');
            })
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function($item) {
                $item->tanggal_formatted = Carbon::parse($item->tanggal)->translatedFormat('d M Y');
                return $item;
            });

        $bermasyarakatBulanIni = Bermasyarakat::where('siswa_id', $siswaId)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

        // ========== BANGUN PAGI ==========
        $bangunPagiMenunggu = BangunPagi::where('siswa_id', $siswaId)
            ->where(function($q) {
                $q->where('sudah_ttd_ortu', false)
                  ->orWhere('sudah_ttd_ortu', 0)
                  ->orWhereNull('sudah_ttd_ortu');
            })
            ->count();
        
        $bangunPagiSudah = BangunPagi::where('siswa_id', $siswaId)
            ->where('sudah_ttd_ortu', true)
            ->count();

        $bangunPagiBelumParaf = BangunPagi::where('siswa_id', $siswaId)
            ->where(function($q) {
                $q->where('sudah_ttd_ortu', false)
                  ->orWhere('sudah_ttd_ortu', 0)
                  ->orWhereNull('sudah_ttd_ortu');
            })
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function($item) {
                $item->tanggal_formatted = Carbon::parse($item->tanggal)->translatedFormat('d M Y');
                $item->pukul_formatted = $item->pukul ? Carbon::parse($item->pukul)->format('H:i') : '-';
                return $item;
            });

        $bangunPagiBulanIni = BangunPagi::where('siswa_id', $siswaId)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

        $todayData = BangunPagi::where('siswa_id', $siswaId)
            ->whereDate('tanggal', today())
            ->first();

        // ========== BERIBADAH - PERBAIKAN LENGKAP ==========
        $beribadahData = $this->getBeribadahNotifikasi($siswaId);
        
        $beribadahMenunggu = $beribadahData['menunggu'];
        $beribadahSudah = $beribadahData['sudah'];
        $beribadahBelumParaf = $beribadahData['belum_paraf'];
        $debugInfo = $beribadahData['debug'] ?? [];

        $beribadahBulanIni = Beribadah::where('siswa_id', $siswaId)
            ->whereMonth('tanggal', date('m'))
            ->whereYear('tanggal', date('Y'))
            ->count();

        $totalNotifikasi = $bermasyarakatMenunggu + $beribadahMenunggu + $bangunPagiMenunggu;

        $tanggapanTotal = 0;
        $tanggapanSudahTTD = 0;
        $tanggapanBelumTTD = 0;

        $chartData = $this->getChartData($siswaId);

        return view('dashboard.orangtua', compact(
            'orangtua',
            'bermasyarakatMenunggu',
            'bermasyarakatSudah',
            'bermasyarakatBelumParaf',
            'bermasyarakatBulanIni',
            'beribadahMenunggu',
            'beribadahSudah',
            'beribadahBelumParaf',
            'beribadahBulanIni',
            'bangunPagiMenunggu',
            'bangunPagiSudah',
            'bangunPagiBelumParaf',
            'bangunPagiBulanIni',
            'todayData',
            'tanggapanTotal',
            'tanggapanSudahTTD',
            'tanggapanBelumTTD',
            'totalNotifikasi',
            'chartData',
            'debugInfo'
        ));
    }

    /**
     * PERBAIKAN LENGKAP: Hitung notifikasi beribadah
     * 
     * Menggunakan Eloquent Model dengan casting boolean yang benar
     * Cek langsung kolom xxx_paraf dari tabel beribadahs
     */
    private function getBeribadahNotifikasi($siswaId)
    {
        $sholatLabels = [
            'subuh' => 'Subuh',
            'dzuhur' => 'Dzuhur', 
            'ashar' => 'Ashar',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya'
        ];

        $jenisSholat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        
        $menunggu = 0;
        $sudah = 0;
        $belumParaf = [];
        $debug = [];

        try {
            // Cek apakah tabel ada
            if (!Schema::hasTable('beribadahs')) {
                $debug['error'] = 'Tabel beribadahs tidak ada';
                return [
                    'menunggu' => 0, 
                    'sudah' => 0, 
                    'belum_paraf' => collect([]), 
                    'debug' => $debug
                ];
            }

            // PENTING: Gunakan Eloquent Model untuk memanfaatkan casting boolean
            $dataBeribadah = Beribadah::where('siswa_id', $siswaId)
                ->orderBy('tanggal', 'desc')
                ->limit(30)
                ->get();

            $debug['total_rows'] = $dataBeribadah->count();
            $debug['siswa_id'] = $siswaId;
            $debug['query_method'] = 'Eloquent Model';

            if ($dataBeribadah->count() == 0) {
                $debug['message'] = 'Tidak ada data beribadah untuk siswa ini';
                return [
                    'menunggu' => 0, 
                    'sudah' => 0, 
                    'belum_paraf' => collect([]), 
                    'debug' => $debug
                ];
            }

            // Sample rows untuk debugging
            $debug['sample_rows'] = [];

            foreach ($dataBeribadah as $index => $row) {
                $sholatBelumParafHariIni = [];
                $rowDebug = [
                    'id' => $row->id,
                    'tanggal' => $row->tanggal ? $row->tanggal->format('Y-m-d') : null,
                ];
                
                foreach ($jenisSholat as $sholat) {
                    $waktuCol = $sholat . '_waktu';
                    $parafCol = $sholat . '_paraf';
                    
                    $waktuValue = $row->$waktuCol;
                    $parafValue = $row->$parafCol;
                    
                    // Debug: simpan sample data
                    if ($index < 3) {
                        $rowDebug[$sholat] = [
                            'waktu' => $waktuValue,
                            'paraf' => $parafValue,
                            'paraf_type' => gettype($parafValue),
                        ];
                    }
                    
                    // Cek apakah ada waktu sholat (sudah dilaksanakan)
                    $adaWaktu = $this->cekAdaWaktuSholat($waktuValue);
                    
                    if ($adaWaktu) {
                        // PERBAIKAN UTAMA: 
                        // Model Eloquent dengan casting 'xxx_paraf' => 'boolean'
                        // Nilai 0/null dari database menjadi false, nilai 1/true menjadi true
                        $sudahParaf = (bool) $parafValue;
                        
                        if ($sudahParaf) {
                            $sudah++;
                        } else {
                            $menunggu++;
                            $sholatBelumParafHariIni[] = [
                                'jenis' => $sholat,
                                'label' => $sholatLabels[$sholat],
                                'waktu' => $this->formatWaktu($waktuValue)
                            ];
                        }
                    }
                }
                
                // Simpan sample untuk debugging
                if ($index < 3) {
                    $debug['sample_rows'][] = $rowDebug;
                }
                
                // Tambahkan ke daftar jika ada sholat yang belum diparaf
                if (count($sholatBelumParafHariIni) > 0) {
                    $belumParaf[] = [
                        'beribadah_id' => $row->id,
                        'tanggal' => $row->tanggal ? Carbon::parse($row->tanggal)->translatedFormat('d M Y') : '-',
                        'tanggal_raw' => $row->tanggal ? $row->tanggal->format('Y-m-d') : null,
                        'hari' => $row->tanggal ? Carbon::parse($row->tanggal)->translatedFormat('l') : '-',
                        'sholat_list' => $sholatBelumParafHariIni,
                        'count' => count($sholatBelumParafHariIni)
                    ];
                }
            }

            // Batasi 10 hari terakhir untuk tampilan
            $belumParaf = array_slice($belumParaf, 0, 10);
            
            $debug['hasil_menunggu'] = $menunggu;
            $debug['hasil_sudah'] = $sudah;
            $debug['belum_paraf_count'] = count($belumParaf);

        } catch (\Exception $e) {
            Log::error('Error getBeribadahNotifikasi: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            $debug['exception'] = $e->getMessage();
        }

        return [
            'menunggu' => $menunggu,
            'sudah' => $sudah,
            'belum_paraf' => collect($belumParaf),
            'debug' => $debug
        ];
    }

    /**
     * Cek apakah ada waktu sholat - handle berbagai format
     */
    private function cekAdaWaktuSholat($waktuValue)
    {
        if ($waktuValue === null) return false;
        if ($waktuValue === '') return false;
        if (is_string($waktuValue) && trim($waktuValue) === '') return false;
        if ($waktuValue === '00:00:00') return false;
        if ($waktuValue === '00:00') return false;
        if ($waktuValue === 0) return false;
        if ($waktuValue === '0') return false;
        if ($waktuValue === false) return false;
        return true;
    }

    /**
     * Format waktu sholat
     */
    private function formatWaktu($waktu)
    {
        if (empty($waktu)) return '-';
        try {
            if (is_string($waktu) && preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', $waktu)) {
                return substr($waktu, 0, 5);
            }
            return Carbon::parse($waktu)->format('H:i');
        } catch (\Exception $e) {
            return substr((string)$waktu, 0, 5);
        }
    }

    /**
     * Get chart data untuk 7 hari terakhir
     */
    private function getChartData($siswaId)
    {
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->toDateString();
            
            $bangunPagi = BangunPagi::where('siswa_id', $siswaId)->whereDate('tanggal', $dateStr)->first();
            $bermasyarakat = Bermasyarakat::where('siswa_id', $siswaId)->whereDate('tanggal', $dateStr)->first();
            $beribadah = Beribadah::where('siswa_id', $siswaId)->whereDate('tanggal', $dateStr)->first();
            
            $chartData[] = [
                'day' => $date->translatedFormat('D'),
                'date' => $date->format('d/m'),
                'bangun_pagi' => $bangunPagi ? true : false,
                'bangun_paraf' => $bangunPagi ? (bool)($bangunPagi->sudah_ttd_ortu ?? false) : false,
                'bermasyarakat' => $bermasyarakat ? true : false,
                'bermasyarakat_paraf' => $bermasyarakat ? (bool)($bermasyarakat->sudah_ttd_ortu ?? false) : false,
                'beribadah' => $beribadah ? true : false,
                'beribadah_paraf' => $beribadah ? $this->cekSemuaSholatSudahParaf($beribadah) : false,
            ];
        }
        return $chartData;
    }

    /**
     * Cek apakah semua sholat yang ada sudah diparaf
     */
    private function cekSemuaSholatSudahParaf($beribadah)
    {
        $jenisSholat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $adaSholat = false;
        
        foreach ($jenisSholat as $sholat) {
            $waktuCol = $sholat . '_waktu';
            $parafCol = $sholat . '_paraf';
            
            $waktuValue = $beribadah->$waktuCol;
            
            if ($this->cekAdaWaktuSholat($waktuValue)) {
                $adaSholat = true;
                if (!$beribadah->$parafCol) {
                    return false;
                }
            }
        }
        
        return $adaSholat;
    }

    public function profile()
    {
        if (!Session::has('orangtua_id')) {
            return redirect()->route('siswa.login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }
        $orangtua = Orangtua::with(['siswa'])->find(Session::get('orangtua_id'));
        if (!$orangtua) {
            Session::flush();
            return redirect()->route('siswa.login')->withErrors(['error' => 'Data orangtua tidak ditemukan']);
        }
        return view('orangtua.profile', compact('orangtua'));
    }

    public function profilAnak()
    {
        if (!Session::has('orangtua_id')) {
            return redirect()->route('siswa.login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }
        $orangtua = Orangtua::with(['siswa.kelas', 'siswa.kelas.guruWali'])->find(Session::get('orangtua_id'));
        if (!$orangtua) {
            Session::flush();
            return redirect()->route('siswa.login')->withErrors(['error' => 'Data orangtua tidak ditemukan']);
        }
        return view('orangtua.profil-anak', compact('orangtua'));
    }

    public function logout(Request $request)
    {
        Session::forget(['orangtua_id', 'orangtua_nama', 'siswa_anak_id', 'siswa_anak_nama', 'siswa_anak_nis']);
        return redirect()->route('siswa.login')->with('success', 'Anda telah berhasil logout');
    }
}