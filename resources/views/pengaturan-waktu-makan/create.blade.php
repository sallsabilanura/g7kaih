@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pengaturan Waktu Makan - 7 Kebiasaan Anak Indonesia Hebat</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Debug Alert -->
        @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat {{ count($errors) }} error:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                            Tambah Pengaturan Waktu Makan
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Buat pengaturan baru untuk waktu dan nilai makan
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Pengaturan Waktu Makan</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan-waktu-makan.store') }}" method="POST" class="p-8" id="pengaturanForm">
                @csrf
                
                <div class="space-y-8">
                    <!-- Jenis Makanan -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Makanan *
                        </label>
                        <select 
                            name="jenis_makanan" 
                            required
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                        >
                            <option value="">Pilih Jenis Makanan</option>
                            <option value="sarapan" {{ old('jenis_makanan') == 'sarapan' ? 'selected' : '' }}>Sarapan</option>
                            <option value="makan_siang" {{ old('jenis_makanan') == 'makan_siang' ? 'selected' : '' }}>Makan Siang</option>
                            <option value="makan_malam" {{ old('jenis_makanan') == 'makan_malam' ? 'selected' : '' }}>Makan Malam</option>
                        </select>
                        @error('jenis_makanan')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori 1: Tepat Waktu -->
                    <div class="p-6 rounded-xl category-green fade-in" style="animation-delay: 0.3s;">
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
                                    value="{{ old('waktu_100_start', '06:00') }}"
                                >
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
                                    value="{{ old('waktu_100_end', '07:00') }}"
                                >
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
                                    value="{{ old('nilai_100', 100) }}"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Kategori 2: Sedikit Terlambat -->
                    <div class="p-6 rounded-xl category-yellow fade-in" style="animation-delay: 0.4s;">
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
                                    value="{{ old('waktu_70_start', '07:00') }}"
                                >
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
                                    value="{{ old('waktu_70_end', '08:00') }}"
                                >
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
                                    value="{{ old('nilai_70', 70) }}"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Kategori 3: Terlambat -->
                    <div class="p-6 rounded-xl category-red fade-in" style="animation-delay: 0.5s;">
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

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nilai Terlambat *
                            </label>
                            <input 
                                type="number" 
                                name="nilai_terlambat" 
                                required 
                                min="0"
                                max="100"
                                class="w-full md:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                value="{{ old('nilai_terlambat', 50) }}"
                            >
                            <p class="text-xs text-gray-500 mt-2">Nilai untuk siswa yang makan di luar rentang waktu</p>
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="fade-in" style="animation-delay: 0.6s;">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                            >
                            <label class="ml-3 text-sm font-medium text-gray-700">
                                Aktifkan pengaturan ini
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.7s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Simpan Pengaturan
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
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.8s;">
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
                    <h3 class="font-semibold text-gray-900 mb-3">Panduan Pengisian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Pastikan waktu selesai lebih besar dari waktu mulai</p>
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
                            <p class="text-sm text-gray-600">Hanya satu pengaturan per jenis makanan</p>
                        </div>
                    </div>
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

            // Validasi waktu
            const form = document.getElementById('pengaturanForm');
            const timeInputs = document.querySelectorAll('.time-input');
            
            form.addEventListener('submit', function(e) {
                const waktu100Start = document.querySelector('input[name="waktu_100_start"]').value;
                const waktu100End = document.querySelector('input[name="waktu_100_end"]').value;
                const waktu70Start = document.querySelector('input[name="waktu_70_start"]').value;
                const waktu70End = document.querySelector('input[name="waktu_70_end"]').value;
                
                let isValid = true;
                let errorMessage = '';
                
                if (waktu100Start && waktu100End && waktu100Start >= waktu100End) {
                    isValid = false;
                    errorMessage = '❌ Waktu selesai Kategori 1 harus setelah waktu mulai\n';
                }
                
                if (waktu70Start && waktu100End && waktu70Start < waktu100End) {
                    isValid = false;
                    errorMessage += '⚠️ Waktu mulai Kategori 2 harus setelah atau sama dengan waktu selesai Kategori 1\n';
                }
                
                if (waktu70Start && waktu70End && waktu70Start >= waktu70End) {
                    isValid = false;
                    errorMessage += '❌ Waktu selesai Kategori 2 harus setelah waktu mulai\n';
                }
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Harap perbaiki pengaturan waktu:\n\n' + errorMessage);
                    return false;
                }
            });

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
        });
    </script>
</body>
</html>
@endsection