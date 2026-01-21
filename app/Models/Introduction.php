<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Introduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'hobi',
        'cita_cita',
        'olahraga_kesukaan',
        'makanan_kesukaan',
        'buah_kesukaan',
        'pelajaran_kesukaan',
        'warna_kesukaan'
    ];

    // Cast warna_kesukaan sebagai array
    protected $casts = [
        'warna_kesukaan' => 'array',
    ];

    /**
     * Relasi ke model Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

     public function introduction()
    {
        return $this->hasOne(Introduction::class, 'siswa_id');
    }
}