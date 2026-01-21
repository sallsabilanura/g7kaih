<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Beribadah extends Model
{
    use HasFactory;

    protected $table = 'beribadahs';

    protected $fillable = [
        'siswa_id', 'tanggal', 'bulan', 'tahun',
        'subuh_waktu', 'subuh_nilai', 'subuh_kategori', 'subuh_paraf', 'subuh_paraf_nama', 'subuh_paraf_waktu',
        'dzuhur_waktu', 'dzuhur_nilai', 'dzuhur_kategori', 'dzuhur_paraf', 'dzuhur_paraf_nama', 'dzuhur_paraf_waktu',
        'ashar_waktu', 'ashar_nilai', 'ashar_kategori', 'ashar_paraf', 'ashar_paraf_nama', 'ashar_paraf_waktu',
        'maghrib_waktu', 'maghrib_nilai', 'maghrib_kategori', 'maghrib_paraf', 'maghrib_paraf_nama', 'maghrib_paraf_waktu',
        'isya_waktu', 'isya_nilai', 'isya_kategori', 'isya_paraf', 'isya_paraf_nama', 'isya_paraf_waktu',
        'total_nilai', 'total_sholat', 'keterangan', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'subuh_paraf' => 'boolean',
        'dzuhur_paraf' => 'boolean',
        'ashar_paraf' => 'boolean',
        'maghrib_paraf' => 'boolean',
        'isya_paraf' => 'boolean',
        'subuh_paraf_waktu' => 'datetime',
        'dzuhur_paraf_waktu' => 'datetime',
        'ashar_paraf_waktu' => 'datetime',
        'maghrib_paraf_waktu' => 'datetime',
        'isya_paraf_waktu' => 'datetime',
        'subuh_nilai' => 'integer',
        'dzuhur_nilai' => 'integer',
        'ashar_nilai' => 'integer',
        'maghrib_nilai' => 'integer',
        'isya_nilai' => 'integer',
        'total_nilai' => 'integer',
        'total_sholat' => 'integer',
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
     * ===========================================
     * RELASI KE MODEL LAIN
     * ===========================================
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function orangtua()
    {
        return $this->hasOneThrough(
            Orangtua::class,
            Siswa::class,
            'id', // Foreign key on Siswa table
            'siswa_id', // Foreign key on Orangtua table
            'siswa_id', // Local key on Beribadah table
            'id' // Local key on Siswa table
        );
    }

    public function parafOrtu(): HasMany
    {
        return $this->hasMany(ParafOrtuBeribadah::class, 'beribadah_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * ===========================================
     * KIRIM NOTIFIKASI KE ORANGTUA (FIXED)
     * ===========================================
     */
    public function sendParafNotificationToOrangtua($jenisSholat)
    {
        try {
            // Ambil data siswa dan orangtua
            $siswa = $this->siswa;
            if (!$siswa) {
                \Log::warning("Siswa tidak ditemukan untuk beribadah ID: {$this->id}");
                return false;
            }

            $orangtua = $siswa->orangtua;
            if (!$orangtua) {
                \Log::warning("Orangtua tidak ditemukan untuk siswa ID: {$siswa->id}");
                return false;
            }

            // Generate token untuk paraf
            $token = $this->generateParafToken();
            \Cache::put("paraf_token_{$this->id}", $token, now()->addDays(3));

            // Buat link paraf
            $parafLink = route('beribadah.paraf-ortu.view', [
                'beribadahId' => $this->id,
                'token' => $token
            ]);

            // Kirim notifikasi WhatsApp
            return $this->kirimWhatsAppNotification($orangtua, $jenisSholat, $parafLink);

        } catch (\Exception $e) {
            \Log::error("Gagal mengirim notifikasi: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate Token Paraf
     */
    public function generateParafToken()
    {
        return md5($this->id . $this->siswa_id . config('app.key') . time());
    }

    /**
     * Kirim WhatsApp Notification
     */
    private function kirimWhatsAppNotification($orangtua, $jenisSholat, $link)
    {
        $phone = $orangtua->telepon_ayah ?? $orangtua->telepon_ibu;
        
        if (!$phone) {
            \Log::warning("Nomor telepon orangtua tidak ditemukan");
            return false;
        }

        $siswa = $this->siswa;
        $namaSiswa = $siswa ? $siswa->nama_lengkap : 'Siswa';
        
        $sholatLabel = self::SHOLAT_LABELS[$jenisSholat] ?? $jenisSholat;
        $message = "Assalamu'alaikum Bapak/Ibu Orangtua dari {$namaSiswa}.\n\n"
                 . "Ananda telah melaksanakan sholat {$sholatLabel} pada tanggal {$this->tanggal_formatted}.\n"
                 . "Silakan berikan paraf konfirmasi melalui link berikut:\n"
                 . $link . "\n\n"
                 . "Terima kasih.";

        // Kirim via WhatsApp API (sesuaikan dengan API yang digunakan)
        $apiKey = config('services.fonnte.api_key');
        if ($apiKey) {
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->post('https://api.fonnte.com/send', [
                    'headers' => [
                        'Authorization' => $apiKey
                    ],
                    'form_params' => [
                        'target' => $phone,
                        'message' => $message,
                        'countryCode' => '62'
                    ]
                ]);
                
                \Log::info("Notifikasi WhatsApp berhasil dikirim ke: {$phone}");
                return true;
            } catch (\Exception $e) {
                \Log::error("Gagal mengirim WhatsApp: " . $e->getMessage());
                return false;
            }
        }
        
        \Log::info("Pesan notifikasi (simulasi): " . $message);
        return true; // Return true untuk testing
    }

    /**
     * ===========================================
     * CEK STATUS PARAF
     * ===========================================
     */
    public function isSholatParafed($sholat)
    {
        $parafField = $sholat . '_paraf';
        return $this->$parafField == true;
    }

    /**
     * Batalkan Paraf
     */
    public function batalkanParaf($sholat)
    {
        try {
            // Hapus paraf dari tabel paraf_ortu_beribadah
            $this->parafOrtu()
                ->where('jenis_sholat', $sholat)
                ->delete();

            // Reset flag paraf di tabel beribadahs
            $this->update([
                $sholat . '_paraf' => false,
                $sholat . '_paraf_nama' => null,
                $sholat . '_paraf_waktu' => null,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error("Gagal membatalkan paraf: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Beri Paraf
     */
    public function beriParaf($sholat, $namaOrtu, $ortuId, $catatan = null)
    {
        DB::beginTransaction();
        
        try {
            // Cek apakah sudah diparaf
            if ($this->isSholatParafed($sholat)) {
                throw new \Exception("Sholat {$sholat} sudah diparaf sebelumnya");
            }

            // Buat paraf
            $paraf = ParafOrtuBeribadah::create([
                'beribadah_id' => $this->id,
                'siswa_id' => $this->siswa_id,
                'ortu_id' => $ortuId,
                'jenis_sholat' => $sholat,
                'nama_ortu' => $namaOrtu,
                'tanda_tangan' => 'Paraf Digital - ' . $namaOrtu,
                'waktu_paraf' => now(),
                'catatan' => $catatan,
                'status' => 'terverifikasi',
            ]);

            // Update flag paraf di tabel beribadahs
            $this->update([
                $sholat . '_paraf' => true,
                $sholat . '_paraf_nama' => $namaOrtu,
                $sholat . '_paraf_waktu' => now(),
            ]);

            // Update status paraf keseluruhan
            $this->updateParafStatus();

            DB::commit();
            
            return $paraf;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update Status Paraf Keseluruhan
     */
    public function updateParafStatus()
    {
        $totalSholat = 0;
        $totalParaf = 0;

        foreach (self::JENIS_SHOLAT as $sholat) {
            $waktuField = $sholat . '_waktu';
            $parafField = $sholat . '_paraf';

            if ($this->$waktuField) {
                $totalSholat++;
                if ($this->$parafField) {
                    $totalParaf++;
                }
            }
        }

        // Bisa tambahkan field status_paraf jika ada di database
        // $this->status_paraf = ($totalSholat > 0 && $totalSholat == $totalParaf) ? 'complete' : 'partial';
        
        return $this;
    }

    /**
     * ===========================================
     * DAPATKAN DAFTAR SHOLAT
     * ===========================================
     */
    public function getUnparafedSholatList()
    {
        $unparafed = [];
        
        foreach (self::JENIS_SHOLAT as $sholat) {
            $waktuField = $sholat . '_waktu';
            $parafField = $sholat . '_paraf';

            if ($this->$waktuField && !$this->$parafField) {
                $unparafed[] = [
                    'jenis' => $sholat,
                    'label' => self::SHOLAT_LABELS[$sholat],
                    'waktu' => $this->getWaktuFormatted($sholat),
                    'kategori' => $this->getKategoriLabel($sholat),
                    'nilai' => $this->{$sholat . '_nilai'} ?? 0,
                ];
            }
        }

        return $unparafed;
    }

    public function getParafedSholatList()
    {
        $parafed = [];
        
        foreach (self::JENIS_SHOLAT as $sholat) {
            $waktuField = $sholat . '_waktu';
            $parafField = $sholat . '_paraf';

            if ($this->$waktuField && $this->$parafField) {
                $parafed[] = [
                    'jenis' => $sholat,
                    'label' => self::SHOLAT_LABELS[$sholat],
                    'waktu' => $this->getWaktuFormatted($sholat),
                    'kategori' => $this->getKategoriLabel($sholat),
                    'nilai' => $this->{$sholat . '_nilai'} ?? 0,
                    'paraf_nama' => $this->{$sholat . '_paraf_nama'},
                    'paraf_waktu' => $this->{$sholat . '_paraf_waktu'} ? 
                        Carbon::parse($this->{$sholat . '_paraf_waktu'})->format('d/m/Y H:i') : null,
                ];
            }
        }

        return $parafed;
    }

    /**
     * Get Paraf Count
     */
    public function getParafCount()
    {
        $parafed = 0;
        $unparafed = 0;

        foreach (self::JENIS_SHOLAT as $sholat) {
            $waktuField = $sholat . '_waktu';
            $parafField = $sholat . '_paraf';

            if ($this->$waktuField) {
                if ($this->$parafField) {
                    $parafed++;
                } else {
                    $unparafed++;
                }
            }
        }

        return [
            'parafed' => $parafed,
            'unparafed' => $unparafed,
            'total' => $parafed + $unparafed
        ];
    }

    /**
     * ===========================================
     * HITUNG TOTAL NILAI DAN SHOLAT
     * ===========================================
     */
    public function hitungTotal()
    {
        $totalNilai = 0;
        $totalSholat = 0;

        foreach (self::JENIS_SHOLAT as $sholat) {
            $nilaiField = $sholat . '_nilai';
            $waktuField = $sholat . '_waktu';
            
            if ($this->$waktuField) {
                $totalNilai += $this->$nilaiField ?? 0;
                $totalSholat++;
            }
        }

        $this->total_nilai = $totalNilai;
        $this->total_sholat = $totalSholat;
        
        return $this;
    }

    /**
     * ===========================================
     * ACCESSORS & HELPERS
     * ===========================================
     */
    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? $this->tanggal->format('d/m/Y') : '-';
    }

    public function getNamaHariAttribute()
    {
        if (!$this->tanggal) return '-';
        $hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        return $hari[$this->tanggal->format('l')] ?? $this->tanggal->format('l');
    }

    public function getWaktuFormatted($sholat)
    {
        $field = $sholat . '_waktu';
        if (!$this->$field) return '-';
        try {
            return Carbon::parse($this->$field)->format('H:i');
        } catch (\Exception $e) {
            return substr((string)$this->$field, 0, 5);
        }
    }

    public function getKategoriLabel($sholat)
    {
        $field = $sholat . '_kategori';
        $labels = [
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat'
        ];
        return $labels[$this->$field] ?? '-';
    }

    public function getKategoriColor($sholat)
    {
        $field = $sholat . '_kategori';
        $colors = [
            'tepat_waktu' => 'success',
            'terlambat' => 'warning'
        ];
        return $colors[$this->$field] ?? 'secondary';
    }

    /**
     * ===========================================
     * METODE STATIS UNTUK PERHITUNGAN NILAI
     * ===========================================
     */
    public static function hitungNilaiSholat($jenisSholat, $waktu)
    {
        if (!$waktu) {
            return ['nilai' => 0, 'kategori' => null];
        }

        $pengaturan = PengaturanWaktuBeribadah::where('jenis_sholat', $jenisSholat)
            ->where('is_active', true)
            ->first();
        
        if (!$pengaturan) {
            return ['nilai' => 0, 'kategori' => null];
        }

        $waktuSholat = Carbon::parse($waktu)->format('H:i:s');
        
        // Cek tepat waktu
        if ($waktuSholat >= $pengaturan->waktu_tepat_start && $waktuSholat <= $pengaturan->waktu_tepat_end) {
            return ['nilai' => $pengaturan->nilai_tepat, 'kategori' => 'tepat_waktu'];
        }
        
        // Cek terlambat
        if ($waktuSholat >= $pengaturan->waktu_terlambat_start && $waktuSholat <= $pengaturan->waktu_terlambat_end) {
            return ['nilai' => $pengaturan->nilai_terlambat, 'kategori' => 'terlambat'];
        }
        
        // Tidak sholat
        return ['nilai' => $pengaturan->nilai_tidak_sholat ?? 0, 'kategori' => 'terlambat'];
    }

    /**
     * ===========================================
     * SCOPE QUERY
     * ===========================================
     */
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    public function scopeByBulanTahun($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    public function scopeByRangeTanggal($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * ===========================================
     * EVENT HANDLERS
     * ===========================================
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->hitungTotal();
        });

        static::saving(function ($model) {
            if ($model->tanggal) {
                $tanggal = Carbon::parse($model->tanggal);
                $bulanList = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                $model->bulan = $bulanList[$tanggal->month];
                $model->tahun = $tanggal->year;
            }
        });

        static::deleting(function ($model) {
            $model->parafOrtu()->delete();
        });
    }
}