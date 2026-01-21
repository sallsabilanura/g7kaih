<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BangunPagiController;
use App\Http\Controllers\Api\TidurCepatController;

// Bangun Pagi API Routes
Route::prefix('bangun-pagi')->group(function () {
    Route::get('/data', [BangunPagiController::class, 'getData']); // GET data bangun pagi
    Route::post('/checklist', [BangunPagiController::class, 'checklist']); // POST checklist
    Route::put('/{id}/update', [BangunPagiController::class, 'update']); // PUT update
    Route::delete('/{id}/hapus', [BangunPagiController::class, 'hapus']); // DELETE hapus
});

// Tidur Cepat API Routes
Route::prefix('tidur-cepat')->group(function () {
    Route::get('/data', [TidurCepatController::class, 'getData']); // GET data tidur cepat
    Route::post('/checklist', [TidurCepatController::class, 'checklist']); // POST checklist
    Route::put('/{id}/update', [TidurCepatController::class, 'update']); // PUT update
    Route::delete('/{id}/hapus', [TidurCepatController::class, 'hapus']); // DELETE hapus
});

// Siswa API
Route::get('/siswa', function (Request $request) {
    $search = $request->get('search', '');
    $tanggal = $request->get('tanggal', date('Y-m-d'));
    
    $query = \App\Models\Siswa::with(['kelas'])
        ->orderBy('nama_lengkap');
    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%");
        });
    }
    
    $siswa = $query->get()->map(function($item) use ($tanggal) {
        $bangunPagi = \App\Models\BangunPagi::where('siswa_id', $item->id)
            ->whereDate('tanggal', $tanggal)
            ->first();
            
        return [
            'id' => $item->id,
            'nama' => $item->nama_lengkap ?: $item->nama,
            'nis' => $item->nis,
            'kelas' => $item->kelas ? $item->kelas->nama : '-',
            'bangun_pagi' => $bangunPagi ? [
                'id' => $bangunPagi->id,
                'pukul' => $bangunPagi->pukul,
                'pukul_formatted' => $bangunPagi->pukul_formatted,
                'pukul_12h' => $bangunPagi->pukul_12h,
                'nilai' => $bangunPagi->nilai,
                'kategori_waktu' => $bangunPagi->kategori_waktu,
                'tidur_cepat_id' => $bangunPagi->tidur_cepat_id,
                'sudah_ttd_ortu' => $bangunPagi->sudah_ttd_ortu,
            ] : null
        ];
    });
    
    return response()->json([
        'success' => true,
        'data' => $siswa
    ]);
});

// Pengaturan API
Route::get('/pengaturan/bangun-pagi', function () {
    $pengaturan = \App\Models\PengaturanWaktuBangunPagi::where('is_active', true)->first();
    
    if (!$pengaturan) {
        return response()->json([
            'success' => false,
            'message' => 'Pengaturan tidak ditemukan'
        ]);
    }
    
    return response()->json([
        'success' => true,
        'data' => [
            'waktu_100_start' => $pengaturan->waktu_100_start,
            'waktu_100_end' => $pengaturan->waktu_100_end,
            'nilai_100' => $pengaturan->nilai_100,
            'waktu_70_start' => $pengaturan->waktu_70_start,
            'waktu_70_end' => $pengaturan->waktu_70_end,
            'nilai_70' => $pengaturan->nilai_70,
            'nilai_terlambat' => $pengaturan->nilai_terlambat,
        ]
    ]);
});

Route::get('/pengaturan/tidur-cepat', function () {
    $pengaturan = \App\Models\PengaturanWaktuTidurCepat::where('is_active', true)->first();
    
    if (!$pengaturan) {
        return response()->json([
            'success' => false,
            'message' => 'Pengaturan tidak ditemukan'
        ]);
    }
    
    return response()->json([
        'success' => true,
        'data' => [
            'waktu_100_start' => $pengaturan->waktu_100_start,
            'waktu_100_end' => $pengaturan->waktu_100_end,
            'nilai_100' => $pengaturan->nilai_100,
            'waktu_70_start' => $pengaturan->waktu_70_start,
            'waktu_70_end' => $pengaturan->waktu_70_end,
            'nilai_70' => $pengaturan->nilai_70,
            'nilai_terlambat' => $pengaturan->nilai_terlambat,
        ]
    ]);
});