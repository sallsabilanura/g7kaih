<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PengaturanWaktuTidurCepat extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_waktu_tidur_cepats';

    protected $fillable = [
        'nama_pengaturan',
        'deskripsi',
        'kategori_waktu',
        'is_active',
    ];

    protected $casts = [
        'kategori_waktu' => 'array',
        'is_active' => 'boolean',
    ];

    // Accessor untuk memastikan format kategori
    public function getKategoriWaktuAttribute($value)
    {
        $kategori = json_decode($value, true);
        
        // Default jika kosong
        if (empty($kategori)) {
            $kategori = [
                [
                    'id' => 1,
                    'nama' => 'Tepat Waktu',
                    'waktu_start' => '20:00',
                    'waktu_end' => '21:00',
                    'nilai' => 100,
                    'warna' => '#10B981',
                    'urutan' => 1,
                    'is_active' => true
                ],
                [
                    'id' => 2,
                    'nama' => 'Sedikit Terlambat',
                    'waktu_start' => '21:00',
                    'waktu_end' => '22:00',
                    'nilai' => 70,
                    'warna' => '#F59E0B',
                    'urutan' => 2,
                    'is_active' => true
                ],
                [
                    'id' => 3,
                    'nama' => 'Terlambat',
                    'waktu_start' => '22:00',
                    'waktu_end' => '23:59',
                    'nilai' => 50,
                    'warna' => '#EF4444',
                    'urutan' => 3,
                    'is_active' => true
                ]
            ];
        }
        
        // Urutkan berdasarkan urutan
        usort($kategori, function($a, $b) {
            return $a['urutan'] <=> $b['urutan'];
        });
        
        return $kategori;
    }

    // Mutator untuk menyimpan sebagai JSON
    public function setKategoriWaktuAttribute($value)
    {
        $this->attributes['kategori_waktu'] = json_encode($value);
    }

    // Hitung nilai berdasarkan waktu tidur (super fleksibel)
    public function hitungNilai($waktuTidur)
    {
        if (!$this->is_active) {
            return null;
        }

        $waktu = Carbon::parse($waktuTidur);
        $kategoriAktif = array_filter($this->kategori_waktu, function($kat) {
            return $kat['is_active'] ?? true;
        });

        foreach ($kategoriAktif as $kategori) {
            $start = Carbon::parse($kategori['waktu_start']);
            $end = Carbon::parse($kategori['waktu_end']);
            
            // Tidak ada validasi, langsung cek apakah masuk rentang
            // Handle semua kemungkinan (termasuk melewati tengah malam)
            if ($end->lessThan($start)) {
                // Melewati tengah malam (misal: 22:00 - 06:00)
                if ($waktu->greaterThanOrEqualTo($start) || $waktu->lessThan($end)) {
                    return $kategori['nilai'];
                }
            } else {
                // Normal (misal: 20:00 - 22:00)
                if ($waktu->between($start, $end)) {
                    return $kategori['nilai'];
                }
            }
        }

        // Jika tidak masuk kategori manapun, default 0
        return 0;
    }

    // Get pengaturan aktif
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    // Cek apakah sudah ada pengaturan
    public static function sudahAda()
    {
        return self::count() > 0;
    }

    // Reset ke default
    public function resetToDefault()
    {
        $defaultKategori = [
            [
                'id' => 1,
                'nama' => 'Tepat Waktu',
                'waktu_start' => '20:00',
                'waktu_end' => '21:00',
                'nilai' => 100,
                'warna' => '#10B981',
                'urutan' => 1,
                'is_active' => true
            ],
            [
                'id' => 2,
                'nama' => 'Sedikit Terlambat',
                'waktu_start' => '21:00',
                'waktu_end' => '22:00',
                'nilai' => 70,
                'warna' => '#F59E0B',
                'urutan' => 2,
                'is_active' => true
            ],
            [
                'id' => 3,
                'nama' => 'Terlambat',
                'waktu_start' => '22:00',
                'waktu_end' => '23:59',
                'nilai' => 50,
                'warna' => '#EF4444',
                'urutan' => 3,
                'is_active' => true
            ]
        ];

        $this->update([
            'nama_pengaturan' => 'Pengaturan Default',
            'deskripsi' => 'Pengaturan waktu tidur default yang fleksibel',
            'kategori_waktu' => $defaultKategori,
            'is_active' => true,
        ]);
        
        return $this;
    }
}