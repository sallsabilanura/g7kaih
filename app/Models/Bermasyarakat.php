<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bermasyarakat extends Model
{
    use HasFactory;

    protected $table = 'bermasyarakat';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'bulan',
        'tahun',
        'nama_kegiatan',
        'pesan_kesan',
        'gambar_kegiatan',
        'paraf_ortu',
        'sudah_ttd_ortu',
        'nilai',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'gambar_kegiatan' => 'array', // Cast sebagai array
        'paraf_ortu' => 'boolean',
        'sudah_ttd_ortu' => 'boolean',
    ];

    // RELASI KE SISWA
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke user yang membuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user yang update
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi ke data paraf ortu
    public function parafOrtu()
    {
        return $this->hasOne(ParafOrtuBermasyarakat::class, 'bermasyarakat_id');
    }

    // Accessor untuk format tanggal lengkap
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('d F Y') : '-';
    }

    // Accessor untuk format tanggal singkat
    public function getTanggalSingkatAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '-';
    }

    // Accessor untuk nama hari
    public function getNamaHariAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->translatedFormat('l') : '-';
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return $this->status == 'approved' ? 'Sudah Dinilai' : 'Menunggu';
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        return $this->status == 'approved' ? 'success' : 'warning';
    }

    // Accessor untuk paraf ortu label
    public function getParafOrtuLabelAttribute()
    {
        return $this->sudah_ttd_ortu ? 'Sudah Diparaf' : 'Belum Diparaf';
    }

    // Accessor untuk paraf ortu color
    public function getParafOrtuColorAttribute()
    {
        return $this->sudah_ttd_ortu ? 'success' : 'secondary';
    }

    // Method untuk mendapatkan gambar pertama sebagai thumbnail
    public function getThumbnailAttribute()
    {
        $gambar = $this->gambar_kegiatan;
        
        // Handle jika masih string (untuk backward compatibility)
        if (is_string($gambar)) {
            return $gambar;
        }
        
        // Handle jika array
        if (!empty($gambar) && is_array($gambar) && count($gambar) > 0) {
            return $gambar[0];
        }
        
        return null;
    }

    // Method untuk membuat/mendapatkan data paraf ortu
    public function getOrCreateParafOrtu()
    {
        if (!$this->parafOrtu) {
            return ParafOrtuBermasyarakat::create([
                'bermasyarakat_id' => $this->id,
                'nama_ortu' => 'Orang Tua Siswa',
                'status' => 'pending'
            ]);
        }

        return $this->parafOrtu;
    }

    // Method untuk memberikan paraf
    public function beriParaf($ortuId, $namaOrtu, $catatan = null)
    {
        $this->update([
            'paraf_ortu' => true,
            'sudah_ttd_ortu' => true,
        ]);

        // Update atau create data paraf
        $paraf = $this->getOrCreateParafOrtu();
        $paraf->update([
            'ortu_id' => $ortuId,
            'nama_ortu' => $namaOrtu,
            'waktu_paraf' => now(),
            'status' => 'terverifikasi',
            'catatan' => $catatan
        ]);

        return $paraf;
    }

    // Method untuk membatalkan paraf
    public function batalkanParaf()
    {
        $this->update([
            'paraf_ortu' => false,
            'sudah_ttd_ortu' => false,
        ]);

        if ($this->parafOrtu) {
            $this->parafOrtu->delete();
        }

        return true;
    }
}