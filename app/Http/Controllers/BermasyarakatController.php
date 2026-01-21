<?php

namespace App\Http\Controllers;

use App\Models\Bermasyarakat;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BermasyarakatController extends Controller
{
    // INDEX - Menampilkan semua data
    public function index()
    {
        // Cek apakah login sebagai siswa atau orangtua
        if (Session::has('siswa_id')) {
            $siswaId = Session::get('siswa_id');
            $data = Bermasyarakat::with(['siswa', 'creator', 'parafOrtu'])
                ->where('siswa_id', $siswaId)
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif (Session::has('orangtua_id')) {
            $orangtuaId = Session::get('orangtua_id');
            $siswa = Siswa::whereHas('orangtua', function($q) use ($orangtuaId) {
                $q->where('id', $orangtuaId);
            })->first();
            
            if ($siswa) {
                $data = Bermasyarakat::with(['siswa', 'creator', 'parafOrtu'])
                    ->where('siswa_id', $siswa->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $data = collect();
            }
        } else {
            // Admin/Guru - tampilkan semua
            $data = Bermasyarakat::with(['siswa', 'creator', 'parafOrtu'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('bermasyarakat.index', compact('data'));
    }

    // CREATE - Menampilkan form create
    public function create()
    {
        return view('bermasyarakat.create');
    }

    public function store(Request $request)
    {
        // Validasi input - HANYA 1 GAMBAR
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'pesan_kesan' => 'required|string',
            'gambar_kegiatan' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Hanya 1 gambar
            'paraf_ortu' => 'boolean'
        ]);

        // Set bulan dan tahun dari tanggal
        $tanggal = Carbon::parse($validated['tanggal']);
        $validated['bulan'] = $tanggal->translatedFormat('F');
        $validated['tahun'] = $tanggal->year;

        // Cari siswa berdasarkan session
        if (Session::has('siswa_id')) {
            $validated['siswa_id'] = Session::get('siswa_id');
        } elseif (Session::has('orangtua_id')) {
            $orangtuaId = Session::get('orangtua_id');
            $siswa = Siswa::whereHas('orangtua', function($q) use ($orangtuaId) {
                $q->where('id', $orangtuaId);
            })->first();
            
            if ($siswa) {
                $validated['siswa_id'] = $siswa->id;
            } else {
                return back()->withInput()->with('error', 'Data siswa tidak ditemukan');
            }
        } else {
            // Jika tidak ada siswa login, gunakan siswa pertama atau null
            $siswa = Siswa::first();
            $validated['siswa_id'] = $siswa ? $siswa->id : null;
        }

        // Set created_by jika user login
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
        }

        // Set status default
        $validated['status'] = 'pending';

        // Handle upload gambar kegiatan (HANYA 1 GAMBAR)
        if ($request->hasFile('gambar_kegiatan')) {
            $gambar = $request->file('gambar_kegiatan');
            $path = $gambar->store('bermasyarakat/kegiatan', 'public');
            $validated['gambar_kegiatan'] = json_encode([$path]); // Simpan sebagai JSON array dengan 1 elemen
        }

        // Set paraf ortu default false
        $validated['paraf_ortu'] = $request->has('paraf_ortu');
        $validated['sudah_ttd_ortu'] = false;

        // Simpan data
        Bermasyarakat::create($validated);

        // Redirect ke index dengan pesan sukses
        return redirect()->route('bermasyarakat.index')
            ->with('success', 'Data kegiatan bermasyarakat berhasil ditambahkan!');
    }

    // SHOW - Menampilkan detail data
    public function show($id)
    {
        $data = Bermasyarakat::with(['siswa', 'creator', 'updater', 'parafOrtu'])->findOrFail($id);
        return view('bermasyarakat.show', compact('data'));
    }

    // EDIT - Menampilkan form edit
    public function edit($id)
    {
        $data = Bermasyarakat::with(['siswa'])->findOrFail($id);
        return view('bermasyarakat.edit', compact('data'));
    }

    // UPDATE - Update data
    public function update(Request $request, $id)
    {
        $data = Bermasyarakat::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'pesan_kesan' => 'required|string',
            'gambar_kegiatan' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048', // 1 gambar optional
            'paraf_ortu' => 'boolean'
        ]);

        // Set bulan dan tahun dari tanggal
        $tanggal = Carbon::parse($validated['tanggal']);
        $validated['bulan'] = $tanggal->translatedFormat('F');
        $validated['tahun'] = $tanggal->year;

        // Set updated_by jika user login
        if (auth()->check()) {
            $validated['updated_by'] = auth()->id();
        }

        // Handle upload gambar kegiatan baru
        if ($request->hasFile('gambar_kegiatan')) {
            // Hapus gambar lama jika ada
            if ($data->gambar_kegiatan) {
                $oldImages = is_array($data->gambar_kegiatan) ? $data->gambar_kegiatan : json_decode($data->gambar_kegiatan, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $gambarLama) {
                        Storage::disk('public')->delete($gambarLama);
                    }
                }
            }

            // Upload gambar baru
            $gambar = $request->file('gambar_kegiatan');
            $path = $gambar->store('bermasyarakat/kegiatan', 'public');
            $validated['gambar_kegiatan'] = json_encode([$path]);
        } else {
            // Jika tidak ada gambar baru, pertahankan gambar lama
            unset($validated['gambar_kegiatan']);
        }

        // Update paraf ortu
        $validated['paraf_ortu'] = $request->has('paraf_ortu');

        $data->update($validated);

        return redirect()->route('bermasyarakat.index')
            ->with('success', 'Data kegiatan bermasyarakat berhasil diupdate!');
    }

    // DESTROY - Hapus data
    public function destroy($id)
    {
        $data = Bermasyarakat::findOrFail($id);

        // Hapus gambar dari storage
        if ($data->gambar_kegiatan) {
            $images = is_array($data->gambar_kegiatan) ? $data->gambar_kegiatan : json_decode($data->gambar_kegiatan, true);
            if (is_array($images)) {
                foreach ($images as $gambar) {
                    Storage::disk('public')->delete($gambar);
                }
            }
        }

        // Hapus paraf ortu jika ada
        if ($data->parafOrtu) {
            $data->parafOrtu->delete();
        }

        $data->delete();

        return redirect()->route('bermasyarakat.index')
            ->with('success', 'Data kegiatan bermasyarakat berhasil dihapus!');
    }

    // UPDATE PARAF ORTU - Khusus untuk orang tua (deprecated - gunakan ParafOrtuBermasyarakatController)
    public function updateParafOrtu(Request $request, $id)
    {
        $data = Bermasyarakat::findOrFail($id);

        $data->update([
            'paraf_ortu' => true,
            'sudah_ttd_ortu' => true,
            'updated_by' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paraf orang tua berhasil diberikan!'
        ]);
    }
}