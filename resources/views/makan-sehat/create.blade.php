@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Makan Sehat</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        
        .meal-type-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .meal-type-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .meal-type-card:hover::before {
            left: 100%;
        }
        
        .meal-type-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .meal-type-card.selected {
            border-width: 3px;
            transform: translateY(-4px) scale(1.05);
        }

        .time-badge {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }

        .floating-label {
            transition: all 0.3s ease;
        }

        input:focus ~ .floating-label,
        textarea:focus ~ .floating-label,
        input:not(:placeholder-shown) ~ .floating-label,
        textarea:not(:placeholder-shown) ~ .floating-label {
            transform: translateY(-1.5rem) scale(0.85);
            color: #0033A0;
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

        .pengaturan-info-card {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
        }

        @media (max-width: 768px) {
            .meal-type-card {
                min-height: auto;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-gray-50 to-green-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 sm:py-8 max-w-6xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-8 border border-gray-100 fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="bg-gradient-to-br from-blue-600 to-green-600 rounded-2xl p-3 shadow-lg pulse-animation">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            @if(isset($selectedType))
                                Tambah Data {{ $jenisMakanan[$selectedType] }}
                            @else
                                Tambah Data Makan Sehat
                            @endif
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            @if(session('siswa_nama'))
                                <span class="font-semibold text-blue-600">{{ session('siswa_nama') }}</span>
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

           
        </div>

    

        @if(isset($selectedType))
        <!-- Info Pengaturan Waktu untuk Jenis yang Dipilih -->
        @if($pengaturan)
        <div class="pengaturan-info-card rounded-2xl p-6 mb-6 slide-in" style="animation-delay: 0.2s;">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-2">Pengaturan Waktu {{ $jenisMakanan[$selectedType] }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 backdrop-blur-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-bold text-white">Tepat Waktu</span>
                            </div>
                            <p class="text-white font-semibold">{{ $pengaturan->waktu_100_start_formatted }} - {{ $pengaturan->waktu_100_end_formatted }}</p>
                           
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 backdrop-blur-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-bold text-white">Sedikit Telat</span>
                            </div>
                            <p class="text-white font-semibold">{{ $pengaturan->waktu_70_start_formatted }} - {{ $pengaturan->waktu_70_end_formatted }}</p>
                       
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 backdrop-blur-sm">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-bold text-white">Terlambat</span>
                            </div>
                            <p class="text-white font-semibold">Di luar waktu yang ditentukan</p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Input -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 fade-in" style="animation-delay: 0.3s;">
         
            <!-- Form Body -->
            <form action="{{ route('makan-sehat.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8">
                @csrf
                
                <input type="hidden" name="jenis_makanan" value="{{ $selectedType }}">
                <input type="hidden" name="tanggal" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                <input type="hidden" name="waktu_makan" id="waktu_makan_input" value="">
                
                @if($selectedSiswa)
                    <input type="hidden" name="siswa_id" value="{{ $selectedSiswa->id }}">
                @endif

               

                    <!-- Menu Makanan -->
                    <div class="slide-in" style="animation-delay: 0.2s;">
                        <label class="block text-sm font-bold text-gray-700 mb-3">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                Menu {{ $jenisMakanan[$selectedType] }}
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                        <textarea name="menu_makanan" 
                                  rows="4" 
                                  placeholder="Contoh: Nasi putih, ayam goreng, sayur bayam, tempe goreng, buah pisang"
                                  class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-300 text-gray-900 resize-none"
                                  >{{ old('menu_makanan') }}</textarea>
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
                                Dokumentasi Foto {{ $jenisMakanan[$selectedType] }}
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
                                    <div class="text-center">
                                        <svg class="upload-icon w-16 h-16 mx-auto text-white mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-white font-bold text-lg mb-2">Klik untuk upload foto</p>
                                        <p class="text-white text-opacity-80 text-sm">Format: JPEG, PNG, JPG, GIF - Maks: 2MB</p>
                                    </div>
                                </div>
                            </label>
                        </div>
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
                    <div class="slide-in" style="animation-delay: 0.4s;">
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
                                  >{{ old('keterangan') }}</textarea>
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
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 slide-in" style="animation-delay: 0.5s;">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center bg-gradient-to-r {{ 
                                    $selectedType == 'sarapan' ? 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-blue-300' : 
                                    ($selectedType == 'makan_siang' ? 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:ring-green-300' : 
                                    'from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-blue-300') 
                                }} text-white font-bold px-8 py-4 rounded-xl focus:outline-none focus:ring-4 transform hover:scale-105 transition-all duration-300 shadow-lg group">
                            <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data {{ $jenisMakanan[$selectedType] }}
                        </button>
                        <a href="{{ route('makan-sehat.index') }}" 
                           class="inline-flex items-center justify-center bg-gray-100 text-gray-700 font-bold px-8 py-4 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 group">
                            <svg class="w-6 h-6 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Batalkan
                        </a>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>

    <script>
        // Set timezone Indonesia
        const TIMEZONE_OFFSET = 7; // WIB (UTC+7)

        // Update waktu real-time dengan timezone Indonesia
        function updateTime() {
            const now = new Date();
            
            // Get waktu lokal browser (otomatis sesuai timezone perangkat)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}`;
            
            // Update tampilan waktu
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = currentTime;
            }
            
            // Update input hidden waktu_makan
            const waktuMakanInput = document.getElementById('waktu_makan_input');
            if (waktuMakanInput) {
                waktuMakanInput.value = currentTime;
            }
            
            // Update info waktu di header form (jika ada)
            const headerTimeElements = document.querySelectorAll('[data-time-display]');
            headerTimeElements.forEach(el => {
                el.textContent = currentTime;
            });

            // Update preview nilai
            updatePreviewNilai(currentTime);

            // Log untuk debugging
            console.log('Waktu sekarang:', currentTime);
        }

        // Update preview nilai berdasarkan waktu
        function updatePreviewNilai(waktu) {
            const previewTime = document.getElementById('preview-time');
            const previewCategory = document.getElementById('preview-category');
            const previewScore = document.getElementById('preview-score');
            
            if (previewTime && previewCategory && previewScore) {
                previewTime.textContent = waktu;
                
                // Simulasi perhitungan nilai
                const [hours, minutes] = waktu.split(':').map(Number);
                const totalMinutes = hours * 60 + minutes;
                
                // Contoh logika perhitungan
                let category = '-';
                let score = '-';
                
                @if(isset($pengaturan))
                    const pengaturan = @json($pengaturan);
                    const start100 = timeToMinutes(pengaturan.waktu_100_start_formatted);
                    const end100 = timeToMinutes(pengaturan.waktu_100_end_formatted);
                    const start70 = timeToMinutes(pengaturan.waktu_70_start_formatted);
                    const end70 = timeToMinutes(pengaturan.waktu_70_end_formatted);
                    
                    if (totalMinutes >= start100 && totalMinutes <= end100) {
                        category = 'Tepat Waktu';
                        score = pengaturan.nilai_100;
                    } else if (totalMinutes >= start70 && totalMinutes <= end70) {
                        category = 'Sedikit Telat';
                        score = pengaturan.nilai_70;
                    } else {
                        category = 'Terlambat';
                        score = pengaturan.nilai_terlambat;
                    }
                @endif
                
                previewCategory.textContent = category;
                previewScore.textContent = score;
                
                // Update warna score
                if (score >= 90) {
                    previewScore.className = 'text-2xl font-bold text-green-600';
                } else if (score >= 70) {
                    previewScore.className = 'text-2xl font-bold text-yellow-600';
                } else {
                    previewScore.className = 'text-2xl font-bold text-red-600';
                }
            }
        }

        // Helper function untuk convert time ke minutes
        function timeToMinutes(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }

        // Update waktu setiap detik
        setInterval(updateTime, 1000);
        
        // Set waktu saat halaman dimuat
        updateTime();

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

        // Tambahkan indikator timezone
        document.addEventListener('DOMContentLoaded', function() {
            const timezoneInfo = Intl.DateTimeFormat().resolvedOptions().timeZone;
            console.log('Timezone Browser:', timezoneInfo);
        });
    </script>
</body>
</html>
@endsection