@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Waktu Bangun Pagi - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(0, 51, 160, 0.02) 0%, rgba(0, 168, 107, 0.02) 100%);
        }

        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #0033A0 0%, #00A86B 100%) border-box;
            border: 1.5px solid transparent;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent mb-2">
                        Pengaturan Waktu Bangun Pagi
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola pengaturan waktu dan nilai untuk sistem bangun pagi
                    </p>
                </div>
                @if(!$sudahAda)
                <a href="{{ route('pengaturan-waktu-bangun-pagi.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pengaturan
                </a>
                @endif
            </div>

            <!-- Alert Success -->
            @if (session('success'))
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
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-primary-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('info') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Tampilan jika belum ada pengaturan -->
        @if(!$sudahAda)
        <div class="glass-card rounded-2xl shadow-lg p-8 fade-in">
            <div class="text-center py-8">
                <div class="max-w-md mx-auto">
                    <!-- Icon -->
                    <div class="bg-gradient-to-br from-primary-100 to-accent-green/20 rounded-full p-8 w-32 h-32 mx-auto mb-6 flex items-center justify-center">
                        <svg class="w-16 h-16 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    
                    <!-- Teks -->
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        Belum Ada Pengaturan Waktu
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Mulai dengan membuat pengaturan waktu bangun pagi untuk mengatur sistem penilaian.
                    </p>
                    
                    <!-- Tombol Buat Pengaturan -->
                    <a href="{{ route('pengaturan-waktu-bangun-pagi.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 inline-flex items-center gap-3 text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Pengaturan Pertama
                    </a>
                    
                    
                </div>
            </div>
        </div>
        @else
        <!-- Tampilan jika sudah ada pengaturan -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in">
            <!-- Header Card -->
            <div class="px-8 py-8 bg-gradient-to-r from-primary-500 to-accent-green">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-4">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">Waktu Bangun Pagi</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengaturan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pengaturan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @if($pengaturan->updated_at)
                                    <span class="text-white text-opacity-90 text-sm">
                                        Terakhir diperbarui: {{ $pengaturan->updated_at->format('d/m/Y H:i') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-white text-sm font-medium">ID: {{ $pengaturan->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Kategori 1: Tepat Waktu -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <h3 class="font-bold text-green-900 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Tepat Waktu
                            </h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-green-700 mb-1">Rentang Waktu</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-mono font-bold text-green-900">{{ $pengaturan->waktu_100_start_formatted }}</span>
                                    <span class="text-gray-400">→</span>
                                    <span class="text-xl font-mono font-bold text-green-900">{{ $pengaturan->waktu_100_end_formatted }}</span>
                                </div>
                                <p class="text-xs text-green-600 mt-1">{{ $pengaturan->waktu_100_display12h }}</p>
                            </div>
                            <div class="pt-4 border-t border-green-200">
                                <p class="text-sm text-green-700 mb-1">Nilai</p>
                                <p class="text-3xl font-bold text-green-900">{{ $pengaturan->nilai_100 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori 2: Sedikit Terlambat -->
                    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 border-2 border-yellow-200 rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                            <h3 class="font-bold text-yellow-900 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Sedikit Terlambat
                            </h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-yellow-700 mb-1">Rentang Waktu</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-mono font-bold text-yellow-900">{{ $pengaturan->waktu_70_start_formatted }}</span>
                                    <span class="text-gray-400">→</span>
                                    <span class="text-xl font-mono font-bold text-yellow-900">{{ $pengaturan->waktu_70_end_formatted }}</span>
                                </div>
                                <p class="text-xs text-yellow-600 mt-1">{{ $pengaturan->waktu_70_display12h }}</p>
                            </div>
                            <div class="pt-4 border-t border-yellow-200">
                                <p class="text-sm text-yellow-700 mb-1">Nilai</p>
                                <p class="text-3xl font-bold text-yellow-900">{{ $pengaturan->nilai_70 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Kategori 3: Terlambat -->
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 border-2 border-red-200 rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <h3 class="font-bold text-red-900 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Terlambat
                            </h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-red-700 mb-1">Rentang Waktu</p>
                                <p class="text-lg font-semibold text-red-900">Di luar rentang waktu</p>
                                <p class="text-xs text-red-600 mt-1">
                                    Sebelum {{ $pengaturan->waktu_100_start_formatted }} atau setelah {{ $pengaturan->waktu_70_end_formatted }}
                                </p>
                            </div>
                            <div class="pt-4 border-t border-red-200">
                                <p class="text-sm text-red-700 mb-1">Nilai</p>
                                <p class="text-3xl font-bold text-red-900">{{ $pengaturan->nilai_terlambat }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-100">
                    <a href="{{ route('pengaturan-waktu-bangun-pagi.edit', $pengaturan->id) }}" 
                       class="flex-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center font-semibold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Pengaturan
                    </a>
                    <form action="{{ route('pengaturan-waktu-bangun-pagi.reset', $pengaturan->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2"
                                onclick="return confirm('Reset pengaturan ke default?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset ke Default
                        </button>
                    </form>
                </div>

                <!-- Delete Button -->
                <div class="mt-4">
                    <form action="{{ route('pengaturan-waktu-bangun-pagi.destroy', $pengaturan->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengaturan waktu bangun pagi? Tindakan ini tidak dapat dibatalkan.')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>

      
        @endif
    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });
        });
    </script>
</body>
</html>
@endsection