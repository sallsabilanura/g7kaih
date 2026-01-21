<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanggapanOrangtua extends Model
{
    use HasFactory;

    protected $table = 'tanggapan_orangtua';
    
    protected $fillable = [
        'siswa_id',
        'orangtua_id',
        'bulan',
        'tahun',
        'tanggal_pengisian',
        'kelas',
        'tanggapan',
        'nama_orangtua',
        'tipe_orangtua',
        'tanda_tangan_digital',
    ];

    protected $casts = [
        'tanggal_pengisian' => 'date',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    /**
     * Relasi ke siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi ke orangtua
     */
    public function orangtua(): BelongsTo
    {
        return $this->belongsTo(Orangtua::class, 'orangtua_id');
    }

    /**
     * Accessor untuk periode
     */
    public function getPeriodeAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return ($bulan[$this->bulan] ?? '') . ' ' . $this->tahun;
    }

    /**
     * Accessor untuk tanggal pengisian format
     */
    public function getTanggalPengisianFormattedAttribute(): string
    {
        return $this->tanggal_pengisian ? 
            $this->tanggal_pengisian->format('d F Y') : 
            '-';
    }

    /**
     * Accessor untuk tipe orangtua label
     */
    public function getTipeOrangtuaLabelAttribute(): string
    {
        $labels = [
            'ayah' => 'Ayah',
            'ibu' => 'Ibu',
            'wali' => 'Wali',
        ];

        return $labels[$this->tipe_orangtua] ?? $this->tipe_orangtua;
    }

    /**
     * Check if sudah tanda tangan
     */
    public function getSudahTandaTanganAttribute(): bool
    {
        return !empty($this->tanda_tangan_digital);
    }
}