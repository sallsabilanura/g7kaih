<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class GemarBelajar extends Model
{
    use HasFactory;

    protected $table = 'gemar_belajar';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'bulan',
        'tahun',
        'judul_buku',
        'informasi_didapat',
        'gambar_buku',
        'gambar_baca',
        'nilai',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nilai' => 'integer',
        'tahun' => 'integer',
        'bulan' => 'integer'
    ];

    // RELASI KE SISWA - PASTIKAN ADA INI
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang mengupdate
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessor untuk format tanggal Indonesia
    public function getTanggalFormattedAttribute()
    {
        if (!$this->tanggal) {
            return '-';
        }
        
        Carbon::setLocale('id');
        return $this->tanggal->translatedFormat('d F Y');
    }

    // Accessor untuk tanggal singkat
    public function getTanggalShortAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    // Accessor untuk bulan nama
    public function getBulanNamaAttribute()
    {
        $bulanNama = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulanNama[$this->bulan] ?? '-';
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'pending' => 'Menunggu',
            'approved' => 'Sudah Dinilai',
            'rejected' => 'Ditolak'
        ];
        
        return $statusLabels[$this->status] ?? 'Unknown';
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];
        
        return $statusColors[$this->status] ?? 'secondary';
    }

    // Accessor untuk URL gambar buku
    public function getGambarBukuUrlAttribute()
    {
        if (!$this->gambar_buku) {
            return asset('images/no-image.png');
        }
        
        return asset('storage/' . $this->gambar_buku);
    }

    // Accessor untuk URL gambar baca
    public function getGambarBacaUrlAttribute()
    {
        if (!$this->gambar_baca) {
            return asset('images/no-image.png');
        }
        
        return asset('storage/' . $this->gambar_baca);
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        return '<span class="badge badge-' . $this->status_color . '">' . $this->status_label . '</span>';
    }

    // Scope untuk filter berdasarkan siswa
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk filter berdasarkan bulan
    public function scopeByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    // Scope untuk filter berdasarkan tahun
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // Scope untuk filter berdasarkan periode (bulan & tahun)
    public function scopeByPeriode($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // Scope untuk data terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');
    }

    // Scope untuk data yang sudah dinilai
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Scope untuk data yang menunggu
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Boot method untuk set default values
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set bulan dan tahun otomatis dari tanggal jika kosong
            if ($model->tanggal) {
                if (!$model->bulan) {
                    $model->bulan = Carbon::parse($model->tanggal)->month;
                }
                if (!$model->tahun) {
                    $model->tahun = Carbon::parse($model->tanggal)->year;
                }
            }

            // Set created_by otomatis
            if (!$model->created_by && auth()->check()) {
                $model->created_by = auth()->id();
            }

            // Set default status jika kosong
            if (!$model->status) {
                $model->status = 'pending';
            }
        });

        static::updating(function ($model) {
            // Update bulan dan tahun jika tanggal berubah
            if ($model->isDirty('tanggal') && $model->tanggal) {
                $model->bulan = Carbon::parse($model->tanggal)->month;
                $model->tahun = Carbon::parse($model->tanggal)->year;
            }

            // Set updated_by otomatis
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    // Method untuk cek apakah sudah dinilai
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Method untuk cek apakah masih pending
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Method untuk approve
    public function approve($nilai = null)
    {
        $this->status = 'approved';
        if ($nilai !== null) {
            $this->nilai = $nilai;
        }
        return $this->save();
    }

    // Method untuk reject
    public function reject()
    {
        $this->status = 'rejected';
        return $this->save();
    }

    // Method untuk reset ke pending
    public function resetToPending()
    {
        $this->status = 'pending';
        $this->nilai = null;
        return $this->save();
    }
}