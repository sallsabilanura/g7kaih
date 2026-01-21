<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Orangtua extends Model
{
    use HasFactory;

    protected $table = 'orangtua';

    protected $fillable = [
        'user_id',
        'siswa_id',
        'nama_ayah',
        'nama_ibu',
        'telepon_ayah',
        'telepon_ibu',
        'alamat',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'pendidikan_ayah',
        'pendidikan_ibu',
        'tanggal_lahir_ayah',
        'tanggal_lahir_ibu',
        'email_ayah',
        'email_ibu',
        'tanda_tangan_ayah',
        'tanda_tangan_ibu',
        'is_active'
    ];

    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get nama lengkap (gabungan ayah dan ibu)
     */
    public function getNamaLengkapAttribute()
    {
        $nama = [];
        if ($this->nama_ayah) $nama[] = $this->nama_ayah . ' (Ayah)';
        if ($this->nama_ibu) $nama[] = $this->nama_ibu . ' (Ibu)';
        
        return implode(' & ', $nama) ?: 'Orang Tua';
    }

    /**
     * Get telepon utama
     */
    public function getTeleponUtamaAttribute()
    {
        return $this->telepon_ayah ?: $this->telepon_ibu;
    }

    /**
     * Get email utama
     */
    public function getEmailUtamaAttribute()
    {
        return $this->email_ayah ?: $this->email_ibu;
    }

    /**
     * Cek apakah email cocok dengan orangtua ini
     */
    public function isEmailMatch($email)
    {
        return $this->email_ayah === $email || $this->email_ibu === $email;
    }

    /**
     * Scope untuk mencari berdasarkan user_id
     */
    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk mencari berdasarkan siswa_id
     */
    public function scopeBySiswaId($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Scope untuk mencari berdasarkan email
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email_ayah', $email)
                    ->orWhere('email_ibu', $email);
    }

    /**
     * Scope untuk hanya data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}