@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kegiatan Bermasyarakat - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .upload-area {
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: #0033A0;
            background: rgba(0, 51, 160, 0.02);
        }
        
        .upload-area.dragover {
            border-color: #00A86B;
            background: rgba(0, 168, 107, 0.05);
            transform: scale(1.02);
        }

        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('bermasyarakat.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Tambah Kegiatan Bermasyarakat
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Form tambah kegiatan sosial siswa
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
                        <h2 class="text-lg font-semibold text-gray-900">Form Data Kegiatan</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('bermasyarakat.store') }}" method="POST" enctype="multipart/form-data" id="bermasyarakatForm">
                @csrf
                
                <div class="p-6 md:p-8 space-y-6">
                    
                    <!-- Tanggal -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Tanggal Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" 
                                   class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                   id="tanggal" 
                                   name="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('tanggal')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="fade-in" style="animation-delay: 0.25s;">
                        <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                   id="nama_kegiatan" 
                                   name="nama_kegiatan" 
                                   value="{{ old('nama_kegiatan') }}"
                                   placeholder="Contoh: Kerja Bakti Lingkungan, Bakti Sosial, dll"
                                   required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('nama_kegiatan')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Pesan & Kesan -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <label for="pesan_kesan" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Pesan & Kesan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300"
                                      id="pesan_kesan" 
                                      name="pesan_kesan" 
                                      rows="6"
                                      placeholder="Tuliskan pesan dan kesan selama mengikuti kegiatan ini..."
                                      required>{{ old('pesan_kesan') }}</textarea>
                        </div>
                        @error('pesan_kesan')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Upload Gambar Kegiatan (HANYA 1 GAMBAR) -->
                    <div class="fade-in" style="animation-delay: 0.35s;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Upload Gambar Kegiatan <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(Hanya 1 gambar, maksimal 2MB)</span>
                        </label>
                        
                        <div class="mb-4" id="uploadArea">
                            <div class="upload-area border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-primary-500 transition-colors duration-300 bg-gray-50">
                                <input type="file" name="gambar_kegiatan" class="hidden" id="fileInput" accept="image/*" required>
                                <div id="uploadContent">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg text-gray-600 mb-2">Klik atau drag untuk upload gambar</p>
                                    <p class="text-sm text-gray-500">Format: JPG, PNG, JPEG (Maksimal 2MB)</p>
                                </div>
                                <div id="previewContainer" class="hidden mt-4">
                                    <div class="relative max-w-md mx-auto">
                                        <img id="imagePreview" class="w-full h-48 object-cover rounded-lg shadow-md">
                                        <button type="button" id="removeImage" 
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg shadow-lg hover:bg-red-600 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                    <p class="text-sm text-accent-green mt-2 font-medium">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Gambar siap diupload
                                    </p>
                                </div>
                            </div>
                        </div>

                        @error('gambar_kegiatan')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                 
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 px-6 md:px-8 pb-6 fade-in" style="animation-delay: 0.45s;">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Kegiatan
                        </span>
                    </button>
                    <a href="{{ route('bermasyarakat.index') }}" 
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

            // Upload image preview untuk SATU GAMBAR
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const uploadContent = document.getElementById('uploadContent');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeImageBtn = document.getElementById('removeImage');
            const form = document.getElementById('bermasyarakatForm');

            // Click area to trigger file input
            uploadArea.addEventListener('click', function(e) {
                if (e.target !== removeImageBtn) {
                    fileInput.click();
                }
            });

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                handleFileUpload(file);
            });

            // Remove image
            removeImageBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                resetUpload();
            });

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    handleFileUpload(file);
                    fileInput.files = files;
                }
            });

            // Function to handle file upload
            function handleFileUpload(file) {
                if (file) {
                    // Validate file type
                    if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                        alert('Hanya file JPG, JPEG, dan PNG yang diizinkan!');
                        return;
                    }
                    
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB!');
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        uploadContent.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        uploadArea.classList.add('border-green-400', 'bg-green-50/30');
                    };
                    reader.readAsDataURL(file);
                }
            }

            // Function to reset upload
            function resetUpload() {
                fileInput.value = '';
                uploadContent.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                uploadArea.classList.remove('border-green-400', 'bg-green-50/30', 'dragover');
            }

            // Form validation
            form.addEventListener('submit', function(e) {
                if (!fileInput.files.length) {
                    e.preventDefault();
                    alert('Harus upload 1 gambar kegiatan!');
                    uploadArea.classList.add('border-red-500');
                    setTimeout(() => {
                        uploadArea.classList.remove('border-red-500');
                    }, 2000);
                }
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
        });
    </script>
</body>
</html>
@endsection