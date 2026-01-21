<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\BangunPagi;

class BangunPagiHelper
{
    /**
     * Generate array tanggal untuk bulan ini dengan status checklist
     */
    public static function getKalenderBulanIni($siswaId, $bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? now()->translatedFormat('F');
        $tahun = $tahun ?? now()->year;
        
        // Convert bulan Indonesia ke angka
        $bulanAngka = self::bulanToNumber($bulan);
        
        // Ambil jumlah hari dalam bulan
        $jumlahHari = Carbon::create($tahun, $bulanAngka, 1)->daysInMonth;
        
        // Ambil data bangun pagi bulan ini
        $dataBangunPagi = BangunPagi::where('siswa_id', $siswaId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->keyBy('tanggal');
        
        $kalender = [];
        
        for ($hari = 1; $hari <= $jumlahHari; $hari++) {
            $tanggal = Carbon::create($tahun, $bulanAngka, $hari)->format('Y-m-d');
            $tanggalCarbon = Carbon::parse($tanggal);
            
            // Cek apakah tanggal sudah lewat atau hari ini
            $sudahLewat = $tanggalCarbon->isPast() || $tanggalCarbon->isToday();
            
            $item = [
                'tanggal' => $tanggal,
                'tanggal_formatted' => $tanggalCarbon->translatedFormat('d F Y'),
                'hari' => $tanggalCarbon->translatedFormat('l'),
                'sudah_lewat' => $sudahLewat,
                'is_today' => $tanggalCarbon->isToday(),
                'data' => null,
                'sudah_checklist' => false,
            ];
            
            // Jika ada data bangun pagi
            if (isset($dataBangunPagi[$tanggal])) {
                $item['data'] = $dataBangunPagi[$tanggal];
                $item['sudah_checklist'] = $dataBangunPagi[$tanggal]->sudah_bangun;
            }
            
            $kalender[] = $item;
        }
        
        return $kalender;
    }
    
    /**
     * Convert nama bulan Indonesia ke angka
     */
    private static function bulanToNumber($bulan)
    {
        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];
        
        return $bulanMap[$bulan] ?? now()->month;
    }
}