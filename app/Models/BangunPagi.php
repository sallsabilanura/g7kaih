<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BangunPagi extends Model
{
    use HasFactory;

    protected $table = 'bangun_pagis';
    
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'pukul',
        'bulan',
        'tahun',
        'nilai',
        'kategori_waktu',
        'sudah_bangun',
        'sudah_tidur_cepat',
        'sudah_ttd_ortu', // Tambahkan ini
        'tanda_tangan_ortu', // Tambahkan ini
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sudah_bangun' => 'boolean',
        'sudah_tidur_cepat' => 'boolean',
        'sudah_ttd_ortu' => 'boolean', // Tambahkan ini
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke tidur cepat (untuk tidur kemarin)
    public function tidurCepat()
    {
        return $this->hasOne(TidurCepat::class, 'bangun_pagi_id');
    }

    // Relasi ke paraf ortu (TAMBAHKAN INI)
    public function parafOrtu()
    {
        return $this->hasOne(ParafOrtuBangunPagi::class, 'bangun_pagi_id');
    }

    // Relasi created_by user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi updated_by user
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessor untuk format tanggal
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('d F Y') : '-';
    }

    // Accessor untuk nama hari
    public function getNamaHariAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('l') : '-';
    }

    // Accessor untuk format waktu 12 jam
    public function getPukul12hAttribute()
    {
        if (!$this->pukul) return '-';
        
        try {
            return Carbon::parse($this->pukul)->translatedFormat('h:i A');
        } catch (\Exception $e) {
            return $this->pukul;
        }
    }

    // Accessor untuk format waktu
    public function getPukulFormattedAttribute()
    {
        if (!$this->pukul) return '-';
        
        // Jika sudah format H:i:s, ambil hanya jam:menit
        if (preg_match('/^(\d{2}:\d{2}):\d{2}$/', $this->pukul, $matches)) {
            return $matches[1];
        }
        
        // Jika format H:i, return langsung
        if (preg_match('/^\d{2}:\d{2}$/', $this->pukul)) {
            return $this->pukul;
        }
        
        // Coba parse
        try {
            return Carbon::parse($this->pukul)->format('H:i');
        } catch (\Exception $e) {
            return $this->pukul;
        }
    }

    // Accessor untuk label kategori waktu
    public function getKategoriWaktuLabelAttribute()
    {
        $labels = [
            'tepat_waktu' => 'Tepat Waktu',
            'sedikit_terlambat' => 'Sedikit Terlambat',
            'terlambat' => 'Terlambat',
            'Belum Ada Data' => 'Belum Ada Data'
        ];
        
        return $labels[$this->kategori_waktu] ?? $this->kategori_waktu;
    }

    // Method untuk menghitung nilai berdasarkan pengaturan
    public function hitungNilai()
    {
        if (!$this->pukul || !$this->pukul_formatted) {
            $this->nilai = 0;
            $this->kategori_waktu = 'Belum Ada Data';
            return;
        }

        // Ambil pengaturan aktif
        $pengaturan = PengaturanWaktuBangunPagi::where('is_active', true)->first();
        
        if (!$pengaturan) {
            // Fallback ke logika lama jika tidak ada pengaturan
            $this->hitungNilaiFallback();
            return;
        }

        // Gunakan waktu dalam format H:i untuk perhitungan
        $waktuBangun = $this->pukul_formatted;
        
        // Hitung nilai berdasarkan pengaturan
        $hasil = $pengaturan->hitungNilai($waktuBangun);
        
        $this->nilai = $hasil['nilai'];
        $this->kategori_waktu = $hasil['kategori'];

        // Bonus jika tidur cepat kemarin bagus
        if ($this->tidurCepat && $this->tidurCepat->nilai >= 80) {
            $this->nilai = min(100, $this->nilai + 10);
        }
    }

    // Fallback method jika tidak ada pengaturan
    private function hitungNilaiFallback()
    {
        $jamBangun = Carbon::parse($this->pukul)->hour;
        $menitBangun = Carbon::parse($this->pukul)->minute;
        $waktuBangun = $jamBangun + ($menitBangun / 60);

        // Skoring berdasarkan waktu bangun (default)
        if ($waktuBangun >= 4 && $waktuBangun <= 5) {
            $this->nilai = 100;
            $this->kategori_waktu = 'tepat_waktu';
        } elseif ($waktuBangun > 5 && $waktuBangun <= 6) {
            $this->nilai = 70;
            $this->kategori_waktu = 'sedikit_terlambat';
        } else {
            $this->nilai = 50;
            $this->kategori_waktu = 'terlambat';
        }
    }

    // Event untuk auto calculate
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Format pukul ke H:i:s jika belum
            if ($model->pukul && !preg_match('/^\d{2}:\d{2}:\d{2}$/', $model->pukul)) {
                if (preg_match('/^\d{2}:\d{2}$/', $model->pukul)) {
                    $model->pukul = $model->pukul . ':00';
                } else {
                    try {
                        $model->pukul = Carbon::parse($model->pukul)->format('H:i:s');
                    } catch (\Exception $e) {
                        // Tetap gunakan value asli
                    }
                }
            }
            
            // Hitung nilai
            $model->hitungNilai();
            
            // Set bulan dan tahun
            if ($model->tanggal) {
                $tanggal = Carbon::parse($model->tanggal);
                $model->bulan = $tanggal->translatedFormat('F');
                $model->tahun = $tanggal->year;
            }
        });

        static::saved(function ($model) {
            // Cari tidur cepat untuk KEMARIN
            $tanggalKemarin = Carbon::parse($model->tanggal)->subDay();
            $tidurCepat = TidurCepat::where('siswa_id', $model->siswa_id)
                ->whereDate('tanggal', $tanggalKemarin)
                ->first();

            if ($tidurCepat) {
                $tidurCepat->update([
                    'bangun_pagi_id' => $model->id,
                    'status_bangun_pagi' => 'sudah_bangun'
                ]);
            }
        });
    }
}