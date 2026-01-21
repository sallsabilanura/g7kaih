<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_kelas',
        'guru_wali_id', // Hanya gunakan guru_wali_id
    ];

    /**
     * Relasi ke guru wali (user)
     */
    public function guruWali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_wali_id');
    }

    /**
     * Relasi ke siswa
     */
    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    // HAPUS method-method berikut karena redundan dan menyebabkan konflik:
    // - waliKelas()
    // - guru()
    // - siswa() (sudah ada siswas())
}