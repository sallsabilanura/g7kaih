@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Waktu Beribadah - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .score-badge {
            min-width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            font-weight: 700;
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
                        Pengaturan Waktu Beribadah
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola pengaturan waktu dan nilai untuk sholat 5 waktu
                    </p>
                </div>
                
                <!-- Cek apakah semua sholat sudah ada pengaturan -->
                @php
                    $sholatTerdaftar = $pengaturanList->pluck('jenis_sholat')->toArray();
                    $sholatTersedia = array_diff(array_keys($sholatLabels), $sholatTerdaftar);
                    $semuaAda = empty($sholatTersedia);
                @endphp
                
                @if(!$semuaAda)
                <a href="{{ route('pengaturan-waktu-beribadah.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengaturan Sholat
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

        <!-- Tampilan jika belum ada pengaturan sama sekali -->
        @if($pengaturanList->isEmpty())
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
                        Belum Ada Pengaturan Waktu Beribadah
                    </h2>
                    <p class="text-gray-600 mb-8">
                        Mulai dengan membuat pengaturan waktu untuk sholat 5 waktu.
                    </p>
                    
                    <!-- Tombol Buat Pengaturan -->
                    <a href="{{ route('pengaturan-waktu-beribadah.create') }}" 
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 fade-in">
            @foreach($pengaturanList as $item)
                @php
                    $isActive = $item->is_active;
                    $color = $sholatColors[$item->jenis_sholat] ?? $sholatColors['subuh'];
                @endphp

                <div class="glass-card rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Header Card -->
                    <div class="px-5 py-4 bg-gradient-to-r {{ $color['bg'] }}">
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-bold text-white">{{ $sholatLabels[$item->jenis_sholat] }}</h2>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $isActive ? 'bg-white/20 text-white' : 'bg-white/30 text-white' }}">
                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <!-- Nilai Score Grid -->
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <!-- Tepat Waktu -->
                            <div class="text-center">
                                <div class="score-badge bg-gradient-to-br from-green-500 to-emerald-600 text-white rounded-xl shadow-md mb-2 mx-auto">
                                    {{ $item->nilai_tepat }}
                                </div>
                                <p class="text-xs font-semibold text-green-700 mb-1">Tepat</p>
                                <p class="text-xs text-gray-500 font-mono leading-tight">
                                    {{ \Carbon\Carbon::parse($item->waktu_tepat_start)->format('H:i') }}<br>
                                    {{ \Carbon\Carbon::parse($item->waktu_tepat_end)->format('H:i') }}
                                </p>
                            </div>

                            <!-- Terlambat -->
                            <div class="text-center">
                                <div class="score-badge bg-gradient-to-br from-yellow-500 to-amber-600 text-white rounded-xl shadow-md mb-2 mx-auto">
                                    {{ $item->nilai_terlambat }}
                                </div>
                                <p class="text-xs font-semibold text-yellow-700 mb-1">Terlambat</p>
                                <p class="text-xs text-gray-500 font-mono leading-tight">
                                    {{ \Carbon\Carbon::parse($item->waktu_terlambat_start)->format('H:i') }}<br>
                                    {{ \Carbon\Carbon::parse($item->waktu_terlambat_end)->format('H:i') }}
                                </p>
                            </div>

                            <!-- Tidak Sholat -->
                            <div class="text-center">
                                <div class="score-badge bg-gradient-to-br from-red-500 to-rose-600 text-white rounded-xl shadow-md mb-2 mx-auto">
                                    {{ $item->nilai_tidak_sholat }}
                                </div>
                                <p class="text-xs font-semibold text-red-700 mb-1">Tidak</p>
                                <p class="text-xs text-gray-500 leading-tight">
                                    Diluar<br>rentang
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 pt-4 border-t border-gray-100">
                            <!-- Toggle Status Button -->
                            <form action="{{ route('pengaturan-waktu-beribadah.toggle-status', $item->jenis_sholat) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="w-full {{ $isActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1.5 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($isActive)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        @endif
                                    </svg>
                                    {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('pengaturan-waktu-beribadah.edit', $item->jenis_sholat) }}" 
                                   class="bg-primary-500 hover:bg-primary-600 text-white text-center font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1.5 text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('pengaturan-waktu-beribadah.reset', $item->jenis_sholat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1.5 text-sm"
                                            onclick="return confirm('Reset pengaturan {{ $sholatLabels[$item->jenis_sholat] }} ke default?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Reset
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('pengaturan-waktu-beribadah.destroy', $item->jenis_sholat) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-1.5 text-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengaturan {{ $sholatLabels[$item->jenis_sholat] }}? Tindakan ini tidak dapat dibatalkan.')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reset All Button -->
        <div class="mt-8 flex justify-center fade-in">
            <form action="{{ route('pengaturan-waktu-beribadah.reset-all') }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2"
                        onclick="return confirm('Reset semua pengaturan waktu beribadah ke default?')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset Semua Pengaturan
                </button>
            </form>
        </div>
        @endif

        <!-- Info jika ada sholat yang belum ada pengaturan -->
        @php
            $sholatTerdaftar = $pengaturanList->pluck('jenis_sholat')->toArray();
            $sholatTersedia = array_diff(array_keys($sholatLabels), $sholatTerdaftar);
        @endphp
        
        @if(!empty($sholatTersedia))
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in">
            <div class="text-center">
                <h3 class="font-semibold text-gray-900 mb-2">Sholat Belum Dikonfigurasi</h3>
                <p class="text-sm text-gray-500 mb-4">Beberapa jenis sholat belum memiliki pengaturan waktu:</p>
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($sholatTersedia as $sholat)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $sholatLabels[$sholat] }}
                        </span>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('pengaturan-waktu-beribadah.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-medium px-5 py-2.5 rounded-xl shadow hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 inline-flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Pengaturan Sholat
                    </a>
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