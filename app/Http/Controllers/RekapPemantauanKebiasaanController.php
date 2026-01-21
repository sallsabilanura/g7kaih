<?php

namespace App\Http\Controllers;

use App\Models\RekapPemantauanKebiasaan;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RekapPemantauanKebiasaanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['kelas', 'bulan', 'tahun', 'nama']);
        $user = Auth::user();
        
        $query = RekapPemantauanKebiasaan::with(['siswa.kelas', 'siswa.orangtua']);
        
        if ($user->peran == 'guru_wali') {
            $kelasGuru = Kelas::where('guru_wali_id', $user->id)->first();
            if ($kelasGuru) {
                $query->where('kelas', $this->extractKelasLevel($kelasGuru->nama_kelas));
            }
        }
        
        $rekap = $query->filter($filters)
            ->orderBy('tahun', 'desc')
            ->orderByRaw("FIELD(bulan, 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember')")
            ->orderBy('nama_lengkap')
            ->paginate(20);
        
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $bulanOptions = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunOptions = range(2024, 2030);
        
        return view('rekap-pemantauan.index', compact('rekap', 'kelasOptions', 'bulanOptions', 'tahunOptions', 'filters'));
    }

    public function create()
    {
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $bulanOptions = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunOptions = range(2024, 2030);
        
        $user = Auth::user();
        $kelasGuru = Kelas::where('guru_wali_id', $user->id)->first();
        
        $siswaList = collect();
        if ($kelasGuru) {
            $siswaList = Siswa::with(['kelas.guruWali', 'orangtua'])
                ->where('kelas_id', $kelasGuru->id)
                ->orderBy('nama_lengkap')
                ->get();
        }
        
        return view('rekap-pemantauan.create', compact('kelasOptions', 'bulanOptions', 'tahunOptions', 'kelasGuru', 'siswaList', 'user'));
    }

    public function getSiswaDetail($siswaId, Request $request)
    {
        try {
            $siswa = Siswa::with(['kelas.guruWali', 'orangtua'])->findOrFail($siswaId);

            $bulan = $request->get('bulan', now()->format('F'));
            $tahun = $request->get('tahun', now()->year);
            $bulanIndonesia = $this->convertBulanToIndonesia($bulan);
            
            $nilaiKebiasaan = $this->hitungNilaiKebiasaan($siswa, $bulanIndonesia, $tahun);

            $data = [
                'id' => $siswa->id,
                'nama_lengkap' => $siswa->nama_lengkap,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                'tingkat_kelas' => $siswa->kelas ? $this->extractKelasLevel($siswa->kelas->nama_kelas) : null,
                'guru_kelas' => $siswa->kelas && $siswa->kelas->guruWali ? $siswa->kelas->guruWali->nama_lengkap : null,
                'orangtua' => null,
                'nilai_kebiasaan' => $nilaiKebiasaan,
            ];
            
            if ($siswa->orangtua) {
                $data['orangtua'] = [
                    'nama_ayah' => $siswa->orangtua->nama_ayah,
                    'nama_ibu' => $siswa->orangtua->nama_ibu,
                    'tanda_tangan_ayah' => $siswa->orangtua->tanda_tangan_ayah ? Storage::url($siswa->orangtua->tanda_tangan_ayah) : null,
                    'tanda_tangan_ibu' => $siswa->orangtua->tanda_tangan_ibu ? Storage::url($siswa->orangtua->tanda_tangan_ibu) : null,
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Gagal memuat data: ' . $e->getMessage()], 500);
        }
    }

    private function hitungNilaiKebiasaan($siswa, $bulan, $tahun)
    {
        $threshold = 85;
        $nilai = ['bangun_pagi' => 0, 'tidur_cepat' => 0, 'berolahraga' => 0, 'makan_sehat' => 0, 'beribadah' => 0, 'gemar_belajar' => 0, 'bermasyarakat' => 0];

        $relations = [
            'bangun_pagi' => 'bangunPagis',
            'tidur_cepat' => 'tidurCepats', 
            'berolahraga' => 'berolahraga',
            'makan_sehat' => 'makanSehat',
            'beribadah' => 'beribadah',
            'gemar_belajar' => 'gemarBelajar',
            'bermasyarakat' => 'bermasyarakat'
        ];

        foreach ($relations as $key => $relation) {
            try {
                if (method_exists($siswa, $relation)) {
                    $avg = $siswa->$relation()->where('bulan', $bulan)->whereYear('tanggal', $tahun)->avg('nilai');
                    $nilai[$key] = $avg ?? 0;
                }
            } catch (\Exception $e) {
                $nilai[$key] = 0;
            }
        }

        $result = [];
        foreach ($nilai as $key => $val) {
            $result[$key] = [
                'nilai' => round($val, 2),
                'status' => $val >= $threshold ? 'sudah_terbiasa' : 'belum_terbiasa'
            ];
        }
        return $result;
    }

    private function extractKelasLevel($namaKelas)
    {
        if (preg_match('/\d+/', $namaKelas, $matches)) return $matches[0];
        return '1';
    }

    private function convertBulanToIndonesia($bulan)
    {
        $map = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];
        return $map[$bulan] ?? $bulan;
    }

    public function show($id)
    {
        $rekap = RekapPemantauanKebiasaan::with(['siswa.orangtua', 'siswa.kelas'])->findOrFail($id);
        $kebiasaanList = [
            ['label' => 'Bangun Pagi', 'status' => $rekap->bangun_pagi_status, 'description' => 'Melatih kedisiplinan', 'icon' => 'fas fa-sun'],
            ['label' => 'Beribadah', 'status' => $rekap->beribadah_status, 'description' => 'Mendekatkan dengan Tuhan', 'icon' => 'fas fa-pray'],
            ['label' => 'Berolahraga', 'status' => $rekap->berolahraga_status, 'description' => 'Menjaga kesehatan', 'icon' => 'fas fa-running'],
            ['label' => 'Makan Sehat', 'status' => $rekap->makan_sehat_status, 'description' => 'Investasi kesehatan', 'icon' => 'fas fa-apple-alt'],
            ['label' => 'Gemar Belajar', 'status' => $rekap->gemar_belajar_status, 'description' => 'Mengembangkan diri', 'icon' => 'fas fa-book-reader'],
            ['label' => 'Bermasyarakat', 'status' => $rekap->bermasyarakat_status, 'description' => 'Gotong royong', 'icon' => 'fas fa-hands-helping'],
            ['label' => 'Tidur Cepat', 'status' => $rekap->tidur_cepat_status, 'description' => 'Memulihkan tubuh', 'icon' => 'fas fa-bed'],
        ];
        return view('rekap-pemantauan.show', compact('rekap', 'kebiasaanList'));
    }

    public function edit($id)
    {
        $rekap = RekapPemantauanKebiasaan::with(['siswa.orangtua'])->findOrFail($id);
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $bulanOptions = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunOptions = range(2024, 2030);
        
        $user = Auth::user();
        $kelasGuru = Kelas::where('guru_wali_id', $user->id)->first();
        $siswaList = $kelasGuru ? Siswa::with(['kelas.guruWali', 'orangtua'])->where('kelas_id', $kelasGuru->id)->orderBy('nama_lengkap')->get() : collect();
        
        return view('rekap-pemantauan.edit', compact('rekap', 'kelasOptions', 'bulanOptions', 'tahunOptions', 'kelasGuru', 'siswaList', 'user'));
    }

    public function store(Request $request)
    {
        // Debug untuk melihat data yang dikirim
        \Log::info('Data yang diterima:', $request->all());
        
        // Validasi dengan pesan error yang lebih jelas
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'nama_lengkap' => 'required|string|max:100',
            'kelas' => 'required|string|max:10', // Ubah validasi kelas
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2024|max:2030',
            'guru_kelas' => 'nullable|string|max:100',
            'orangtua_siswa' => 'nullable|string|max:100',
            'tanggal_persetujuan' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
            'bangun_pagi_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'beribadah_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'berolahraga_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'makan_sehat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'gemar_belajar_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'bermasyarakat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'tidur_cepat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
        ], [
            'siswa_id.required' => 'Siswa harus dipilih',
            'siswa_id.exists' => 'Siswa tidak ditemukan',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'kelas.required' => 'Kelas harus diisi',
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.in' => 'Bulan tidak valid',
            'tahun.required' => 'Tahun harus dipilih',
            'tahun.integer' => 'Tahun harus berupa angka',
            'tahun.min' => 'Tahun minimal 2024',
            'tahun.max' => 'Tahun maksimal 2030',
            'bangun_pagi_status.required' => 'Status bangun pagi harus diisi',
            'beribadah_status.required' => 'Status beribadah harus diisi',
            'berolahraga_status.required' => 'Status berolahraga harus diisi',
            'makan_sehat_status.required' => 'Status makan sehat harus diisi',
            'gemar_belajar_status.required' => 'Status gemar belajar harus diisi',
            'bermasyarakat_status.required' => 'Status bermasyarakat harus diisi',
            'tidur_cepat_status.required' => 'Status tidur cepat harus diisi',
        ]);
        
        if ($validator->fails()) {
            \Log::error('Validasi gagal:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menyimpan data. Periksa form kembali.');
        }
        
        try {
            // Cek duplikasi data
            $exists = RekapPemantauanKebiasaan::where('siswa_id', $request->siswa_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data rekap untuk siswa ini pada bulan dan tahun yang sama sudah ada.');
            }
            
            // Simpan data
            $rekap = RekapPemantauanKebiasaan::create([
                'siswa_id' => $request->siswa_id,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas' => $request->kelas,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'guru_kelas' => $request->guru_kelas,
                'orangtua_siswa' => $request->orangtua_siswa,
                'tanggal_persetujuan' => $request->tanggal_persetujuan,
                'catatan' => $request->catatan,
                'bangun_pagi_status' => $request->bangun_pagi_status,
                'beribadah_status' => $request->beribadah_status,
                'berolahraga_status' => $request->berolahraga_status,
                'makan_sehat_status' => $request->makan_sehat_status,
                'gemar_belajar_status' => $request->gemar_belajar_status,
                'bermasyarakat_status' => $request->bermasyarakat_status,
                'tidur_cepat_status' => $request->tidur_cepat_status,
            ]);
            
            \Log::info('Data berhasil disimpan:', $rekap->toArray());
            
            return redirect()->route('rekap-pemantauan.index')
                ->with('success', 'Data rekap pemantauan berhasil disimpan!');
                
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $rekap = RekapPemantauanKebiasaan::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'nama_lengkap' => 'required|string|max:100',
            'kelas' => 'required|string|max:10',
            'bulan' => 'required|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember',
            'tahun' => 'required|integer|min:2024|max:2030',
            'guru_kelas' => 'nullable|string|max:100',
            'orangtua_siswa' => 'nullable|string|max:100',
            'tanggal_persetujuan' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
            'bangun_pagi_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'beribadah_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'berolahraga_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'makan_sehat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'gemar_belajar_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'bermasyarakat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
            'tidur_cepat_status' => 'required|in:belum_terbiasa,sudah_terbiasa',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Periksa form kembali.');
        }
        
        try {
            // Cek duplikasi data (kecuali data yang sedang diedit)
            $exists = RekapPemantauanKebiasaan::where('siswa_id', $request->siswa_id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->where('id', '!=', $id)
                ->exists();
                
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data rekap untuk siswa ini pada bulan dan tahun yang sama sudah ada.');
            }
            
            $rekap->update([
                'siswa_id' => $request->siswa_id,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas' => $request->kelas,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'guru_kelas' => $request->guru_kelas,
                'orangtua_siswa' => $request->orangtua_siswa,
                'tanggal_persetujuan' => $request->tanggal_persetujuan,
                'catatan' => $request->catatan,
                'bangun_pagi_status' => $request->bangun_pagi_status,
                'beribadah_status' => $request->beribadah_status,
                'berolahraga_status' => $request->berolahraga_status,
                'makan_sehat_status' => $request->makan_sehat_status,
                'gemar_belajar_status' => $request->gemar_belajar_status,
                'bermasyarakat_status' => $request->bermasyarakat_status,
                'tidur_cepat_status' => $request->tidur_cepat_status,
            ]);
            
            return redirect()->route('rekap-pemantauan.index')
                ->with('success', 'Data rekap pemantauan berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('Error saat update:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            RekapPemantauanKebiasaan::findOrFail($id)->delete();
            return redirect()->route('rekap-pemantauan.index')
                ->with('success', 'Data rekap pemantauan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportPDF($id)
    {
        $rekap = RekapPemantauanKebiasaan::with(['siswa.orangtua', 'siswa.kelas.guruWali'])->findOrFail($id);
        
        $tandaTanganOrangtua = null;
        if ($rekap->siswa && $rekap->siswa->orangtua) {
            $orangtua = $rekap->siswa->orangtua;
            $ttdPath = $orangtua->tanda_tangan_ayah ?: $orangtua->tanda_tangan_ibu;
            if ($ttdPath) {
                $path = storage_path('app/public/' . $ttdPath);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $tandaTanganOrangtua = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
                }
            }
        }
        
        $tandaTanganGuru = null;
        $pdf = PDF::loadView('rekap-pemantauan.pdf', compact('rekap', 'tandaTanganOrangtua', 'tandaTanganGuru'))->setPaper('a4', 'portrait');
        return $pdf->download('Rekap_7_Kebiasaan_' . str_replace(' ', '_', $rekap->nama_lengkap) . '_' . $rekap->bulan . '_' . $rekap->tahun . '.pdf');
    }

    public function statistics(Request $request)
    {
        $filters = $request->only(['kelas', 'bulan', 'tahun']);
        $user = Auth::user();
        $query = RekapPemantauanKebiasaan::query();
        
        if ($user->peran == 'guru_wali') {
            $kelasGuru = Kelas::where('guru_wali_id', $user->id)->first();
            if ($kelasGuru) $query->where('kelas', $this->extractKelasLevel($kelasGuru->nama_kelas));
        }
        
        $query->filter($filters);
        $totalRecords = $query->count();
        
        $stats = ['bangun_pagi'=>0,'beribadah'=>0,'berolahraga'=>0,'makan_sehat'=>0,'gemar_belajar'=>0,'bermasyarakat'=>0,'tidur_cepat'=>0];
        $avgTotalTerbiasa = 0;
        
        if ($totalRecords > 0) {
            foreach (array_keys($stats) as $key) {
                $stats[$key] = round(($query->clone()->where($key.'_status', 'sudah_terbiasa')->count() / $totalRecords) * 100, 2);
            }
            $avgTotalTerbiasa = round($query->get()->avg('total_terbiasa'), 2);
        }
        
        $kelasOptions = ['1', '2', '3', '4', '5', '6'];
        $bulanOptions = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tahunOptions = range(2024, 2030);
        
        return view('rekap-pemantauan.statistics', compact('stats', 'avgTotalTerbiasa', 'totalRecords', 'kelasOptions', 'bulanOptions', 'tahunOptions', 'filters'));
    }
}