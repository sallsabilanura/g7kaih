@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pengenalan Diri - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .student-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .badge {
            @apply inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold;
        }

        .badge-hobby {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
            color: white;
        }

        .badge-sport {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .badge-food {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: white;
        }

        .badge-fruit {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
        }

        .badge-lesson {
            background: linear-gradient(135deg, #EC4899 0%, #DB2777 100%);
            color: white;
        }

        .badge-dream {
            background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
            color: white;
        }

        .color-card {
            position: relative;
            transition: all 0.3s ease;
        }

        .color-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('introductions.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Detail Pengenalan Diri
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Informasi lengkap data pribadi siswa
                        </p>
                    </div>
                </div>
                
                <!-- Badge Info -->
                <div class="hidden md:flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-primary-50 to-blue-50 rounded-xl">
                    <div class="w-2 h-2 bg-accent-green rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-gray-600">
                        Diperbarui: {{ $introduction->updated_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Siswa Utama -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 rounded-2xl student-avatar flex items-center justify-center text-white font-bold text-3xl">
                        {{ strtoupper(substr($introduction->siswa->nama_lengkap, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $introduction->siswa->nama_lengkap }}</h2>
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-6 mt-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <span class="text-sm text-gray-600">NIS: <strong>{{ $introduction->siswa->nis }}</strong></span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Kelas: <strong>{{ $introduction->siswa->kelas->nama_kelas ?? 'Belum ada' }}</strong></span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Dibuat: <strong>{{ $introduction->created_at->format('d M Y') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 fade-in" style="animation-delay: 0.2s;">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <!-- Hobi -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-blue-500 to-blue-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Hobi & Minat</h3>
                            <p class="text-gray-500 text-sm">Aktivitas yang disukai</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @php
                            $hobis = explode(',', $introduction->hobi);
                        @endphp
                        @foreach($hobis as $hobi)
                            <span class="badge badge-hobby">
                                {{ trim($hobi) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Olahraga Kesukaan -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-green-500 to-green-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Olahraga Favorit</h3>
                            <p class="text-gray-500 text-sm">Aktivitas fisik yang disukai</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                        <div class="flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <p class="text-xl font-bold text-green-700">{{ $introduction->olahraga_kesukaan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pelajaran Kesukaan -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-purple-500 to-purple-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pelajaran Favorit</h3>
                            <p class="text-gray-500 text-sm">Mata pelajaran yang diminati</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-5 border border-purple-100">
                        <div class="flex items-center justify-center">
                            <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="text-xl font-bold text-purple-700">{{ $introduction->pelajaran_kesukaan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Cita-cita -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-cyan-500 to-cyan-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Cita-cita</h3>
                            <p class="text-gray-500 text-sm">Impian masa depan</p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-cyan-50 to-sky-50 rounded-xl p-5 border border-cyan-100">
                        <div class="flex items-center justify-center">
                            <svg class="w-8 h-8 text-cyan-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-xl font-bold text-cyan-700">{{ $introduction->cita_cita }}</p>
                        </div>
                    </div>
                </div>

                <!-- Makanan & Buah Kesukaan -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-orange-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Kesukaan Makanan</h3>
                            <p class="text-gray-500 text-sm">Makanan dan buah favorit</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-xl p-5 border border-orange-100">
                            <div class="flex flex-col items-center text-center">
                                <svg class="w-8 h-8 text-orange-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                </svg>
                                <p class="text-sm font-medium text-orange-700 mb-1">Makanan</p>
                                <p class="text-lg font-bold text-orange-800">{{ $introduction->makanan_kesukaan }}</p>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-100">
                            <div class="flex flex-col items-center text-center">
                                <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                </svg>
                                <p class="text-sm font-medium text-green-700 mb-1">Buah</p>
                                <p class="text-lg font-bold text-green-800">{{ $introduction->buah_kesukaan }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warna Kesukaan -->
                <div class="glass-card rounded-2xl shadow-lg p-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-2 h-8 bg-gradient-to-b from-pink-500 to-pink-600 rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Warna Favorit</h3>
                            <p class="text-gray-500 text-sm">3 pilihan warna kesukaan</p>
                        </div>
                    </div>
                    
                    @if(is_array($introduction->warna_kesukaan) && count($introduction->warna_kesukaan) === 3)
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($introduction->warna_kesukaan as $index => $warna)
                            <div class="color-card">
                                <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200 hover:border-primary-300 transition-all duration-300">
                                    <div class="flex flex-col items-center space-y-3">
                                        <div class="w-20 h-20 rounded-xl border-2 border-white shadow-lg" 
                                             style="background-color: {{ $warna }}"></div>
                                        <div class="text-center">
                                            <p class="text-sm font-medium text-gray-900">Warna {{ $index + 1 }}</p>
                                            <p class="text-xs font-mono text-gray-600 mt-1">{{ $warna }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-8 border border-gray-200 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">Data warna tidak tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.3s;">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('introductions.edit', $introduction) }}" 
                   class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                    <span class="relative flex items-center">
                        <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Data
                    </span>
                </a>
                
                <!-- Hanya tampilkan tombol hapus untuk admin -->
                @auth
                    @if(auth()->user()->peran === 'admin')
                    <form action="{{ route('introductions.destroy', $introduction) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data pengenalan diri {{ $introduction->siswa->nama_lengkap }}?')" 
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus Data
                            </span>
                        </button>
                    </form>
                    @endif
                @endauth
                
                <a href="{{ route('introductions.index') }}" 
                   class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                    <span class="relative">
                        Kembali ke Daftar
                        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                    </span>
                </a>
            </div>
        </div>

      
    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });
        });
    </script>
</body>
</html>
@endsection