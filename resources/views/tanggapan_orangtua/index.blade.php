@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tanggapan Orangtua - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .user-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(0, 51, 160, 0.02) 0%, rgba(0, 168, 107, 0.02) 100%);
        }

        .badge-blue {
            background: linear-gradient(135deg, #0033A0 0%, #2D68C4 100%);
            color: white;
        }

        .badge-green {
            background: linear-gradient(135deg, #00A86B 0%, #2ECC71 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent mb-2">
                        <i class="fas fa-comments mr-3"></i>
                        Tanggapan Orangtua
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola data tanggapan perkembangan anak setiap akhir bulan
                    </p>
                </div>
                <a href="{{ route('tanggapan-orangtua.create') }}" 
                   class="bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Tanggapan Baru
                </a>
            </div>

            <!-- Alert Success -->
            @if (session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-[#00A86B] p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-[#00A86B] mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
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
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl shadow-lg p-6 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Total Tanggapan</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $tanggapan->total() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-[#0033A0]/10 flex items-center justify-center">
                        <span class="text-xl font-bold text-[#0033A0]">{{ $tanggapan->total() }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl shadow-lg p-6 fade-in" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Sudah TTD</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $tanggapan->whereNotNull('tanda_tangan_digital')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-[#00A86B]/20 flex items-center justify-center">
                        <span class="text-xl font-bold text-[#00A86B]">{{ $tanggapan->whereNotNull('tanda_tangan_digital')->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl shadow-lg p-6 fade-in" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Belum TTD</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $tanggapan->whereNull('tanda_tangan_digital')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center">
                        <span class="text-xl font-bold text-amber-600">{{ $tanggapan->whereNull('tanda_tangan_digital')->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl shadow-lg p-6 fade-in" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Tahun Ini</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $tanggapan->where('tahun', date('Y'))->count() }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
                        <span class="text-xl font-bold text-purple-600">{{ $tanggapan->where('tahun', date('Y'))->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.3s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Tanggapan Orangtua</h2>
                            <p class="text-gray-500 text-sm">Total {{ $tanggapan->total() }} data ditemukan</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari nama siswa atau NIS..." 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] focus:ring-1 focus:ring-[#0033A0] transition-all text-gray-900 text-sm"
                                   onkeyup="filterTable(this.value)">
                            <div class="absolute right-3 top-3">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                        
                        <button type="button" 
                                onclick="toggleFilter()"
                                class="border border-gray-300 text-gray-700 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors flex items-center text-sm">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                        
                        @if($tanggapan->count() > 0)
                            <a href="#" 
                               class="border border-gray-300 text-gray-700 px-4 py-3 rounded-xl hover:bg-gray-50 transition-colors flex items-center text-sm">
                                <i class="fas fa-print mr-2"></i>
                                Cetak
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div id="filterSection" class="hidden px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <form method="GET" action="{{ route('tanggapan-orangtua.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <select name="kelas" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] focus:ring-1 focus:ring-[#0033A0] transition-all">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                        {{ $kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] focus:ring-1 focus:ring-[#0033A0] transition-all">
                                <option value="">Semua Bulan</option>
                                @foreach($bulanList as $key => $bulan)
                                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                        {{ $bulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] focus:ring-1 focus:ring-[#0033A0] transition-all">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status TTD</label>
                            <select name="tanda_tangan" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] focus:ring-1 focus:ring-[#0033A0] transition-all">
                                <option value="">Semua Status</option>
                                <option value="sudah" {{ request('tanda_tangan') == 'sudah' ? 'selected' : '' }}>Sudah TTD</option>
                                <option value="belum" {{ request('tanda_tangan') == 'belum' ? 'selected' : '' }}>Belum TTD</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button type="submit" 
                                class="bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-medium px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-300 flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('tanggapan-orangtua.index') }}" 
                           class="border border-gray-300 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-50 transition-colors">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Data Table -->
            @if($tanggapan->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-[#0033A0]/10 to-[#00A86B]/10">
                                <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                                <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Periode</th>
                                <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Orangtua/Wali</th>
                                <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Status TTD</th>
                                <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($tanggapan as $item)
                            <tr class="table-row-hover transition-all duration-200">
                                <!-- Siswa Column -->
                                <td class="p-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-base mr-3">
                                            {{ strtoupper(substr($item->siswa->nama_lengkap ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900 text-sm block">{{ $item->siswa->nama_lengkap ?? 'N/A' }}</span>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs px-2 py-1 rounded-lg bg-blue-100 text-[#0033A0]">
                                                    NIS: {{ $item->siswa->nis ?? '-' }}
                                                </span>
                                                <span class="text-xs px-2 py-1 rounded-lg bg-green-100 text-[#00A86B]">
                                                    {{ $item->kelas }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Periode Column -->
                                <td class="p-4">
                                    <div class="space-y-1">
                                        <div class="inline-flex items-center px-3 py-1.5 rounded-lg badge-blue text-xs font-semibold">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ $item->nama_bulan }} {{ $item->tahun }}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $item->tanggal_pengisian_formatted }}
                                        </p>
                                    </div>
                                </td>
                                
                                <!-- Orangtua Column -->
                                <td class="p-4">
                                    <div class="space-y-1">
                                        <p class="font-medium text-gray-900 text-sm">{{ $item->nama_orangtua }}</p>
                                        <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                                            {{ $item->tipe_orangtua == 'ayah' ? 'bg-blue-100 text-[#0033A0]' : 
                                               ($item->tipe_orangtua == 'ibu' ? 'bg-pink-100 text-pink-800' : 
                                               'bg-gray-100 text-gray-800') }}">
                                            <i class="fas {{ 
                                                $item->tipe_orangtua == 'ayah' ? 'fa-male' : 
                                                ($item->tipe_orangtua == 'ibu' ? 'fa-female' : 'fa-user-tie') 
                                            }} mr-1"></i>
                                            {{ ucfirst($item->tipe_orangtua) }}
                                        </span>
                                    </div>
                                </td>
                                
                                <!-- Status TTD Column -->
                                <td class="p-4">
                                    @if($item->tanda_tangan_digital)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-100 text-[#00A86B]">
                                            <i class="fas fa-check-circle mr-1.5"></i>
                                            Sudah TTD
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-800">
                                            <i class="fas fa-clock mr-1.5"></i>
                                            Belum TTD
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Aksi Column -->
                                <td class="p-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('tanggapan-orangtua.show', $item->id) }}" 
                                           class="text-[#0033A0] hover:text-[#00257A] font-medium text-sm px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                            Detail
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('tanggapan-orangtua.edit', $item->id) }}" 
                                           class="text-[#00A86B] hover:text-[#008F5C] font-medium text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition-colors">
                                            Edit
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('tanggapan-orangtua.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Yakin ingin menghapus tanggapan ini?')" 
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

                <!-- Mobile Cards -->
                <div class="lg:hidden p-4 space-y-4">
                    @foreach ($tanggapan as $item)
                    <div class="glass-card rounded-xl border border-gray-200 p-4 fade-in">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-lg mr-3">
                                {{ strtoupper(substr($item->siswa->nama_lengkap ?? 'A', 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $item->siswa->nama_lengkap ?? 'N/A' }}</h3>
                                <p class="text-xs text-gray-500 mb-1">NIS: {{ $item->siswa->nis ?? '-' }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs px-2 py-1 rounded-lg bg-blue-100 text-[#0033A0]">
                                        {{ $item->kelas }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded-lg {{ 
                                        $item->tipe_orangtua == 'ayah' ? 'bg-blue-100 text-[#0033A0]' : 
                                        ($item->tipe_orangtua == 'ibu' ? 'bg-pink-100 text-pink-800' : 
                                        'bg-gray-100 text-gray-800') 
                                    }}">
                                        {{ ucfirst($item->tipe_orangtua) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Periode</p>
                                    <div class="inline-flex items-center px-3 py-1 rounded-lg badge-blue text-xs font-medium">
                                        <i class="fas fa-calendar mr-1.5"></i>
                                        {{ $item->nama_bulan }} {{ $item->tahun }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 mb-1">TTD</p>
                                    @if($item->tanda_tangan_digital)
                                        <span class="inline-flex items-center text-xs font-medium text-[#00A86B]">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Sudah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-medium text-amber-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            Belum
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Orangtua/Wali</p>
                                <p class="font-medium text-gray-900 text-sm">{{ $item->nama_orangtua }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('tanggapan-orangtua.show', $item->id) }}" 
                               class="flex-1 text-center bg-[#0033A0] text-white font-medium py-2.5 rounded-lg hover:bg-[#00257A] transition-colors text-sm">
                                <i class="fas fa-eye mr-1.5"></i>
                                Detail
                            </a>
                            <a href="{{ route('tanggapan-orangtua.edit', $item->id) }}" 
                               class="flex-1 text-center bg-[#00A86B] text-white font-medium py-2.5 rounded-lg hover:bg-[#008F5C] transition-colors text-sm">
                                <i class="fas fa-edit mr-1.5"></i>
                                Edit
                            </a>
                            <form action="{{ route('tanggapan-orangtua.destroy', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin menghapus tanggapan ini?')" 
                                        class="w-full text-center bg-red-500 text-white font-medium py-2.5 rounded-lg hover:bg-red-600 transition-colors text-sm">
                                    <i class="fas fa-trash mr-1.5"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($tanggapan->hasPages())
                <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $tanggapan->firstItem() ?? 0 }} - {{ $tanggapan->lastItem() ?? 0 }} dari {{ $tanggapan->total() }} data
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $tanggapan->links() }}
                        </div>
                    </div>
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-16 px-4">
                    <div class="inline-flex w-32 h-32 bg-gradient-to-br from-blue-100 to-green-100 rounded-full items-center justify-center mb-6">
                        <i class="fas fa-inbox text-5xl text-[#0033A0]"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Belum ada data tanggapan</h3>
                    <p class="text-gray-500 text-sm max-w-md mx-auto mb-8">
                        Mulai dengan menambahkan tanggapan orangtua pertama untuk memantau perkembangan anak.
                    </p>
                    <a href="{{ route('tanggapan-orangtua.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white rounded-xl hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Tanggapan Pertama
                    </a>
                </div>
            @endif
        </div>

        <!-- Info Footer -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.4s;">
            <div class="text-center">
                <h3 class="font-semibold text-gray-900 mb-3">Tips Pengelolaan Tanggapan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 bg-[#0033A0] rounded-full"></div>
                        </div>
                        <p class="ml-3 text-gray-500 text-sm">
                            Tanggapan hanya dapat diisi setiap akhir bulan (tanggal 25-31)
                        </p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 bg-[#00A86B] rounded-full"></div>
                        </div>
                        <p class="ml-3 text-gray-500 text-sm">
                            Data dapat diedit dalam 3 hari setelah pengisian
                        </p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 bg-[#0033A0] rounded-full"></div>
                        </div>
                        <p class="ml-3 text-gray-500 text-sm">
                            Pastikan tanda tangan digital telah disimpan sebelum mengirim
                        </p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-2 h-2 bg-[#00A86B] rounded-full"></div>
                        </div>
                        <p class="ml-3 text-gray-500 text-sm">
                            Tanggapan akan direview oleh guru wali setiap awal bulan
                        </p>
                    </div>
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

            // Initialize dropdowns
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                const button = dropdown.querySelector('button');
                const menu = dropdown.querySelector('.dropdown-menu');
                
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            });
        });

        // Toggle filter section
        function toggleFilter() {
            const filterSection = document.getElementById('filterSection');
            filterSection.classList.toggle('hidden');
        }

        // Filter table function
        function filterTable(searchTerm) {
            searchTerm = searchTerm.toLowerCase();
            const rows = document.querySelectorAll('tbody tr, .lg\\:hidden .glass-card');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-gradient-to-r');
            alerts.forEach(alert => {
                if (alert.classList.contains('from-green-50') || alert.classList.contains('from-red-50')) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
    </script>
</body>
</html>
@endsection