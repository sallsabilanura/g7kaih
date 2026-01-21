@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Waktu Makan - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .time-badge {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .status-active {
            background: linear-gradient(135deg, #00A86B 0%, #10B981 100%);
        }

        .status-inactive {
            background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);
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
                        Pengaturan Waktu Makan
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola waktu dan nilai untuk sarapan, makan siang, dan makan malam
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengaturan-waktu-makan.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Pengaturan
                    </a>
                </div>
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

            @if (session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>


        <!-- Main Content -->
        @if($pengaturanWaktu->isEmpty())
        <!-- Empty State -->
        <div class="glass-card rounded-2xl shadow-lg p-12 text-center fade-in">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-primary-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-3">Belum Ada Pengaturan</h3>
                <p class="text-gray-500 mb-8">
                    Mulai dengan membuat pengaturan waktu makan untuk mengatur sistem penilaian.
                </p>
                
                <a href="{{ route('pengaturan-waktu-makan.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Pengaturan Pertama
                </a>
            </div>
        </div>
        @else
        <!-- Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($pengaturanWaktu as $item)
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
                <!-- Card Header -->
                <div class="p-6 bg-gradient-to-r from-primary-500/10 to-accent-green/10 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-primary-500 to-accent-green flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $jenisLabels[$item->jenis_makanan] }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold {{ $item->is_active ? 'status-active text-white' : 'status-inactive text-white' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Categories -->
                <div class="p-6 space-y-4">
                    <!-- Tepat Waktu -->
                    <div class="border border-green-200 rounded-xl p-4 bg-green-50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-green-800">Tepat Waktu</span>
                            </div>
                            <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $item->nilai_100 }}
                            </span>
                        </div>
                        <div class="text-center">
                            <div class="font-mono font-bold text-green-700 text-sm">{{ $item->waktu_100_display }}</div>
                            <div class="text-xs text-green-600 mt-1">{{ $item->waktu_100_display12h }}</div>
                        </div>
                    </div>

                    <!-- Sedikit Terlambat -->
                    <div class="border border-yellow-200 rounded-xl p-4 bg-yellow-50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-yellow-800">Sedikit Terlambat</span>
                            </div>
                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $item->nilai_70 }}
                            </span>
                        </div>
                        <div class="text-center">
                            <div class="font-mono font-bold text-yellow-700 text-sm">{{ $item->waktu_70_display }}</div>
                            <div class="text-xs text-yellow-600 mt-1">{{ $item->waktu_70_display12h }}</div>
                        </div>
                    </div>

                    <!-- Terlambat -->
                    <div class="border border-red-200 rounded-xl p-4 bg-red-50">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-semibold text-red-800">Terlambat</span>
                            </div>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $item->nilai_terlambat }}
                            </span>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-red-700 font-semibold">Di luar rentang waktu</p>
                            <p class="text-xs text-red-600 mt-1">Setelah {{ $item->waktu_70_display12h }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 border-t border-gray-100">
                    <div class="flex gap-3">
                        <a href="{{ route('pengaturan-waktu-makan.edit', $item->jenis_makanan) }}" 
                           class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('pengaturan-waktu-makan.destroy', $item->jenis_makanan) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengaturan {{ $jenisLabels[$item->jenis_makanan] }}?')" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
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

        <!-- Info Card -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.4s;">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-primary-500/10 to-accent-green/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">Informasi Pengaturan Waktu</h3>
                    <p class="text-gray-500 text-sm">
                        Sistem penilaian menggunakan 3 kategori waktu untuk setiap jenis makanan. 
                        Edit pengaturan untuk mengubah waktu dan nilai sesuai kebutuhan sistem.
                    </p>
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