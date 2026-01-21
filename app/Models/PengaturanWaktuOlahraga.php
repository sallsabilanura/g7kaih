<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengaturanWaktuOlahraga extends Model
{
    use HasFactory;

    // TAMBAHKAN INI
    protected $table = 'pengaturan_waktu_olahraga'; // tanpa 's'

    protected $fillable = [
        'waktu_sangat_baik_start',
        'waktu_sangat_baik_end',
        'nilai_sangat_baik',
        'waktu_baik_start',
        'waktu_baik_end',
        'nilai_baik',
        'waktu_cukup_start',
        'waktu_cukup_end',
        'nilai_cukup',
        'nilai_kurang',
        'durasi_minimal',
        'durasi_maksimal',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Hitung nilai berdasarkan waktu mulai olahraga
     */
    public function hitungNilai($waktuMulai)
    {
        $waktu = Carbon::parse($waktuMulai);
        
        // Cek kategori sangat baik
        $sangatBaikStart = Carbon::parse($this->waktu_sangat_baik_start);
        $sangatBaikEnd = Carbon::parse($this->waktu_sangat_baik_end);
        
        if ($waktu->between($sangatBaikStart, $sangatBaikEnd)) {
            return [
                'nilai' => $this->nilai_sangat_baik,
                'kategori' => 'sangat_baik',
                'label' => 'Sangat Baik'
            ];
        }
        
        // Cek kategori baik
        $baikStart = Carbon::parse($this->waktu_baik_start);
        $baikEnd = Carbon::parse($this->waktu_baik_end);
        
        if ($waktu->between($baikStart, $baikEnd)) {
            return [
                'nilai' => $this->nilai_baik,
                'kategori' => 'baik',
                'label' => 'Baik'
            ];
        }
        
        // Cek kategori cukup
        $cukupStart = Carbon::parse($this->waktu_cukup_start);
        $cukupEnd = Carbon::parse($this->waktu_cukup_end);
        
        if ($waktu->between($cukupStart, $cukupEnd)) {
            return [
                'nilai' => $this->nilai_cukup,
                'kategori' => 'cukup',
                'label' => 'Cukup'
            ];
        }
        
        // Selain itu, kategori kurang
        return [
            'nilai' => $this->nilai_kurang,
            'kategori' => 'kurang',
            'label' => 'Kurang'
        ];
    }

    /**
     * Accessor untuk format waktu sangat baik
     */
    public function getWaktuSangatBaikDisplayAttribute()
    {
        return Carbon::parse($this->waktu_sangat_baik_start)->format('H:i') . ' - ' . 
               Carbon::parse($this->waktu_sangat_baik_end)->format('H:i');
    }

    /**
     * Accessor untuk format waktu baik
     */
    public function getWaktuBaikDisplayAttribute()
    {
        return Carbon::parse($this->waktu_baik_start)->format('H:i') . ' - ' . 
               Carbon::parse($this->waktu_baik_end)->format('H:i');
    }

    /**
     * Accessor untuk format waktu cukup
     */
    public function getWaktuCukupDisplayAttribute()
    {
        return Carbon::parse($this->waktu_cukup_start)->format('H:i') . ' - ' . 
               Carbon::parse($this->waktu_cukup_end)->format('H:i');
    }

    /**
     * Scope untuk pengaturan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get pengaturan aktif (singleton)
     */
    public static function getPengaturanAktif()
    {
        return self::active()->first();
    }

    /**
     * Validasi durasi olahraga
     */
    public function validasiDurasi($durasiMenit)
    {
        if ($durasiMenit < $this->durasi_minimal) {
            return [
                'valid' => false,
                'message' => "Durasi olahraga minimal {$this->durasi_minimal} menit"
            ];
        }
        
        if ($durasiMenit > $this->durasi_maksimal) {
            return [
                'valid' => false,
                'message' => "Durasi olahraga maksimal {$this->durasi_maksimal} menit"
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Durasi valid'
        ];
    }
}