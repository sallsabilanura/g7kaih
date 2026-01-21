@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Input Data Olahraga - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.98);
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
            cursor: pointer;
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

        .duration-info {
            font-size: 0.875rem;
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 4px;
        }
        
        .duration-info.valid {
            background: rgba(0, 168, 107, 0.1);
            color: #00A86B;
            border: 1px solid rgba(0, 168, 107, 0.3);
        }
        
        .duration-info.invalid {
            background: rgba(220, 20, 60, 0.1);
            color: #DC143C;
            border: 1px solid rgba(220, 20, 60, 0.3);
        }
        
        .hidden-file-input {
            opacity: 0;
            position: absolute;
            z-index: -1;
            width: 0.1px;
            height: 0.1px;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('berolahraga.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Input Data Olahraga
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Upload bukti video olahraga harian
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Error Messages -->
        @if(session('error'))
        <div class="glass-card rounded-2xl p-6 mb-8 fade-in" style="animation-delay: 0.15s;">
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="glass-card rounded-2xl p-6 mb-8 fade-in" style="animation-delay: 0.2s;">
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-red-800 font-medium mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.25s;">
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Upload Olahraga</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('berolahraga.store') }}" method="POST" enctype="multipart/form-data" id="formOlahraga">
                @csrf
                
                <div class="p-6 md:p-8 space-y-6">
                    <!-- Grid untuk tanggal dan waktu -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Olahraga <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ old('tanggal', date('Y-m-d')) }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
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

                        <!-- Waktu Mulai -->
                        <div>
                            <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="time" 
                                       id="waktu_mulai" 
                                       name="waktu_mulai" 
                                       value="{{ old('waktu_mulai') }}"
                                       class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                       required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-primary-600 font-medium">Waktu mulai menentukan nilai</p>
                            @error('waktu_mulai')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu Selesai -->
                        <div>
                            <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Waktu Selesai <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="time" 
                                       id="waktu_selesai" 
                                       name="waktu_selesai" 
                                       value="{{ old('waktu_selesai') }}"
                                       class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                       required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('waktu_selesai')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preview Durasi -->
                    <div id="previewBox" class="hidden glass-card rounded-2xl p-6 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-2">⏱️ Durasi Olahraga</p>
                                <p class="text-2xl font-bold text-gray-900" id="previewDurasi">-</p>
                                <div id="durasiInfoContainer" class="mt-2">
                                    <!-- Info durasi akan ditampilkan di sini -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Video - PERBAIKAN BESAR -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Video Bukti Olahraga <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(Maksimal 5MB)</span>
                        </label>
                        
                        <!-- Input file yang tersembunyi -->
                        <input type="file" 
                               id="video" 
                               name="video" 
                               class="hidden-file-input" 
                               accept="video/mp4,video/mov,video/avi,video/wmv" 
                               required>
                        
                        <!-- Area upload yang bisa diklik -->
                        <div class="mt-2">
                            <div class="upload-area border-2 border-dashed border-gray-300 rounded-2xl p-8 text-center transition-colors duration-300 bg-gray-50" 
                                 id="dropZone"
                                 onclick="triggerFileInput()">
                                <div id="uploadContent">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-600 mb-2">
                                        <span class="text-primary-600 hover:text-primary-700 cursor-pointer">Klik di sini</span> atau drag untuk upload video
                                    </p>
                                    <p class="text-sm text-gray-500">Format: MP4, MOV, AVI, WMV (Maksimal 5MB)</p>
                                </div>
                                <div id="videoPreview" class="hidden mt-4">
                                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-accent-green/10 rounded-xl border border-green-200">
                                        <svg class="w-8 h-8 text-accent-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                        <div class="flex-1 text-left">
                                            <p class="font-medium text-gray-900" id="videoName">-</p>
                                            <p class="text-sm text-gray-500" id="videoSize">-</p>
                                            <p class="text-xs text-green-600 mt-1" id="videoStatus">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Video siap diupload
                                            </p>
                                        </div>
                                        <button type="button" 
                                                id="removeVideo" 
                                                class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                                onclick="removeVideoFile()">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('video')
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
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 px-6 md:px-8 pb-6">
                    <button type="submit" 
                            id="btnSubmit"
                            class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data Olahraga
                        </span>
                    </button>
                    <a href="{{ route('berolahraga.index') }}" 
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
    // Data pengaturan dari server
    const pengaturan = {
        sangat_baik: {
            start: '{{ $pengaturan->waktu_sangat_baik_start ?? "05:00" }}',
            end: '{{ $pengaturan->waktu_sangat_baik_end ?? "06:00" }}',
            nilai: {{ $pengaturan->nilai_sangat_baik ?? 100 }}
        },
        baik: {
            start: '{{ $pengaturan->waktu_baik_start ?? "06:01" }}',
            end: '{{ $pengaturan->waktu_baik_end ?? "07:00" }}',
            nilai: {{ $pengaturan->nilai_baik ?? 85 }}
        },
        cukup: {
            start: '{{ $pengaturan->waktu_cukup_start ?? "07:01" }}',
            end: '{{ $pengaturan->waktu_cukup_end ?? "08:00" }}',
            nilai: {{ $pengaturan->nilai_cukup ?? 70 }}
        },
        kurang: {
            nilai: {{ $pengaturan->nilai_kurang ?? 50 }}
        },
        durasi_minimal: {{ $pengaturan->durasi_minimal ?? 30 }}
    };

    // Batas ukuran video (5MB dalam bytes)
    const MAX_VIDEO_SIZE = 5 * 1024 * 1024; // 5MB

    // Fungsi untuk trigger input file
    function triggerFileInput() {
        document.getElementById('video').click();
    }

    // Fungsi untuk menghapus video
    function removeVideoFile() {
        const videoInput = document.getElementById('video');
        videoInput.value = '';
        
        const videoPreview = document.getElementById('videoPreview');
        videoPreview.classList.add('hidden');
        
        const uploadContent = document.getElementById('uploadContent');
        uploadContent.classList.remove('hidden');
        
        const dropZone = document.getElementById('dropZone');
        dropZone.classList.remove('border-green-400', 'bg-green-50/30');
    }

    // Update preview saat waktu berubah
    function updatePreview() {
        const waktuMulai = document.getElementById('waktu_mulai').value;
        const waktuSelesai = document.getElementById('waktu_selesai').value;
        const previewBox = document.getElementById('previewBox');
        const durasiInfoContainer = document.getElementById('durasiInfoContainer');

        if (waktuMulai && waktuSelesai) {
            // Hitung durasi
            const mulai = new Date(`2000-01-01 ${waktuMulai}`);
            const selesai = new Date(`2000-01-01 ${waktuSelesai}`);
            const durasiMenit = Math.round((selesai - mulai) / 60000);

            if (durasiMenit > 0) {
                previewBox.classList.remove('hidden');
                
                // Tampilkan durasi
                const jam = Math.floor(durasiMenit / 60);
                const menit = durasiMenit % 60;
                let durasiText = '';
                
                if (jam > 0 && menit > 0) {
                    durasiText = `${jam} jam ${menit} menit`;
                } else if (jam > 0) {
                    durasiText = `${jam} jam`;
                } else {
                    durasiText = `${menit} menit`;
                }
                
                const previewDurasi = document.getElementById('previewDurasi');
                
                // Validasi durasi MINIMAL SAJA
                if (durasiMenit < pengaturan.durasi_minimal) {
                    previewDurasi.textContent = `${durasiText} ⚠️`;
                    previewDurasi.className = 'text-2xl font-bold text-red-600';
                    
                    // Tampilkan info durasi
                    durasiInfoContainer.innerHTML = `
                        <div class="duration-info invalid">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Durasi belum memenuhi minimal ${pengaturan.durasi_minimal} menit
                        </div>
                    `;
                } else {
                    previewDurasi.textContent = durasiText;
                    previewDurasi.className = 'text-2xl font-bold text-gray-900';
                    
                    // Tampilkan info durasi yang valid
                    durasiInfoContainer.innerHTML = `
                        <div class="duration-info valid">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Durasi memenuhi syarat (≥ ${pengaturan.durasi_minimal} menit)
                        </div>
                    `;
                }
            } else {
                previewBox.classList.add('hidden');
                durasiInfoContainer.innerHTML = '';
            }
        } else {
            previewBox.classList.add('hidden');
            durasiInfoContainer.innerHTML = '';
        }
    }

    // Event listeners untuk waktu
    document.getElementById('waktu_mulai').addEventListener('change', updatePreview);
    document.getElementById('waktu_selesai').addEventListener('change', updatePreview);

    // Handle video upload
    const videoInput = document.getElementById('video');

    videoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            validateAndPreviewVideo(file);
        }
    });

    function validateAndPreviewVideo(file) {
        // Validasi ukuran file (5MB maksimal)
        if (file.size > MAX_VIDEO_SIZE) {
            alert('Ukuran video maksimal 5MB!');
            videoInput.value = '';
            return;
        }

        // Validasi tipe file
        const validTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/wmv'];
        if (!validTypes.includes(file.type)) {
            alert('Format video harus MP4, MOV, AVI, atau WMV!');
            videoInput.value = '';
            return;
        }

        showVideoPreview(file);
    }

    function showVideoPreview(file) {
        const videoPreview = document.getElementById('videoPreview');
        const uploadContent = document.getElementById('uploadContent');
        const dropZone = document.getElementById('dropZone');
        
        document.getElementById('videoName').textContent = file.name;
        document.getElementById('videoSize').textContent = formatFileSize(file.size);
        
        uploadContent.classList.add('hidden');
        videoPreview.classList.remove('hidden');
        dropZone.classList.add('border-green-400', 'bg-green-50/30');
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    // Drag and drop
    const dropZone = document.getElementById('dropZone');

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('video/')) {
            validateAndPreviewVideo(file);
            
            // Set file ke input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            videoInput.files = dataTransfer.files;
        } else {
            alert('Silakan upload file video!');
        }
    });

    // Form submit loading dan validasi
    document.getElementById('formOlahraga').addEventListener('submit', function(e) {
        // Validasi video
        if (!videoInput.files.length) {
            e.preventDefault();
            alert('Silakan upload video olahraga!');
            return;
        }

        const videoFile = videoInput.files[0];
        if (videoFile.size > MAX_VIDEO_SIZE) {
            e.preventDefault();
            alert('Ukuran video maksimal 5MB!');
            return;
        }

        // Validasi durasi MINIMAL SAJA
        const waktuMulai = document.getElementById('waktu_mulai').value;
        const waktuSelesai = document.getElementById('waktu_selesai').value;
        if (waktuMulai && waktuSelesai) {
            const mulai = new Date(`2000-01-01 ${waktuMulai}`);
            const selesai = new Date(`2000-01-01 ${waktuSelesai}`);
            const durasiMenit = Math.round((selesai - mulai) / 60000);
            
            if (durasiMenit < pengaturan.durasi_minimal) {
                e.preventDefault();
                alert(`Durasi olahraga minimal ${pengaturan.durasi_minimal} menit!`);
                return;
            }
        }

        // Tampilkan loading
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Menyimpan...</span>
        `;
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

    // Animasi untuk elements
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${0.1 * index}s`;
        });

        // Init preview jika ada old value
        updatePreview();
    });
    </script>
</body>
</html>
@endsection