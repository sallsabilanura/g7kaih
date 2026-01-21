<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapPemantauanKebiasaan extends Model
{
    use HasFactory;

    protected $table = 'rekap_pemantauan_kebiasaan';
    
    protected $fillable = [
        'siswa_id',
        'nama_lengkap',
        'kelas',
        'bulan',
        'tahun',
        'bangun_pagi_status',
        'beribadah_status',
        'berolahraga_status',
        'makan_sehat_status',
        'gemar_belajar_status',
        'bermasyarakat_status',
        'tidur_cepat_status',
        'guru_kelas',
        'orangtua_siswa',
        'tanggal_persetujuan',
        'catatan'
    ];
    
    protected $casts = [
        'tanggal_persetujuan' => 'date',
        'tahun' => 'integer'
    ];
    
    const STATUS_BELUM_TERBIASA = 'belum_terbiasa';
    const STATUS_SUDAH_TERBIASA = 'sudah_terbiasa';

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['kelas']) && $filters['kelas'] != '') {
            $kelas = $filters['kelas'];
            // Normalize kelas filter
            if (!in_array($kelas, ['1', '2', '3', '4', '5', '6'])) {
                if (preg_match('/\d+/', $kelas, $matches)) {
                    $kelas = $matches[0];
                }
            }
            $query->where('kelas', $kelas);
        }
        
        if (isset($filters['bulan']) && $filters['bulan'] != '') {
            $query->where('bulan', $filters['bulan']);
        }
        
        if (isset($filters['tahun']) && $filters['tahun'] != '') {
            $query->where('tahun', $filters['tahun']);
        }
        
        if (isset($filters['nama']) && $filters['nama'] != '') {
            $query->where('nama_lengkap', 'like', '%' . $filters['nama'] . '%');
        }
        
        return $query;
    }
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Normalize kelas sebelum disimpan
            if ($model->kelas && !in_array($model->kelas, ['1', '2', '3', '4', '5', '6'])) {
                if (preg_match('/\d+/', $model->kelas, $matches)) {
                    $model->kelas = $matches[0];
                }
            }
        });
        
        static::updating(function ($model) {
            // Normalize kelas sebelum update
            if ($model->kelas && !in_array($model->kelas, ['1', '2', '3', '4', '5', '6'])) {
                if (preg_match('/\d+/', $model->kelas, $matches)) {
                    $model->kelas = $matches[0];
                }
            }
        });
    }
    
    public function getTotalTerbiasaAttribute()
    {
        $kebiasaan = [
            $this->bangun_pagi_status === self::STATUS_SUDAH_TERBIASA,
            $this->beribadah_status === self::STATUS_SUDAH_TERBIASA,
            $this->berolahraga_status === self::STATUS_SUDAH_TERBIASA,
            $this->makan_sehat_status === self::STATUS_SUDAH_TERBIASA,
            $this->gemar_belajar_status === self::STATUS_SUDAH_TERBIASA,
            $this->bermasyarakat_status === self::STATUS_SUDAH_TERBIASA,
            $this->tidur_cepat_status === self::STATUS_SUDAH_TERBIASA,
        ];
        
        return count(array_filter($kebiasaan));
    }
    
    public function getProgressStatusAttribute()
    {
        $total = $this->total_terbiasa;
        
        if ($total == 7) {
            return 'Excellent';
        } elseif ($total >= 5) {
            return 'Good';
        } elseif ($total >= 3) {
            return 'Fair';
        } else {
            return 'Need Improvement';
        }
    }
    
    public function getStatusTextAttribute()
    {
        $statuses = [];
        
        $fields = [
            'bangun_pagi_status' => 'Bangun Pagi',
            'beribadah_status' => 'Beribadah',
            'berolahraga_status' => 'Berolahraga',
            'makan_sehat_status' => 'Makan Sehat & Bergizi',
            'gemar_belajar_status' => 'Gemar Belajar',
            'bermasyarakat_status' => 'Bermasyarakat',
            'tidur_cepat_status' => 'Tidur Cepat',
        ];
        
        foreach ($fields as $field => $label) {
            $statuses[$label] = $this->$field === self::STATUS_SUDAH_TERBIASA ? 'Sudah Terbiasa' : 'Belum Terbiasa';
        }
        
        return $statuses;
    }
    
    public function getKebiasaanArrayAttribute()
    {
        return [
            [
                'name' => 'bangun_pagi_status',
                'label' => 'Bangun Pagi',
                'status' => $this->bangun_pagi_status,
                'is_terbiasa' => $this->bangun_pagi_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'beribadah_status',
                'label' => 'Beribadah',
                'status' => $this->beribadah_status,
                'is_terbiasa' => $this->beribadah_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'berolahraga_status',
                'label' => 'Berolahraga',
                'status' => $this->berolahraga_status,
                'is_terbiasa' => $this->berolahraga_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'makan_sehat_status',
                'label' => 'Makan Sehat & Bergizi',
                'status' => $this->makan_sehat_status,
                'is_terbiasa' => $this->makan_sehat_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'gemar_belajar_status',
                'label' => 'Gemar Belajar',
                'status' => $this->gemar_belajar_status,
                'is_terbiasa' => $this->gemar_belajar_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'bermasyarakat_status',
                'label' => 'Bermasyarakat',
                'status' => $this->bermasyarakat_status,
                'is_terbiasa' => $this->bermasyarakat_status === self::STATUS_SUDAH_TERBIASA
            ],
            [
                'name' => 'tidur_cepat_status',
                'label' => 'Tidur Cepat',
                'status' => $this->tidur_cepat_status,
                'is_terbiasa' => $this->tidur_cepat_status === self::STATUS_SUDAH_TERBIASA
            ],
        ];
    }

    public function getOrangtuaAttribute()
    {
        if ($this->siswa && $this->siswa->orangtua) {
            return $this->siswa->orangtua;
        }
        return null;
    }

    public function getTandaTanganOrangtuaUrlAttribute()
    {
        $orangtua = $this->orangtua;
        if ($orangtua) {
            return $orangtua->tanda_tangan_ayah_url ?? $orangtua->tanda_tangan_ibu_url;
        }
        return null;
    }
}