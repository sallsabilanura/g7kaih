@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengaturan Waktu Makan - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .select-arrow {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230033A0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }

        .category-green {
            border-left: 4px solid #10B981;
            background: linear-gradient(to right, #ECFDF5, #D1FAE5);
        }

        .category-yellow {
            border-left: 4px solid #F59E0B;
            background: linear-gradient(to right, #FFFBEB, #FEF3C7);
        }

        .category-red {
            border-left: 4px solid #EF4444;
            background: linear-gradient(to right, #FEF2F2, #FEE2E2);
        }

        .input-valid {
            border-color: #10B981 !important;
            background-color: #ECFDF5 !important;
        }

        .input-invalid {
            border-color: #EF4444 !important;
            background-color: #FEF2F2 !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pengaturan-waktu-makan.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Edit Pengaturan Waktu Makan
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Edit pengaturan untuk {{ $jenisLabel }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold 
                        {{ $pengaturanWaktu->is_active ? 'bg-green-100 text-accent-green' : 'bg-gray-100 text-gray-600' }}">
                        {{ $pengaturanWaktu->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-primary-600">
                        {{ $jenisLabel }}
                    </span>
                </div>
            </div>

            @if(session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-accent-green mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Edit Pengaturan</h2>
                        <p class="text-gray-500 text-sm">Perbarui waktu dan nilai untuk {{ strtolower($jenisLabel) }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan-waktu-makan.update', $jenis) }}" method="POST" class="p-8" id="pengaturanForm">
                @csrf
                @method('PUT')
                
                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Perbaiki {{ count($errors) }} error berikut:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-8">
                    <!-- Kategori 1: Tepat Waktu -->
                    <div class="p-6 rounded-xl category-green fade-in" style="animation-delay: 0.2s;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Kategori 1: Tepat Waktu</h3>
                                <p class="text-sm text-gray-500">Rentang waktu untuk nilai maksimal</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai *
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_100_start" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm time-input"
                                    value="{{ old('waktu_100_start', $pengaturanWaktu->waktu_100_start_formatted) }}"
                                    id="waktu_100_start"
                                >
                                @error('waktu_100_start')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Selesai *
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_100_end" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm time-input"
                                    value="{{ old('waktu_100_end', $pengaturanWaktu->waktu_100_end_formatted) }}"
                                    id="waktu_100_end"
                                >
                                @error('waktu_100_end')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai *
                                </label>
                                <input 
                                    type="number" 
                                    name="nilai_100" 
                                    required 
                                    min="0"
                                    max="100"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('nilai_100', $pengaturanWaktu->nilai_100) }}"
                                >
                                @error('nilai_100')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex items-start gap-2 text-sm text-green-600">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Siswa yang makan dalam rentang ini mendapat nilai tertinggi</p>
                        </div>
                    </div>

                    <!-- Kategori 2: Sedikit Terlambat -->
                    <div class="p-6 rounded-xl category-yellow fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Kategori 2: Sedikit Terlambat</h3>
                                <p class="text-sm text-gray-500">Rentang waktu untuk nilai sedang</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai *
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_70_start" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm time-input"
                                    value="{{ old('waktu_70_start', $pengaturanWaktu->waktu_70_start_formatted) }}"
                                    id="waktu_70_start"
                                >
                                @error('waktu_70_start')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Selesai *
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_70_end" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm time-input"
                                    value="{{ old('waktu_70_end', $pengaturanWaktu->waktu_70_end_formatted) }}"
                                    id="waktu_70_end"
                                >
                                @error('waktu_70_end')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai *
                                </label>
                                <input 
                                    type="number" 
                                    name="nilai_70" 
                                    required 
                                    min="0"
                                    max="100"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('nilai_70', $pengaturanWaktu->nilai_70) }}"
                                >
                                @error('nilai_70')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex items-start gap-2 text-sm text-yellow-600">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Waktu mulai harus setelah waktu selesai Kategori 1</p>
                        </div>
                    </div>

                    <!-- Kategori 3: Terlambat -->
                    <div class="p-6 rounded-xl category-red fade-in" style="animation-delay: 0.4s;">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Kategori 3: Terlambat</h3>
                                <p class="text-sm text-gray-500">Nilai untuk waktu di luar rentang</p>
                            </div>
                        </div>

                        <div class="max-w-xs">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Terlambat *
                            </label>
                            <input 
                                type="number" 
                                name="nilai_terlambat" 
                                required 
                                min="0"
                                max="100"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                value="{{ old('nilai_terlambat', $pengaturanWaktu->nilai_terlambat) }}"
                            >
                            @error('nilai_terlambat')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="mt-4 flex items-start gap-2 text-sm text-red-600">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Nilai ini diberikan untuk waktu makan di luar rentang kategori 1 dan 2</p>
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="fade-in" style="animation-delay: 0.5s;">
                        <div class="flex items-center p-4 border border-gray-200 rounded-xl">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', $pengaturanWaktu->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                                id="is_active"
                            >
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700 flex-1">
                                <div class="font-semibold">Aktifkan pengaturan ini</div>
                                <p class="text-gray-500 text-sm mt-1">Nonaktifkan jika pengaturan ini tidak digunakan sementara</p>
                            </label>
                            <div class="w-10 h-6 rounded-full bg-gray-200 relative" id="toggleSwitch">
                                <div class="w-4 h-4 bg-white rounded-full absolute top-1 left-1 transition-transform duration-300"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.6s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                        id="submitBtn"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </span>
                    </button>
                    <a href="{{ route('pengaturan-waktu-makan.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batalkan
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                        </span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.7s;">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-3">Informasi Edit Pengaturan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Pastikan waktu selesai > waktu mulai</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Kategori 2 harus setelah Kategori 1</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Nilai dapat diatur antara 0-100</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Perubahan disimpan secara permanen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-6 fade-in" style="animation-delay: 0.8s;">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Preview Pengaturan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-xl border border-green-200">
                    <div class="text-sm font-semibold text-green-800 mb-1">Tepat Waktu</div>
                    <div class="text-2xl font-bold text-green-600" id="preview_nilai_100">{{ $pengaturanWaktu->nilai_100 }}</div>
                    <div class="text-sm text-green-700 font-mono" id="preview_waktu_100">
                        {{ $pengaturanWaktu->waktu_100_start_formatted }} - {{ $pengaturanWaktu->waktu_100_end_formatted }}
                    </div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                    <div class="text-sm font-semibold text-yellow-800 mb-1">Sedikit Terlambat</div>
                    <div class="text-2xl font-bold text-yellow-600" id="preview_nilai_70">{{ $pengaturanWaktu->nilai_70 }}</div>
                    <div class="text-sm text-yellow-700 font-mono" id="preview_waktu_70">
                        {{ $pengaturanWaktu->waktu_70_start_formatted }} - {{ $pengaturanWaktu->waktu_70_end_formatted }}
                    </div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-xl border border-red-200">
                    <div class="text-sm font-semibold text-red-800 mb-1">Terlambat</div>
                    <div class="text-2xl font-bold text-red-600" id="preview_nilai_terlambat">{{ $pengaturanWaktu->nilai_terlambat }}</div>
                    <div class="text-sm text-red-700">Di luar rentang waktu</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animasi untuk form elements dengan delay bertahap
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.fade-in');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Toggle switch styling
            const toggleCheckbox = document.getElementById('is_active');
            const toggleSwitch = document.getElementById('toggleSwitch');
            
            function updateToggleSwitch() {
                if (toggleCheckbox.checked) {
                    toggleSwitch.classList.remove('bg-gray-200');
                    toggleSwitch.classList.add('bg-green-500');
                    toggleSwitch.querySelector('div').style.transform = 'translateX(20px)';
                } else {
                    toggleSwitch.classList.remove('bg-green-500');
                    toggleSwitch.classList.add('bg-gray-200');
                    toggleSwitch.querySelector('div').style.transform = 'translateX(0)';
                }
            }
            
            toggleCheckbox.addEventListener('change', updateToggleSwitch);
            updateToggleSwitch();

            // Efek hover untuk input
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-1', 'ring-primary-200');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-1', 'ring-primary-200');
                });
            });

            // Validasi waktu real-time
            const waktu100Start = document.getElementById('waktu_100_start');
            const waktu100End = document.getElementById('waktu_100_end');
            const waktu70Start = document.getElementById('waktu_70_start');
            const waktu70End = document.getElementById('waktu_70_end');
            
            const nilai100Input = document.querySelector('input[name="nilai_100"]');
            const nilai70Input = document.querySelector('input[name="nilai_70"]');
            const nilaiTerlambatInput = document.querySelector('input[name="nilai_terlambat"]');

            function updatePreview() {
                // Update preview nilai
                document.getElementById('preview_nilai_100').textContent = nilai100Input.value;
                document.getElementById('preview_nilai_70').textContent = nilai70Input.value;
                document.getElementById('preview_nilai_terlambat').textContent = nilaiTerlambatInput.value;
                
                // Update preview waktu
                if (waktu100Start.value && waktu100End.value) {
                    document.getElementById('preview_waktu_100').textContent = 
                        `${waktu100Start.value} - ${waktu100End.value}`;
                }
                
                if (waktu70Start.value && waktu70End.value) {
                    document.getElementById('preview_waktu_70').textContent = 
                        `${waktu70Start.value} - ${waktu70End.value}`;
                }
            }

            function validateTimes() {
                let isValid = true;
                
                // Reset styles
                [waktu100Start, waktu100End, waktu70Start, waktu70End].forEach(input => {
                    if (input) {
                        input.classList.remove('input-valid', 'input-invalid');
                    }
                });
                
                // Validate Category 1
                if (waktu100Start.value && waktu100End.value) {
                    if (waktu100Start.value >= waktu100End.value) {
                        waktu100Start.classList.add('input-invalid');
                        waktu100End.classList.add('input-invalid');
                        isValid = false;
                    } else {
                        waktu100Start.classList.add('input-valid');
                        waktu100End.classList.add('input-valid');
                    }
                }
                
                // Validate Category 2
                if (waktu70Start.value && waktu70End.value) {
                    if (waktu70Start.value >= waktu70End.value) {
                        waktu70Start.classList.add('input-invalid');
                        waktu70End.classList.add('input-invalid');
                        isValid = false;
                    } else {
                        waktu70Start.classList.add('input-valid');
                        waktu70End.classList.add('input-valid');
                    }
                }
                
                // Validate between categories
                if (waktu100End.value && waktu70Start.value && waktu70Start.value < waktu100End.value) {
                    waktu70Start.classList.add('input-invalid');
                    isValid = false;
                    
                    // Suggest correction
                    const suggestion = addOneMinute(waktu100End.value);
                    console.log('Suggestion: Set Kategori 2 start to ' + suggestion);
                }
                
                return isValid;
            }
            
            function addOneMinute(timeString) {
                const [hours, minutes] = timeString.split(':').map(Number);
                let newHours = hours;
                let newMinutes = minutes + 1;
                
                if (newMinutes >= 60) {
                    newMinutes = 0;
                    newHours += 1;
                }
                
                if (newHours >= 24) {
                    newHours = 0;
                }
                
                return `${String(newHours).padStart(2, '0')}:${String(newMinutes).padStart(2, '0')}`;
            }
            
            // Event listeners for real-time validation and preview
            const timeInputs = [waktu100Start, waktu100End, waktu70Start, waktu70End];
            timeInputs.forEach(input => {
                if (input) {
                    input.addEventListener('input', function() {
                        validateTimes();
                        updatePreview();
                    });
                }
            });
            
            // Event listeners for nilai inputs
            [nilai100Input, nilai70Input, nilaiTerlambatInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', updatePreview);
                }
            });
            
            // Form submission validation
            const form = document.getElementById('pengaturanForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!validateTimes()) {
                        e.preventDefault();
                        alert('Harap perbaiki pengaturan waktu sebelum menyimpan:\n\n' +
                              '1. Waktu selesai harus setelah waktu mulai\n' +
                              '2. Kategori 2 harus setelah Kategori 1\n\n' +
                              'Periksa kembali input waktu Anda.');
                        return false;
                    }
                    
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<svg class="w-5 h-5 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                    }
                });
            }
            
            // Initialize preview
            updatePreview();
            
            // Auto-suggest correction for Category 2 start time
            if (waktu100End) {
                waktu100End.addEventListener('change', function() {
                    if (waktu70Start && waktu70Start.value && waktu70Start.value < this.value) {
                        const suggestedTime = addOneMinute(this.value);
                        if (confirm('Waktu mulai Kategori 2 lebih awal dari waktu selesai Kategori 1.\n\n' +
                                   'Ingin mengatur otomatis menjadi ' + suggestedTime + '?')) {
                            waktu70Start.value = suggestedTime;
                            validateTimes();
                            updatePreview();
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
@endsection