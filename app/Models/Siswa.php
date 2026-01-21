<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'nama_lengkap',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'email',
        'orangtua_id',
        'kelas_id',
        'tahun_masuk',
        'tanggal_lahir',
        // HAPUS 'status' dari sini
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
    ];

    // ========== RELASI UTAMA ==========
    
    /**
     * Relasi ke kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke orangtua (ONE-TO-ONE)
     */
    public function orangtua(): HasOne
    {
        return $this->hasOne(Orangtua::class, 'siswa_id');
    }


    
    /**
     * Relasi ke introduction
     */
    public function introduction(): HasOne
    {
        return $this->hasOne(Introduction::class, 'siswa_id');
    }

    // ========== RELASI KEBIASAAN ==========
    
    /**
     * Relasi ke tidur cepat
     */
    public function tidurCepats(): HasMany
    {
        return $this->hasMany(TidurCepat::class, 'siswa_id');
    }

    /**
     * Relasi ke bangun pagi
     */
    public function bangunPagis(): HasMany
    {
        return $this->hasMany(BangunPagi::class, 'siswa_id');
    }

    /**
     * Relasi ke makan sehat
     */
    public function makanSehat(): HasMany
    {
        return $this->hasMany(MakanSehat::class, 'siswa_id');
    }

    /**
     * Relasi ke berolahraga
     */
    public function berolahraga(): HasMany
    {
        return $this->hasMany(Berolahraga::class, 'siswa_id');
    }

    /**
     * Relasi ke tanggapan orangtua
     */
    public function tanggapanOrangtua(): HasMany
    {
        return $this->hasMany(TanggapanOrangtua::class, 'siswa_id');
    }

    // ========== SCOPES ==========
    
    /**
     * Scope by NIS
     */
    public function scopeByNis($query, $nis)
    {
        return $query->where('nis', $nis);
    }

    /**
     * Scope by NISN
     */
    public function scopeByNisn($query, $nisn)
    {
        return $query->where('nisn', $nisn);
    }

    /**
     * Scope by kelas
     */
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Scope search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
    }

    /**
     * Scope order by nama
     */
    public function scopeOrderByNamaLengkap($query, $direction = 'asc')
    {
        return $query->orderBy('nama_lengkap', $direction);
    }

    // ========== ACCESSORS UMUM ==========
    
    /**
     * Format tanggal lahir
     */
    public function getFormattedTanggalLahirAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->format('d F Y') : '-';
    }

    /**
     * Get umur
     */
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Alias untuk umur
     */
    public function getAgeAttribute()
    {
        return $this->getUmurAttribute();
    }

    /**
     * Check if has orangtua
     */
    public function hasOrangtua(): bool
    {
        return $this->orangtua()->exists();
    }

    /**
     * Get nama dropdown
     */
    public function getNamaDropdownAttribute()
    {
        return $this->nama_lengkap ?: $this->nama;
    }

    /**
     * Get info lengkap
     */
    public function getInfoLengkapAttribute()
    {
        $info = $this->nama_lengkap ?: $this->nama;
        
        if ($this->nis) {
            $info .= " - {$this->nis}";
        }
        
        if ($this->kelas) {
            $info .= " - {$this->kelas->nama_kelas}";
        }
        
        return $info;
    }

    // ========== ACCESSORS ORANGTUA ==========
    
    /**
     * Get nama orangtua lengkap
     */
    public function getNamaOrangtuaAttribute()
    {
        if ($this->orangtua) {
            return $this->orangtua->nama_lengkap;
        }
        return '-';
    }

    /**
     * Get nama ayah
     */
    public function getNamaAyahAttribute()
    {
        if ($this->orangtua) {
            return $this->orangtua->nama_ayah;
        }
        return null;
    }

    /**
     * Get nama ibu
     */
    public function getNamaIbuAttribute()
    {
        if ($this->orangtua) {
            return $this->orangtua->nama_ibu;
        }
        return null;
    }

    /**
     * Get nama ayah dengan format
     */
    public function getNamaAyahFormattedAttribute()
    {
        if ($this->orangtua && $this->orangtua->nama_ayah) {
            return $this->orangtua->nama_ayah . ' (Ayah)';
        }
        return null;
    }

    /**
     * Get nama ibu dengan format
     */
    public function getNamaIbuFormattedAttribute()
    {
        if ($this->orangtua && $this->orangtua->nama_ibu) {
            return $this->orangtua->nama_ibu . ' (Ibu)';
        }
        return null;
    }

    /**
     * Get semua pilihan nama orangtua untuk dropdown
     */
    public function getOrangtuaOptionsAttribute()
    {
        $options = [];
        
        if ($this->orangtua) {
            if ($this->orangtua->nama_ayah) {
                $options[] = [
                    'value' => $this->orangtua->nama_ayah,
                    'label' => $this->orangtua->nama_ayah . ' (Ayah)'
                ];
            }
            if ($this->orangtua->nama_ibu) {
                $options[] = [
                    'value' => $this->orangtua->nama_ibu,
                    'label' => $this->orangtua->nama_ibu . ' (Ibu)'
                ];
            }
        }
        
        // Jika tidak ada data orangtua, tambahkan opsi umum
        if (empty($options)) {
            $options[] = [
                'value' => 'orangtua_lainnya',
                'label' => 'Orang Tua/Wali Lainnya'
            ];
        }
        
        return $options;
    }

    /**
     * Get nomor telepon orangtua
     */
    public function getTeleponOrangtuaAttribute()
    {
        if ($this->orangtua) {
            return $this->orangtua->telepon_utama;
        }
        return null;
    }

    // ========== METHODS TIDUR & BANGUN PAGI ==========
    
    /**
     * Get riwayat tidur dan bangun pagi
     */
    public function getRiwayatTidurBangun($jumlahHari = 7)
    {
        $riwayat = collect();
        
        for ($i = $jumlahHari - 1; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            
            $tidurCepat = $this->tidurCepats()
                ->whereDate('tanggal', $tanggal)
                ->first();
            
            $bangunPagi = $this->bangunPagis()
                ->whereDate('tanggal', $tanggal)
                ->first();
            
            $riwayat->push((object)[
                'tanggal' => $tanggal,
                'tidur_cepat' => $tidurCepat,
                'bangun_pagi' => $bangunPagi,
            ]);
        }
        
        return $riwayat;
    }

    /**
     * Get statistik tidur bulan ini
     */
    public function getStatistikTidurBulanIni()
    {
        $bulanIni = now()->format('F Y');
        
        $tidurData = $this->tidurCepats()
            ->where('bulan', $bulanIni)
            ->get();
        
        $bangunData = $this->bangunPagis()
            ->where('bulan', $bulanIni)
            ->get();
        
        return [
            'total_tidur' => $tidurData->count(),
            'total_bangun' => $bangunData->count(),
            'rata_nilai_tidur' => round($tidurData->avg('nilai') ?? 0),
            'rata_nilai_bangun' => round($bangunData->avg('nilai') ?? 0),
            'tidur_tepat_waktu' => $tidurData->where('nilai', '>=', 90)->count(),
            'bangun_tepat_waktu' => $bangunData->where('nilai', '>=', 90)->count(),
            'hari_lengkap' => min($tidurData->count(), $bangunData->count()),
        ];
    }

    /**
     * Cek apakah sudah tidur hari ini
     */
    public function sudahTidurHariIni(): bool
    {
        return $this->tidurCepats()
            ->whereDate('tanggal', Carbon::today())
            ->exists();
    }

    /**
     * Cek apakah sudah bangun hari ini
     */
    public function sudahBangunHariIni(): bool
    {
        return $this->bangunPagis()
            ->whereDate('tanggal', Carbon::today())
            ->exists();
    }

    /**
     * Get tidur cepat hari ini
     */
    public function getTidurCepatHariIni()
    {
        return $this->tidurCepats()
            ->whereDate('tanggal', Carbon::today())
            ->first();
    }

    /**
     * Get bangun pagi hari ini
     */
    public function getBangunPagiHariIni()
    {
        return $this->bangunPagis()
            ->whereDate('tanggal', Carbon::today())
            ->first();
    }

    // ========== METHODS MAKAN SEHAT ==========
    
    /**
     * Get makan sehat harian
     */
    public function getMakanSehatHarian($tanggal)
    {
        return $this->makanSehat()
                   ->where('tanggal', $tanggal)
                   ->get()
                   ->groupBy('jenis_makanan');
    }

    /**
     * Get makan sehat bulanan
     */
    public function getMakanSehatBulanan($bulan, $tahun = null)
    {
        $query = $this->makanSehat()->where('bulan', $bulan);
        
        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        return $query->get();
    }

    /**
     * Get rata-rata makan sehat bulanan
     */
    public function getRataRataMakanSehatBulanan($bulan, $tahun = null)
    {
        $data = $this->getMakanSehatBulanan($bulan, $tahun);
        
        if ($data->count() > 0) {
            return round($data->avg('nilai'));
        }

        return 0;
    }

    /**
     * Get rekap makan sehat harian
     */
    public function getRekapMakanSehatHarian($tanggal)
    {
        $data = $this->getMakanSehatHarian($tanggal);
        
        $rekap = [
            'sarapan' => $data->get('sarapan')?->first(),
            'makan_siang' => $data->get('makan_siang')?->first(),
            'makan_malam' => $data->get('makan_malam')?->first(),
        ];

        $totalNilai = collect($rekap)->filter()->sum('nilai');
        $jumlahData = collect($rekap)->filter()->count();
        $rataRata = $jumlahData > 0 ? round($totalNilai / $jumlahData) : 0;

        return [
            'rekap' => $rekap,
            'total_data' => $jumlahData,
            'rata_rata' => $rataRata,
            'kategori' => $this->getKategoriNilai($rataRata)
        ];
    }

    /**
     * Get kategori nilai
     */
    private function getKategoriNilai($nilai)
    {
        if ($nilai >= 90) return 'Terbiasa';
        if ($nilai >= 70) return 'Mulai Terbiasa';
        return 'Belum Terbiasa';
    }

    /**
     * Cek apakah sudah input makan sehat hari ini
     */
    public function sudahInputMakanSehatHariIni($jenisMakanan = null): bool
    {
        $query = $this->makanSehat()->where('tanggal', today());
        
        if ($jenisMakanan) {
            $query->where('jenis_makanan', $jenisMakanan);
        }

        return $query->exists();
    }

    /**
     * Get statistik makan sehat bulan ini
     */
    public function getStatistikMakanSehatBulanIni()
    {
        $bulanIni = now()->format('F Y');
        
        $data = $this->makanSehat()
                    ->where('bulan', $bulanIni)
                    ->get();

        $totalHari = $data->unique('tanggal')->count();
        $rataRataNilai = $data->count() > 0 ? round($data->avg('nilai')) : 0;
        
        $perJenis = [
            'sarapan' => $data->where('jenis_makanan', 'sarapan')->count(),
            'makan_siang' => $data->where('jenis_makanan', 'makan_siang')->count(),
            'makan_malam' => $data->where('jenis_makanan', 'makan_malam')->count(),
        ];

        return [
            'total_hari' => $totalHari,
            'rata_rata_nilai' => $rataRataNilai,
            'kategori' => $this->getKategoriNilai($rataRataNilai),
            'per_jenis' => $perJenis,
            'total_input' => $data->count()
        ];
    }

    /**
     * Get progress makan sehat bulan ini
     */
    public function getProgressMakanSehatBulanIni()
    {
        $statistik = $this->getStatistikMakanSehatBulanIni();
        $hariDalamBulan = now()->daysInMonth;
        
        if ($hariDalamBulan > 0) {
            $progress = ($statistik['total_hari'] / $hariDalamBulan) * 100;
            return round($progress, 1);
        }

        return 0;
    }

    /**
     * Get rekomendasi makan sehat
     */
    public function getRekomendasiMakanSehat()
    {
        $statistik = $this->getStatistikMakanSehatBulanIni();
        $rekomendasi = [];

        if ($statistik['per_jenis']['sarapan'] < 15) {
            $rekomendasi[] = 'Tingkatkan kebiasaan sarapan pagi';
        }

        if ($statistik['per_jenis']['makan_siang'] < 15) {
            $rekomendasi[] = 'Perbaiki konsistensi makan siang';
        }

        if ($statistik['per_jenis']['makan_malam'] < 15) {
            $rekomendasi[] = 'Jaga rutinitas makan malam';
        }

        if ($statistik['rata_rata_nilai'] < 70) {
            $rekomendasi[] = 'Perhatikan waktu makan yang ideal';
        }

        return empty($rekomendasi) ? ['Kebiasaan makan sudah baik, pertahankan!'] : $rekomendasi;
    }

    // ========== METHODS UNTUK PARAF ORANGTUA ==========
    
    /**
     * Get data orangtua untuk paraf
     */
    public function getOrangtuaForParaf()
    {
        $data = [];
        
        if ($this->orangtua) {
            if ($this->orangtua->nama_ayah) {
                $data['ayah'] = [
                    'nama' => $this->orangtua->nama_ayah,
                    'formatted' => $this->orangtua->nama_ayah . ' (Ayah)'
                ];
            }
            
            if ($this->orangtua->nama_ibu) {
                $data['ibu'] = [
                    'nama' => $this->orangtua->nama_ibu,
                    'formatted' => $this->orangtua->nama_ibu . ' (Ibu)'
                ];
            }
        }
        
        return $data;
    }

    /**
     * Get pilihan orangtua untuk dropdown paraf
     */
    public function getOrangtuaParafOptions()
    {
        $options = [];
        
        if ($this->orangtua) {
            if ($this->orangtua->nama_ayah) {
                $options[] = [
                    'value' => $this->orangtua->nama_ayah,
                    'label' => $this->orangtua->nama_ayah . ' (Ayah)',
                    'type' => 'ayah'
                ];
            }
            
            if ($this->orangtua->nama_ibu) {
                $options[] = [
                    'value' => $this->orangtua->nama_ibu,
                    'label' => $this->orangtua->nama_ibu . ' (Ibu)',
                    'type' => 'ibu'
                ];
            }
        }
        
        // Tambahkan opsi lainnya
        $options[] = [
            'value' => 'orangtua_lainnya',
            'label' => 'Orang Tua/Wali Lainnya',
            'type' => 'lainnya'
        ];
        
        return $options;
    }

    /**
     * Cek apakah orangtua sudah memberikan paraf untuk tanggal tertentu
     */
    public function orangtuaSudahParaf($tanggal)
    {
        return $this->bangunPagis()
            ->whereDate('tanggal', $tanggal)
            ->where('sudah_ttd_ortu', true)
            ->exists();
    }

    /**
     * Get paraf orangtua untuk tanggal tertentu
     */
    public function getParafOrangtua($tanggal)
    {
        $bangunPagi = $this->bangunPagis()
            ->whereDate('tanggal', $tanggal)
            ->first();
            
        if ($bangunPagi && $bangunPagi->parafOrtu) {
            return $bangunPagi->parafOrtu;
        }
        
        return null;
    }

    // ========== METHODS BEROLAHRAGA ==========
    
    /**
     * Get riwayat berolahraga
     */
    public function getRiwayatBerolahraga($jumlahHari = 7)
    {
        $riwayat = collect();
        
        for ($i = $jumlahHari - 1; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);
            
            $berolahraga = $this->berolahraga()
                ->whereDate('tanggal', $tanggal)
                ->first();
            
            $riwayat->push((object)[
                'tanggal' => $tanggal,
                'berolahraga' => $berolahraga,
            ]);
        }
        
        return $riwayat;
    }

    /**
     * Get statistik berolahraga bulan ini
     */
    public function getStatistikBerolahragaBulanIni()
    {
        $bulanIni = now()->format('F Y');
        
        $data = $this->berolahraga()
            ->where('bulan', $bulanIni)
            ->get();
        
        return [
            'total_hari' => $data->count(),
            'rata_nilai' => round($data->avg('nilai') ?? 0),
            'olahraga_teratur' => $data->where('nilai', '>=', 90)->count(),
            'total_durasi' => $data->sum('durasi'),
            'durasi_rata' => $data->count() > 0 ? round($data->avg('durasi')) : 0,
            'jenis_olahraga' => $data->groupBy('jenis_olahraga')->map->count(),
        ];
    }

    /**
     * Cek apakah sudah berolahraga hari ini
     */
    public function sudahBerolahragaHariIni(): bool
    {
        return $this->berolahraga()
            ->whereDate('tanggal', Carbon::today())
            ->exists();
    }

    /**
     * Get berolahraga hari ini
     */
    public function getBerolahragaHariIni()
    {
        return $this->berolahraga()
            ->whereDate('tanggal', Carbon::today())
            ->first();
    }

    /**
     * Get rekomendasi olahraga
     */
    public function getRekomendasiOlahraga()
    {
        $statistik = $this->getStatistikBerolahragaBulanIni();
        $rekomendasi = [];

        if ($statistik['total_hari'] < 15) {
            $rekomendasi[] = 'Tingkatkan frekuensi olahraga minimal 3-4 kali seminggu';
        }

        if ($statistik['durasi_rata'] < 30) {
            $rekomendasi[] = 'Perpanjang durasi olahraga minimal 30 menit per sesi';
        }

        if ($statistik['rata_nilai'] < 70) {
            $rekomendasi[] = 'Perhatikan intensitas dan kualitas olahraga';
        }

        return empty($rekomendasi) ? ['Kebiasaan olahraga sudah baik, pertahankan!'] : $rekomendasi;
    }

    // ========== METHODS TANGGAPAN ORANGTUA ==========
    
    /**
     * Get tanggapan orangtua untuk bulan tertentu
     */
    public function getTanggapanOrangtuaByPeriode($bulan, $tahun)
    {
        return $this->tanggapanOrangtua()
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->first();
    }

    /**
     * Cek apakah sudah ada tanggapan untuk bulan tertentu
     */
    public function sudahAdaTanggapan($bulan, $tahun): bool
    {
        return $this->tanggapanOrangtua()
                    ->where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->exists();
    }

    /**
     * Get tanggapan orangtua bulan ini
     */
    public function tanggapanBulanIni()
    {
        return $this->tanggapanOrangtua()
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->first();
    }

    /**
     * Cek apakah sudah ada tanggapan bulan ini
     */
    public function sudahAdaTanggapanBulanIni(): bool
    {
        return $this->tanggapanOrangtua()
                    ->where('bulan', now()->month)
                    ->where('tahun', now()->year)
                    ->exists();
    }

    /**
     * Get riwayat tanggapan orangtua
     */
    public function getRiwayatTanggapan($limit = 12)
    {
        return $this->tanggapanOrangtua()
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->limit($limit)
                    ->get();
    }

    /**
     * Get statistik tanggapan orangtua
     */
    public function getStatistikTanggapan()
    {
        $total = $this->tanggapanOrangtua()->count();
        $tahunIni = $this->tanggapanOrangtua()
                        ->where('tahun', now()->year)
                        ->count();
        $perTipe = $this->tanggapanOrangtua()
                       ->selectRaw('tipe_orangtua, COUNT(*) as jumlah')
                       ->groupBy('tipe_orangtua')
                       ->get()
                       ->pluck('jumlah', 'tipe_orangtua');

        return [
            'total' => $total,
            'tahun_ini' => $tahunIni,
            'per_tipe' => $perTipe,
            'persentase' => $total > 0 ? round(($tahunIni / 12) * 100, 1) : 0,
        ];
    }

    // ========== METHODS OVERALL STATISTICS ==========
    
    /**
     * Get statistik keseluruhan kebiasaan
     */
    public function getStatistikKeseluruhan()
    {
        $statistikTidur = $this->getStatistikTidurBulanIni();
        $statistikMakan = $this->getStatistikMakanSehatBulanIni();
        $statistikOlahraga = $this->getStatistikBerolahragaBulanIni();
        $statistikTanggapan = $this->getStatistikTanggapan();

        // Hitung rata-rata nilai keseluruhan
        $rataTidur = ($statistikTidur['rata_nilai_tidur'] + $statistikTidur['rata_nilai_bangun']) / 2;
        $rataMakan = $statistikMakan['rata_rata_nilai'];
        $rataOlahraga = $statistikOlahraga['rata_nilai'];

        $totalRata = round(($rataTidur + $rataMakan + $rataOlahraga) / 3);

        return [
            'tidur' => [
                'nilai' => $rataTidur,
                'kategori' => $this->getKategoriNilai($rataTidur),
                'progress' => round(($statistikTidur['hari_lengkap'] / now()->daysInMonth) * 100, 1)
            ],
            'makan' => [
                'nilai' => $rataMakan,
                'kategori' => $statistikMakan['kategori'],
                'progress' => $this->getProgressMakanSehatBulanIni()
            ],
            'olahraga' => [
                'nilai' => $rataOlahraga,
                'kategori' => $this->getKategoriNilai($rataOlahraga),
                'progress' => round(($statistikOlahraga['total_hari'] / now()->daysInMonth) * 100, 1)
            ],
            'tanggapan' => [
                'jumlah' => $statistikTanggapan['tahun_ini'],
                'persentase' => $statistikTanggapan['persentase']
            ],
            'overall' => [
                'nilai' => $totalRata,
                'kategori' => $this->getKategoriNilai($totalRata)
            ]
        ];
    }

    /**
     * Get rekomendasi keseluruhan
     */
    public function getRekomendasiKeseluruhan()
    {
        $rekomendasi = [];
        $statistik = $this->getStatistikKeseluruhan();

        // Rekomendasi berdasarkan kategori
        if ($statistik['tidur']['kategori'] === 'Belum Terbiasa') {
            $rekomendasi[] = 'Perbaiki pola tidur dengan tidur lebih awal dan bangun lebih pagi';
        }
        
        if ($statistik['makan']['kategori'] === 'Belum Terbiasa') {
            $rekomendasi[] = 'Tingkatkan konsistensi makan 3 kali sehari dengan gizi seimbang';
        }
        
        if ($statistik['olahraga']['kategori'] === 'Belum Terbiasa') {
            $rekomendasi[] = 'Tingkatkan frekuensi dan kualitas olahraga';
        }

        // Rekomendasi berdasarkan progress
        if ($statistik['tidur']['progress'] < 70) {
            $rekomendasi[] = 'Tingkatkan konsistensi tidur dan bangun setiap hari';
        }
        
        if ($statistik['makan']['progress'] < 70) {
            $rekomendasi[] = 'Tingkatkan jumlah hari makan sehat dalam sebulan';
        }
        
        if ($statistik['olahraga']['progress'] < 70) {
            $rekomendasi[] = 'Tingkatkan frekuensi olahraga dalam sebulan';
        }

        return empty($rekomendasi) ? ['Semua kebiasaan baik sudah terjaga dengan baik!'] : $rekomendasi;
    }

    // ========== METHODS UNTUK DASHBOARD ==========
    
    /**
     * Get data untuk dashboard siswa
     */
    public function getDashboardData()
    {
        $today = Carbon::today();
        
        return [
            'hari_ini' => [
                'tidur' => $this->getTidurCepatHariIni(),
                'bangun' => $this->getBangunPagiHariIni(),
                'makan' => $this->getRekapMakanSehatHarian($today),
                'olahraga' => $this->getBerolahragaHariIni(),
                'tanggapan' => $this->tanggapanBulanIni(),
            ],
            'statistik_bulan_ini' => $this->getStatistikKeseluruhan(),
            'riwayat_7_hari' => [
                'tidur_bangun' => $this->getRiwayatTidurBangun(7),
                'olahraga' => $this->getRiwayatBerolahraga(7),
            ],
            'rekomendasi' => $this->getRekomendasiKeseluruhan(),
        ];
    }

    /**
     * Get summary untuk report card
     */
    public function getReportSummary($bulan = null, $tahun = null)
    {
        if (!$bulan) $bulan = now()->month;
        if (!$tahun) $tahun = now()->year;

        $namaBulan = Carbon::create()->month($bulan)->locale('id')->monthName;

        return [
            'periode' => "$namaBulan $tahun",
            'siswa' => [
                'nama' => $this->nama_lengkap,
                'kelas' => $this->kelas->nama_kelas ?? '-',
                'nis' => $this->nis,
            ],
            'kebiasaan' => [
                'tidur' => [
                    'rata_nilai' => $this->getStatistikTidurBulanIni(),
                    'progress' => round(($this->tidurCepats()->where('bulan', $namaBulan)->count() / 30) * 100, 1)
                ],
                'makan' => [
                    'rata_nilai' => $this->getRataRataMakanSehatBulanan($bulan, $tahun),
                    'statistik' => $this->getStatistikMakanSehatBulanIni()
                ],
                'olahraga' => [
                    'rata_nilai' => $this->berolahraga()
                                       ->where('bulan', $namaBulan)
                                       ->avg('nilai') ?? 0,
                    'statistik' => $this->getStatistikBerolahragaBulanIni()
                ],
            ],
            'tanggapan_orangtua' => $this->getTanggapanOrangtuaByPeriode($bulan, $tahun),
            'created_at' => now(),
        ];
    }
}