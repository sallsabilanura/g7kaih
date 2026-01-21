<?php

namespace App\Http\Controllers;

use App\Models\MakanSehat;
use App\Models\Siswa;
use App\Models\PengaturanWaktuMakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MakanSehatController extends Controller
{
    /**
     * Helper untuk konversi nama bulan ke Bahasa Indonesia
     */
    private function getBulanIndonesia($bulanAngka)
    {
        $bulanIndo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $bulanIndo[$bulanAngka] ?? 'Januari';
    }

    public function index(Request $request)
    {
        // Get filter parameters
        $bulan = $request->get('month', date('n'));
        $tahun = $request->get('year', date('Y'));
        $siswaId = Session::get('siswa_id');

        // Get siswa yang login atau semua siswa
        if ($siswaId) {
            $siswa = Siswa::findOrFail($siswaId);
        } else {
            $siswa = Siswa::first();
            if (!$siswa) {
                return redirect()->route('makan-sehat.create')
                    ->with('info', 'Silakan tambah data siswa terlebih dahulu.');
            }
        }

        // Get nama bulan dalam Bahasa Indonesia
        $namaBulan = $this->getBulanIndonesia($bulan);
        $jumlahHari = Carbon::create($tahun, $bulan)->daysInMonth;
        
        // Prepare data per hari
        $dataPerHari = [];
        $now = Carbon::now();
        $today = Carbon::today();
        
        // Get all data untuk bulan ini
        $dataQuery = MakanSehat::where('siswa_id', $siswa->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });

        // Ambil semua pengaturan waktu aktif
        $pengaturanWaktu = PengaturanWaktuMakan::active()->get();

        // Loop untuk setiap hari dalam bulan
        for ($hari = 1; $hari <= $jumlahHari; $hari++) {
            $tanggal = Carbon::create($tahun, $bulan, $hari);
            $tanggalString = $tanggal->format('Y-m-d');
            
            // Initialize data untuk hari ini
            $dataHari = [
                'sarapan' => null,
                'makan_siang' => null,
                'makan_malam' => null,
                'status_sarapan' => 'kosong',
                'status_makan_siang' => 'kosong',
                'status_makan_malam' => 'kosong',
                'total_nilai' => 0,
                'jumlah_makan' => 0,
                'tanggal_objek' => $tanggal
            ];

            // Check apakah hari ini atau masa depan
            $isFuture = $tanggal->isFuture();
            $isToday = $tanggal->isToday();
            $isPast = $tanggal->isPast() && !$isToday;

            // Get data untuk tanggal ini jika ada
            if (isset($dataQuery[$tanggalString])) {
                foreach ($dataQuery[$tanggalString] as $item) {
                    $dataHari[$item->jenis_makanan] = $item;
                    $dataHari['total_nilai'] += $item->nilai;
                    $dataHari['jumlah_makan']++;
                    // Set status ke 'terisi' untuk yang sudah ada data
                    $dataHari['status_' . $item->jenis_makanan] = 'terisi';
                }
            }

            // Set status untuk tanggal yang sudah lewat (DIGEMBOK)
            if ($isPast) {
                // Jika data kosong di hari yang sudah lewat, set status ke tidak_bisa_diisi
                if (!$dataHari['sarapan']) {
                    $dataHari['status_sarapan'] = 'tidak_bisa_diisi';
                }
                if (!$dataHari['makan_siang']) {
                    $dataHari['status_makan_siang'] = 'tidak_bisa_diisi';
                }
                if (!$dataHari['makan_malam']) {
                    $dataHari['status_makan_malam'] = 'tidak_bisa_diisi';
                }
            }
            // Set status untuk hari ini berdasarkan waktu
            elseif ($isToday) {
                $currentTime = $now->format('H:i:s');
                
                // Hanya set status "kosong" atau "belum_waktunya" jika belum ada data
                if (!$dataHari['sarapan']) {
                    $pengaturanSarapan = $pengaturanWaktu->where('jenis_makanan', 'sarapan')->first();
                    if ($pengaturanSarapan) {
                        $dataHari['status_sarapan'] = $this->getStatusWaktuMakan($currentTime, $pengaturanSarapan, $today);
                    }
                }

                if (!$dataHari['makan_siang']) {
                    $pengaturanSiang = $pengaturanWaktu->where('jenis_makanan', 'makan_siang')->first();
                    if ($pengaturanSiang) {
                        $dataHari['status_makan_siang'] = $this->getStatusWaktuMakan($currentTime, $pengaturanSiang, $today);
                    }
                }

                if (!$dataHari['makan_malam']) {
                    $pengaturanMalam = $pengaturanWaktu->where('jenis_makanan', 'makan_malam')->first();
                    if ($pengaturanMalam) {
                        $dataHari['status_makan_malam'] = $this->getStatusWaktuMakan($currentTime, $pengaturanMalam, $today);
                    }
                }
            }
            // Set status untuk tanggal masa depan
            elseif ($isFuture) {
                if (!$dataHari['sarapan']) {
                    $dataHari['status_sarapan'] = 'belum_waktunya';
                }
                if (!$dataHari['makan_siang']) {
                    $dataHari['status_makan_siang'] = 'belum_waktunya';
                }
                if (!$dataHari['makan_malam']) {
                    $dataHari['status_makan_malam'] = 'belum_waktunya';
                }
            }

            $dataPerHari[$tanggalString] = $dataHari;
        }

        // Sort: hari ini di atas, kemudian hari-hari sebelumnya
        $dataPerHariCollection = collect($dataPerHari)->sortByDesc(function($item, $key) use ($today) {
            $tanggal = Carbon::parse($key);
            if ($tanggal->isToday()) {
                return PHP_INT_MAX; // Hari ini paling atas
            }
            return $tanggal->timestamp;
        });

        // PAGINATION
        $currentPage = $request->get('page', 1);
        $perPage = 10;
        $currentPageItems = $dataPerHariCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $dataPerHariCollection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get list siswa untuk filter
        $siswaList = $siswaId ? collect([$siswa]) : Siswa::orderBy('nama_lengkap')->get();

        return view('makan-sehat.index', compact(
            'paginatedData',
            'namaBulan',
            'tahun',
            'bulan',
            'siswaList',
            'siswa',
            'pengaturanWaktu'
        ));
    }

    /**
     * Helper method untuk menentukan status waktu makan berdasarkan pengaturan
     * Ditambahkan parameter tanggal untuk cek apakah sudah lewat batas waktu
     */
    private function getStatusWaktuMakan($currentTime, $pengaturan, $tanggal)
    {
        try {
            $current = Carbon::createFromFormat('H:i:s', $currentTime);
            $waktuMulai = Carbon::createFromFormat('H:i:s', $pengaturan->waktu_100_start);
            $waktuAkhir70 = Carbon::createFromFormat('H:i:s', $pengaturan->waktu_70_end);
            
            // Jika waktu sekarang sudah lewat dari batas waktu 70, GEMBOK
            if ($current->gt($waktuAkhir70)) {
                return 'tidak_bisa_diisi';
            }
            
            // Jika belum sampai waktu mulai kategori 100
            if ($current->lt($waktuMulai)) {
                return 'belum_waktunya';
            }
            
            return 'kosong'; // Bisa diisi
        } catch (\Exception $e) {
            return 'kosong';
        }
    }
    
    public function create()
    {
        $siswaId = Session::get('siswa_id');
        
        if ($siswaId) {
            $selectedSiswa = Siswa::findOrFail($siswaId);
            $siswas = collect([$selectedSiswa]);
        } else {
            $siswas = Siswa::orderBy('nama_lengkap')->get();
            $selectedSiswa = null;
        }

        // Ambil semua pengaturan waktu aktif
        $pengaturanWaktu = PengaturanWaktuMakan::active()->get()->keyBy('jenis_makanan');

        $jenisMakanan = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang',
            'makan_malam' => 'Makan Malam'
        ];
        
        return view('makan-sehat.create', compact('siswas', 'jenisMakanan', 'selectedSiswa', 'pengaturanWaktu'));
    }

    public function store(Request $request)
    {
        $siswaId = Session::get('siswa_id');
        
        $validated = $request->validate([
            'siswa_id' => $siswaId ? 'sometimes|exists:siswas,id' : 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jenis_makanan' => 'required|in:sarapan,makan_siang,makan_malam',
            'waktu_makan' => 'required',
            'menu_makanan' => 'required|string|max:500',
            'dokumentasi_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string|max:1000'
        ]);

        if ($siswaId) {
            $validated['siswa_id'] = $siswaId;
        }

        // Validasi: Cek apakah tanggal sudah lewat
        $tanggalInput = Carbon::parse($validated['tanggal']);
        $now = Carbon::now();
        
        if ($tanggalInput->isPast() && !$tanggalInput->isToday()) {
            return back()->with('error', 'Tidak dapat menambahkan data untuk tanggal yang sudah lewat!')
                        ->withInput();
        }

        // Jika hari ini, validasi waktu makan sudah lewat atau belum
        if ($tanggalInput->isToday()) {
            $pengaturan = PengaturanWaktuMakan::getByJenis($validated['jenis_makanan']);
            if ($pengaturan) {
                $currentTime = $now->format('H:i:s');
                $waktuAkhir70 = Carbon::createFromFormat('H:i:s', $pengaturan->waktu_70_end);
                $current = Carbon::createFromFormat('H:i:s', $currentTime);
                
                if ($current->gt($waktuAkhir70)) {
                    return back()->with('error', 'Waktu untuk mengisi ' . $validated['jenis_makanan'] . ' sudah lewat!')
                                ->withInput();
                }
            }
        }

        // Cek duplikasi data
        $existingData = MakanSehat::where('siswa_id', $validated['siswa_id'])
                                  ->where('tanggal', $validated['tanggal'])
                                  ->where('jenis_makanan', $validated['jenis_makanan'])
                                  ->first();

        if ($existingData) {
            return back()->with('error', 'Data untuk ' . $validated['jenis_makanan'] . ' pada tanggal ini sudah ada!')
                        ->withInput();
        }

        // Format waktu makan
        $validated['waktu_makan'] = $this->formatWaktuMakan($validated['waktu_makan']);

        // Buat instance model untuk hitung nilai
        $makanSehat = new MakanSehat($validated);
        $validated['nilai'] = $makanSehat->hitungNilai($validated['waktu_makan'], $validated['jenis_makanan']);
        
        // Set bulan dalam Bahasa Indonesia
        $tanggalCarbon = Carbon::parse($validated['tanggal']);
        $validated['bulan'] = $this->getBulanIndonesia($tanggalCarbon->month) . ' ' . $tanggalCarbon->year;

        // Handle file upload dengan perbaikan
        if ($request->hasFile('dokumentasi_foto')) {
            $file = $request->file('dokumentasi_foto');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store file di folder public/storage/makan-sehat
            $path = $file->storeAs('makan-sehat', $filename, 'public');
            
            // Simpan hanya nama file (tanpa path)
            $validated['dokumentasi_foto'] = $filename;
            
            \Log::info('File uploaded:', [
                'filename' => $filename,
                'path' => $path,
                'full_path' => storage_path('app/public/' . $path)
            ]);
        }

        MakanSehat::create($validated);

        return redirect()->route('makan-sehat.index')
            ->with('success', 'Data makan sehat berhasil ditambahkan!');
    }

    public function show(MakanSehat $makanSehat)
    {
        $makanSehat->load('siswa');
        
        // Ambil pengaturan waktu untuk info tambahan
        $pengaturan = PengaturanWaktuMakan::getByJenis($makanSehat->jenis_makanan);
        
        return view('makan-sehat.show', compact('makanSehat', 'pengaturan'));
    }

    public function edit(MakanSehat $makanSehat)
    {
        $siswaId = Session::get('siswa_id');
        if ($siswaId && $makanSehat->siswa_id != $siswaId) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        // Validasi: tidak bisa edit data yang sudah lewat
        $tanggalData = Carbon::parse($makanSehat->tanggal);
        if ($tanggalData->isPast() && !$tanggalData->isToday()) {
            return redirect()->route('makan-sehat.index')
                ->with('error', 'Tidak dapat mengedit data untuk tanggal yang sudah lewat!');
        }

        if ($siswaId) {
            $siswas = Siswa::where('id', $siswaId)->get();
        } else {
            $siswas = Siswa::orderBy('nama_lengkap')->get();
        }

        // Ambil pengaturan waktu untuk info
        $pengaturan = PengaturanWaktuMakan::getByJenis($makanSehat->jenis_makanan);

        $jenisMakanan = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang',
            'makan_malam' => 'Makan Malam'
        ];
        
        return view('makan-sehat.edit', compact('makanSehat', 'siswas', 'jenisMakanan', 'pengaturan'));
    }

    public function update(Request $request, MakanSehat $makanSehat)
    {
        $siswaId = Session::get('siswa_id');
        if ($siswaId && $makanSehat->siswa_id != $siswaId) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate data ini.');
        }

        // Validasi: tidak bisa update data yang sudah lewat
        $tanggalData = Carbon::parse($makanSehat->tanggal);
        if ($tanggalData->isPast() && !$tanggalData->isToday()) {
            return redirect()->route('makan-sehat.index')
                ->with('error', 'Tidak dapat mengupdate data untuk tanggal yang sudah lewat!');
        }

        $validator = Validator::make($request->all(), [
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'jenis_makanan' => 'required|in:sarapan,makan_siang,makan_malam',
            'waktu_makan' => 'required',
            'menu_makanan' => 'required|string|max:500',
            'dokumentasi_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'keterangan' => 'nullable|string|max:1000',
            'hapus_foto' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Format waktu makan
        $validated['waktu_makan'] = $this->formatWaktuMakan($validated['waktu_makan']);

        // Hitung nilai baru
        $makanSehat->fill($validated);
        $validated['nilai'] = $makanSehat->hitungNilai($validated['waktu_makan'], $validated['jenis_makanan']);

        // Handle penghapusan foto
        if ($request->has('hapus_foto') && $request->boolean('hapus_foto')) {
            if ($makanSehat->dokumentasi_foto) {
                // Hapus file lama
                if (Storage::disk('public')->exists('makan-sehat/' . $makanSehat->dokumentasi_foto)) {
                    Storage::disk('public')->delete('makan-sehat/' . $makanSehat->dokumentasi_foto);
                }
                $validated['dokumentasi_foto'] = null;
            }
        } else {
            $validated['dokumentasi_foto'] = $makanSehat->dokumentasi_foto;
        }

        // Handle upload foto baru
        if ($request->hasFile('dokumentasi_foto')) {
            // Hapus foto lama jika ada
            if ($makanSehat->dokumentasi_foto) {
                if (Storage::disk('public')->exists('makan-sehat/' . $makanSehat->dokumentasi_foto)) {
                    Storage::disk('public')->delete('makan-sehat/' . $makanSehat->dokumentasi_foto);
                }
            }

            $file = $request->file('dokumentasi_foto');
            
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs('makan-sehat', $filename, 'public');
            
            $validated['dokumentasi_foto'] = $filename;
            
            \Log::info('File updated:', [
                'filename' => $filename,
                'path' => $path
            ]);
        }

        // Update bulan dalam Bahasa Indonesia
        $tanggalCarbon = Carbon::parse($validated['tanggal']);
        $validated['bulan'] = $this->getBulanIndonesia($tanggalCarbon->month) . ' ' . $tanggalCarbon->year;

        try {
            $makanSehat->update($validated);

            return redirect()->route('makan-sehat.index')
                ->with('success', 'Data makan sehat berhasil diperbarui!');

        } catch (\Exception $e) {
            \Log::error('Error updating makan sehat:', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                        ->withInput();
        }
    }

    // Helper method untuk format waktu
    private function formatWaktuMakan($waktu)
    {
        try {
            // Jika sudah format H:i, return langsung
            if (preg_match('/^\d{1,2}:\d{2}$/', $waktu)) {
                return $waktu;
            }
            
            // Jika format H:i:s, ambil hanya jam dan menit
            if (preg_match('/^(\d{1,2}:\d{2}):\d{2}$/', $waktu, $matches)) {
                return $matches[1];
            }
            
            // Default: coba parse dengan Carbon
            $carbonTime = Carbon::createFromFormat('H:i:s', $waktu);
            return $carbonTime->format('H:i');
            
        } catch (\Exception $e) {
            \Log::error('Error format waktu:', ['waktu' => $waktu, 'error' => $e->getMessage()]);
            return '00:00';
        }
    }

    public function destroy(MakanSehat $makanSehat)
    {
        $siswaId = Session::get('siswa_id');
        if ($siswaId && $makanSehat->siswa_id != $siswaId) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // Validasi: tidak bisa hapus data yang sudah lewat
        $tanggalData = Carbon::parse($makanSehat->tanggal);
        if ($tanggalData->isPast() && !$tanggalData->isToday()) {
            return redirect()->route('makan-sehat.index')
                ->with('error', 'Tidak dapat menghapus data untuk tanggal yang sudah lewat!');
        }

        // Hapus foto jika ada
        if ($makanSehat->dokumentasi_foto) {
            if (Storage::disk('public')->exists('makan-sehat/' . $makanSehat->dokumentasi_foto)) {
                Storage::disk('public')->delete('makan-sehat/' . $makanSehat->dokumentasi_foto);
            }
        }

        $makanSehat->delete();

        return redirect()->route('makan-sehat.index')
            ->with('success', 'Data makan sehat berhasil dihapus!');
    }

    public function createByType($jenis)
    {
        if (!in_array($jenis, ['sarapan', 'makan_siang', 'makan_malam'])) {
            abort(404);
        }

        $siswaId = Session::get('siswa_id');
        
        if ($siswaId) {
            $selectedSiswa = Siswa::findOrFail($siswaId);
            $siswas = collect([$selectedSiswa]);
        } else {
            $siswas = Siswa::orderBy('nama_lengkap')->get();
            $selectedSiswa = null;
        }

        // Ambil pengaturan waktu untuk jenis makanan ini
        $pengaturan = PengaturanWaktuMakan::getByJenis($jenis);

        // Ambil semua pengaturan untuk ditampilkan
        $pengaturanWaktu = PengaturanWaktuMakan::active()->get()->keyBy('jenis_makanan');

        $jenisMakanan = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang',
            'makan_malam' => 'Makan Malam'
        ];

        $selectedType = $jenis;

        return view('makan-sehat.create', compact('siswas', 'jenisMakanan', 'selectedType', 'selectedSiswa', 'pengaturan', 'pengaturanWaktu'));
    }
}