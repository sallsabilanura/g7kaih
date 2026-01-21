<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParafOrtuBangunPagi extends Model
{
    use HasFactory;

    protected $table = 'paraf_ortu_bangun_pagis';

    protected $fillable = [
        'bangun_pagi_id',
        'nama_ortu',
        'tanda_tangan',
        'waktu_paraf',
        'catatan',
        'status',
    ];

    protected $casts = [
        'waktu_paraf' => 'datetime',
    ];

    /**
     * Relasi ke model BangunPagi
     */
    public function bangunPagi(): BelongsTo
    {
        return $this->belongsTo(BangunPagi::class, 'bangun_pagi_id');
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