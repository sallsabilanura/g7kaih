<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParafOrtuBermasyarakat extends Model
{
    use HasFactory;

    protected $table = 'paraf_ortu_bermasyarakat';

    protected $fillable = [
        'bermasyarakat_id',
        'nama_ortu',
        'tanda_tangan',
        'waktu_paraf',
        'catatan',
        'status',
        'ortu_id'
    ];

    protected $casts = [
        'waktu_paraf' => 'datetime',
    ];

    /**
     * Relasi ke model Bermasyarakat
     */
    public function bermasyarakat(): BelongsTo
    {
        return $this->belongsTo(Bermasyarakat::class, 'bermasyarakat_id');
    }

    /**
     * Relasi ke model Orangtua
     */
    public function ortu(): BelongsTo
    {
        return $this->belongsTo(Orangtua::class, 'ortu_id');
    }

    /**
     * Accessor untuk format waktu paraf
     */
    public function getWaktuParafFormattedAttribute()
    {
        return $this->waktu_paraf ? $this->waktu_paraf->format('d/m/Y H:i') : '-';
    }

    /**
     * Scope untuk paraf yang sudah diverifikasi
     */
    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }

    /**
     * Scope untuk paraf yang belum diverifikasi
     */
    public function scopeBelumTerverifikasi($query)
    {
        return $query->where('status', 'belum_verifikasi')->orWhereNull('status');
    }

    /**
     * Cek apakah paraf sudah terverifikasi
     */
    public function getIsTerverifikasiAttribute(): bool
    {
        return $this->status === 'terverifikasi';
    }
}