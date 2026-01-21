<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Berolahraga extends Model
{
    use HasFactory;

    // TAMBAHKAN INI - Tentukan nama tabel secara eksplisit
    protected $table = 'berolahraga'; // tanpa 's'

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
        'video_path',
        'video_filename',
        'video_size',
        'nilai',
        'kategori_nilai',
        'catatan_admin',
        'verified_by',
        'verified_at',
        'waktu_mulai_koreksi',
        'waktu_selesai_koreksi',
        'ada_koreksi_waktu',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'verified_at' => 'datetime',
        'ada_koreksi_waktu' => 'boolean',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Boot method - OTOMATIS HITUNG NILAI SAAT CREATE & UPDATE
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->hitungNilaiOtomatis();
        });

        static::updating(function ($model) {
            if ($model->isDirty(['waktu_mulai', 'waktu_selesai'])) {
                $model->hitungNilaiOtomatis();
            }
        });
    }

    /**
     * HITUNG NILAI OTOMATIS BERDASARKAN PENGATURAN WAKTU
     */
    public function hitungNilaiOtomatis()
    {
        $pengaturan = PengaturanWaktuOlahraga::where('is_active', true)->first();
        
        if (!$pengaturan) {
            $this->nilai = 50;
            $this->kategori_nilai = 'kurang';
            $this->verified_at = now();
            return;
        }

        $waktuMulai = $this->waktu_mulai;

        if (!$waktuMulai) {
            $this->nilai = $pengaturan->nilai_kurang;
            $this->kategori_nilai = 'kurang';
            $this->verified_at = now();
            return;
        }

        $hasil = $pengaturan->hitungNilai($waktuMulai);
        
        $this->nilai = $hasil['nilai'];
        $this->kategori_nilai = $hasil['kategori'];
        $this->verified_at = now();
    }

    /**
     * Accessor - Nilai selalu otomatis
     */
    public function getNilaiOtomatisAttribute()
    {
        return true;
    }

    // ============ ACCESSOR LAINNYA ============

    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    public function getNamaHariAttribute()
    {
        if (!$this->tanggal) return '-';
        $hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        return $hari[$this->tanggal->format('l')] ?? '-';
    }

    public function getMulaiOlahragaAttribute()
    {
        return $this->waktu_mulai ? Carbon::parse($this->waktu_mulai)->format('H:i') : '-';
    }

    public function getSelesaiOlahragaAttribute()
    {
        return $this->waktu_selesai ? Carbon::parse($this->waktu_selesai)->format('H:i') : '-';
    }

    public function getDurasiAttribute()
    {
        if (!$this->durasi_menit) return '-';
        $jam = floor($this->durasi_menit / 60);
        $menit = $this->durasi_menit % 60;
        if ($jam > 0) {
            return $jam . ' jam ' . $menit . ' menit';
        }
        return $menit . ' menit';
    }

    public function getVideoUrlAttribute()
    {
        return $this->video_path ? Storage::url($this->video_path) : null;
    }

    public function getVideoSizeFormattedAttribute()
    {
        if (!$this->video_size) return '-';
        if ($this->video_size < 1024) {
            return $this->video_size . ' KB';
        }
        return round($this->video_size / 1024, 2) . ' MB';
    }

    public function getKategoriNilaiLabelAttribute()
    {
        $labels = [
            'sangat_baik' => 'Sangat Baik',
            'baik' => 'Baik',
            'cukup' => 'Cukup',
            'kurang' => 'Kurang',
        ];
        return $labels[$this->kategori_nilai] ?? '-';
    }

    public function getKategoriNilaiColorAttribute()
    {
        $colors = [
            'sangat_baik' => 'green',
            'baik' => 'blue',
            'cukup' => 'yellow',
            'kurang' => 'red',
        ];
        return $colors[$this->kategori_nilai] ?? 'gray';
    }

    public function scopeSudahDinilai($query)
    {
        return $query->whereNotNull('nilai');
    }

    public function scopeBelumDinilai($query)
    {
        return $query->whereNull('nilai');
    }

    public function scopeBulan($query, $tahun, $bulan)
    {
        return $query->whereYear('tanggal', $tahun)->whereMonth('tanggal', $bulan);
    }
}