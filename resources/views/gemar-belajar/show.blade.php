@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Gemar Belajar - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .book-icon {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
        }

        .image-container img {
            transition: transform 0.3s ease;
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
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
                            Detail Gemar Belajar
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Informasi lengkap catatan membaca
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <!-- Card Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-primary-500/5 to-accent-green/5">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Catatan Membaca</h2>
                        <p class="text-gray-500 text-sm">Data lengkap gemar belajar</p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-8">
                <div class="space-y-8">
                    <!-- Siswa Info -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Informasi Siswa</h3>
                        <div class="bg-gradient-to-r from-primary-50 to-accent-green/10 p-6 rounded-xl border border-primary-100">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-full book-icon flex items-center justify-center text-white font-bold text-2xl">
                                    {{ strtoupper(substr($gemarBelajar->siswa->nama_lengkap ?? $gemarBelajar->siswa->nama ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-lg">{{ $gemarBelajar->siswa->nama_lengkap ?? $gemarBelajar->siswa->nama ?? 'Siswa tidak ditemukan' }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">NIS:</span>
                                            <span class="font-medium text-gray-900">{{ $gemarBelajar->siswa->nis ?? '-' }}</span>
                                        </div>
                                        @if($gemarBelajar->siswa->kelas)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Kelas:</span>
                                            <span class="font-medium text-gray-900">{{ $gemarBelajar->siswa->kelas->nama_kelas ?? '-' }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid untuk Tanggal dan Nilai -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.25s;">
                        <!-- Tanggal Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Informasi Waktu</h3>
                            <div class="space-y-3">
                                <div class="info-badge bg-blue-50 text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Tanggal: {{ $gemarBelajar->tanggal->format('d/m/Y') }}</span>
                                </div>
                                <div class="info-badge bg-purple-50 text-purple-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Periode: {{ $gemarBelajar->bulan }} {{ $gemarBelajar->tahun }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status dan Nilai -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Status & Nilai</h3>
                            <div class="space-y-3">
                                @if($gemarBelajar->nilai)
                                <div class="info-badge {{ $gemarBelajar->nilai >= 80 ? 'bg-emerald-50 text-emerald-700' : ($gemarBelajar->nilai >= 60 ? 'bg-yellow-50 text-yellow-700' : 'bg-red-50 text-red-700') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Nilai: {{ $gemarBelajar->nilai }}/100</span>
                                </div>
                                @else
                                <div class="info-badge bg-gray-50 text-gray-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Status: Belum Dinilai</span>
                                </div>
                                @endif
                                
                                <div class="info-badge {{ $gemarBelajar->status == 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-yellow-50 text-yellow-700' }}">
                                    @if($gemarBelajar->status == 'approved')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Status: Telah Disetujui</span>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Status: Menunggu Persetujuan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Judul Buku -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Judul Buku</h3>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <p class="text-lg font-medium text-gray-900">{{ $gemarBelajar->judul_buku }}</p>
                        </div>
                    </div>

                    <!-- Informasi Didapat -->
                    <div class="fade-in" style="animation-delay: 0.35s;">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-medium text-gray-700">Informasi yang Didapat</h3>
                            <span class="text-xs text-gray-500">{{ Str::length($gemarBelajar->informasi_didapat) }} karakter</span>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="prose prose-sm max-w-none text-gray-700">
                                {!! nl2br(e($gemarBelajar->informasi_didapat)) !!}
                            </div>
                        </div>
                    </div>

                    <!-- Grid untuk Gambar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in" style="animation-delay: 0.4s;">
                        <!-- Gambar Buku -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Gambar Buku</h3>
                            @if($gemarBelajar->gambar_buku)
                            <div class="image-container bg-gray-100 rounded-xl overflow-hidden">
                                <img src="{{ Storage::url($gemarBelajar->gambar_buku) }}" 
                                     alt="Cover Buku {{ $gemarBelajar->judul_buku }}" 
                                     class="w-full h-64 object-cover">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                    <p class="text-white text-sm font-medium">Cover Buku</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($gemarBelajar->gambar_buku) }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-2 mt-2 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Lihat Gambar Lengkap
                            </a>
                            @else
                            <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada gambar buku</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Gambar Baca -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Gambar Saat Membaca</h3>
                            @if($gemarBelajar->gambar_baca)
                            <div class="image-container bg-gray-100 rounded-xl overflow-hidden">
                                <img src="{{ Storage::url($gemarBelajar->gambar_baca) }}" 
                                     alt="Siswa Sedang Membaca" 
                                     class="w-full h-64 object-cover">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-4">
                                    <p class="text-white text-sm font-medium">Aktivitas Membaca</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($gemarBelajar->gambar_baca) }}" 
                               target="_blank" 
                               class="inline-flex items-center gap-2 mt-2 text-primary-600 hover:text-primary-700 text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Lihat Gambar Lengkap
                            </a>
                            @else
                            <div class="bg-gray-100 rounded-xl h-64 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada gambar membaca</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.5s;">
                    <a href="{{ route('gemar-belajar.edit', $gemarBelajar->id) }}" 
                       class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Data
                        </span>
                    </a>
                    <a href="{{ route('gemar-belajar.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Kembali ke Daftar
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                        </span>
                    </a>
                    <form action="{{ route('gemar-belajar.destroy', $gemarBelajar->id) }}" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menghapus data gemar belajar ini?')" 
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Data
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

      
    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Image preview enhancement
            const images = document.querySelectorAll('.image-container img');
            images.forEach(img => {
                img.addEventListener('click', function() {
                    const src = this.src;
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4';
                    modal.innerHTML = `
                        <div class="relative max-w-4xl max-h-full">
                            <img src="${src}" class="max-w-full max-h-[90vh] object-contain rounded-lg">
                            <button onclick="this.parentElement.parentElement.remove()" 
                                    class="absolute top-4 right-4 text-white bg-black/50 hover:bg-black/70 p-2 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    `;
                    document.body.appendChild(modal);
                });
            });
        });
    </script>
</body>
</html>
@endsection