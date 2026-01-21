<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanWaktuMakan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_waktu_makan';

    protected $fillable = [
        'jenis_makanan',
        'waktu_100_start',
        'waktu_100_end',
        'waktu_70_start',
        'waktu_70_end',
        'nilai_100',
        'nilai_70',
        'nilai_terlambat',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Label untuk jenis makanan
    const JENIS_MAKANAN_LABELS = [
        'sarapan' => 'Sarapan',
        'makan_siang' => 'Makan Siang',
        'makan_malam' => 'Makan Malam'
    ];

    // Accessor untuk label
    public function getJenisMakananLabelAttribute()
    {
        return self::JENIS_MAKANAN_LABELS[$this->jenis_makanan] ?? $this->jenis_makanan;
    }

    // Accessor untuk format waktu yang lebih mudah
    public function getWaktu100StartFormattedAttribute()
    {
        return substr($this->waktu_100_start, 0, 5);
    }

    public function getWaktu100EndFormattedAttribute()
    {
        return substr($this->waktu_100_end, 0, 5);
    }

    public function getWaktu70StartFormattedAttribute()
    {
        return substr($this->waktu_70_start, 0, 5);
    }

    public function getWaktu70EndFormattedAttribute()
    {
        return substr($this->waktu_70_end, 0, 5);
    }

    /**
     * Format waktu untuk display yang lebih user-friendly
     */
    public function getWaktu100DisplayAttribute()
    {
        return $this->formatWaktuDisplay($this->waktu_100_start_formatted, $this->waktu_100_end_formatted);
    }

    public function getWaktu70DisplayAttribute()
    {
        return $this->formatWaktuDisplay($this->waktu_70_start_formatted, $this->waktu_70_end_formatted);
    }

    /**
     * Format waktu dengan AM/PM
     */
    private function formatWaktuDisplay($start, $end)
    {
        try {
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $start);
            $endTime = \Carbon\Carbon::createFromFormat('H:i', $end);
            
            return $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
        } catch (\Exception $e) {
            return $start . ' - ' . $end;
        }
    }

    /**
     * Hitung nilai berdasarkan waktu makan - FLEKSIBEL
     */
    public function hitungNilai($waktuMakan)
    {
        try {
            // Convert waktu makan ke format H:i
            if (strlen($waktuMakan) > 5) {
                $waktuMakan = substr($waktuMakan, 0, 5);
            }
            
            // Konversi semua waktu ke menit sejak midnight untuk perbandingan yang lebih mudah
            $waktuMakanMinutes = $this->timeToMinutes($waktuMakan);
            
            $start100Minutes = $this->timeToMinutes(substr($this->waktu_100_start, 0, 5));
            $end100Minutes = $this->timeToMinutes(substr($this->waktu_100_end, 0, 5));
            
            $start70Minutes = $this->timeToMinutes(substr($this->waktu_70_start, 0, 5));
            $end70Minutes = $this->timeToMinutes(substr($this->waktu_70_end, 0, 5));
            
            // Cek rentang waktu - SANGAT FLEKSIBEL
            if ($waktuMakanMinutes >= $start100Minutes && $waktuMakanMinutes <= $end100Minutes) {
                return $this->nilai_100;
            } elseif ($waktuMakanMinutes >= $start70Minutes && $waktuMakanMinutes <= $end70Minutes) {
                return $this->nilai_70;
            } else {
                return $this->nilai_terlambat;
            }
        } catch (\Exception $e) {
            \Log::error('Error hitung nilai:', [
                'jenis' => $this->jenis_makanan,
                'waktu' => $waktuMakan,
                'error' => $e->getMessage()
            ]);
            return $this->nilai_terlambat;
        }
    }

    /**
     * Convert time string (H:i) to minutes since midnight
     */
    private function timeToMinutes($time)
    {
        $parts = explode(':', $time);
        return ((int)$parts[0] * 60) + (int)$parts[1];
    }

    // Scope
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get pengaturan berdasarkan jenis makanan
     */
    public static function getByJenis($jenisMakanan)
    {
        return self::where('jenis_makanan', $jenisMakanan)->active()->first();
    }

    /**
     * Get semua pengaturan
     */
    public static function getAll()
    {
        return self::orderByRaw("FIELD(jenis_makanan, 'sarapan', 'makan_siang', 'makan_malam')")->get();
    }
}