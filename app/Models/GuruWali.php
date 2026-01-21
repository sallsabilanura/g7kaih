<?php
// app/Models/GuruWali.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class GuruWali extends Authenticatable
{
    use HasFactory;

    protected $table = 'guru_wali';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'foto_profil',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi dengan Kelas (One to One)
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_guru_wali', 'id_guru');
    }

    // Relasi dengan Monitoring (One to Many)
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_guru', 'id_guru');
    }
}