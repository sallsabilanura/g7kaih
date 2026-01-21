@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Makan Sehat - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .info-card {
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 sm:py-8 max-w-6xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 mb-8 border border-gray-100 fade-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            Detail Makan Sehat
                        </h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            @if($makanSehat->siswa)
                                <span class="font-semibold text-blue-600">{{ $makanSehat->siswa->nama_lengkap }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">Siswa</span>
                            @else
                                <span class="font-semibold text-indigo-600">{{ Auth::user()->nama_lengkap }}</span>
                                <span class="text-gray-400">|</span>
                                <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-medium">{{ Auth::user()->peran }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('makan-sehat.edit', $makanSehat->id) }}" 
                       class="inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-semibold px-6 py-3 rounded-xl hover:from-blue-600 hover:to-indigo-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-300 group">
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('makan-sehat.index') }}" 
                       class="inline-flex items-center justify-center bg-gray-100 text-gray-700 font-semibold px-6 py-3 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-300 group">
                        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Foto Dokumentasi -->
            <div class="lg:col-span-1 fade-in">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Dokumentasi Foto
                    </h3>
                    @if($makanSehat->dokumentasi_foto)
                        <img src="{{ $makanSehat->foto_url }}" alt="Dokumentasi Makan Sehat" 
                             class="w-full h-64 object-cover rounded-xl shadow-md mb-4">
                        <p class="text-center text-sm text-gray-500">Foto dokumentasi {{ $makanSehat->jenis_makanan }}</p>
                    @else
                        <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex flex-col items-center justify-center border-2 border-dashed border-gray-300">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada foto</p>
                            <p class="text-gray-400 text-sm mt-1">Foto dokumentasi belum diupload</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Detail -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Utama -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 fade-in" style="animation-delay: 0.1s;">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Informasi Detail
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Data Siswa -->
                        @if($makanSehat->siswa)
                        <div class="info-card bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-blue-500 rounded-lg p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-blue-900">Data Siswa</h4>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">Nama Lengkap</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $makanSehat->siswa->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium">NIS</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $makanSehat->siswa->nis }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Jenis Makanan -->
                        <div class="info-card bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border border-green-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-green-500 rounded-lg p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-green-900">Jadwal Makan</h4>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-green-600 font-medium">Jenis</p>
                                    <p class="text-sm font-semibold text-gray-900 capitalize">{{ str_replace('_', ' ', $makanSehat->jenis_makanan) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-green-600 font-medium">Waktu</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $makanSehat->waktu_makan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div class="info-card bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-5 border border-purple-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-purple-500 rounded-lg p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-purple-900">Periode</h4>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">Tanggal</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($makanSehat->tanggal)->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-purple-600 font-medium">Bulan</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $makanSehat->bulan }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai -->
                        <div class="info-card bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-200">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="bg-amber-500 rounded-lg p-2">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-amber-900">Penilaian</h4>
                            </div>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-amber-600 font-medium">Status</p>
                                    <p class="text-sm font-semibold text-gray-900">
                                        @if($makanSehat->nilai == 100)
                                            <span class="text-green-600">Sangat Baik</span>
                                        @elseif($makanSehat->nilai == 70)
                                            <span class="text-yellow-600">Baik</span>
                                        @else
                                            <span class="text-red-600">Perlu Perbaikan</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-amber-600 font-medium">Nilai</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $makanSehat->nilai }}/100</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Makanan -->
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 fade-in" style="animation-delay: 0.2s;">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Menu Makanan Sehat
                    </h3>
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed text-sm sm:text-base">{{ $makanSehat->menu_makanan }}</p>
                    </div>
                </div>

                <!-- Keterangan -->
                @if($makanSehat->keterangan)
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 fade-in" style="animation-delay: 0.3s;">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        Keterangan Tambahan
                    </h3>
                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed text-sm sm:text-base">{{ $makanSehat->keterangan }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8 fade-in" style="animation-delay: 0.4s;">
            <a href="{{ route('makan-sehat.edit', $makanSehat->id) }}" 
               class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-bold px-8 py-4 rounded-xl hover:from-blue-600 hover:to-indigo-600 focus:outline-none focus:ring-4 focus:ring-blue-300 transform hover:scale-105 transition-all duration-300 shadow-lg group">
                <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Data
            </a>
            <form action="{{ route('makan-sehat.destroy', $makanSehat->id) }}" method="POST" class="flex-1" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="button" 
                        onclick="confirmDelete()"
                        class="w-full inline-flex items-center justify-center bg-gradient-to-r from-red-500 to-pink-500 text-white font-bold px-8 py-4 rounded-xl hover:from-red-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-red-300 transform hover:scale-105 transition-all duration-300 shadow-lg group">
                    <svg class="w-6 h-6 mr-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Data
                </button>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Animasi untuk elemen yang masuk
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.fade-in, .slide-in');
            animatedElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>
@endsection