<?php

namespace App\Http\Controllers;

use App\Models\GemarBelajar;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GemarBelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $userType = null;
        $siswaId = null;
        $currentSiswa = null;

        // Tentukan siapa yang login
        if (Session::has('siswa_id')) {
            // Login sebagai siswa
            $userType = 'siswa';
            $siswaId = Session::get('siswa_id');
            $currentSiswa = Siswa::find($siswaId);
        } elseif (Session::has('orangtua_id')) {
            // Login sebagai orangtua
            $userType = 'orangtua';
            $siswaId = Session::get('siswa_anak_id');
            $currentSiswa = Siswa::find($siswaId);
        }

        $query = GemarBelajar::with('siswa');

        // Filter berdasarkan login
        if ($siswaId) {
            // Jika login sebagai siswa/orangtua, hanya tampilkan data siswa tersebut
            $query->where('siswa_id', $siswaId);
        } elseif ($search) {
            // Jika admin/guru wali, bisa filter berdasarkan search
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $data = $query->orderBy('tanggal', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        // Statistik
        $stats = [
            'total' => $query->count(),
            'dinilai' => $query->whereNotNull('nilai')->count(),
            'belum_dinilai' => $query->whereNull('nilai')->count(),
        ];

        return view('gemar-belajar.index', compact(
            'data',
            'search',
            'stats',
            'userType',
            'currentSiswa'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userType = null;
        $siswaId = null;
        $currentSiswa = null;

        // Cek siapa yang login
        if (Session::has('siswa_id')) {
            // Login sebagai siswa
            $userType = 'siswa';
            $siswaId = Session::get('siswa_id');
            $currentSiswa = Siswa::find($siswaId);
        } elseif (Session::has('orangtua_id')) {
            // Login sebagai orangtua
            $userType = 'orangtua';
            $siswaId = Session::get('siswa_anak_id');
            $currentSiswa = Siswa::find($siswaId);
        }

        // Jika bukan siswa/orangtua login, redirect ke index
        if (!$currentSiswa) {
            return redirect()->route('gemar-belajar.index')
                ->with('error', 'Hanya siswa atau orangtua yang dapat menambahkan data gemar belajar.');
        }

        return view('gemar-belajar.create', compact('currentSiswa', 'userType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'judul_buku' => 'required|string|max:255',
            'informasi_didapat' => 'required|string|min:10',
            'gambar_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_baca' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Tentukan siswa_id berdasarkan login
            $siswaId = null;
            
            if (Session::has('siswa_id')) {
                // Login sebagai siswa
                $siswaId = Session::get('siswa_id');
            } elseif (Session::has('orangtua_id')) {
                // Login sebagai orangtua
                $siswaId = Session::get('siswa_anak_id');
            }

            // Validasi siswa_id harus ada
            if (!$siswaId) {
                return redirect()->back()
                    ->with('error', 'Data siswa tidak ditemukan. Silakan login ulang.')
                    ->withInput();
            }

            $tanggal = Carbon::parse($request->tanggal);
            
            $data = [
                'siswa_id' => $siswaId,
                'tanggal' => $tanggal,
                'bulan' => $tanggal->translatedFormat('F'),
                'tahun' => $tanggal->year,
                'judul_buku' => $request->judul_buku,
                'informasi_didapat' => $request->informasi_didapat,
                'status' => 'pending', // Default status
            ];

            // Upload gambar buku jika ada
            if ($request->hasFile('gambar_buku')) {
                $gambarBuku = $request->file('gambar_buku');
                $filename = 'buku_' . time() . '_' . $siswaId . '.' . $gambarBuku->getClientOriginalExtension();
                $path = $gambarBuku->storeAs('images/gemar-belajar/buku', $filename, 'public');
                $data['gambar_buku'] = $path;
            }

            // Upload gambar baca jika ada
            if ($request->hasFile('gambar_baca')) {
                $gambarBaca = $request->file('gambar_baca');
                $filename = 'baca_' . time() . '_' . $siswaId . '.' . $gambarBaca->getClientOriginalExtension();
                $path = $gambarBaca->storeAs('images/gemar-belajar/baca', $filename, 'public');
                $data['gambar_baca'] = $path;
            }

            GemarBelajar::create($data);

            $message = 'Catatan gemar belajar berhasil disimpan!';

            return redirect()->route('gemar-belajar.index')->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $gemarBelajar = GemarBelajar::with('siswa')->findOrFail($id);
        
        // Cek apakah user berhak melihat data ini
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $gemarBelajar->siswa_id != $siswaId) {
            return redirect()->route('gemar-belajar.index')
                ->with('error', 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return view('gemar-belajar.show', compact('gemarBelajar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gemarBelajar = GemarBelajar::with('siswa')->findOrFail($id);
        
        // Cek apakah user berhak mengedit data ini
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $gemarBelajar->siswa_id != $siswaId) {
            return redirect()->route('gemar-belajar.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        return view('gemar-belajar.edit', compact('gemarBelajar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $gemarBelajar = GemarBelajar::findOrFail($id);
        
        // Cek apakah user berhak mengedit data ini
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $gemarBelajar->siswa_id != $siswaId) {
            return redirect()->route('gemar-belajar.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'judul_buku' => 'required|string|max:255',
            'informasi_didapat' => 'required|string|min:10',
            'gambar_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_baca' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $tanggal = Carbon::parse($request->tanggal);
            
            $data = [
                'tanggal' => $tanggal,
                'bulan' => $tanggal->translatedFormat('F'),
                'tahun' => $tanggal->year,
                'judul_buku' => $request->judul_buku,
                'informasi_didapat' => $request->informasi_didapat,
            ];

            // Upload gambar buku baru jika ada
            if ($request->hasFile('gambar_buku')) {
                if ($gemarBelajar->gambar_buku) {
                    Storage::disk('public')->delete($gemarBelajar->gambar_buku);
                }
                
                $gambarBuku = $request->file('gambar_buku');
                $filename = 'buku_' . time() . '_' . $gemarBelajar->siswa_id . '.' . $gambarBuku->getClientOriginalExtension();
                $path = $gambarBuku->storeAs('images/gemar-belajar/buku', $filename, 'public');
                $data['gambar_buku'] = $path;
            }

            // Upload gambar baca baru jika ada
            if ($request->hasFile('gambar_baca')) {
                if ($gemarBelajar->gambar_baca) {
                    Storage::disk('public')->delete($gemarBelajar->gambar_baca);
                }
                
                $gambarBaca = $request->file('gambar_baca');
                $filename = 'baca_' . time() . '_' . $gemarBelajar->siswa_id . '.' . $gambarBaca->getClientOriginalExtension();
                $path = $gambarBaca->storeAs('images/gemar-belajar/baca', $filename, 'public');
                $data['gambar_baca'] = $path;
            }

            $gemarBelajar->update($data);

            $message = 'Catatan gemar belajar berhasil diperbarui!';

            return redirect()->route('gemar-belajar.index')->with('success', $message);
                
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
            $gemarBelajar = GemarBelajar::findOrFail($id);
            
        // Cek apakah user berhak menghapus data ini
        $siswaId = null;
        
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $siswaId = Session::get('siswa_anak_id');
        }
        
        // Jika siswa/orangtua login, cek apakah data milik siswa tersebut
        if ($siswaId && $gemarBelajar->siswa_id != $siswaId) {
            return redirect()->route('gemar-belajar.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
        }
            
            // Hapus gambar jika ada
            if ($gemarBelajar->gambar_buku) {
                Storage::disk('public')->delete($gemarBelajar->gambar_buku);
            }
            
            if ($gemarBelajar->gambar_baca) {
                Storage::disk('public')->delete($gemarBelajar->gambar_baca);
            }

            $gemarBelajar->delete();

            return redirect()->route('gemar-belajar.index')
                ->with('success', 'Data gemar belajar berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}