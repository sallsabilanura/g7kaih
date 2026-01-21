<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanWaktuBangunPagi extends Model
{
    use HasFactory;

    protected $fillable = [
        'waktu_100_start',
        'waktu_100_end',
        'nilai_100',
        'waktu_70_start',
        'waktu_70_end',
        'nilai_70',
        'nilai_terlambat',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nilai_100' => 'integer',
        'nilai_70' => 'integer',
        'nilai_terlambat' => 'integer',
    ];

    // Mutator untuk format waktu
    public function setWaktu100StartAttribute($value)
    {
        $this->attributes['waktu_100_start'] = $this->formatTimeToDb($value);
    }

    public function setWaktu100EndAttribute($value)
    {
        $this->attributes['waktu_100_end'] = $this->formatTimeToDb($value);
    }

    public function setWaktu70StartAttribute($value)
    {
        $this->attributes['waktu_70_start'] = $this->formatTimeToDb($value);
    }

    public function setWaktu70EndAttribute($value)
    {
        $this->attributes['waktu_70_end'] = $this->formatTimeToDb($value);
    }

    // Helper method untuk format waktu ke database
    private function formatTimeToDb($value)
    {
        if (empty($value)) {
            return null;
        }
        
        // Jika sudah format H:i, tambahkan :00
        if (preg_match('/^\d{2}:\d{2}$/', $value)) {
            return $value . ':00';
        }
        
        // Jika format H:i:s, return langsung
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        
        // Coba parse string time
        $time = strtotime($value);
        if ($time !== false) {
            return date('H:i:s', $time);
        }
        
        return $value;
    }

    // Accessor untuk format tampilan
    public function getWaktu100StartFormattedAttribute()
    {
        return $this->formatTimeForDisplay($this->waktu_100_start);
    }

    public function getWaktu100EndFormattedAttribute()
    {
        return $this->formatTimeForDisplay($this->waktu_100_end);
    }

    public function getWaktu70StartFormattedAttribute()
    {
        return $this->formatTimeForDisplay($this->waktu_70_start);
    }

    public function getWaktu70EndFormattedAttribute()
    {
        return $this->formatTimeForDisplay($this->waktu_70_end);
    }

    // Format untuk display (H:i)
    private function formatTimeForDisplay($time)
    {
        if (empty($time)) {
            return '00:00';
        }
        
        // Jika sudah format H:i:s, ambil hanya jam:menit
        if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $time, $matches)) {
            return $matches[1];
        }
        
        return substr($time, 0, 5);
    }

    // Untuk menampilkan dalam format 12 jam
    public function getWaktu100Display12hAttribute()
    {
        return $this->format12Hour($this->waktu_100_start) . ' - ' . $this->format12Hour($this->waktu_100_end);
    }
    
    public function getWaktu70Display12hAttribute()
    {
        return $this->format12Hour($this->waktu_70_start) . ' - ' . $this->format12Hour($this->waktu_70_end);
    }

    private function format12Hour($time)
    {
        if (empty($time)) {
            return '00:00 AM';
        }
        
        $time = substr($time, 0, 5);
        return date('h:i A', strtotime($time));
    }

    // Method untuk menghitung nilai berdasarkan waktu
    public function hitungNilai($waktuBangun)
    {
        if (!$waktuBangun) {
            return [
                'nilai' => 0,
                'kategori' => 'Belum Ada Data'
            ];
        }

        $waktuBangunTime = strtotime($waktuBangun);
        $start100 = strtotime($this->waktu_100_start);
        $end100 = strtotime($this->waktu_100_end);
        $start70 = strtotime($this->waktu_70_start);
        $end70 = strtotime($this->waktu_70_end);

        if ($waktuBangunTime >= $start100 && $waktuBangunTime <= $end100) {
            return [
                'nilai' => $this->nilai_100,
                'kategori' => 'Tepat Waktu'
            ];
        } elseif ($waktuBangunTime >= $start70 && $waktuBangunTime <= $end70) {
            return [
                'nilai' => $this->nilai_70,
                'kategori' => 'Sedikit Terlambat'
            ];
        } else {
            return [
                'nilai' => $this->nilai_terlambat,
                'kategori' => 'Terlambat'
            ];
        }
    }

    // Method untuk cek apakah waktu valid
    public function validateTimes()
    {
        $errors = [];

        // Cek format waktu
        $times = [
            'waktu_100_start' => $this->waktu_100_start,
            'waktu_100_end' => $this->waktu_100_end,
            'waktu_70_start' => $this->waktu_70_start,
            'waktu_70_end' => $this->waktu_70_end,
        ];

        foreach ($times as $key => $time) {
            if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $time)) {
                $errors[$key] = 'Format waktu tidak valid';
            }
        }

        // Cek waktu start < end
        if (strtotime($this->waktu_100_start) >= strtotime($this->waktu_100_end)) {
            $errors['waktu_100'] = 'Waktu mulai harus sebelum waktu selesai (Kategori 1)';
        }

        if (strtotime($this->waktu_70_start) >= strtotime($this->waktu_70_end)) {
            $errors['waktu_70'] = 'Waktu mulai harus sebelum waktu selesai (Kategori 2)';
        }

        // Cek gap antar kategori
        if (strtotime($this->waktu_100_end) > strtotime($this->waktu_70_start)) {
            $errors['gap'] = 'Waktu Kategori 2 harus dimulai setelah atau sama dengan waktu akhir Kategori 1';
        }

        return $errors;
    }
}