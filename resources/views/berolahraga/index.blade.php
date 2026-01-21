@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Olahraga Siswa - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .badge-nilai {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            display: inline-block;
            min-width: 60px;
            text-align: center;
        }

        .badge-sangat-baik {
            background: linear-gradient(135deg, #00A86B 0%, rgba(0, 168, 107, 0.1) 100%);
            color: #00A86B;
            border: 1px solid rgba(0, 168, 107, 0.3);
        }

        .badge-baik {
            background: linear-gradient(135deg, #0033A0 0%, rgba(0, 51, 160, 0.1) 100%);
            color: #0033A0;
            border: 1px solid rgba(0, 51, 160, 0.3);
        }

        .badge-cukup {
            background: linear-gradient(135deg, #FFD700 0%, rgba(255, 215, 0, 0.1) 100%);
            color: #8B7500;
            border: 1px solid rgba(255, 215, 0, 0.3);
        }

        .badge-kurang {
            background: linear-gradient(135deg, #DC143C 0%, rgba(220, 20, 60, 0.1) 100%);
            color: #DC143C;
            border: 1px solid rgba(220, 20, 60, 0.3);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .mobile-card {
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                margin-bottom: 16px;
                overflow: hidden;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }
            
            .mobile-card-header {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                padding: 16px;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .mobile-card-body {
                padding: 16px;
            }
            
            .mobile-row {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #f1f5f9;
            }
            
            .mobile-row:last-child {
                border-bottom: none;
            }
            
            .mobile-label {
                font-size: 14px;
                color: #6b7280;
                font-weight: 500;
                min-width: 100px;
            }
            
            .mobile-value {
                font-size: 14px;
                color: #111827;
                font-weight: 500;
                text-align: right;
                flex: 1;
            }
            
            .mobile-actions {
                display: flex;
                gap: 12px;
                justify-content: flex-end;
                padding-top: 16px;
                border-top: 1px solid #f1f5f9;
                margin-top: 16px;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
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
                        <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Data Olahraga Siswa
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Sistem penilaian otomatis berdasarkan waktu mulai olahraga
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('berolahraga.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        Input Olahraga Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.15s;">
            <form action="{{ route('berolahraga.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari nama atau NIS siswa..."
                               class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="lg:w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <input type="month" 
                           name="bulan" 
                           value="{{ $selectedBulan }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full lg:w-auto bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300 shadow-lg">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
        <div class="glass-card rounded-2xl shadow-lg mb-8 fade-in" style="animation-delay: 0.35s;">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl flex items-start">
                <div class="w-8 h-8 rounded-lg bg-accent-green/20 flex items-center justify-center mr-3">
                    <span class="text-accent-green font-bold">✓</span>
                </div>
                <div>
                    <p class="text-gray-900 font-medium">{!! session('success') !!}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="glass-card rounded-2xl shadow-lg mb-8 fade-in" style="animation-delay: 0.35s;">
            <div class="bg-gradient-to-r from-red-50 to-secondary-50 border-l-4 border-secondary-600 p-4 rounded-xl flex items-start">
                <div class="w-8 h-8 rounded-lg bg-secondary-600/20 flex items-center justify-center mr-3">
                    <span class="text-secondary-600 font-bold">!</span>
                </div>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Data Table -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.4s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Olahraga</h2>
                        <p class="text-gray-500 text-sm">Total {{ $berolahragaList->total() }} data ditemukan</p>
                    </div>
                </div>
            </div>

            @if($berolahragaList->isEmpty())
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-gray-400 text-2xl font-bold">O</span>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Data Olahraga</h3>
                <p class="text-gray-500 mb-6">Belum ada data olahraga untuk bulan ini</p>
                <a href="{{ route('berolahraga.create') }}" 
                   class="inline-flex bg-gradient-to-r from-primary-500 to-accent-green text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 shadow-lg">
                    Input Data Pertama
                </a>
            </div>
            @else
            <!-- Desktop Table (hidden on mobile) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Tanggal</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Waktu</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Durasi</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Video</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($berolahragaList as $index => $olahraga)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50/50 hover:to-blue-50/50 transition-all duration-200">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ ($berolahragaList->currentPage() - 1) * $berolahragaList->perPage() + $index + 1 }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-green text-white flex items-center justify-center font-bold text-sm mr-3">
                                        {{ substr($olahraga->siswa->nama_lengkap ?? $olahraga->siswa->nama ?? 'S', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $olahraga->siswa->nama_lengkap ?? $olahraga->siswa->nama ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">NIS: {{ $olahraga->siswa->nis ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $olahraga->tanggal_formatted }}</div>
                                    <div class="text-xs text-gray-500">{{ $olahraga->nama_hari }}</div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">Mulai: {{ $olahraga->mulai_olahraga }}</div>
                                    <div class="text-xs text-gray-500">Selesai: {{ $olahraga->selesai_olahraga }}</div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-primary-600">
                                    {{ $olahraga->durasi }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($olahraga->video_path)
                                    <a href="{{ route('berolahraga.downloadVideo', $olahraga->id) }}" 
                                       class="text-primary-600 hover:text-primary-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-primary-50 transition-colors">
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('berolahraga.edit', $olahraga->id) }}" 
                                       class="text-primary-600 hover:text-primary-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-primary-50 transition-colors">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('berolahraga.destroy', $olahraga->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus data ini?')" 
                                                class="text-red-600 hover:text-red-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (visible only on mobile) -->
            <div class="md:hidden p-4">
                @foreach($berolahragaList as $index => $olahraga)
                <div class="mobile-card fade-in" style="animation-delay: {{ $index * 0.05 }}s;">
                    <div class="mobile-card-header">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-green text-white flex items-center justify-center font-bold text-sm mr-3">
                                    {{ substr($olahraga->siswa->nama_lengkap ?? $olahraga->siswa->nama ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ $olahraga->siswa->nama_lengkap ?? $olahraga->siswa->nama ?? '-' }}</h3>
                                    <p class="text-xs text-gray-500">NIS: {{ $olahraga->siswa->nis ?? '-' }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-medium text-gray-500">
                                #{{ ($berolahragaList->currentPage() - 1) * $berolahragaList->perPage() + $index + 1 }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mobile-card-body">
                        <!-- Tanggal -->
                        <div class="mobile-row">
                            <span class="mobile-label">Tanggal</span>
                            <div class="mobile-value">
                                <div class="text-sm font-medium text-gray-900">{{ $olahraga->tanggal_formatted }}</div>
                                <div class="text-xs text-gray-500">{{ $olahraga->nama_hari }}</div>
                            </div>
                        </div>
                        
                        <!-- Waktu -->
                        <div class="mobile-row">
                            <span class="mobile-label">Waktu</span>
                            <div class="mobile-value">
                                <div class="text-sm text-gray-900">Mulai: {{ $olahraga->mulai_olahraga }}</div>
                                <div class="text-xs text-gray-500">Selesai: {{ $olahraga->selesai_olahraga }}</div>
                            </div>
                        </div>
                        
                        <!-- Durasi -->
                        <div class="mobile-row">
                            <span class="mobile-label">Durasi</span>
                            <span class="mobile-value">
                                <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-primary-600">
                                    {{ $olahraga->durasi }}
                                </span>
                            </span>
                        </div>
                        
                        <!-- Video -->
                        <div class="mobile-row">
                            <span class="mobile-label">Video</span>
                            <div class="mobile-value">
                                @if($olahraga->video_path)
                                    <a href="{{ route('berolahraga.downloadVideo', $olahraga->id) }}" 
                                       class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                        Download Video
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="mobile-actions">
                            <a href="{{ route('berolahraga.edit', $olahraga->id) }}" 
                               class="text-primary-600 hover:text-primary-800 font-medium text-sm px-4 py-2 rounded-lg border border-primary-200 hover:bg-primary-50 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('berolahraga.destroy', $olahraga->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus data ini?')" 
                                        class="text-red-600 hover:text-red-800 font-medium text-sm px-4 py-2 rounded-lg border border-red-200 hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($berolahragaList->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $berolahragaList->firstItem() ?? 0 }} - {{ $berolahragaList->lastItem() ?? 0 }} dari {{ $berolahragaList->total() }} data
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $berolahragaList->links() }}
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });
            
            // Responsive adjustments
            function adjustForMobile() {
                const isMobile = window.innerWidth < 768;
                
                if (isMobile) {
                    // Add touch-friendly styles for mobile
                    const buttons = document.querySelectorAll('button, a');
                    buttons.forEach(btn => {
                        btn.style.minHeight = '44px';
                        btn.style.minWidth = '44px';
                    });
                }
            }
            
            // Initial adjustment
            adjustForMobile();
            
            // Adjust on resize
            window.addEventListener('resize', adjustForMobile);
        });
    </script>
</body>
</html>
@endsection