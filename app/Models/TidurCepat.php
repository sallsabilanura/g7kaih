<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TidurCepat extends Model
{
    use HasFactory;

    protected $table = 'tidur_cepats';
    
    protected $fillable = [
        'siswa_id',
        'bangun_pagi_id',
        'tanggal',
        'pukul_tidur',
        'bulan',
        'tahun',
        'nilai',
        'kategori_waktu',
        'status_bangun_pagi',
        'keterangan',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $appends = ['pukul_tidur_formatted', 'is_today'];

    // ========== RELASI ==========
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function bangunPagi()
    {
        return $this->belongsTo(BangunPagi::class, 'bangun_pagi_id');
    }

    // ========== ACCESSORS ==========
    
    public function getPukulTidurFormattedAttribute()
    {
        if (!$this->pukul_tidur) return '-';
        return Carbon::parse($this->pukul_tidur)->format('H:i');
    }

    public function getIsTodayAttribute()
    {
        return $this->tanggal && Carbon::parse($this->tanggal)->isToday();
    }

    // ========== METHODS UTAMA ==========
    
    /**
     * Hitung nilai tidur cepat berdasarkan waktu tidur
     */
    public function hitungNilaiTidurCepat()
    {
        if (!$this->pukul_tidur) {
            $this->nilai = 0;
            $this->kategori_waktu = 'Belum Ada Data';
            return;
        }

        $waktuTidur = Carbon::parse($this->pukul_tidur);
        $jamTidur = $waktuTidur->hour;
        
        // Skoring berdasarkan waktu tidur (20:00 - 21:00 = Sangat Baik)
        if ($jamTidur >= 20 && $jamTidur <= 21) {
            $this->nilai = 100;
            $this->kategori_waktu = 'Sangat Baik';
        } 
        // 21:01 - 22:00 = Baik
        elseif ($jamTidur == 22 || ($jamTidur == 21 && $waktuTidur->minute > 0)) {
            $this->nilai = 90;
            $this->kategori_waktu = 'Baik';
        } 
        // 22:01 - 23:59 = Cukup
        elseif ($jamTidur >= 23 || ($jamTidur == 22 && $waktuTidur->minute > 0)) {
            $this->nilai = 80;
            $this->kategori_waktu = 'Cukup';
        }
        // Sebelum 20:00 = Kurang (terlalu cepat)
        else {
            $this->nilai = 60;
            $this->kategori_waktu = 'Kurang';
        }
    }

    // ========== EVENTS ==========
    
    protected static function boot()
    {
        parent::boot();

        // Sebelum menyimpan, isi bulan, tahun, dan hitung nilai
        static::saving(function ($model) {
            if ($model->tanggal) {
                $tanggal = Carbon::parse($model->tanggal);
                $model->bulan = $tanggal->translatedFormat('F');
                $model->tahun = $tanggal->year;
            }
            
            // Selalu hitung nilai saat menyimpan
            $model->hitungNilaiTidurCepat();
        });

        // Setelah menyimpan, cari bangun pagi untuk BESOK
        static::saved(function ($model) {
            // Hanya cari jika belum ada bangun_pagi_id
            if (!$model->bangun_pagi_id) {
                $model->cariDanHubungkanBangunPagi();
            }
        });

        // Setelah menghapus, update status di bangun pagi
        static::deleted(function ($model) {
            if ($model->bangun_pagi_id) {
                $bangunPagi = BangunPagi::find($model->bangun_pagi_id);
                if ($bangunPagi) {
                    $bangunPagi->update(['sudah_tidur_cepat' => false]);
                }
            }
        });
    }

    /**
     * Cari dan hubungkan dengan bangun pagi untuk tanggal BESOK
     */
    public function cariDanHubungkanBangunPagi()
    {
        try {
            // Tidur hari ini, bangun besok
            $tanggalBesok = Carbon::parse($this->tanggal)->addDay();
            
            $bangunPagi = BangunPagi::where('siswa_id', $this->siswa_id)
                ->whereDate('tanggal', $tanggalBesok)
                ->first();

            if ($bangunPagi) {
                $this->bangun_pagi_id = $bangunPagi->id;
                $this->status_bangun_pagi = $bangunPagi->sudah_bangun ? 'sudah_bangun' : 'belum_bangun';
                $this->saveQuietly(); // Save tanpa trigger event lagi
                
                // Update status sudah tidur cepat di bangun pagi
                $bangunPagi->update(['sudah_tidur_cepat' => true]);
            }
        } catch (\Exception $e) {
            \Log::error('Error cariDanHubungkanBangunPagi: ' . $e->getMessage());
        }
    }
}