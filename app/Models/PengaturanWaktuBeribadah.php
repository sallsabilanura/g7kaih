<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengaturanWaktuBeribadah extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_waktu_beribadahs';

    protected $fillable = [
        'jenis_sholat',
        'waktu_tepat_start', 'waktu_tepat_end', 'nilai_tepat',
        'waktu_terlambat_start', 'waktu_terlambat_end', 'nilai_terlambat',
        'nilai_tidak_sholat', 'is_active',
    ];

    protected $casts = [
        'nilai_tepat' => 'integer',
        'nilai_terlambat' => 'integer',
        'nilai_tidak_sholat' => 'integer',
        'is_active' => 'boolean',
    ];

    const JENIS_SHOLAT = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
    
    const SHOLAT_LABELS = [
        'subuh' => 'Subuh',
        'dzuhur' => 'Dzuhur',
        'ashar' => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya' => 'Isya'
    ];

    const SHOLAT_COLORS = [
        'subuh' => ['bg' => 'from-blue-400 to-indigo-500', 'border' => 'border-blue-300', 'light' => 'bg-blue-50'],
        'dzuhur' => ['bg' => 'from-yellow-400 to-orange-500', 'border' => 'border-yellow-300', 'light' => 'bg-yellow-50'],
        'ashar' => ['bg' => 'from-orange-400 to-red-500', 'border' => 'border-orange-300', 'light' => 'bg-orange-50'],
        'maghrib' => ['bg' => 'from-purple-400 to-pink-500', 'border' => 'border-purple-300', 'light' => 'bg-purple-50'],
        'isya' => ['bg' => 'from-gray-600 to-gray-800', 'border' => 'border-gray-400', 'light' => 'bg-gray-100'],
    ];

    // Accessor untuk format waktu
    public function getWaktuTepatStartFormattedAttribute()
    {
        return $this->waktu_tepat_start ? Carbon::parse($this->waktu_tepat_start)->format('H:i') : null;
    }

    public function getWaktuTepatEndFormattedAttribute()
    {
        return $this->waktu_tepat_end ? Carbon::parse($this->waktu_tepat_end)->format('H:i') : null;
    }

    public function getWaktuTerlambatStartFormattedAttribute()
    {
        return $this->waktu_terlambat_start ? Carbon::parse($this->waktu_terlambat_start)->format('H:i') : null;
    }

    public function getWaktuTerlambatEndFormattedAttribute()
    {
        return $this->waktu_terlambat_end ? Carbon::parse($this->waktu_terlambat_end)->format('H:i') : null;
    }

    public function getWaktuTepatDisplayAttribute()
    {
        return $this->waktu_tepat_start_formatted . ' - ' . $this->waktu_tepat_end_formatted;
    }

    public function getWaktuTerlambatDisplayAttribute()
    {
        return $this->waktu_terlambat_start_formatted . ' - ' . $this->waktu_terlambat_end_formatted;
    }

    public function getJenisSholatLabelAttribute()
    {
        return self::SHOLAT_LABELS[$this->jenis_sholat] ?? ucfirst($this->jenis_sholat);
    }

    public function getSholatColorAttribute()
    {
        return self::SHOLAT_COLORS[$this->jenis_sholat] ?? self::SHOLAT_COLORS['subuh'];
    }

    // Get semua pengaturan aktif
    public static function getAllActive()
    {
        return self::where('is_active', true)
            ->orderByRaw("FIELD(jenis_sholat, 'subuh', 'dzuhur', 'ashar', 'maghrib', 'isya')")
            ->get();
    }

    // Get atau buat default untuk semua sholat
    public static function getOrCreateDefaults()
    {
        $defaults = self::getDefaultSettings();
        
        foreach ($defaults as $jenis => $setting) {
            self::firstOrCreate(
                ['jenis_sholat' => $jenis],
                $setting
            );
        }

        return self::orderByRaw("FIELD(jenis_sholat, 'subuh', 'dzuhur', 'ashar', 'maghrib', 'isya')")->get();
    }

    // Default settings
    public static function getDefaultSettings()
    {
        return [
            'subuh' => [
                'waktu_tepat_start' => '04:30:00',
                'waktu_tepat_end' => '05:15:00',
                'nilai_tepat' => 100,
                'waktu_terlambat_start' => '05:15:00',
                'waktu_terlambat_end' => '06:00:00',
                'nilai_terlambat' => 70,
                'nilai_tidak_sholat' => 0,
                'is_active' => true,
            ],
            'dzuhur' => [
                'waktu_tepat_start' => '12:00:00',
                'waktu_tepat_end' => '12:30:00',
                'nilai_tepat' => 100,
                'waktu_terlambat_start' => '12:30:00',
                'waktu_terlambat_end' => '15:00:00',
                'nilai_terlambat' => 70,
                'nilai_tidak_sholat' => 0,
                'is_active' => true,
            ],
            'ashar' => [
                'waktu_tepat_start' => '15:00:00',
                'waktu_tepat_end' => '15:30:00',
                'nilai_tepat' => 100,
                'waktu_terlambat_start' => '15:30:00',
                'waktu_terlambat_end' => '17:30:00',
                'nilai_terlambat' => 70,
                'nilai_tidak_sholat' => 0,
                'is_active' => true,
            ],
            'maghrib' => [
                'waktu_tepat_start' => '18:00:00',
                'waktu_tepat_end' => '18:30:00',
                'nilai_tepat' => 100,
                'waktu_terlambat_start' => '18:30:00',
                'waktu_terlambat_end' => '19:30:00',
                'nilai_terlambat' => 70,
                'nilai_tidak_sholat' => 0,
                'is_active' => true,
            ],
            'isya' => [
                'waktu_tepat_start' => '19:00:00',
                'waktu_tepat_end' => '20:00:00',
                'nilai_tepat' => 100,
                'waktu_terlambat_start' => '20:00:00',
                'waktu_terlambat_end' => '22:00:00',
                'nilai_terlambat' => 70,
                'nilai_tidak_sholat' => 0,
                'is_active' => true,
            ],
        ];
    }

    // Reset ke default
    public function resetToDefault()
    {
        $defaults = self::getDefaultSettings();
        if (isset($defaults[$this->jenis_sholat])) {
            $this->update($defaults[$this->jenis_sholat]);
        }
        return $this;
    }
}