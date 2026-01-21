@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Introduction</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <!-- Header Card -->
            <div class="bg-gradient-primary px-6 py-4 border-b border-primary-200">
                <h2 class="text-2xl font-bold text-white">Edit Data Introduction</h2>
                <p class="text-primary-100 mt-1">Perbarui informasi pengenalan diri siswa</p>
            </div>
            
            <!-- Form Content -->
            <div class="p-6">
                <form action="{{ route('introductions.update', $introduction->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <!-- Field Siswa (Read Only) -->
                            <div>
                                <label for="siswa_nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                                <div class="relative">
                                    <input type="text" 
                                           class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                           id="siswa_nama" 
                                           value="{{ $introduction->siswa->nama_lengkap ?? 'N/A' }}" 
                                           readonly>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Data siswa tidak dapat diubah</p>
                            </div>
                            
                            <!-- Hobi -->
                            <div>
                                <label for="hobi" class="block text-sm font-medium text-gray-700 mb-1">Hobi (pisahkan dengan koma)</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('hobi') border-secondary-500 @enderror" 
                                       id="hobi" 
                                       name="hobi" 
                                       value="{{ old('hobi', $introduction->hobi) }}" 
                                       placeholder="Membaca, Berenang, Musik" 
                                       required>
                                @error('hobi')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Cita-cita -->
                            <div>
                                <label for="cita_cita" class="block text-sm font-medium text-gray-700 mb-1">Cita-cita</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('cita_cita') border-secondary-500 @enderror" 
                                       id="cita_cita" 
                                       name="cita_cita" 
                                       value="{{ old('cita_cita', $introduction->cita_cita) }}" 
                                       required>
                                @error('cita_cita')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Olahraga Kesukaan -->
                            <div>
                                <label for="olahraga_kesukaan" class="block text-sm font-medium text-gray-700 mb-1">Olahraga Kesukaan</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('olahraga_kesukaan') border-secondary-500 @enderror" 
                                       id="olahraga_kesukaan" 
                                       name="olahraga_kesukaan" 
                                       value="{{ old('olahraga_kesukaan', $introduction->olahraga_kesukaan) }}" 
                                       required>
                                @error('olahraga_kesukaan')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <!-- Makanan Kesukaan -->
                            <div>
                                <label for="makanan_kesukaan" class="block text-sm font-medium text-gray-700 mb-1">Makanan Kesukaan</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('makanan_kesukaan') border-secondary-500 @enderror" 
                                       id="makanan_kesukaan" 
                                       name="makanan_kesukaan" 
                                       value="{{ old('makanan_kesukaan', $introduction->makanan_kesukaan) }}" 
                                       required>
                                @error('makanan_kesukaan')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Buah Kesukaan -->
                            <div>
                                <label for="buah_kesukaan" class="block text-sm font-medium text-gray-700 mb-1">Buah Kesukaan</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('buah_kesukaan') border-secondary-500 @enderror" 
                                       id="buah_kesukaan" 
                                       name="buah_kesukaan" 
                                       value="{{ old('buah_kesukaan', $introduction->buah_kesukaan) }}" 
                                       required>
                                @error('buah_kesukaan')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Pelajaran Kesukaan -->
                            <div>
                                <label for="pelajaran_kesukaan" class="block text-sm font-medium text-gray-700 mb-1">Pelajaran Kesukaan</label>
                                <input type="text" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('pelajaran_kesukaan') border-secondary-500 @enderror" 
                                       id="pelajaran_kesukaan" 
                                       name="pelajaran_kesukaan" 
                                       value="{{ old('pelajaran_kesukaan', $introduction->pelajaran_kesukaan) }}" 
                                       required>
                                @error('pelajaran_kesukaan')
                                    <p class="mt-1 text-sm text-secondary-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Warna Kesukaan - 3 Pilihan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warna Kesukaan (Pilih 3 warna)</label>
                                
                                <!-- Warna 1 -->
                                <div class="mb-3">
                                    <label class="block text-xs text-gray-500 mb-1">Warna Favorit 1</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" 
                                               class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500 @error('warna_kesukaan.0') border-secondary-500 @enderror" 
                                               name="warna_kesukaan[]" 
                                               value="{{ old('warna_kesukaan.0', $introduction->warna_kesukaan[0] ?? '#3490dc') }}" 
                                               required>
                                        <span class="text-xs text-gray-500">Pilih warna pertama</span>
                                    </div>
                                    @error('warna_kesukaan.0')
                                        <p class="mt-1 text-xs text-secondary-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Warna 2 -->
                                <div class="mb-3">
                                    <label class="block text-xs text-gray-500 mb-1">Warna Favorit 2</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" 
                                               class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500 @error('warna_kesukaan.1') border-secondary-500 @enderror" 
                                               name="warna_kesukaan[]" 
                                               value="{{ old('warna_kesukaan.1', $introduction->warna_kesukaan[1] ?? '#e3342f') }}" 
                                               required>
                                        <span class="text-xs text-gray-500">Pilih warna kedua</span>
                                    </div>
                                    @error('warna_kesukaan.1')
                                        <p class="mt-1 text-xs text-secondary-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Warna 3 -->
                                <div class="mb-3">
                                    <label class="block text-xs text-gray-500 mb-1">Warna Favorit 3</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" 
                                               class="w-12 h-12 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500 @error('warna_kesukaan.2') border-secondary-500 @enderror" 
                                               name="warna_kesukaan[]" 
                                               value="{{ old('warna_kesukaan.2', $introduction->warna_kesukaan[2] ?? '#38c172') }}" 
                                               required>
                                        <span class="text-xs text-gray-500">Pilih warna ketiga</span>
                                    </div>
                                    @error('warna_kesukaan.2')
                                        <p class="mt-1 text-xs text-secondary-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <p class="text-xs text-gray-500 mt-2">Klik setiap kotak warna untuk memilih 3 warna favorit</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Data
                        </button>
                        <a href="{{ route('introductions.index') }}" 
                           class="px-5 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
@endsection