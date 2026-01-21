@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Makan Sehat - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .photo-preview-zone {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
        }

        .photo-preview-zone.has-image {
            background: #f3f4f6;
        }

        .upload-icon {
            transition: all 0.3s ease;
        }

        .photo-preview-zone:hover .upload-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .time-badge {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-gray-50 to-green-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 sm:py-8 max-w-6xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-8 border border-gray-100 fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="bg-gradient-to-br from-blue-600 to-green-600 rounded-2xl p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            Edit Data Makan Sehat
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            @if($makanSehat->siswa)
                                <span class="font-semibold text-blue-600">{{ $makanSehat->siswa->nama_lengkap }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">Siswa</span>
                            @else
                                <span class="font-semibold text-green-600">{{ Auth::user()->nama_lengkap }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">{{ Auth::user()->peran }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('makan-sehat.index') }}" 
                   class="inline-flex items-center justify-center bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 text-sm sm:text-base group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Info Data -->
            <div class="mt-6 bg-gradient-to-r from-blue-50 to-green-50 border-2 border-blue-200 rounded-xl p-5 slide-in">
                <div class="flex items-start gap-4">
                    <div class="bg-blue-600 rounded-full p-2 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-blue-900 text-lg mb-2">Informasi Data</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                <p class="text-xs text-gray-600 mb-1">Jenis Makanan</p>
                                <p class="text-lg font-bold text-blue-600 capitalize">{{ str_replace('_', ' ', $makanSehat->jenis_makanan) }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                <p class="text-xs text-gray-600 mb-1">Tanggal</p>
                                <p class="text-lg font-bold text-green-600">{{ \Carbon\Carbon::parse($makanSehat->tanggal)->format('d/m/Y') }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                <p class="text-xs text-gray-600 mb-1">Waktu Makan</p>
                                <p class="text-lg font-bold text-blue-600">{{ $makanSehat->waktu_makan_formatted }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 fade-in" style="animation-delay: 0.3s;">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-500 to-green-500 p-6 sm:p-8">
                <div class="flex items-center gap-4">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Form Edit Data Makan Sehat</h2>
                        <div class="flex flex-wrap gap-3 text-sm text-white text-opacity-90">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                @if($makanSehat->siswa)
                                    {{ $makanSehat->siswa->nama_lengkap }} (Siswa)
                                @else
                                    {{ Auth::user()->nama_lengkap }} ({{ Auth::user()->peran }})
                                @endif
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($makanSehat->tanggal)->format('d/m/Y') }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $makanSehat->waktu_makan_formatted }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form action="{{ route('makan-sehat.update', $makanSehat->id) }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                @method('PUT')
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                        <div class="flex items-center gap-2 text-red-800 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">Terjadi kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center gap-2 text-green-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-semibold">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <div class="space-y-8">
                    <!-- Pilih Siswa (jika admin) -->
                    @if(!Session::get('siswa_id'))
                    <div class="slide-in">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Pilih Siswa
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="siswa_id" 
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id', $makanSehat->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_lengkap }} - {{ $siswa->nis }}
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    @else
                    <!-- Data Siswa (Read Only untuk siswa) -->
                    <div class="slide-in bg-gradient-to-r from-blue-50 to-green-50 rounded-xl p-6 border-2 border-blue-200">
                        <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Siswa
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Nama</p>
                                <p class="text-base font-bold text-gray-900">{{ $makanSehat->siswa->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">NIS</p>
                                <p class="text-base font-bold text-gray-900">{{ $makanSehat->siswa->nis }}</p>
                            </div>
                        </div>
                        <input type="hidden" name="siswa_id" value="{{ $makanSehat->siswa_id }}">
                    </div>
                    @endif

                    <!-- Tanggal -->
                    <div class="slide-in" style="animation-delay: 0.1s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <input type="date" 
                               name="tanggal" 
                               value="{{ old('tanggal', $makanSehat->tanggal->format('Y-m-d')) }}"
                               class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-gray-900 font-medium"
                               required>
                        @error('tanggal')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Jenis Makanan -->
                    <div class="slide-in" style="animation-delay: 0.15s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jenis Makanan
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <select name="jenis_makanan" 
                                class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium"
                                required>
                            @foreach($jenisMakanan as $key => $value)
                                <option value="{{ $key }}" {{ old('jenis_makanan', $makanSehat->jenis_makanan) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_makanan')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Waktu Makan -->
                    <div class="slide-in" style="animation-delay: 0.2s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Waktu Makan
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        
                        <input type="time" 
                               name="waktu_makan" 
                               id="waktu_makan_input"
                               value="{{ old('waktu_makan', $makanSehat->waktu_makan_formatted) }}"
                               class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 font-medium"
                               required>
                        
                        <!-- Debug info -->
                        <div class="mt-2 text-xs text-gray-500">
                            Format: HH:MM (contoh: 06:30, 12:45, 15:20)
                        </div>
            
                        <!-- Info Nilai Berdasarkan Waktu -->
                        <div class="mt-3 grid grid-cols-1 gap-2">
                            @php
                                $currentType = old('jenis_makanan', $makanSehat->jenis_makanan);
                                $warna = [
                                    'sarapan' => ['bg' => 'blue', 'text' => 'blue'],
                                    'makan_siang' => ['bg' => 'green', 'text' => 'green'],
                                    'makan_malam' => ['bg' => 'indigo', 'text' => 'indigo']
                                ][$currentType] ?? ['bg' => 'blue', 'text' => 'blue'];
                            @endphp
                            
                            @if($pengaturan)
                                <div class="time-badge rounded-lg p-3 border border-{{ $warna['bg'] }}-200 bg-{{ $warna['bg'] }}-50">
                                    <p class="text-xs font-semibold text-{{ $warna['text'] }}-800">
                                        {{ $pengaturan->waktu_100_start_formatted }}-{{ $pengaturan->waktu_100_end_formatted }}: 
                                        <span class="text-green-600 font-bold">Nilai {{ $pengaturan->nilai_100 }}</span>
                                    </p>
                                </div>
                                <div class="time-badge rounded-lg p-3 border border-{{ $warna['bg'] }}-200 bg-{{ $warna['bg'] }}-50">
                                    <p class="text-xs font-semibold text-{{ $warna['text'] }}-800">
                                        {{ $pengaturan->waktu_70_start_formatted }}-{{ $pengaturan->waktu_70_end_formatted }}: 
                                        <span class="text-yellow-600 font-bold">Nilai {{ $pengaturan->nilai_70 }}</span>
                                    </p>
                                </div>
                                <div class="time-badge rounded-lg p-3 border border-{{ $warna['bg'] }}-200 bg-{{ $warna['bg'] }}-50">
                                    <p class="text-xs font-semibold text-{{ $warna['text'] }}-800">
                                        Lainnya: 
                                        <span class="text-red-600 font-bold">Nilai {{ $pengaturan->nilai_terlambat }}</span>
                                    </p>
                                </div>
                            @else
                                <!-- Default waktu jika pengaturan tidak tersedia -->
                                @if($currentType == 'sarapan')
                                    <div class="time-badge rounded-lg p-3 border border-blue-200 bg-blue-50">
                                        <p class="text-xs font-semibold text-blue-800">06:00-06:30: <span class="text-green-600 font-bold">Nilai 100</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-blue-200 bg-blue-50">
                                        <p class="text-xs font-semibold text-blue-800">06:31-07:00: <span class="text-yellow-600 font-bold">Nilai 70</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-blue-200 bg-blue-50">
                                        <p class="text-xs font-semibold text-blue-800">Lainnya: <span class="text-red-600 font-bold">Nilai 50</span></p>
                                    </div>
                                @elseif($currentType == 'makan_siang')
                                    <div class="time-badge rounded-lg p-3 border border-green-200 bg-green-50">
                                        <p class="text-xs font-semibold text-green-800">12:00-12:30: <span class="text-green-600 font-bold">Nilai 100</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-green-200 bg-green-50">
                                        <p class="text-xs font-semibold text-green-800">12:31-13:00: <span class="text-yellow-600 font-bold">Nilai 70</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-green-200 bg-green-50">
                                        <p class="text-xs font-semibold text-green-800">Lainnya: <span class="text-red-600 font-bold">Nilai 50</span></p>
                                    </div>
                                @else
                                    <div class="time-badge rounded-lg p-3 border border-indigo-200 bg-indigo-50">
                                        <p class="text-xs font-semibold text-indigo-800">15:00-15:30: <span class="text-green-600 font-bold">Nilai 100</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-indigo-200 bg-indigo-50">
                                        <p class="text-xs font-semibold text-indigo-800">15:31-16:00: <span class="text-yellow-600 font-bold">Nilai 70</span></p>
                                    </div>
                                    <div class="time-badge rounded-lg p-3 border border-indigo-200 bg-indigo-50">
                                        <p class="text-xs font-semibold text-indigo-800">Lainnya: <span class="text-red-600 font-bold">Nilai 50</span></p>
                                    </div>
                                @endif
                            @endif
                        </div>
                        
                        @error('waktu_makan')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Menu Makanan -->
                    <div class="slide-in" style="animation-delay: 0.25s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Menu Makanan
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <textarea name="menu_makanan" 
                                  rows="4" 
                                  placeholder="Contoh: Nasi putih, ayam goreng, sayur bayam, tempe goreng, buah pisang"
                                  class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-gray-900 resize-none"
                                  required>{{ old('menu_makanan', $makanSehat->menu_makanan) }}</textarea>
                        @error('menu_makanan')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Upload Foto -->
                    <div class="slide-in" style="animation-delay: 0.3s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Dokumentasi Foto
                            </span>
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   name="dokumentasi_foto" 
                                   id="foto-input"
                                   accept="image/*"
                                   class="hidden">
                            <label for="foto-input" 
                                   class="photo-preview-zone cursor-pointer block w-full rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 hover:border-blue-400 transition-all duration-300">
                                <div id="preview-container" class="h-64 flex items-center justify-center">
                                    @if($makanSehat->dokumentasi_foto)
                                        <img src="{{ $makanSehat->foto_url }}" alt="Preview" class="w-full h-full object-cover">
                                    @else
                                        <div class="text-center">
                                            <svg class="upload-icon w-16 h-16 mx-auto text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <p class="text-white font-bold text-lg mb-2">Klik untuk upload foto</p>
                                            <p class="text-white text-opacity-80 text-sm">Format: JPEG, PNG, JPG, GIF - Maks: 2MB</p>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @if($makanSehat->dokumentasi_foto)
                            <div class="mt-3 flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <input type="checkbox" name="hapus_foto" id="hapus_foto" value="1" class="rounded text-yellow-600 focus:ring-yellow-500">
                                <label for="hapus_foto" class="text-sm text-yellow-700 font-medium">Hapus foto saat ini</label>
                            </div>
                        @endif
                        @error('dokumentasi_foto')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="slide-in" style="animation-delay: 0.35s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Keterangan Tambahan
                            </span>
                        </label>
                        <textarea name="keterangan" 
                                  rows="3" 
                                  placeholder="Tambahkan catatan atau keterangan lainnya (opsional)"
                                  class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-300 text-gray-900 resize-none"
                                  >{{ old('keterangan', $makanSehat->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 slide-in" style="animation-delay: 0.4s;">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white font-bold px-8 py-4 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-300 transform hover:scale-105 transition-all duration-300 shadow-lg group">
                            <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Perbarui Data
                        </button>
                        <a href="{{ route('makan-sehat.show', $makanSehat->id) }}" 
                           class="inline-flex items-center justify-center bg-gray-100 text-gray-700 font-bold px-8 py-4 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 group">
                            <svg class="w-6 h-6 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview foto
        const fotoInput = document.getElementById('foto-input');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const previewZone = document.querySelector('.photo-preview-zone');
                const previewContainer = document.getElementById('preview-container');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewZone.classList.add('has-image');
                        previewContainer.innerHTML = `
                            <div class="relative w-full h-full">
                                <img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                    <p class="text-white font-bold opacity-0 hover:opacity-100 transition-opacity">Klik untuk ganti foto</p>
                                </div>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Format waktu input
        const waktuInput = document.getElementById('waktu_makan_input');
        
        if (waktuInput) {
            // Format nilai awal jika perlu
            const initialValue = waktuInput.value;
            if (initialValue && initialValue.length > 5) {
                // Jika format panjang, ambil hanya jam dan menit
                const timeParts = initialValue.split(':');
                if (timeParts.length >= 2) {
                    waktuInput.value = timeParts[0] + ':' + timeParts[1];
                }
            }

            // Validasi format saat submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const waktuValue = waktuInput.value;
                // Validasi format HH:MM
                if (!/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/.test(waktuValue)) {
                    e.preventDefault();
                    alert('Format waktu tidak valid. Gunakan format HH:MM (contoh: 06:30, 12:45)');
                    waktuInput.focus();
                }
            });
        }

        // Update info waktu berdasarkan jenis makanan yang dipilih
        const jenisMakananSelect = document.querySelector('select[name="jenis_makanan"]');
        if (jenisMakananSelect) {
            jenisMakananSelect.addEventListener('change', function() {
                // Reload halaman untuk update info waktu
                window.location.href = '{{ route("makan-sehat.edit", $makanSehat->id) }}?jenis=' + this.value;
            });
        }

        // Animasi slide-in
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.slide-in');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease-out';
                    el.style.opacity = '1';
                }, index * 100);
            });
        });
    </script>
</body>
</html>
@endsection