<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Orangtua;
use App\Models\Siswa;
use Intervention\Image\Facades\Image; // TAMBAHKAN INI

class DataOrangtuaController extends Controller
{
    /**
     * Tampilkan data orangtua untuk siswa
     */
    public function index()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $orangtua = Orangtua::where('siswa_id', Session::get('siswa_id'))->first();
        return view('data-orangtua.index', compact('orangtua'));
    }

    /**
     * Tampilkan form create data orangtua untuk siswa
     */
    public function create()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $orangtua = Orangtua::where('siswa_id', Session::get('siswa_id'))->first();
        if ($orangtua) {
            return redirect()->route('data-orangtua.index')
                ->with('info', 'Data orangtua sudah ada. Anda dapat mengedit data yang sudah ada.');
        }

        return view('data-orangtua.create');
    }

    /**
     * Simpan data orangtua untuk siswa
     */
    public function store(Request $request)
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $siswaId = Session::get('siswa_id');

        // Validasi data
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'telepon_ayah' => 'nullable|string|max:15',
            'telepon_ibu' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',
            'tanggal_lahir_ayah' => 'nullable|date',
            'tanggal_lahir_ibu' => 'nullable|date',
            'tanda_tangan_ayah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanda_tangan_ibu' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek apakah sudah ada data orangtua
        $existingOrangtua = Orangtua::where('siswa_id', $siswaId)->first();
        if ($existingOrangtua) {
            return redirect()->route('data-orangtua.index')
                ->with('error', 'Data orangtua sudah ada. Gunakan form edit untuk mengubah data.');
        }

        // Handle upload tanda tangan ayah
        $tandaTanganAyahPath = null;
        if ($request->hasFile('tanda_tangan_ayah')) {
            $tandaTanganAyahPath = $this->uploadTandaTangan($request->file('tanda_tangan_ayah'), 'ayah', $siswaId);
        }

        // Handle upload tanda tangan ibu
        $tandaTanganIbuPath = null;
        if ($request->hasFile('tanda_tangan_ibu')) {
            $tandaTanganIbuPath = $this->uploadTandaTangan($request->file('tanda_tangan_ibu'), 'ibu', $siswaId);
        }

        // Simpan data orangtua
        Orangtua::create([
            'siswa_id' => $siswaId,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'telepon_ayah' => $request->telepon_ayah,
            'telepon_ibu' => $request->telepon_ibu,
            'alamat' => $request->alamat,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
            'tanda_tangan_ayah' => $tandaTanganAyahPath,
            'tanda_tangan_ibu' => $tandaTanganIbuPath,
            'is_active' => true,
        ]);

        return redirect()->route('data-orangtua.index')
            ->with('success', 'Data orangtua berhasil disimpan!');
    }

    /**
     * Tampilkan form edit data orangtua untuk siswa
     */
    public function edit()
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $orangtua = Orangtua::where('siswa_id', Session::get('siswa_id'))->first();
        if (!$orangtua) {
            return redirect()->route('data-orangtua.index')
                ->with('error', 'Data orangtua belum ada. Silakan tambah data terlebih dahulu.');
        }

        return view('data-orangtua.edit', compact('orangtua'));
    }

    /**
     * Update data orangtua untuk siswa
     */
    public function update(Request $request)
    {
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        $siswaId = Session::get('siswa_id');

        // Validasi data
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'telepon_ayah' => 'nullable|string|max:15',
            'telepon_ibu' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',
            'tanggal_lahir_ayah' => 'nullable|date',
            'tanggal_lahir_ibu' => 'nullable|date',
            'tanda_tangan_ayah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanda_tangan_ibu' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data orangtua
        $orangtua = Orangtua::where('siswa_id', $siswaId)->firstOrFail();
        
        // Handle upload tanda tangan ayah
        if ($request->hasFile('tanda_tangan_ayah')) {
            // Hapus file lama jika ada
            if ($orangtua->tanda_tangan_ayah) {
                Storage::delete($orangtua->tanda_tangan_ayah);
            }
            $tandaTanganAyahPath = $this->uploadTandaTangan($request->file('tanda_tangan_ayah'), 'ayah', $siswaId);
            $orangtua->tanda_tangan_ayah = $tandaTanganAyahPath;
        }

        // Handle upload tanda tangan ibu
        if ($request->hasFile('tanda_tangan_ibu')) {
            // Hapus file lama jika ada
            if ($orangtua->tanda_tangan_ibu) {
                Storage::delete($orangtua->tanda_tangan_ibu);
            }
            $tandaTanganIbuPath = $this->uploadTandaTangan($request->file('tanda_tangan_ibu'), 'ibu', $siswaId);
            $orangtua->tanda_tangan_ibu = $tandaTanganIbuPath;
        }

        $orangtua->update([
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'telepon_ayah' => $request->telepon_ayah,
            'telepon_ibu' => $request->telepon_ibu,
            'alamat' => $request->alamat,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
        ]);

        return redirect()->route('data-orangtua.index')
            ->with('success', 'Data orangtua berhasil diperbarui!');
    }

    /**
     * Helper function untuk upload tanda tangan
     */
    private function uploadTandaTangan($file, $type, $siswaId)
    {
        $filename = 'tanda_tangan_' . $type . '_' . $siswaId . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Simpan di folder public/tanda-tangan
        $path = $file->storeAs('tanda-tangan', $filename, 'public');
        
        return $path;
    }

    /**
     * Hapus tanda tangan ayah
     */
    public function hapusTandaTanganAyah()
    {
        if (!Session::has('siswa_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orangtua = Orangtua::where('siswa_id', Session::get('siswa_id'))->first();
        
        if ($orangtua && $orangtua->tanda_tangan_ayah) {
            Storage::delete($orangtua->tanda_tangan_ayah);
            $orangtua->update(['tanda_tangan_ayah' => null]);
            
            return response()->json(['success' => 'Tanda tangan ayah berhasil dihapus']);
        }

        return response()->json(['error' => 'Tanda tangan tidak ditemukan'], 404);
    }

    /**
     * Hapus tanda tangan ibu
     */
    public function hapusTandaTanganIbu()
    {
        if (!Session::has('siswa_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $orangtua = Orangtua::where('siswa_id', Session::get('siswa_id'))->first();
        
        if ($orangtua && $orangtua->tanda_tangan_ibu) {
            Storage::delete($orangtua->tanda_tangan_ibu);
            $orangtua->update(['tanda_tangan_ibu' => null]);
            
            return response()->json(['success' => 'Tanda tangan ibu berhasil dihapus']);
        }

        return response()->json(['error' => 'Tanda tangan tidak ditemukan'], 404);
    }

    // ==================== METHOD UNTUK ADMIN ====================

    /**
     * Tampilkan daftar semua orangtua (Admin)
     */
    public function adminIndex()
    {
        $orangtua = Orangtua::with('siswa')->get();
        return view('admin.orangtua.index', compact('orangtua'));
    }

    /**
     * Tampilkan form tambah orangtua (Admin)
     */
    public function adminCreate()
    {
        $siswa = Siswa::whereDoesntHave('orangtua')->get();
        return view('admin.orangtua.create', compact('siswa'));
    }

    /**
     * Simpan data orangtua (Admin)
     */
    public function adminStore(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'telepon_ayah' => 'nullable|string|max:15',
            'telepon_ibu' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',
            'tanggal_lahir_ayah' => 'nullable|date',
            'tanggal_lahir_ibu' => 'nullable|date',
            'tanda_tangan_ayah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanda_tangan_ibu' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek apakah siswa sudah memiliki data orangtua
        $existingOrangtua = Orangtua::where('siswa_id', $request->siswa_id)->first();
        if ($existingOrangtua) {
            return redirect()->route('orangtua.index')
                ->with('error', 'Siswa ini sudah memiliki data orangtua.');
        }

        // Handle upload tanda tangan
        $tandaTanganAyahPath = null;
        $tandaTanganIbuPath = null;

        if ($request->hasFile('tanda_tangan_ayah')) {
            $tandaTanganAyahPath = $this->uploadTandaTangan($request->file('tanda_tangan_ayah'), 'ayah', $request->siswa_id);
        }

        if ($request->hasFile('tanda_tangan_ibu')) {
            $tandaTanganIbuPath = $this->uploadTandaTangan($request->file('tanda_tangan_ibu'), 'ibu', $request->siswa_id);
        }

        Orangtua::create([
            'siswa_id' => $request->siswa_id,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'telepon_ayah' => $request->telepon_ayah,
            'telepon_ibu' => $request->telepon_ibu,
            'alamat' => $request->alamat,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
            'tanda_tangan_ayah' => $tandaTanganAyahPath,
            'tanda_tangan_ibu' => $tandaTanganIbuPath,
            'is_active' => true,
        ]);

        return redirect()->route('orangtua.index')
            ->with('success', 'Data orangtua berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit orangtua (Admin)
     */
    public function adminEdit($id)
    {
        $orangtua = Orangtua::findOrFail($id);
        $siswa = Siswa::all();
        return view('admin.orangtua.edit', compact('orangtua', 'siswa'));
    }

    /**
     * Update data orangtua (Admin)
     */
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'telepon_ayah' => 'nullable|string|max:15',
            'telepon_ibu' => 'nullable|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan_ayah' => 'nullable|string|max:255',
            'pekerjaan_ibu' => 'nullable|string|max:255',
            'pendidikan_ayah' => 'nullable|string|max:255',
            'pendidikan_ibu' => 'nullable|string|max:255',
            'tanggal_lahir_ayah' => 'nullable|date',
            'tanggal_lahir_ibu' => 'nullable|date',
            'tanda_tangan_ayah' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanda_tangan_ibu' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $orangtua = Orangtua::findOrFail($id);
        
        // Handle upload tanda tangan ayah
        if ($request->hasFile('tanda_tangan_ayah')) {
            // Hapus file lama jika ada
            if ($orangtua->tanda_tangan_ayah) {
                Storage::delete($orangtua->tanda_tangan_ayah);
            }
            $tandaTanganAyahPath = $this->uploadTandaTangan($request->file('tanda_tangan_ayah'), 'ayah', $request->siswa_id);
            $orangtua->tanda_tangan_ayah = $tandaTanganAyahPath;
        }

        // Handle upload tanda tangan ibu
        if ($request->hasFile('tanda_tangan_ibu')) {
            // Hapus file lama jika ada
            if ($orangtua->tanda_tangan_ibu) {
                Storage::delete($orangtua->tanda_tangan_ibu);
            }
            $tandaTanganIbuPath = $this->uploadTandaTangan($request->file('tanda_tangan_ibu'), 'ibu', $request->siswa_id);
            $orangtua->tanda_tangan_ibu = $tandaTanganIbuPath;
        }

        $orangtua->update([
            'siswa_id' => $request->siswa_id,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'telepon_ayah' => $request->telepon_ayah,
            'telepon_ibu' => $request->telepon_ibu,
            'alamat' => $request->alamat,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pendidikan_ibu' => $request->pendidikan_ibu,
            'tanggal_lahir_ayah' => $request->tanggal_lahir_ayah,
            'tanggal_lahir_ibu' => $request->tanggal_lahir_ibu,
        ]);

        return redirect()->route('orangtua.index')
            ->with('success', 'Data orangtua berhasil diperbarui!');
    }

    /**
     * Hapus data orangtua (Admin)
     */
    public function adminDestroy($id)
    {
        $orangtua = Orangtua::findOrFail($id);
        $orangtua->delete();

        return redirect()->route('orangtua.index')
            ->with('success', 'Data orangtua berhasil dihapus!');
    }
}