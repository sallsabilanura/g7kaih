<?php
/**
 * File: app/Helpers/BeribadahHelper.php
 * 
 * Helper functions untuk sistem beribadah
 * Fungsi untuk mengecek status gembok sholat berdasarkan pengaturan waktu dari database
 */

if (!function_exists('cekKunciSholat')) {
    /**
     * Cek apakah sholat terkunci (belum bisa dichecklist)
     * Sholat terbuka untuk checklist mulai dari waktu_tepat_start
     * 
     * @param string $jenisSholat (subuh, dzuhur, ashar, maghrib, isya)
     * @param string|null $tanggal Format: Y-m-d (optional, default: today)
     * @return bool true = terkunci, false = terbuka
     */
    function cekKunciSholat($jenisSholat, $tanggal = null)
    {
        // Jika tanggal tidak diisi, gunakan tanggal hari ini
        if (!$tanggal) {
            $tanggal = now()->format('Y-m-d');
        }
        
        // Cek apakah tanggal adalah hari ini
        $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
        
        // Jika bukan hari ini, langsung terkunci
        if (!$isToday) {
            return true;
        }
        
        // Ambil pengaturan waktu dari database
        $pengaturan = \App\Models\PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)
            ->where('is_active', true)
            ->first();
        
        // Jika tidak ada pengaturan atau tidak aktif, terkunci
        if (!$pengaturan) {
            return true;
        }
        
        // Ambil waktu sekarang
        $now = now();
        $currentTime = $now->format('H:i:s');
        
        // Ambil waktu mulai dari pengaturan (waktu_tepat_start)
        $waktuMulai = $pengaturan->waktu_tepat_start;
        
        // Sholat terbuka jika waktu sekarang >= waktu_tepat_start
        // Contoh: Jika waktu_tepat_start = 04:00:00 dan sekarang jam 04:30:00, maka terbuka
        if ($currentTime >= $waktuMulai) {
            return false; // Tidak terkunci (bisa dichecklist)
        }
        
        return true; // Terkunci (belum waktunya)
    }
}

if (!function_exists('getWaktuBukaSholat')) {
    /**
     * Mendapatkan waktu buka sholat (waktu_tepat_start)
     * 
     * @param string $jenisSholat
     * @return string|null Format: H:i (misal: "04:00")
     */
    function getWaktuBukaSholat($jenisSholat)
    {
        $pengaturan = \App\Models\PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)
            ->where('is_active', true)
            ->first();
        
        if (!$pengaturan) {
            return null;
        }
        
        return \Carbon\Carbon::parse($pengaturan->waktu_tepat_start)->format('H:i');
    }
}

if (!function_exists('getStatusSholat')) {
    /**
     * Mendapatkan status lengkap sholat (terkunci/terbuka)
     * 
     * @param string $jenisSholat
     * @param string|null $tanggal
     * @return array
     */
    function getStatusSholat($jenisSholat, $tanggal = null)
    {
        $isLocked = cekKunciSholat($jenisSholat, $tanggal);
        $waktuBuka = getWaktuBukaSholat($jenisSholat);
        
        $status = [
            'is_locked' => $isLocked,
            'waktu_buka' => $waktuBuka,
            'message' => ''
        ];
        
        if (!$tanggal) {
            $tanggal = now()->format('Y-m-d');
        }
        
        $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
        
        if (!$isToday) {
            $status['message'] = 'Tidak bisa checklist';
        } elseif ($isLocked) {
            $status['message'] = $waktuBuka ? "Buka jam {$waktuBuka}" : 'Belum waktunya';
        } else {
            $status['message'] = 'Bisa checklist';
        }
        
        return $status;
    }
}

if (!function_exists('getAllStatusSholat')) {
    /**
     * Mendapatkan status semua sholat untuk tanggal tertentu
     * 
     * @param string|null $tanggal
     * @return array
     */
    function getAllStatusSholat($tanggal = null)
    {
        $jenisSholat = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
        $statuses = [];
        
        foreach ($jenisSholat as $sholat) {
            $statuses[$sholat] = getStatusSholat($sholat, $tanggal);
        }
        
        return $statuses;
    }
}