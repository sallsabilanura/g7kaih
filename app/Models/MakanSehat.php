<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MakanSehat extends Model
{
    use HasFactory;

    protected $table = 'makan_sehat';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'bulan',
        'jenis_makanan',
        'waktu_makan',
        'menu_makanan',
        'nilai',
        'dokumentasi_foto',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    /**
     * Hitung nilai berdasarkan pengaturan waktu dari database
     */
    public function hitungNilai($waktu, $jenis)
    {
        if (!$waktu) return 0;
        
        try {
            // Ambil pengaturan waktu dari database
            $pengaturan = PengaturanWaktuMakan::getByJenis($jenis);
            
            if (!$pengaturan || !$pengaturan->is_active) {
                \Log::warning('Pengaturan waktu tidak aktif atau tidak ditemukan untuk: ' . $jenis);
                return 50; // Default nilai
            }

            // Format waktu makan
            $waktuFormatted = $this->formatWaktuForCalculation($waktu);
            $waktuCarbon = Carbon::createFromFormat('H:i', $waktuFormatted);
            
            // Hitung nilai menggunakan method dari model PengaturanWaktuMakan
            return $pengaturan->hitungNilai($waktuFormatted);
            
        } catch (\Exception $e) {
            \Log::error('Error hitung nilai:', [
                'waktu' => $waktu,
                'jenis' => $jenis,
                'error' => $e->getMessage()
            ]);
            return 50;
        }
    }

    // Helper untuk format waktu perhitungan
    private function formatWaktuForCalculation($waktu)
    {
        // Jika format H:i:s, ambil hanya H:i
        if (strlen($waktu) > 5) {
            return substr($waktu, 0, 5);
        }
        return $waktu;
    }

    // Set tanggal dan bulan otomatis dengan Bahasa Indonesia
    public function setTanggalAttribute($value)
    {
        $this->attributes['tanggal'] = $value;
        
        // Format bulan dalam Bahasa Indonesia
        $carbonDate = Carbon::parse($value);
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
        
        $namaBulan = $bulanIndo[$carbonDate->month];
        $this->attributes['bulan'] = $namaBulan . ' ' . $carbonDate->year;
    }

    // Set nilai otomatis ketika waktu di-set
    public function setWaktuMakanAttribute($value)
    {
        $this->attributes['waktu_makan'] = $value;
        if ($value && $this->jenis_makanan) {
            $this->attributes['nilai'] = $this->hitungNilai($value, $this->jenis_makanan);
        }
    }

    public function setJenisMakananAttribute($value)
    {
        $this->attributes['jenis_makanan'] = $value;
        if ($this->waktu_makan && $value) {
            $this->attributes['nilai'] = $this->hitungNilai($this->waktu_makan, $value);
        }
    }

    // Update nilai sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->waktu_makan && $model->jenis_makanan) {
                $model->nilai = $model->hitungNilai($model->waktu_makan, $model->jenis_makanan);
            }
        });
    }

    // Accessor untuk label jenis makanan
    public function getJenisMakananLabelAttribute()
    {
        $labels = [
            'sarapan' => 'Sarapan',
            'makan_siang' => 'Makan Siang',
            'makan_malam' => 'Makan Malam'
        ];
        
        return $labels[$this->jenis_makanan] ?? $this->jenis_makanan;
    }

    // Accessor untuk badge color berdasarkan nilai
    public function getNilaiBadgeAttribute()
    {
        if ($this->nilai >= 90) {
            return 'bg-green-100 text-green-800';
        } elseif ($this->nilai >= 70) {
            return 'bg-yellow-100 text-yellow-800';
        } else {
            return 'bg-red-100 text-red-800';
        }
    }

    // Accessor untuk URL foto - PERBAIKAN UTAMA
    public function getFotoUrlAttribute()
    {
        if ($this->dokumentasi_foto) {
            // Cek apakah file ada di storage
            $path = 'makan-sehat/' . $this->dokumentasi_foto;
            
            if (Storage::disk('public')->exists($path)) {
                // Generate URL yang benar
                return asset('storage/' . $path);
            }
            
            \Log::warning('File tidak ditemukan:', [
                'file' => $this->dokumentasi_foto,
                'path' => $path,
                'full_path' => storage_path('app/public/' . $path)
            ]);
        }
        
        // Return default image jika tidak ada foto atau file tidak ditemukan
        return asset('images/no-image.png');
    }

    // Helper untuk cek apakah foto ada
    public function hasFoto()
    {
        if (!$this->dokumentasi_foto) {
            return false;
        }
        
        return Storage::disk('public')->exists('makan-sehat/' . $this->dokumentasi_foto);
    }

    // Accessor untuk menampilkan nama lengkap siswa
    public function getNamaSiswaAttribute()
    {
        return $this->siswa ? $this->siswa->nama_lengkap : 'Siswa Tidak Ditemukan';
    }

    // Accessor untuk format waktu yang rapi
    public function getWaktuMakanFormattedAttribute()
    {
        try {
            $waktu = $this->waktu_makan;
            
            if (preg_match('/^\d{1,2}:\d{2}$/', $waktu)) {
                return $waktu;
            }
            
            if (preg_match('/^(\d{1,2}:\d{2}):\d{2}$/', $waktu, $matches)) {
                return $matches[1];
            }
            
            return $this->waktu_makan;
            
        } catch (\Exception $e) {
            return $this->waktu_makan;
        }
    }

    // Scope untuk filter
    public function scopeBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->whereYear('tanggal', $tahun);
    }

    public function scopeSiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_makanan', $jenis);
    }
}