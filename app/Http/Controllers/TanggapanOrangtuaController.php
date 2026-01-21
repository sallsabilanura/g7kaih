<?php

namespace App\Http\Controllers;

use App\Models\TanggapanOrangtua;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Orangtua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TanggapanOrangtuaController extends Controller
{
    /**
     * Tampilkan daftar semua tanggapan orangtua
     */
    public function index(Request $request)
    {
        $query = TanggapanOrangtua::with(['siswa.kelas', 'orangtua'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan kelas
        if ($request->has('kelas') && $request->kelas) {
            $query->where('kelas', $request->kelas);
        }

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan status tanda tangan
        if ($request->has('tanda_tangan')) {
            if ($request->tanda_tangan == 'sudah') {
                $query->whereNotNull('tanda_tangan_digital');
            } elseif ($request->tanda_tangan == 'belum') {
                $query->whereNull('tanda_tangan_digital');
            }
        }

        // Filter berdasarkan tipe orangtua
        if ($request->has('tipe_orangtua') && $request->tipe_orangtua) {
            $query->where('tipe_orangtua', $request->tipe_orangtua);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $tanggapan = $query->paginate(20);

        // Data untuk filter
        $kelasList = Kelas::pluck('nama_kelas', 'nama_kelas')->unique();
        $tahunList = TanggapanOrangtua::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('tanggapan_orangtua.index', compact('tanggapan', 'kelasList', 'tahunList', 'bulanList'));
    }

    /**
     * Tampilkan form untuk membuat tanggapan baru
     */
    public function create(Request $request)
    {
        $siswaId = $request->get('siswa_id');
        
        if ($siswaId) {
            // Jika ada siswa_id di request, ambil siswa tersebut
            $siswa = Siswa::with(['kelas', 'orangtua'])->findOrFail($siswaId);
            $siswas = collect([$siswa]);
        } else {
            // Ambil semua siswa yang sudah memiliki data orangtua
            $siswas = Siswa::with(['kelas', 'orangtua'])
                ->whereHas('orangtua')
                ->orderBy('nama_lengkap')
                ->get();
        }

        $bulanSekarang = date('n');
        $tahunSekarang = date('Y');

        return view('tanggapan_orangtua.create', compact('siswas', 'bulanSekarang', 'tahunSekarang', 'siswaId'));
    }

    /**
     * Simpan tanggapan baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'tanggal_pengisian' => 'required|date',
            'tanggapan' => 'required|string|min:10',
            'nama_orangtua' => 'required|string|max:100',
            'tipe_orangtua' => 'required|in:ayah,ibu,wali',
            'tanda_tangan_digital' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah sudah ada tanggapan untuk siswa di bulan dan tahun yang sama
        $sudahAda = TanggapanOrangtua::where('siswa_id', $request->siswa_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->with('error', 'Sudah ada tanggapan untuk siswa ini di bulan dan tahun yang dipilih.')
                ->withInput();
        }

        // Dapatkan data siswa untuk mendapatkan kelas
        $siswa = Siswa::with('orangtua')->findOrFail($request->siswa_id);
        $kelas = $siswa->kelas ? $siswa->kelas->nama_kelas : '-';

        DB::beginTransaction();

        try {
            $tanggapanData = [
                'siswa_id' => $request->siswa_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'tanggal_pengisian' => $request->tanggal_pengisian,
                'kelas' => $kelas,
                'tanggapan' => $request->tanggapan,
                'nama_orangtua' => $request->nama_orangtua,
                'tipe_orangtua' => $request->tipe_orangtua,
                'tanda_tangan_digital' => $request->tanda_tangan_digital,
            ];

            // Coba link ke orangtua jika data sesuai
            if ($siswa->orangtua) {
                if ($request->tipe_orangtua === 'ayah' && 
                    $siswa->orangtua->nama_ayah === $request->nama_orangtua) {
                    $tanggapanData['orangtua_id'] = $siswa->orangtua->id;
                } elseif ($request->tipe_orangtua === 'ibu' && 
                         $siswa->orangtua->nama_ibu === $request->nama_orangtua) {
                    $tanggapanData['orangtua_id'] = $siswa->orangtua->id;
                }
            }

            $tanggapan = TanggapanOrangtua::create($tanggapanData);

            DB::commit();

            return redirect()->route('tanggapan-orangtua.index')
                ->with('success', 'Tanggapan orangtua berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menyimpan tanggapan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail tanggapan
     */
    public function show($id)
    {
        $tanggapanOrangtua = TanggapanOrangtua::with(['siswa.kelas', 'orangtua'])
            ->findOrFail($id);
        
        return view('tanggapan_orangtua.show', compact('tanggapanOrangtua'));
    }

    /**
     * Tampilkan form untuk mengedit tanggapan
     */
    public function edit($id)
    {
        $tanggapanOrangtua = TanggapanOrangtua::with(['siswa.orangtua'])->findOrFail($id);
        
        // Ambil siswa yang sudah memiliki orangtua untuk dropdown
        $siswas = Siswa::with('kelas')
            ->whereHas('orangtua')
            ->orderBy('nama_lengkap')
            ->get();

        return view('tanggapan_orangtua.edit', compact('tanggapanOrangtua', 'siswas'));
    }

    /**
     * Update tanggapan di database
     */
    public function update(Request $request, $id)
    {
        $tanggapanOrangtua = TanggapanOrangtua::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'tanggal_pengisian' => 'required|date',
            'tanggapan' => 'required|string|min:10',
            'nama_orangtua' => 'required|string|max:100',
            'tipe_orangtua' => 'required|in:ayah,ibu,wali',
            'tanda_tangan_digital' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah sudah ada tanggapan lain untuk siswa di bulan dan tahun yang sama
        $sudahAda = TanggapanOrangtua::where('siswa_id', $request->siswa_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->where('id', '!=', $tanggapanOrangtua->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->with('error', 'Sudah ada tanggapan lain untuk siswa ini di bulan dan tahun yang dipilih.')
                ->withInput();
        }

        // Dapatkan data siswa untuk mendapatkan kelas
        $siswa = Siswa::with('orangtua')->findOrFail($request->siswa_id);
        $kelas = $siswa->kelas ? $siswa->kelas->nama_kelas : '-';

        DB::beginTransaction();

        try {
            $updateData = [
                'siswa_id' => $request->siswa_id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'tanggal_pengisian' => $request->tanggal_pengisian,
                'kelas' => $kelas,
                'tanggapan' => $request->tanggapan,
                'nama_orangtua' => $request->nama_orangtua,
                'tipe_orangtua' => $request->tipe_orangtua,
                'tanda_tangan_digital' => $request->tanda_tangan_digital ?? $tanggapanOrangtua->tanda_tangan_digital,
            ];

            // Update orangtua_id jika perlu
            if ($siswa->orangtua) {
                if ($request->tipe_orangtua === 'ayah' && 
                    $siswa->orangtua->nama_ayah === $request->nama_orangtua) {
                    $updateData['orangtua_id'] = $siswa->orangtua->id;
                } elseif ($request->tipe_orangtua === 'ibu' && 
                         $siswa->orangtua->nama_ibu === $request->nama_orangtua) {
                    $updateData['orangtua_id'] = $siswa->orangtua->id;
                } else {
                    $updateData['orangtua_id'] = null;
                }
            } else {
                $updateData['orangtua_id'] = null;
            }

            $tanggapanOrangtua->update($updateData);

            DB::commit();

            return redirect()->route('tanggapan-orangtua.index')
                ->with('success', 'Tanggapan orangtua berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tanggapan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus tanggapan dari database
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $tanggapanOrangtua = TanggapanOrangtua::findOrFail($id);
            $tanggapanOrangtua->delete();
            
            DB::commit();

            return redirect()->route('tanggapan-orangtua.index')
                ->with('success', 'Tanggapan orangtua berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus tanggapan: ' . $e->getMessage());
        }
    }

    /**
     * Get siswa info (AJAX)
     */
    public function getSiswaInfo($siswaId)
    {
        $siswa = Siswa::with(['kelas', 'orangtua'])->findOrFail($siswaId);
        
        $data = [
            'id' => $siswa->id,
            'nama_lengkap' => $siswa->nama_lengkap,
            'nis' => $siswa->nis,
            'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
            'orangtua' => $siswa->orangtua ? [
                'ayah' => $siswa->orangtua->nama_ayah,
                'ibu' => $siswa->orangtua->nama_ibu,
            ] : null,
        ];

        return response()->json($data);
    }

    /**
     * Check existing tanggapan (AJAX)
     */
    public function checkTanggapan(Request $request)
    {
        $exists = TanggapanOrangtua::where('siswa_id', $request->siswa_id)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();
        
        return response()->json(['exists' => $exists]);
    }

    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $total = TanggapanOrangtua::count();
        $sudahTtd = TanggapanOrangtua::whereNotNull('tanda_tangan_digital')->count();
        $belumTtd = $total - $sudahTtd;
        
        $perBulan = TanggapanOrangtua::selectRaw('bulan, tahun, COUNT(*) as jumlah')
            ->where('tahun', date('Y'))
            ->groupBy('bulan', 'tahun')
            ->orderBy('bulan')
            ->get();
        
        $perKelas = TanggapanOrangtua::selectRaw('kelas, COUNT(*) as jumlah')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();
        
        $perTipe = TanggapanOrangtua::selectRaw('tipe_orangtua, COUNT(*) as jumlah')
            ->groupBy('tipe_orangtua')
            ->get();

        return view('tanggapan_orangtua.dashboard', compact(
            'total', 'sudahTtd', 'belumTtd', 'perBulan', 'perKelas', 'perTipe'
        ));
    }
}