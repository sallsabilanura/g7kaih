<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'peran',
        'nip',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'is_active',
        'username',
        'nama_lengkap',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi untuk mendapatkan kelas yang diampu sebagai guru wali
     */
    public function kelasWali(): HasOne
    {
        return $this->hasOne(Kelas::class, 'guru_wali_id');
    }

    /**
     * Get all kelas where this user is guru wali
     */
    public function kelasAsGuruWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'guru_wali_id');
    }

    /**
     * Relasi ke orangtua (jika user adalah orangtua)
     */
    public function orangtua(): HasOne
    {
        return $this->hasOne(Orangtua::class, 'user_id');
    }

    /**
     * Get all orangtua records associated with this user
     */
    public function semuaOrangtua(): HasMany
    {
        return $this->hasMany(Orangtua::class, 'user_id');
    }

    /**
     * Get siswa anak-anak dari user (as orangtua)
     */
    public function getAnakSiswa()
    {
        if ($this->peran !== 'orangtua') {
            return collect();
        }

        // Cari semua orangtua yang terkait dengan user ini
        $orangtuaRecords = Orangtua::where(function($query) {
            $query->where('user_id', $this->id)
                ->orWhere('email_ayah', $this->email)
                ->orWhere('email_ibu', $this->email);
        })->get();

        $siswaIds = $orangtuaRecords->pluck('siswa_id')->toArray();

        return Siswa::whereIn('id', $siswaIds)->get();
    }

    /**
     * Get siswa IDs dari user (as orangtua)
     */
    public function getSiswaIds()
    {
        if ($this->peran !== 'orangtua') {
            return [];
        }

        // Cari semua orangtua yang terkait dengan user ini
        $orangtuaRecords = Orangtua::where(function($query) {
            $query->where('user_id', $this->id)
                ->orWhere('email_ayah', $this->email)
                ->orWhere('email_ibu', $this->email);
        })->get();

        return $orangtuaRecords->pluck('siswa_id')->toArray();
    }

    /**
     * Scope: Filter users yang berperan sebagai orangtua
     */
    public function scopeOrangtua($query)
    {
        return $query->where('peran', 'orangtua');
    }

    /**
     * Scope: Filter users yang berperan sebagai guru wali
     */
    public function scopeGuruWali($query)
    {
        return $query->where('peran', 'guru_wali');
    }

    /**
     * Scope: Filter users yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope: Filter by peran
     */
    public function scopeByPeran($query, $peran)
    {
        return $query->where('peran', $peran);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }

    /**
     * Check if user is guru wali
     */
    public function isGuruWali(): bool
    {
        return $this->peran === 'guru_wali';
    }

    /**
     * Check if user is siswa
     */
    public function isSiswa(): bool
    {
        return $this->peran === 'siswa';
    }

    /**
     * Check if user is orangtua
     */
    public function isOrangtua(): bool
    {
        return $this->peran === 'orangtua';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->is_active == 1;
    }

    /**
     * Check if user has access to specific siswa
     */
    public function hasAccessToSiswa($siswaId): bool
    {
        if ($this->isAdmin() || $this->isGuruWali()) {
            return true;
        }

        if ($this->isOrangtua()) {
            // Cek apakah siswa ini adalah anak dari orangtua
            $siswaIds = $this->getSiswaIds();
            return in_array($siswaId, $siswaIds);
        }

        return false;
    }
}