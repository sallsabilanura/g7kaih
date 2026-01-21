<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'username' => 'admin',
                'nama_lengkap' => 'Administrator Sistem',
                'jenis_kelamin' => 'L',
                'peran' => 'admin',
                'is_active' => true,
            ]);

            $this->command->info('✅ Admin berhasil dibuat: admin@example.com / password123');
        } else {
            $this->command->info('ℹ️ Admin sudah ada, tidak dibuat ulang.');
        }
    }
}
