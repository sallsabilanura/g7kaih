<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelasController;

use App\Http\Controllers\KelasSayaController;
use App\Http\Controllers\GemarBelajarController;
use App\Http\Controllers\NilaiManualGemarBelajarController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use App\Http\Controllers\Auth\SiswaLoginController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\IntroductionController;
use App\Http\Controllers\MakanSehatController;
use App\Http\Controllers\BerolahragaController;
use App\Http\Controllers\PengaturanWaktuMakanController;
use App\Http\Controllers\Orangtua\OrangtuaDashboardController;
use App\Http\Controllers\DataOrangtuaController;
use App\Http\Controllers\BangunPagiController;
use App\Http\Controllers\PengaturanWaktuTidurCepatController;
use App\Http\Controllers\TidurCepatController;
use App\Http\Controllers\ParafOrtuBangunPagiController;
use App\Http\Controllers\PengaturanWaktuBangunPagiController;
use App\Http\Controllers\BeribadahController;
use App\Http\Controllers\PengaturanWaktuBeribadahController;
use App\Http\Controllers\ParafOrtuBeribadahController;
use App\Http\Controllers\NilaiManualOlahragaController;
use App\Http\Controllers\BermasyarakatController;
use App\Http\Controllers\ParafOrtuBermasyarakatController;
use App\Http\Controllers\NilaiManualBermasyarakatController;
use App\Http\Controllers\TanggapanOrangtuaController;
use App\Http\Controllers\RekapPemantauanKebiasaanController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman root
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES - LOGIN UTAMA
|--------------------------------------------------------------------------
*/

// Login untuk user biasa (admin, guru_wali) - MENGGUNAKAN AuthController
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTES SISWA - TANPA MIDDLEWARE AUTH (SESSION BASED)
|--------------------------------------------------------------------------
*/

// Login untuk siswa (session based)
Route::get('/siswa/login', [SiswaLoginController::class, 'showLoginForm'])->name('siswa.login');
Route::post('/siswa/login', [SiswaLoginController::class, 'login'])->name('siswa.login.submit');
Route::post('/siswa/login/barcode', [SiswaLoginController::class, 'loginWithBarcode'])->name('siswa.login.barcode');

// Siswa Routes
Route::prefix('siswas')->name('siswas.')->group(function () {
    Route::get('/', [SiswaController::class, 'index'])->name('index');
    Route::get('/create', [SiswaController::class, 'create'])->name('create');
    Route::post('/', [SiswaController::class, 'store'])->name('store');
    Route::get('/{siswa}/edit', [SiswaController::class, 'edit'])->name('edit');
    Route::put('/{siswa}', [SiswaController::class, 'update'])->name('update');
    Route::delete('/{siswa}', [SiswaController::class, 'destroy'])->name('destroy');
    
    // Show detail
    Route::get('/{siswa}', [SiswaController::class, 'show'])->name('show');
    
    // Import/Export routes
    Route::get('/download-template', [SiswaController::class, 'downloadTemplate'])->name('download-template');
    Route::post('/import', [SiswaController::class, 'import'])->name('import');
    
    // Barcode routes
    Route::get('/{siswa}/barcode/download', [SiswaController::class, 'downloadBarcode'])->name('barcode.download');
    Route::get('/barcode/download-all', [SiswaController::class, 'downloadAllBarcodes'])->name('barcode.download-all');
});
Route::get('/introductions/create', [IntroductionController::class, 'create'])
     ->name('introductions.create');
Route::post('/introductions', [IntroductionController::class, 'store'])
     ->name('introductions.store');

// Routes untuk siswa yang sudah login (dilindungi middleware siswa.auth)
Route::middleware(['siswa.auth'])->group(function () {
    // Dashboard Siswa
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [SiswaDashboardController::class, 'profile'])->name('profile');
        Route::get('/jadwal', [SiswaDashboardController::class, 'jadwal'])->name('jadwal');
        Route::get('/nilai', [SiswaDashboardController::class, 'nilai'])->name('nilai');
        Route::get('/absensi', [SiswaDashboardController::class, 'absensi'])->name('absensi');
        Route::post('/logout', [SiswaDashboardController::class, 'logout'])->name('logout');
        
        // Route khusus introduction untuk siswa
        Route::get('/introduction/create', [IntroductionController::class, 'createForStudent'])
             ->name('siswa.introduction.create');
        Route::get('/my-introduction', [IntroductionController::class, 'myIntroduction'])
             ->name('siswa.introduction.my');
    });
    
    // Routes Data Orangtua untuk Siswa
    Route::prefix('data-orangtua')->name('data-orangtua.')->group(function () {
        Route::get('/', [DataOrangtuaController::class, 'index'])->name('index');
        Route::get('/create', [DataOrangtuaController::class, 'create'])->name('create');
        Route::post('/', [DataOrangtuaController::class, 'store'])->name('store');
        Route::get('/edit', [DataOrangtuaController::class, 'edit'])->name('edit');
        Route::put('/', [DataOrangtuaController::class, 'update'])->name('update');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTES ORANGTUA - TANPA MIDDLEWARE AUTH (SESSION BASED)
|--------------------------------------------------------------------------
*/

// Login untuk orangtua (session based)
Route::get('/orangtua/login', [AuthController::class, 'showOrangtuaLoginForm'])->name('orangtua.login');
Route::post('/orangtua/login', [AuthController::class, 'orangtuaLogin'])->name('orangtua.login.submit');

// Routes untuk orangtua yang sudah login
Route::middleware(['orangtua.auth'])->prefix('orangtua')->name('orangtua.')->group(function () {
    Route::get('/dashboard', [OrangtuaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [OrangtuaDashboardController::class, 'profile'])->name('profile');
    Route::get('/profil-anak', [OrangtuaDashboardController::class, 'profilAnak'])->name('profil-anak');
    Route::get('/jadwal-anak', [OrangtuaDashboardController::class, 'jadwalAnak'])->name('jadwal-anak');
    Route::get('/nilai-anak', [OrangtuaDashboardController::class, 'nilaiAnak'])->name('nilai-anak');
    Route::get('/absensi-anak', [OrangtuaDashboardController::class, 'absensiAnak'])->name('absensi-anak');
    Route::get('/raport-anak', [OrangtuaDashboardController::class, 'raportAnak'])->name('raport-anak');
   Route::post('/logout', [OrangtuaDashboardController::class, 'logout'])->name('orangtua.logout');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES - HANYA UNTUK USER YANG SUDAH LOGIN (admin, guru_wali)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Main dashboard route (redirect berdasarkan peran)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
   
    // ✅ PERBAIKAN: Tambahkan semua route dashboard berdasarkan peran
    Route::get('/dashboard/admin', [DashboardController::class, 'adminDashboard'])->name('dashboard.admin');
    Route::get('/dashboard/guruwali', [DashboardController::class, 'guruWaliDashboard'])->name('dashboard.guruwali');
    Route::get('/dashboard/siswa-auth', [DashboardController::class, 'siswaAuthDashboard'])->name('dashboard.siswa.auth');
    Route::get('/dashboard/orangtua-admin', [DashboardController::class, 'orangtuaDashboard'])->name('dashboard.orangtua.admin');

    // Route admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('siswas', SiswaController::class);
        Route::post('/siswas/import', [SiswaController::class, 'import'])->name('siswas.import');
        Route::get('/siswas/download-template', [SiswaController::class, 'downloadTemplate'])->name('siswas.download-template');
        
        // Routes untuk mengelola orangtua (Admin) - GUNAKAN DataOrangtuaController
        Route::get('/orangtua', [DataOrangtuaController::class, 'adminIndex'])->name('orangtua.index');
        Route::get('/orangtua/create', [DataOrangtuaController::class, 'adminCreate'])->name('orangtua.create');
        Route::post('/orangtua', [DataOrangtuaController::class, 'adminStore'])->name('orangtua.store');
        Route::get('/orangtua/{id}/edit', [DataOrangtuaController::class, 'adminEdit'])->name('orangtua.edit');
        Route::put('/orangtua/{id}', [DataOrangtuaController::class, 'adminUpdate'])->name('orangtua.update');
        Route::delete('/orangtua/{id}', [DataOrangtuaController::class, 'adminDestroy'])->name('orangtua.destroy');
        
        // Route untuk hapus tanda tangan
        Route::post('/data-orangtua/hapus-tanda-tangan-ayah', [DataOrangtuaController::class, 'hapusTandaTanganAyah'])->name('data-orangtua.hapus-tanda-tangan-ayah');
        Route::post('/data-orangtua/hapus-tanda-tangan-ibu', [DataOrangtuaController::class, 'hapusTandaTanganIbu'])->name('data-orangtua.hapus-tanda-tangan-ibu');
    });

    // Route untuk Kelas Saya (Guru Wali) - dengan middleware role:guru_wali
    Route::middleware('role:guru_wali')->group(function () {
        Route::prefix('kelas-saya')->name('kelas-saya.')->group(function () {
            Route::get('/', [KelasSayaController::class, 'index'])->name('index');
            Route::get('/dashboard', [KelasSayaController::class, 'dashboard'])->name('dashboard');
            Route::get('/{id}', [KelasSayaController::class, 'show'])->name('show');
            Route::get('/{id}/siswa', [KelasSayaController::class, 'siswa'])->name('siswa');
            Route::get('/{id}/edit', [KelasSayaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [KelasSayaController::class, 'update'])->name('update');
            Route::get('/{id}/statistik', [KelasSayaController::class, 'statistik'])->name('statistik');
            Route::get('/{id}/export/{type?}', [KelasSayaController::class, 'export'])->name('export');
            Route::post('/{id}/tambah-siswa', [KelasSayaController::class, 'tambahSiswa'])->name('tambah-siswa');
            Route::delete('/{id}/hapus-siswa/{siswaId}', [KelasSayaController::class, 'hapusSiswa'])->name('hapus-siswa');
            Route::get('/{id}/cari-siswa', [KelasSayaController::class, 'cariSiswa'])->name('cari-siswa');
        });
    });

    // API Routes
    Route::prefix('api')->group(function () {
        Route::get('/kelas-saya', [KelasSayaController::class, 'getDataKelas'])->name('api.kelas-saya');
        Route::get('/siswa/{siswaId}/orangtua', [DataOrangtuaController::class, 'getBySiswa'])->name('api.orangtua.by-siswa');
    });

    // Profile (untuk semua user yang login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| PUBLIC CONTENT ROUTES - BISA DIAKSES TANPA LOGIN
|--------------------------------------------------------------------------
*/

// Route resource introduction (untuk index, show, edit, update, destroy)
Route::resource('introductions', IntroductionController::class)->except(['create', 'store']);

// Routes untuk Berolahraga
Route::prefix('berolahraga')->name('berolahraga.')->group(function () {
    Route::get('/', [BerolahragaController::class, 'index'])->name('index');
    Route::get('/create', [BerolahragaController::class, 'create'])->name('create');
    Route::post('/', [BerolahragaController::class, 'store'])->name('store');
    Route::get('/{id}', [BerolahragaController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BerolahragaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BerolahragaController::class, 'update'])->name('update');
    Route::delete('/{id}', [BerolahragaController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/update-nilai', [BerolahragaController::class, 'updateNilai'])->name('updateNilai');
    Route::get('/{id}/download-video', [BerolahragaController::class, 'downloadVideo'])->name('downloadVideo');
});

// Routes untuk Nilai Manual Olahraga
Route::prefix('nilai-manual-olahraga')->name('nilai-manual-olahraga.')->group(function () {
    Route::get('/', [NilaiManualOlahragaController::class, 'index'])->name('index');
    Route::post('/simpan/{id}', [NilaiManualOlahragaController::class, 'simpanNilai'])->name('simpan');
    Route::post('/reset/{id}', [NilaiManualOlahragaController::class, 'resetNilai'])->name('reset');
});

// Routes untuk Makan Sehat
Route::resource('makan-sehat', MakanSehatController::class);
Route::get('/makan-sehat/create-by-type/{jenis}', [MakanSehatController::class, 'createByType'])
    ->name('makan-sehat.create-by-type');
Route::get('/makan-sehat/statistik/{siswa}', [MakanSehatController::class, 'statistik'])
    ->name('makan-sehat.statistik');
Route::get('/makan-sehat/rekap-harian/{siswa}', [MakanSehatController::class, 'rekapHarianSiswa'])
    ->name('makan-sehat.rekap-harian');
Route::get('/makan-sehat/rekap-bulanan/{siswa}/{bulan}', [MakanSehatController::class, 'rekapBulanan'])
    ->name('makan-sehat.rekap-bulanan');

// Routes untuk Gemar Belajar
Route::prefix('gemar-belajar')->name('gemar-belajar.')->group(function () {
    Route::get('/', [GemarBelajarController::class, 'index'])->name('index');
    Route::get('/create', [GemarBelajarController::class, 'create'])->name('create');
    Route::post('/', [GemarBelajarController::class, 'store'])->name('store');
    Route::get('/{id}', [GemarBelajarController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [GemarBelajarController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GemarBelajarController::class, 'update'])->name('update');
    Route::delete('/{id}', [GemarBelajarController::class, 'destroy'])->name('destroy');
});

// Routes untuk Nilai Manual Gemar Belajar (dilindungi auth)
Route::prefix('nilai-manual-gemar-belajar')->name('nilai-manual-gemar-belajar.')->middleware(['auth'])->group(function () {
    Route::get('/', [NilaiManualGemarBelajarController::class, 'index'])->name('index');
    Route::post('/simpan/{id}', [NilaiManualGemarBelajarController::class, 'simpanNilai'])->name('simpan');
    Route::delete('/reset/{id}', [NilaiManualGemarBelajarController::class, 'resetNilai'])->name('reset');
});

// Routes Pengaturan Waktu Makan (admin only)
Route::middleware(['auth', 'admin'])->prefix('pengaturan-waktu-makan')->name('pengaturan-waktu-makan.')->group(function () {
    Route::get('/', [PengaturanWaktuMakanController::class, 'index'])->name('index');
    Route::get('/create', [PengaturanWaktuMakanController::class, 'create'])->name('create');
    Route::post('/', [PengaturanWaktuMakanController::class, 'store'])->name('store');
    Route::get('/{jenis}/edit', [PengaturanWaktuMakanController::class, 'edit'])->name('edit');
    Route::put('/{jenis}', [PengaturanWaktuMakanController::class, 'update'])->name('update');
    Route::delete('/{jenis}', [PengaturanWaktuMakanController::class, 'destroy'])->name('destroy');
    Route::put('/{jenis}/reset', [PengaturanWaktuMakanController::class, 'reset'])->name('reset');
});

/*
|--------------------------------------------------------------------------
| BANGUN PAGI ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('bangun-pagi')->name('bangun-pagi.')->group(function () {
    Route::get('/', [BangunPagiController::class, 'index'])->name('index');
    Route::post('/checklist', [BangunPagiController::class, 'checklist'])->name('checklist');
    Route::get('/{id}/get-waktu', [BangunPagiController::class, 'getWaktu'])->name('get-waktu');
    Route::put('/{id}/update-waktu', [BangunPagiController::class, 'updateWaktu'])->name('update-waktu');
    Route::delete('/{id}/hapus-checklist', [BangunPagiController::class, 'hapusChecklist'])->name('hapus-checklist');
    Route::get('/{id}', [BangunPagiController::class, 'show'])->name('show');
    Route::delete('/{id}', [BangunPagiController::class, 'destroy'])->name('destroy');
    Route::get('/laporan/{siswaId}', [BangunPagiController::class, 'laporanSiswa'])->name('laporan-siswa');
});

// Routes untuk Paraf Orang Tua - BANGUN PAGI
Route::prefix('paraf-ortu-bangun-pagi')->name('paraf-ortu-bangun-pagi.')->group(function () {
    Route::get('/', [ParafOrtuBangunPagiController::class, 'index'])->name('index');
    Route::get('/create', [ParafOrtuBangunPagiController::class, 'create'])->name('create');
    Route::post('/', [ParafOrtuBangunPagiController::class, 'store'])->name('store');
    Route::get('/{id}', [ParafOrtuBangunPagiController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ParafOrtuBangunPagiController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ParafOrtuBangunPagiController::class, 'update'])->name('update');
    Route::delete('/{id}', [ParafOrtuBangunPagiController::class, 'destroy'])->name('destroy');
    
    Route::post('/checklist', [ParafOrtuBangunPagiController::class, 'checklistParaf'])->name('checklist');
    Route::delete('/{id}/batalkan', [ParafOrtuBangunPagiController::class, 'batalkanParaf'])->name('batalkan');
    Route::get('/{id}/verifikasi', [ParafOrtuBangunPagiController::class, 'verifikasi'])->name('verifikasi');
    Route::get('/{id}/batalkan-verifikasi', [ParafOrtuBangunPagiController::class, 'batalkanVerifikasi'])->name('batalkan-verifikasi');
    Route::get('/riwayat', [ParafOrtuBangunPagiController::class, 'riwayat'])->name('riwayat');
    Route::get('/laporan', [ParafOrtuBangunPagiController::class, 'laporan'])->name('laporan');
});

// Routes untuk Pengaturan Waktu Bangun Pagi (admin only)
Route::middleware(['auth', 'admin'])->prefix('pengaturan-waktu-bangun-pagi')->name('pengaturan-waktu-bangun-pagi.')->group(function () {
    Route::get('/', [PengaturanWaktuBangunPagiController::class, 'index'])->name('index');
    Route::get('/create', [PengaturanWaktuBangunPagiController::class, 'create'])->name('create');
    Route::post('/store', [PengaturanWaktuBangunPagiController::class, 'store'])->name('store');
    Route::get('/edit', [PengaturanWaktuBangunPagiController::class, 'edit'])->name('edit');
    Route::put('/update', [PengaturanWaktuBangunPagiController::class, 'update'])->name('update');
    Route::delete('/destroy', [PengaturanWaktuBangunPagiController::class, 'destroy'])->name('destroy');
    Route::put('/reset', [PengaturanWaktuBangunPagiController::class, 'reset'])->name('reset');
});

/*
|--------------------------------------------------------------------------
| BERIBADAH ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('beribadah')->name('beribadah.')->group(function () {
    Route::get('/', [BeribadahController::class, 'index'])->name('index');
    Route::get('/create', [BeribadahController::class, 'create'])->name('create');
    Route::post('/', [BeribadahController::class, 'store'])->name('store');
    Route::get('/{id}', [BeribadahController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BeribadahController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BeribadahController::class, 'update'])->name('update');
    Route::delete('/{id}', [BeribadahController::class, 'destroy'])->name('destroy');
    
    Route::post('/checklist', [BeribadahController::class, 'storeChecklist'])->name('store-checklist');
    Route::put('/checklist/{id}/update-waktu', [BeribadahController::class, 'updateWaktuChecklist'])->name('update-waktu-checklist');
    Route::delete('/checklist/{id}/hapus-checklist', [BeribadahController::class, 'hapusChecklist'])->name('hapus-checklist');
    
    Route::post('/{id}/paraf-semua', [BeribadahController::class, 'parafSemua'])->name('paraf-semua');
    Route::post('/{id}/paraf/{sholat}', [BeribadahController::class, 'parafOrtu'])->name('paraf-ortu');
    Route::delete('/{id}/paraf/{sholat}', [BeribadahController::class, 'batalParaf'])->name('batal-paraf');
    
    Route::get('/laporan/siswa/{siswaId}', [BeribadahController::class, 'laporanSiswa'])->name('laporan-siswa');
});

// Routes untuk Paraf Orang Tua Beribadah

// Paraf Orangtua Beribadah Routes
Route::prefix('paraf-ortu-beribadah')->name('paraf-ortu-beribadah.')->group(function () {
    Route::get('/', [ParafOrtuBeribadahController::class, 'index'])->name('index');
    Route::get('/detail/{id}', [ParafOrtuBeribadahController::class, 'getDetail'])->name('get-detail');
    Route::post('/store', [ParafOrtuBeribadahController::class, 'store'])->name('store');
    Route::post('/batalkan-paraf/{id}', [ParafOrtuBeribadahController::class, 'batalkanParaf'])->name('batalkan-paraf');
});

// Routes untuk Pengaturan Waktu Beribadah (admin only)
Route::prefix('pengaturan-waktu-beribadah')->name('pengaturan-waktu-beribadah.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [PengaturanWaktuBeribadahController::class, 'index'])->name('index');
    Route::get('/create', [PengaturanWaktuBeribadahController::class, 'create'])->name('create');
    Route::post('/', [PengaturanWaktuBeribadahController::class, 'store'])->name('store');
    Route::get('/edit/{jenisSholat}', [PengaturanWaktuBeribadahController::class, 'edit'])->name('edit');
    Route::put('/{jenisSholat}', [PengaturanWaktuBeribadahController::class, 'update'])->name('update');
    Route::delete('/{jenisSholat}', [PengaturanWaktuBeribadahController::class, 'destroy'])->name('destroy');
    Route::put('/reset/{jenisSholat}', [PengaturanWaktuBeribadahController::class, 'reset'])->name('reset');
    Route::put('/reset-all', [PengaturanWaktuBeribadahController::class, 'resetAll'])->name('reset-all');
    Route::put('/toggle-status/{jenisSholat}', [PengaturanWaktuBeribadahController::class, 'toggleStatus'])->name('toggle-status');
});

/*
|--------------------------------------------------------------------------
| BERMASYARAKAT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('bermasyarakat')->name('bermasyarakat.')->group(function () {
    Route::get('/', [BermasyarakatController::class, 'index'])->name('index');
    Route::get('/create', [BermasyarakatController::class, 'create'])->name('create');
    Route::post('/', [BermasyarakatController::class, 'store'])->name('store');
    Route::get('/{id}', [BermasyarakatController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [BermasyarakatController::class, 'edit'])->name('edit');
    Route::put('/{id}', [BermasyarakatController::class, 'update'])->name('update');
    Route::delete('/{id}', [BermasyarakatController::class, 'destroy'])->name('destroy');
    
    Route::post('/{id}/paraf-ortu', [BermasyarakatController::class, 'updateParafOrtu'])->name('update-paraf-ortu');
});

// Routes untuk Paraf Orang Tua Bermasyarakat
Route::prefix('paraf-ortu-bermasyarakat')->name('paraf-ortu-bermasyarakat.')->group(function () {
    Route::get('/', [ParafOrtuBermasyarakatController::class, 'index'])->name('index');
    Route::post('/update-paraf/{id}', [ParafOrtuBermasyarakatController::class, 'updateParaf'])->name('update-paraf');
    Route::post('/batalkan-paraf/{id}', [ParafOrtuBermasyarakatController::class, 'batalkanParaf'])->name('batalkan-paraf');
    Route::post('/', [ParafOrtuBermasyarakatController::class, 'store'])->name('store');
    Route::get('/detail/{id}', [ParafOrtuBermasyarakatController::class, 'getDetailForParaf'])->name('detail');
    Route::get('/check/{id}', [ParafOrtuBermasyarakatController::class, 'checkParafExists'])->name('check');
    Route::get('/statistik', [ParafOrtuBermasyarakatController::class, 'getStatistik'])->name('statistik');
    Route::delete('/{id}', [ParafOrtuBermasyarakatController::class, 'destroy'])->name('destroy');
});

// Routes untuk Nilai Manual Bermasyarakat (dilindungi auth)
Route::prefix('nilai-manual-bermasyarakat')->name('nilai-manual-bermasyarakat.')->middleware(['auth'])->group(function () {
    Route::get('/', [NilaiManualBermasyarakatController::class, 'index'])->name('index');
    Route::post('/simpan/{id}', [NilaiManualBermasyarakatController::class, 'simpanNilai'])->name('simpan');
    Route::delete('/reset/{id}', [NilaiManualBermasyarakatController::class, 'resetNilai'])->name('reset');
});

/*
|--------------------------------------------------------------------------
| TIDUR CEPAT ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('tidur-cepat')->name('tidur-cepat.')->group(function () {
    Route::get('/', [TidurCepatController::class, 'index'])->name('index');
    Route::get('/create', [TidurCepatController::class, 'create'])->name('create');
    Route::post('/', [TidurCepatController::class, 'store'])->name('store');
    Route::get('/{id}', [TidurCepatController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [TidurCepatController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TidurCepatController::class, 'update'])->name('update');
    Route::delete('/{id}', [TidurCepatController::class, 'destroy'])->name('destroy');
    
    Route::post('/checklist', [TidurCepatController::class, 'checklist'])->name('checklist');
    Route::post('/uncheck', [TidurCepatController::class, 'uncheck'])->name('uncheck');
    Route::get('/get-today', [TidurCepatController::class, 'getToday'])->name('get-today');
    
    Route::post('/checklist-siswa', [TidurCepatController::class, 'checklistSiswa'])->name('checklist-siswa');
    Route::get('/cek-hari-ini', [TidurCepatController::class, 'cekHariIni'])->name('cek-hari-ini');
    
    Route::get('/{id}/get-data', [TidurCepatController::class, 'getData'])->name('get-data');
    Route::put('/{id}/update-waktu', [TidurCepatController::class, 'updateWaktu'])->name('update-waktu');
    Route::delete('/{id}/hapus-checklist', [TidurCepatController::class, 'hapusChecklist'])->name('hapus-checklist');
    
    Route::get('/statistik', [TidurCepatController::class, 'statistik'])->name('statistik');
    Route::get('/riwayat', [TidurCepatController::class, 'riwayat'])->name('riwayat');
});

// Route khusus untuk halaman checklist siswa
Route::get('/siswa/tidur-cepat', [TidurCepatController::class, 'checklistSiswaView'])->name('tidur-cepat.siswa');

// Routes Pengaturan Waktu Tidur Cepat
Route::prefix('pengaturan-waktu-tidur-cepat')->name('pengaturan-waktu-tidur-cepat.')->group(function () {
    Route::get('/', [PengaturanWaktuTidurCepatController::class, 'index'])->name('index');
    Route::get('/create', [PengaturanWaktuTidurCepatController::class, 'create'])->name('create');
    Route::post('/store', [PengaturanWaktuTidurCepatController::class, 'store'])->name('store');
    Route::get('/edit', [PengaturanWaktuTidurCepatController::class, 'edit'])->name('edit');
    Route::put('/update', [PengaturanWaktuTidurCepatController::class, 'update'])->name('update');
    Route::delete('/destroy', [PengaturanWaktuTidurCepatController::class, 'destroy'])->name('destroy');
    Route::put('/reset', [PengaturanWaktuTidurCepatController::class, 'reset'])->name('reset');
});

/*
|--------------------------------------------------------------------------
| TANGGAPAN ORANGTUA ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('tanggapan-orangtua')->name('tanggapan-orangtua.')->group(function () {
    Route::get('/', [TanggapanOrangtuaController::class, 'index'])->name('index');
    Route::get('/create', [TanggapanOrangtuaController::class, 'create'])->name('create');
    Route::post('/', [TanggapanOrangtuaController::class, 'store'])->name('store');
    Route::get('/{id}', [TanggapanOrangtuaController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [TanggapanOrangtuaController::class, 'edit'])->name('edit');
    Route::put('/{id}', [TanggapanOrangtuaController::class, 'update'])->name('update');
    Route::delete('/{id}', [TanggapanOrangtuaController::class, 'destroy'])->name('destroy');
    
    Route::get('/{siswa}/orangtua-options', [TanggapanOrangtuaController::class, 'getOrangtuaOptions'])
        ->name('orangtua-options');
    Route::post('/check', [TanggapanOrangtuaController::class, 'checkTanggapan'])
        ->name('check');
    Route::get('/print', [TanggapanOrangtuaController::class, 'print'])
        ->name('print');
    Route::get('/dashboard', [TanggapanOrangtuaController::class, 'dashboard'])
        ->name('dashboard');
});

// Route untuk admin dengan middleware
Route::middleware(['auth', 'role:admin,guru'])->prefix('admin/tanggapan-orangtua')->name('admin.tanggapan-orangtua.')->group(function () {
    Route::get('/', [TanggapanOrangtuaController::class, 'adminIndex'])->name('index');
    Route::get('/{id}', [TanggapanOrangtuaController::class, 'adminShow'])->name('show');
    Route::get('/export/excel', [TanggapanOrangtuaController::class, 'adminExportExcel'])->name('export-excel');
    Route::get('/export/pdf', [TanggapanOrangtuaController::class, 'adminExportPdf'])->name('export-pdf');
    
    Route::post('/{id}/force-submit', [TanggapanOrangtuaController::class, 'adminForceSubmit'])->name('force-submit');
    Route::post('/{id}/force-cancel', [TanggapanOrangtuaController::class, 'adminForceCancel'])->name('force-cancel');
    Route::delete('/{id}/force-delete', [TanggapanOrangtuaController::class, 'adminForceDelete'])->name('force-delete');
});

// API routes untuk AJAX calls
Route::prefix('api/tanggapan-orangtua')->name('api.tanggapan-orangtua.')->group(function () {
    Route::get('/siswa/{siswaId}', [TanggapanOrangtuaController::class, 'apiGetBySiswa']);
    Route::get('/bulanan/{siswaId}/{bulan}/{tahun}', [TanggapanOrangtuaController::class, 'apiGetBulanan']);
    Route::post('/save-draft', [TanggapanOrangtuaController::class, 'apiSaveDraft']);
    Route::post('/save-tanda-tangan', [TanggapanOrangtuaController::class, 'apiSaveTandaTangan']);
});

/*
|--------------------------------------------------------------------------
| REKAP PEMANTAUAN ROUTES
|--------------------------------------------------------------------------
*/

Route::resource('rekap-pemantauan', RekapPemantauanKebiasaanController::class);
Route::get('/rekap-pemantauan/{id}/export-pdf', [RekapPemantauanKebiasaanController::class, 'exportPDF'])
    ->name('rekap-pemantauan.export-pdf');
Route::post('/rekap-pemantauan/export-rekap-kelas', [RekapPemantauanKebiasaanController::class, 'exportRekapKelas'])
    ->name('rekap-pemantauan.export-rekap-kelas');
Route::get('/rekap-pemantauan/statistics', [RekapPemantauanKebiasaanController::class, 'statistics'])
    ->name('rekap-pemantauan.statistics');

// API Routes tambahan
Route::prefix('api')->group(function () {
    Route::get('/paraf/check/{id}', [ParafOrtuBangunPagiController::class, 'checkParafExists']);
    Route::get('/paraf/detail/{id}', [ParafOrtuBangunPagiController::class, 'getDetailForParaf']);
    Route::get('/paraf/statistik', [ParafOrtuBangunPagiController::class, 'getStatistik']);
    
    // API Routes untuk Orangtua
    Route::get('/orangtua/get-names', [DataOrangtuaController::class, 'getNamesForDropdown']);
    Route::get('/orangtua/by-siswa/{siswaId}', [DataOrangtuaController::class, 'getBySiswaId']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});




// Route Orangtua
Route::prefix('orangtua')->middleware(['orangtua.auth'])->group(function () {
    Route::get('/dashboard', [OrangtuaDashboardController::class, 'index'])->name('orangtua.dashboard');
    Route::get('/profile', [OrangtuaDashboardController::class, 'profile'])->name('orangtua.profile');
    Route::get('/profil-anak', [OrangtuaDashboardController::class, 'profilAnak'])->name('orangtua.profil-anak');
    Route::post('/logout', [OrangtuaDashboardController::class, 'logout'])->name('orangtua.logout');
});
require __DIR__.'/auth.php';