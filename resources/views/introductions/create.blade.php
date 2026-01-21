@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pengenalan Diri - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #0033A0 0%, #00A86B 100%) border-box;
            border: 1.5px solid transparent;
        }

        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
            outline: none;
        }
        
        .student-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }
        
        .color-picker-container {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .color-picker-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .color-preview {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
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
                            {{ $hasIntroduction ? 'Update' : 'Tambah' }} Pengenalan Diri
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            {{ $hasIntroduction ? 'Perbarui' : 'Isi' }} data pengenalan diri Anda
                        </p>
                    </div>
                </div>
                
                <!-- Status Badge -->
                @if($hasIntroduction)
                <div class="hidden md:flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-50 to-accent-green/20 rounded-xl">
                    <div class="w-2 h-2 bg-accent-green rounded-full animate-pulse"></div>
                    <span class="text-xs font-medium text-gray-600">
                        Anda sudah memiliki data
                    </span>
                </div>
                @endif
            </div>

            <!-- Alert Pesan -->
            @if($hasIntroduction)
            <div class="mt-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">
                        <span class="font-bold">Perhatian!</span> Anda sudah mengisi data pengenalan diri sebelumnya.
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        Mengisi form ini akan menggantikan data yang sudah ada.
                    </p>
                </div>
            </div>
            @endif

            @if (session('warning'))
            <div class="mt-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Info Siswa -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-2xl student-avatar flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
                        <div class="flex items-center space-x-4 mt-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                                <span class="text-sm text-gray-600">NIS: {{ $siswa->nis }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="text-sm text-gray-600">Kelas: {{ $siswa->kelas->nama_kelas ?? 'Belum ada' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Terdaftar</p>
                    <p class="text-sm font-medium text-gray-900">{{ $siswa->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.2s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Pengenalan Diri</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ $hasIntroduction ? route('introductions.update', $siswa->introduction) : route('introductions.store') }}" 
                  method="POST" 
                  class="p-6 md:p-8"
                  id="introductionForm">
                @csrf
                @if($hasIntroduction)
                    @method('PUT')
                @endif
                
                <div class="space-y-6">
                    <!-- Grid untuk Hobi dan Cita-cita -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.25s;">
                        <!-- Hobi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hobi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="hobi" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Membaca, Olahraga, Musik"
                                    value="{{ old('hobi', $siswa->introduction->hobi ?? '') }}"
                                >
                            </div>
                            @error('hobi')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Pisahkan hobi dengan koma</p>
                        </div>

                        <!-- Cita-cita -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cita-cita <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="cita_cita" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Dokter, Guru, Insinyur"
                                    value="{{ old('cita_cita', $siswa->introduction->cita_cita ?? '') }}"
                                >
                            </div>
                            @error('cita_cita')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid untuk Olahraga dan Makanan Kesukaan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.3s;">
                        <!-- Olahraga Kesukaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Olahraga Kesukaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="olahraga_kesukaan" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Sepak Bola, Basket, Renang"
                                    value="{{ old('olahraga_kesukaan', $siswa->introduction->olahraga_kesukaan ?? '') }}"
                                >
                            </div>
                            @error('olahraga_kesukaan')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Makanan Kesukaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Makanan Kesukaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="makanan_kesukaan" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Nasi Goreng, Soto, Bakso"
                                    value="{{ old('makanan_kesukaan', $siswa->introduction->makanan_kesukaan ?? '') }}"
                                >
                            </div>
                            @error('makanan_kesukaan')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Grid untuk Buah dan Pelajaran Kesukaan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.35s;">
                        <!-- Buah Kesukaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Buah Kesukaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="buah_kesukaan" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Apel, Jeruk, Mangga"
                                    value="{{ old('buah_kesukaan', $siswa->introduction->buah_kesukaan ?? '') }}"
                                >
                            </div>
                            @error('buah_kesukaan')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Pelajaran Kesukaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pelajaran Kesukaan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="pelajaran_kesukaan" 
                                    required 
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                    placeholder="Contoh: Matematika, IPA, Bahasa"
                                    value="{{ old('pelajaran_kesukaan', $siswa->introduction->pelajaran_kesukaan ?? '') }}"
                                >
                            </div>
                            @error('pelajaran_kesukaan')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Warna Kesukaan -->
                    <div class="fade-in" style="animation-delay: 0.4s;">
                        <label class="block text-sm font-medium text-gray-700 mb-4">
                            Warna Kesukaan <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500 font-normal">(Pilih 3 warna berbeda)</span>
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @for($i = 0; $i < 3; $i++)
                                <div class="color-picker-container">
                                    <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-xl p-4 border border-gray-200 hover:border-primary-300 transition-all duration-300">
                                        <label class="block text-sm font-medium text-gray-700 mb-3 text-center">
                                            Warna Favorit {{ $i + 1 }}
                                        </label>
                                        <div class="flex flex-col items-center space-y-3">
                                            <input type="color" 
                                                   name="warna_kesukaan[]" 
                                                   class="w-16 h-16 rounded-xl cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                                   value="{{ old('warna_kesukaan.' . $i, $siswa->introduction->warna_kesukaan[$i] ?? ['#0033A0', '#DC143C', '#00A86B'][$i]) }}"
                                                   required>
                                            <div class="flex items-center justify-center space-x-2">
                                                <div class="color-preview" style="background-color: {{ old('warna_kesukaan.' . $i, $siswa->introduction->warna_kesukaan[$i] ?? ['#0033A0', '#DC143C', '#00A86B'][$i]) }}"></div>
                                                <span class="text-xs font-mono text-gray-600">
                                                    {{ old('warna_kesukaan.' . $i, $siswa->introduction->warna_kesukaan[$i] ?? ['#0033A0', '#DC143C', '#00A86B'][$i]) }}
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500 text-center">Klik untuk memilih warna</span>
                                        </div>
                                    </div>
                                    @error('warna_kesukaan.' . $i)
                                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endfor
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-4 text-center">
                            Pastikan ketiga warna berbeda satu sama lain
                        </p>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.5s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $hasIntroduction ? 'Perbarui Data' : 'Simpan Pengenalan Diri' }}
                        </span>
                    </button>
                    <a href="{{ route('introductions.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batalkan
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                        </span>
                    </a>
                </div>
            </form>
        </div>

    </div>

    <script>
        // Animasi untuk form elements dengan delay bertahap
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.fade-in');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Efek hover untuk input
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-1', 'ring-primary-200');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-1', 'ring-primary-200');
                });
            });

            // Update color preview
            const colorInputs = document.querySelectorAll('input[type="color"]');
            colorInputs.forEach(input => {
                const container = input.closest('.color-picker-container');
                const preview = container.querySelector('.color-preview');
                const textSpan = container.querySelector('.text-gray-600');
                
                input.addEventListener('input', function() {
                    preview.style.backgroundColor = this.value;
                    textSpan.textContent = this.value;
                });
            });

            // Validasi warna unik
            const form = document.getElementById('introductionForm');
            form.addEventListener('submit', function(e) {
                const colorInputs = document.querySelectorAll('input[type="color"]');
                const colors = Array.from(colorInputs).map(input => input.value);
                const uniqueColors = new Set(colors);
                
                if (uniqueColors.size !== colors.length) {
                    e.preventDefault();
                    alert('Harap pilih 3 warna yang berbeda untuk warna kesukaan!');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
@endsection