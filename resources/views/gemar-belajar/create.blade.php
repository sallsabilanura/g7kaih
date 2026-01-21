@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Gemar Belajar - 7 Kebiasaan Anak Indonesia Hebat</title>
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
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 1rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        
        .readonly-field {
            background-color: #f9fafb;
            cursor: not-allowed;
            color: #6b7280;
        }
        
        .date-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 0.75rem;
            color: #0369a1;
            font-weight: 500;
        }
        
        /* Preview Image Styles */
        .image-preview-container {
            position: relative;
            margin-top: 0.5rem;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            background-color: #f9fafb;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .upload-success {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        
        .preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-preview-container:hover .preview-overlay {
            opacity: 1;
        }
        
        .checkmark {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.3s ease-out;
        }
        
        .checkmark svg {
            width: 30px;
            height: 30px;
            color: white;
        }
        
        @keyframes scaleIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .file-name {
            display: block;
            margin-top: 0.5rem;
            padding: 0.5rem;
            background-color: #f3f4f6;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #374151;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .upload-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            padding: 0.5rem 0.75rem;
            background-color: #ecfdf5;
            border: 1px solid #d1fae5;
            border-radius: 0.5rem;
            color: #065f46;
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Debug Alert -->
        @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
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

        @if (session('error'))
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
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
                    <a href="{{ route('gemar-belajar.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Tambah Gemar Belajar Baru
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Tambah catatan membaca siswa
                        </p>
                    </div>
                </div>
            </div>

            <!-- Info Siswa Login -->
            @if($currentSiswa)
            <div class="bg-gradient-to-r from-primary-50 to-accent-green/10 p-4 rounded-xl border border-primary-100">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-500 to-accent-green flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                            {{ strtoupper(substr($currentSiswa->nama_lengkap, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $currentSiswa->nama_lengkap }}</h4>
                        <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-3 text-sm text-gray-600">
                            <span>NIS: {{ $currentSiswa->nis }}</span>
                            @if($currentSiswa->kelas)
                                <span class="hidden md:inline text-gray-300">•</span>
                                <span>Kelas: {{ $currentSiswa->kelas->nama_kelas ?? '-' }}</span>
                            @endif
                        </div>
                    </div>
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
                        <h2 class="text-lg font-semibold text-gray-900">Form Data Gemar Belajar</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('gemar-belajar.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                <!-- Hidden input untuk siswa_id -->
                @if($currentSiswa)
                    <input type="hidden" name="siswa_id" value="{{ $currentSiswa->id }}">
                @endif
                
                <div class="space-y-6">
                    <!-- Tanggal (Tidak bisa diubah) -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal *
                        </label>
                        <div class="date-display">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span id="current-date">{{ date('d F Y') }}</span>
                        </div>
                        <input 
                            type="hidden" 
                            name="tanggal" 
                            value="{{ date('Y-m-d') }}"
                        >
                        @error('tanggal')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Judul Buku -->
                    <div class="fade-in" style="animation-delay: 0.25s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Buku *
                        </label>
                        <input 
                            type="text" 
                            name="judul_buku" 
                            required 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                            placeholder="Masukkan judul buku yang dibaca"
                            value="{{ old('judul_buku') }}"
                        >
                        @error('judul_buku')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Informasi Didapat -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Informasi yang Didapat *
                        </label>
                        <textarea 
                            name="informasi_didapat" 
                            required 
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                            placeholder="Tuliskan informasi atau ilmu yang didapat dari buku ini"
                        >{{ old('informasi_didapat') }}</textarea>
                        @error('informasi_didapat')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Minimal 10 karakter</p>
                    </div>

                    <!-- Grid untuk file upload -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.35s;">
                        <!-- Gambar Buku -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Buku (Cover)
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    name="gambar_buku"
                                    id="gambar_buku"
                                    accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                    onchange="previewImage(this, 'previewBuku')"
                                >
                                <div class="mt-2 hidden" id="fileInfoBuku">
                                    <span class="upload-indicator">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        File berhasil diunggah
                                    </span>
                                </div>
                            </div>
                            <!-- Preview untuk Gambar Buku -->
                            <div class="image-preview-container mt-3" id="previewContainerBuku" style="display: none;">
                                <div class="image-preview" id="previewBuku">
                                    <!-- Preview akan ditampilkan di sini -->
                                </div>
                                <div class="preview-overlay">
                                    <div class="checkmark">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @error('gambar_buku')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                        </div>

                        <!-- Gambar Baca -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Saat Membaca
                            </label>
                            <div class="relative">
                                <input 
                                    type="file" 
                                    name="gambar_baca"
                                    id="gambar_baca"
                                    accept="image/*"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100"
                                    onchange="previewImage(this, 'previewBaca')"
                                >
                                <div class="mt-2 hidden" id="fileInfoBaca">
                                    <span class="upload-indicator">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        File berhasil diunggah
                                    </span>
                                </div>
                            </div>
                            <!-- Preview untuk Gambar Baca -->
                            <div class="image-preview-container mt-3" id="previewContainerBaca" style="display: none;">
                                <div class="image-preview" id="previewBaca">
                                    <!-- Preview akan ditampilkan di sini -->
                                </div>
                                <div class="preview-overlay">
                                    <div class="checkmark">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            @error('gambar_baca')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.4s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                            </svg>
                            Simpan Catatan
                        </span>
                    </button>
                    <a href="{{ route('gemar-belajar.index') }}" 
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
        // Fungsi untuk preview gambar
        function previewImage(input, previewId) {
            const previewContainerId = previewId === 'previewBuku' ? 'previewContainerBuku' : 'previewContainerBaca';
            const fileInfoId = previewId === 'previewBuku' ? 'fileInfoBuku' : 'fileInfoBaca';
            
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById(previewContainerId);
            const fileInfo = document.getElementById(fileInfoId);
            const fileInput = input;
            
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Tampilkan preview
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewContainer.style.display = 'block';
                    previewContainer.classList.add('upload-success');
                    
                    // Tampilkan info file
                    fileInfo.classList.remove('hidden');
                    fileInfo.classList.add('flex');
                    
                    // Tambah animasi
                    previewContainer.style.animation = 'scaleIn 0.3s ease-out';
                    
                    // Update nama file
                    const fileName = fileInput.files[0].name;
                    const nextElement = fileInput.nextElementSibling;
                    if (nextElement) {
                        nextElement.innerHTML = `<span class="file-name">${fileName}</span>`;
                    }
                }
                
                reader.readAsDataURL(fileInput.files[0]);
                
                // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
                const fileSize = fileInput.files[0].size;
                const maxSize = 2 * 1024 * 1024;
                
                if (fileSize > maxSize) {
                    alert('Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.');
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    fileInfo.classList.add('hidden');
                    return;
                }
                
                // Validasi tipe file
                const fileType = fileInput.files[0].type;
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                
                if (!validTypes.includes(fileType)) {
                    alert('Format file tidak didukung. Harap unggah file gambar (JPG, PNG, GIF).');
                    fileInput.value = '';
                    previewContainer.style.display = 'none';
                    fileInfo.classList.add('hidden');
                    return;
                }
            }
        }

        // Format tanggal Indonesia
        function formatIndonesianDate(date) {
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            const day = date.getDate();
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            
            return `${day} ${month} ${year}`;
        }

        // Set tanggal saat ini
        document.addEventListener('DOMContentLoaded', function() {
            const currentDateElement = document.getElementById('current-date');
            if (currentDateElement) {
                currentDateElement.textContent = formatIndonesianDate(new Date());
            }

            // Animasi untuk form elements
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

            // Validasi form submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const fileInputs = [
                        document.getElementById('gambar_buku'),
                        document.getElementById('gambar_baca')
                    ];
                    
                    let isValid = true;
                    
                    fileInputs.forEach(input => {
                        if (input && input.files.length > 0) {
                            const fileSize = input.files[0].size;
                            const maxSize = 2 * 1024 * 1024;
                            
                            if (fileSize > maxSize) {
                                alert(`File ${input.name} melebihi ukuran 2MB.`);
                                isValid = false;
                            }
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            }

            // Cek jika ada file yang sudah dipilih (misal jika ada error validation)
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                if (input.value) {
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            });
        });
    </script>
</body>
</html>
@endsection