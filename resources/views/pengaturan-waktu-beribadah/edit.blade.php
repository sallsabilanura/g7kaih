@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengaturan Waktu Beribadah - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        input:focus, select:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Debug Alert -->
        @if ($errors->any())
        <div class="mb-8 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
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

        @if (session('success'))
        <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-accent-green" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-8 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pengaturan-waktu-beribadah.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Edit Pengaturan {{ $sholatLabels[$pengaturan->jenis_sholat] }}
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Edit data pengaturan waktu beribadah
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $pengaturan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $pengaturan->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <span class="text-xs text-gray-500">ID: {{ $pengaturan->id }}</span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Edit Data Pengaturan</h2>
                        <p class="text-gray-500 text-sm">Perbarui informasi pengaturan waktu {{ $sholatLabels[$pengaturan->jenis_sholat] }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan-waktu-beribadah.update', $pengaturan->jenis_sholat) }}" method="POST" class="p-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <!-- Jenis Sholat (Readonly) -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Sholat
                        </label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl text-gray-900 text-sm border border-gray-200">
                            {{ $sholatLabels[$pengaturan->jenis_sholat] }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Jenis sholat tidak dapat diubah setelah dibuat</p>
                    </div>

                    <!-- Status Aktif -->
                    <div class="fade-in" style="animation-delay: 0.25s;">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="is_active" value="1" 
                                   class="w-4 h-4 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   {{ old('is_active', $pengaturan->is_active) ? 'checked' : '' }}>
                            <label class="ml-2 text-sm font-medium text-gray-700">
                                Aktifkan pengaturan ini
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">Jika dinonaktifkan, sistem tidak akan menggunakan pengaturan ini untuk perhitungan nilai</p>
                    </div>

                    <!-- Waktu Tepat -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">Waktu Tepat</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="waktu_tepat_start" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_tepat_start" 
                                    id="waktu_tepat_start" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('waktu_tepat_start', $pengaturan->waktu_tepat_start_form) }}"
                                >
                                @error('waktu_tepat_start')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="waktu_tepat_end" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_tepat_end" 
                                    id="waktu_tepat_end" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('waktu_tepat_end', $pengaturan->waktu_tepat_end_form) }}"
                                >
                                @error('waktu_tepat_end')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="nilai_tepat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="nilai_tepat" 
                                    id="nilai_tepat" 
                                    min="0" 
                                    max="100" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('nilai_tepat', $pengaturan->nilai_tepat) }}"
                                >
                                @error('nilai_tepat')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Waktu Terlambat -->
                    <div class="fade-in" style="animation-delay: 0.35s;">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">Waktu Terlambat</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="waktu_terlambat_start" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_terlambat_start" 
                                    id="waktu_terlambat_start" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('waktu_terlambat_start', $pengaturan->waktu_terlambat_start_form) }}"
                                >
                                @error('waktu_terlambat_start')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="waktu_terlambat_end" class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="time" 
                                    name="waktu_terlambat_end" 
                                    id="waktu_terlambat_end" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('waktu_terlambat_end', $pengaturan->waktu_terlambat_end_form) }}"
                                >
                                @error('waktu_terlambat_end')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="nilai_terlambat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="nilai_terlambat" 
                                    id="nilai_terlambat" 
                                    min="0" 
                                    max="100" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('nilai_terlambat', $pengaturan->nilai_terlambat) }}"
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
                        </div>
                    </div>

                    <!-- Tidak Sholat -->
                    <div class="fade-in" style="animation-delay: 0.4s;">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <h3 class="text-lg font-bold text-gray-900">Tidak Sholat</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rentang Waktu
                                </label>
                                <div class="px-4 py-3 bg-gray-50 rounded-xl text-gray-600 text-sm border border-gray-200">
                                    Diluar rentang waktu sholat
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Nilai ketika tidak melaksanakan sholat dalam rentang waktu yang ditentukan</p>
                            </div>
                            
                            <div>
                                <label for="nilai_tidak_sholat" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="nilai_tidak_sholat" 
                                    id="nilai_tidak_sholat" 
                                    min="0" 
                                    max="100" 
                                    required 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    value="{{ old('nilai_tidak_sholat', $pengaturan->nilai_tidak_sholat) }}"
                                >
                                @error('nilai_tidak_sholat')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.45s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
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
                    <a href="{{ route('pengaturan-waktu-beribadah.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batal
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

            // Validasi client-side untuk waktu
            const waktuTepatStart = document.getElementById('waktu_tepat_start');
            const waktuTepatEnd = document.getElementById('waktu_tepat_end');
            const waktuTerlambatStart = document.getElementById('waktu_terlambat_start');
            const waktuTerlambatEnd = document.getElementById('waktu_terlambat_end');

            function validateTimes() {
                // Validasi Waktu Tepat
                if (waktuTepatStart.value && waktuTepatEnd.value && waktuTepatStart.value >= waktuTepatEnd.value) {
                    alert('Waktu selesai "Tepat Waktu" harus setelah waktu mulai');
                    return false;
                }

                // Validasi Waktu Terlambat
                if (waktuTerlambatStart.value && waktuTerlambatEnd.value && waktuTerlambatStart.value >= waktuTerlambatEnd.value) {
                    alert('Waktu selesai "Terlambat" harus setelah waktu mulai');
                    return false;
                }

                // Validasi gap antara kategori
                if (waktuTepatEnd.value && waktuTerlambatStart.value && waktuTerlambatStart.value < waktuTepatEnd.value) {
                    alert('Waktu mulai "Terlambat" harus setelah atau sama dengan waktu selesai "Tepat Waktu"');
                    return false;
                }

                return true;
            }

            // Tambahkan event listener untuk form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!validateTimes()) {
                    e.preventDefault();
                }
            });

            // Efek hover untuk input
            const inputs = document.querySelectorAll('input');
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