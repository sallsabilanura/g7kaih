<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Tampilkan daftar semua kelas.
     */
    public function index()
    {
        $kelas = Kelas::with('guruWali')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Tampilkan form untuk menambah kelas.
     */
    public function create()
    {
        $guruWali = User::guruWali()->active()->get();
        return view('kelas.create', compact('guruWali'));
    }

    /**
     * Simpan data kelas baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'guru_wali_id' => 'required|exists:users,id', // PASTIKAN guru_wali_id
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit kelas.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $guruWali = User::guruWali()->active()->get();
        return view('kelas.edit', compact('kelas', 'guruWali'));
    }

    /**
     * Update data kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas')->ignore($kelas->id)
            ],
            'guru_wali_id' => 'required|exists:users,id', // PASTIKAN guru_wali_id
        ]);

        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        
        // Cek apakah kelas memiliki siswa
        if ($kelas->siswas()->count() > 0) {
            return redirect()->route('kelas.index')
                ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa!');
        }

        $kelas->delete();

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus!');
    }
}