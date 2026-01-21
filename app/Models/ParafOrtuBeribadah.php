<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ParafOrtuBeribadah extends Model
{
    use HasFactory;

    protected $table = 'paraf_ortu_beribadah';

    protected $fillable = [
        'beribadah_id',
        'siswa_id',
        'ortu_id',
        'jenis_sholat',
        'nama_ortu',
        'tanda_tangan',
        'waktu_paraf',
        'catatan',
        'status'
    ];

    protected $casts = [
        'waktu_paraf' => 'datetime',
    ];

    const STATUSES = [
        'pending' => 'Menunggu',
        'terverifikasi' => 'Terverifikasi',
        'ditolak' => 'Ditolak'
    ];

    const JENIS_SHOLAT = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
    
    const SHOLAT_LABELS = [
        'subuh' => 'Subuh',
        'dzuhur' => 'Dzuhur',
        'ashar' => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya' => 'Isya'
    ];

    /**
     * RELASI KE MODEL LAIN
     */
    
    public function beribadah(): BelongsTo
    {
        return $this->belongsTo(Beribadah::class, 'beribadah_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function orangtua(): BelongsTo
    {
        return $this->belongsTo(Orangtua::class, 'ortu_id');
    }

    /**
     * RELASI SPESIFIK KE BERIBADAH UNTUK SETIAP SHOLAT
     */
    public function beribadahSholat(): HasOne
    {
        return $this->hasOne(Beribadah::class, 'id', 'beribadah_id');
    }

    /**
     * BUAT PARAF OTOMATIS KETIKA SISWA ISI SHOLAT
     */
    public static function createParafOtomatis($beribadahId, $siswaId, $jenisSholat, $ortuId = null)
    {
        try {
            // Cek apakah sudah ada paraf untuk sholat ini
            $existing = self::where('beribadah_id', $beribadahId)
                ->where('jenis_sholat', $jenisSholat)
                ->first();

            if ($existing) {
                return $existing;
            }

            // Dapatkan data orang tua dari siswa
            $siswa = Siswa::with('orangtua')->find($siswaId);
            $namaOrtu = 'Orang Tua';

            if ($siswa && $siswa->orangtua) {
                $ortuId = $ortuId ?? $siswa->orangtua->id;
                $namaOrtu = $siswa->orangtua->nama_ayah ?? 
                           $siswa->orangtua->nama_ibu ?? 
                           $siswa->orangtua->nama_wali ?? 
                           'Orang Tua';
            }

            // Buat paraf baru
            $paraf = self::create([
                'beribadah_id' => $beribadahId,
                'siswa_id' => $siswaId,
                'ortu_id' => $ortuId,
                'jenis_sholat' => $jenisSholat,
                'nama_ortu' => $namaOrtu,
                'tanda_tangan' => 'Paraf Otomatis - Menunggu Konfirmasi',
                'waktu_paraf' => now(),
                'catatan' => 'Sholat sudah dilaksanakan, menunggu konfirmasi orang tua',
                'status' => 'pending', // Status pending menunggu konfirmasi orang tua
            ]);

            return $paraf;

        } catch (\Exception $e) {
            \Log::error('Gagal membuat paraf otomatis: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * KONFIRMASI PARAF OLEH ORANG TUA
     */
    public function konfirmasiParaf($namaOrtu, $ortuId = null, $catatan = null, $tandaTangan = null)
    {
        try {
            $this->update([
                'nama_ortu' => $namaOrtu,
                'ortu_id' => $ortuId ?? $this->ortu_id,
                'tanda_tangan' => $tandaTangan ?? 'Paraf Digital - ' . $namaOrtu,
                'waktu_paraf' => now(),
                'catatan' => $catatan,
                'status' => 'terverifikasi',
            ]);

            // Update flag paraf di tabel beribadahs
            if ($this->beribadahSholat) {
                $this->beribadahSholat->update([
                    $this->jenis_sholat . '_paraf' => true,
                    $this->jenis_sholat . '_paraf_nama' => $namaOrtu,
                    $this->jenis_sholat . '_paraf_waktu' => now(),
                ]);
            }

            return $this;

        } catch (\Exception $e) {
            \Log::error('Gagal konfirmasi paraf: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * DAPATKAN PARAF BERDASARKAN BERIBADAH DAN SHOLAT
     */
    public static function getByBeribadahAndSholat($beribadahId, $jenisSholat)
    {
        return self::where('beribadah_id', $beribadahId)
            ->where('jenis_sholat', $jenisSholat)
            ->first();
    }

    /**
     * DAPATKAN SEMUA PARAF UNTUK BERIBADAH TERTENTU
     */
    public static function getAllByBeribadah($beribadahId)
    {
        return self::where('beribadah_id', $beribadahId)
            ->orderBy('jenis_sholat')
            ->get();
    }

    /**
     * ACCESSORS
     */

    public function getJenisSholatLabelAttribute()
    {
        return self::SHOLAT_LABELS[$this->jenis_sholat] ?? $this->jenis_sholat;
    }

    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getWaktuParafFormattedAttribute()
    {
        return $this->waktu_paraf ? $this->waktu_paraf->format('d/m/Y H:i') : '-';
    }

    public function getWaktuParafTimeAttribute()
    {
        return $this->waktu_paraf ? $this->waktu_paraf->format('H:i') : '-';
    }

    public function getWaktuParafDateAttribute()
    {
        return $this->waktu_paraf ? $this->waktu_paraf->format('d/m/Y') : '-';
    }

    /**
     * CEK APAKAH PARAF SUDAH DIKONFIRMASI
     */
    public function isTerverifikasi()
    {
        return $this->status === 'terverifikasi';
    }

    /**
     * SCOPE QUERY
     */
    
    public function scopeByBeribadah($query, $beribadahId)
    {
        return $query->where('beribadah_id', $beribadahId);
    }

    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByJenisSholat($query, $jenisSholat)
    {
        return $query->where('jenis_sholat', $jenisSholat);
    }

    public function scopeByOrtu($query, $ortuId)
    {
        return $query->where('ortu_id', $ortuId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('status', 'terverifikasi');
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('waktu_paraf', $tanggal);
    }

    public function scopeByRangeTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('waktu_paraf', [$startDate, $endDate]);
    }

    /**
     * EVENT HANDLERS
     */
    
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            \Log::info("Paraf otomatis dibuat untuk beribadah ID: {$model->beribadah_id}, sholat: {$model->jenis_sholat}");
        });

        static::updated(function ($model) {
            if ($model->isDirty('status') && $model->status === 'terverifikasi') {
                \Log::info("Paraf diverifikasi untuk beribadah ID: {$model->beribadah_id}, sholat: {$model->jenis_sholat}");
            }
        });

        static::deleted(function ($model) {
            \Log::info("Paraf dihapus untuk beribadah ID: {$model->beribadah_id}, sholat: {$model->jenis_sholat}");
        });
    }
}