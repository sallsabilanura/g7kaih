@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paraf Orang Tua - Bangun Pagi</title>
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

        .primary-gradient {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
        }

        .avatar-circle {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .success-badge {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .warning-badge {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        .danger-badge {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .info-badge {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }

        .highlight-new {
            position: relative;
            overflow: hidden;
        }

        .highlight-new::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #0033A0, #00A86B);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .paraf-status-new {
            position: relative;
        }

        .paraf-status-new::after {
            content: 'Baru';
            position: absolute;
            top: -8px;
            right: -8px;
            background: #00A86B;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            animation: bounce 1s infinite alternate;
        }

        @keyframes bounce {
            from { transform: translateY(0); }
            to { transform: translateY(-3px); }
        }

        .order-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            margin-right: 5px;
        }

        .order-belum {
            background: #fbbf24;
            color: #92400e;
        }

        .order-sudah {
            background: #10b981;
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl primary-gradient flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent mb-2">
                            Paraf Orang Tua - Bangun Pagi
                        </h1>
                        <p class="text-gray-500 text-sm">
                            Berikan paraf untuk checklist bangun pagi siswa
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('bangun-pagi.index') }}" 
                       class="bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 w-full lg:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Checklist
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <form method="GET" action="{{ route('paraf-ortu-bangun-pagi.index') }}" id="filterForm">
                @if(auth()->check() && auth()->user()->peran === 'admin')
                <!-- Layout 4 kolom untuk Admin -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selectedTanggal }}" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all">
                    </div>

                    <!-- Search (Hanya Admin) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Nama atau NIS..." 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all pr-10">
                            <div class="absolute right-3 top-3.5 text-[#0033A0]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Paraf</label>
                        <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="sudah" {{ $statusFilter == 'sudah' ? 'selected' : '' }}>Sudah Diparaf</option>
                            <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Diparaf</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            Terapkan Filter
                        </button>
                        <button type="button" onclick="resetFilter()"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300">
                            Reset
                        </button>
                    </div>
                </div>
                @else
                <!-- Layout 3 kolom untuk Non-Admin (Orang Tua, Guru, dll) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selectedTanggal }}" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Paraf</label>
                        <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                            <option value="sudah" {{ $statusFilter == 'sudah' ? 'selected' : '' }}>Sudah Diparaf</option>
                            <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Diparaf</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            Terapkan Filter
                        </button>
                        <button type="button" onclick="resetFilter()"
                                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300">
                            Reset
                        </button>
                    </div>
                </div>
                @endif
            </form>

            <!-- Ordering Info -->
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <div class="flex items-center gap-1">
                        <div class="order-indicator order-belum">1</div>
                        <span>Belum Diparaf (Paling Atas)</span>
                    </div>
                    <div class="flex items-center gap-1 ml-4">
                        <div class="order-indicator order-sudah">2</div>
                        <span>Sudah Diparaf (Di Bawah)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div id="messageContainer"></div>

        <!-- Data Table Card -->
        @if($bangunPagiList->isEmpty())
        <div class="glass-card rounded-2xl shadow-lg p-12 text-center fade-in">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data</h3>
            <p class="text-gray-500">Belum ada checklist bangun pagi untuk tanggal ini</p>
        </div>
        @else
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.5s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Data Checklist Bangun Pagi</h2>
                            <p class="text-gray-500 text-sm">
                                Menampilkan {{ $bangunPagiList->firstItem() ?? 0 }} - {{ $bangunPagiList->lastItem() ?? 0 }} dari {{ $bangunPagiList->total() }} data
                                <span class="ml-3 px-2 py-1 rounded-full text-xs {{ $belumDiparaf > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $belumDiparaf }} belum, {{ $sudahDiparaf }} sudah diparaf
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span>Belum Diparaf</span>
                            <div class="w-3 h-3 rounded-full bg-green-500 ml-4"></div>
                            <span>Sudah Diparaf</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#0033A0]/10 to-[#00A86B]/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Orang Tua</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Tanggal</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Pukul Bangun</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Paraf</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($bangunPagiList as $index => $item)
                        <tr class="table-row-hover transition-all duration-200 {{ $item->sudah_ttd_ortu ? 'bg-gradient-to-r from-[#00A86B]/5 to-[#0033A0]/5' : 'bg-gradient-to-r from-yellow-50/50 to-amber-50/50' }}" 
                            id="row-{{ $item->id }}"
                            data-status="{{ $item->sudah_ttd_ortu ? 'sudah' : 'belum' }}"
                            data-order="{{ $loop->iteration }}">
                            <!-- Nomor dengan indikator -->
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="order-indicator {{ $item->sudah_ttd_ortu ? 'order-sudah' : 'order-belum' }}">
                                        {{ $loop->iteration }}
                                    </div>
                                    <div class="font-medium text-gray-600 text-sm">
                                        @if(!$item->sudah_ttd_ortu)
                                            <span class="text-xs text-yellow-600 font-bold">PRIORITAS</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="p-4">
                                @if($item->sudah_ttd_ortu)
                                    <span class="status-badge success-badge">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sudah
                                    </span>
                                @else
                                    <span class="status-badge warning-badge">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Belum
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Data Siswa -->
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl avatar-circle flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($item->siswa->nama_lengkap ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $item->siswa->nama_lengkap ?? 'Data tidak ditemukan' }}</span>
                                        <span class="text-xs text-gray-500">
                                            NIS: {{ $item->siswa->nis ?? '-' }}
                                            @if($item->siswa && $item->siswa->kelas)
                                                | Kelas: {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Orang Tua -->
                            <td class="p-4">
                                @if($item->siswa && $item->siswa->orangtua)
                                    <div class="space-y-1">
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Ayah:</span> {{ $item->siswa->orangtua->nama_ayah ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-900">
                                            <span class="font-medium">Ibu:</span> {{ $item->siswa->orangtua->nama_ibu ?? '-' }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 italic">Data orangtua tidak ditemukan</span>
                                @endif
                            </td>
                            
                            <!-- Tanggal -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-900 font-medium">{{ $item->tanggal_formatted }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->nama_hari }}</div>
                                </div>
                            </td>
                            
                            <!-- Pukul Bangun -->
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="font-mono font-semibold text-gray-900 text-sm">{{ $item->pukul_formatted }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->pukul_12h }}</div>
                                    @php
                                        $badgeClass = match($item->kategori_waktu) {
                                            'tepat_waktu' => 'success-badge',
                                            'sedikit_terlambat' => 'warning-badge',
                                            'terlambat' => 'danger-badge',
                                            default => 'info-badge'
                                        };
                                    @endphp
                                    <span class="text-xs {{ $badgeClass }} inline-block px-2 py-0.5 rounded-full mt-1">
                                        {{ $item->kategori_waktu_label }}
                                    </span>
                                </div>
                            </td>
                            
                            <!-- Status Paraf -->
                            <td class="p-4">
                                @if($item->sudah_ttd_ortu)
                                    <span class="status-badge success-badge">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sudah Diparaf
                                    </span>
                                    @if($item->parafOrtu)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $item->parafOrtu->waktu_paraf_formatted ?? '' }}
                                        </div>
                                    @endif
                                @else
                                    <span class="status-badge warning-badge">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Belum Diparaf
                                    </span>
                                    <div class="text-xs text-yellow-600 mt-1">
                                        Butuh validasi orang tua
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Aksi -->
                            <td class="p-4" id="aksi-{{ $item->id }}">
                                @if(!$item->sudah_ttd_ortu)
                                    <button onclick="parafChecklist({{ $item->id }})" 
                                            class="bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-medium text-sm px-4 py-2.5 rounded-xl shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Beri Paraf
                                    </button>
                                @else
                                    <div class="flex flex-col items-start gap-2">
                                        <span class="text-xs text-[#00A86B] font-semibold flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Valid
                                        </span>
                                        @if($item->parafOrtu)
                                            <a href="{{ route('paraf-ortu-bangun-pagi.show', $item->parafOrtu->id) }}" 
                                               class="text-xs text-[#0033A0] hover:text-[#002266] underline flex items-center gap-1 hover:gap-2 transition-all"
                                               target="_blank">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach($bangunPagiList as $item)
                <div class="glass-card rounded-xl border border-gray-200 p-4 fade-in {{ $item->sudah_ttd_ortu ? 'bg-gradient-to-r from-[#00A86B]/5 to-[#0033A0]/5' : 'bg-gradient-to-r from-yellow-50/50 to-amber-50/50' }}" 
                     id="mobile-row-{{ $item->id }}"
                     data-status="{{ $item->sudah_ttd_ortu ? 'sudah' : 'belum' }}">
                    <div class="flex items-start mb-4">
                        <!-- Order Indicator -->
                        <div class="order-indicator {{ $item->sudah_ttd_ortu ? 'order-sudah' : 'order-belum' }} mr-3 mt-1">
                            {{ $loop->iteration }}
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-start mb-2">
                                <div class="w-10 h-10 rounded-xl avatar-circle flex items-center justify-center text-white font-bold text-base mr-3">
                                    {{ strtoupper(substr($item->siswa->nama_lengkap ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm">{{ $item->siswa->nama_lengkap ?? 'Data tidak ditemukan' }}</h3>
                                    <p class="text-xs text-gray-500 mb-1">NIS: {{ $item->siswa->nis ?? '-' }}</p>
                                    <p class="text-gray-900 text-sm">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            @if($item->sudah_ttd_ortu)
                                <span class="status-badge success-badge inline-block mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sudah Diparaf
                                </span>
                            @else
                                <span class="status-badge warning-badge inline-block mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum Diparaf
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Orangtua Info -->
                    @if($item->siswa && $item->siswa->orangtua)
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Orang Tua:</p>
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Ayah:</span> {{ $item->siswa->orangtua->nama_ayah ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Ibu:</span> {{ $item->siswa->orangtua->nama_ibu ?? '-' }}
                        </p>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="text-sm font-medium text-gray-900">{{ $item->tanggal_formatted }}</p>
                            <p class="text-xs text-gray-500">{{ $item->nama_hari }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs text-gray-500">Pukul Bangun</p>
                            <p class="text-sm font-medium text-gray-900">{{ $item->pukul_formatted }}</p>
                            <p class="text-xs text-gray-500">{{ $item->pukul_12h }}</p>
                            @php
                                $badgeClass = match($item->kategori_waktu) {
                                    'tepat_waktu' => 'success-badge',
                                    'sedikit_terlambat' => 'warning-badge',
                                    'terlambat' => 'danger-badge',
                                    default => 'info-badge'
                                };
                            @endphp
                            <span class="text-xs {{ $badgeClass }} inline-block px-2 py-0.5 rounded-full mt-1">
                                {{ $item->kategori_waktu_label }}
                            </span>
                        </div>
                    </div>
                    
                    <div id="mobile-aksi-{{ $item->id }}" class="pt-3 border-t border-gray-100">
                        @if(!$item->sudah_ttd_ortu)
                            <button onclick="parafChecklist({{ $item->id }})" 
                                    class="w-full bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-medium text-sm px-4 py-3 rounded-xl shadow hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Berikan Paraf
                            </button>
                        @else
                            <div class="flex flex-col items-center gap-2">
                                <span class="text-xs text-[#00A86B] font-semibold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Valid
                                </span>
                                @if($item->parafOrtu)
                                    <a href="{{ route('paraf-ortu-bangun-pagi.show', $item->parafOrtu->id) }}" 
                                       class="text-xs text-[#0033A0] hover:text-[#002266] underline flex items-center gap-1"
                                       target="_blank">
                                        Lihat Detail Paraf
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bangunPagiList->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $bangunPagiList->firstItem() ?? 0 }} - {{ $bangunPagiList->lastItem() ?? 0 }} dari {{ $bangunPagiList->total() }} data
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $bangunPagiList->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Modal Paraf -->
    <div id="modalParaf" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-auto transform transition-all fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent">
                    Paraf Checklist Bangun Pagi
                </h3>
                <button onclick="tutupModalParaf()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="mb-6 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl">
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Siswa:</span>
                        <span class="text-sm font-medium text-gray-900" id="detailSiswaNama">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">NIS:</span>
                        <span class="text-sm font-medium text-gray-900" id="detailSiswaNis">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kelas:</span>
                        <span class="text-sm font-medium text-gray-900" id="detailSiswaKelas">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tanggal:</span>
                        <span class="text-sm font-medium text-gray-900" id="detailTanggal">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pukul Bangun:</span>
                        <span class="text-sm font-medium text-gray-900" id="detailPukul">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="text-sm font-semibold" id="detailStatus">-</span>
                    </div>
                </div>
            </div>
            
            <form id="formParaf" onsubmit="submitParaf(event)">
                <input type="hidden" id="parafBangunPagiId">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Nama Orang Tua
                    </label>
                    <select id="namaOrtu" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all text-gray-900 text-sm">
                        <option value="">Pilih Orang Tua</option>
                        <!-- Options akan diisi oleh JavaScript -->
                    </select>
                </div>
                
                <!-- Input untuk orangtua lainnya -->
                <div id="otherNameInput" class="hidden mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="text-red-500">*</span> Nama Orang Tua/Wali
                    </label>
                    <input type="text" id="otherNamaOrtu" 
                           placeholder="Masukkan nama lengkap"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all text-gray-900 text-sm">
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="catatan" rows="3"
                              placeholder="Contoh: Anak terlambat bangun karena kurang tidur kemarin malam..."
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-[#0033A0] focus:border-[#0033A0] transition-all text-gray-900 text-sm"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Berikan penjelasan jika anak terlambat atau ada kondisi khusus</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="tutupModalParaf()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        Batal
                    </button>
                    <button type="submit" id="submitParafBtn"
                            class="flex-1 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        Simpan Paraf
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Data sementara untuk menyimpan detail
    let currentBangunPagiId = null;
    let lastParafId = null;

    // Reset Filter
    function resetFilter() {
        document.getElementById('filterForm').reset();
        document.querySelector('input[name="tanggal"]').value = '{{ date("Y-m-d") }}';
        document.getElementById('filterForm').submit();
    }

    // Paraf Checklist dengan cek terlebih dahulu
    async function parafChecklist(bangunPagiId) {
        try {
            console.log('Checking paraf for:', bangunPagiId);
            currentBangunPagiId = bangunPagiId;
            
            // Cek apakah sudah ada paraf untuk data ini
            const response = await fetch(`/paraf-ortu-bangun-pagi/check-paraf/${bangunPagiId}`);
            const result = await response.json();
            
            console.log('Check result:', result);
            
            if (result.success && result.exists) {
                // Jika sudah ada paraf, tampilkan pesan
                showMessage('info', `Data sudah diparaf sebelumnya. ID Paraf: ${result.paraf_id}`);
                return;
            }
            
            // Load detail untuk modal
            await loadDetailForModal(bangunPagiId);
            
        } catch (error) {
            console.error('Error checking paraf:', error);
            showMessage('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    // Load detail untuk modal
    async function loadDetailForModal(bangunPagiId) {
        try {
            console.log('Loading detail for modal:', bangunPagiId);
            
            const response = await fetch(`/paraf-ortu-bangun-pagi/detail/${bangunPagiId}`);
            const result = await response.json();
            
            console.log('Detail result:', result);
            
            if (result.success) {
                const data = result.data;
                
                // Isi modal dengan data
                document.getElementById('parafBangunPagiId').value = bangunPagiId;
                document.getElementById('detailSiswaNama').textContent = data.siswa_nama;
                document.getElementById('detailSiswaNis').textContent = data.siswa_nis;
                document.getElementById('detailSiswaKelas').textContent = data.siswa_kelas;
                document.getElementById('detailTanggal').textContent = `${data.tanggal} (${data.hari})`;
                document.getElementById('detailPukul').textContent = data.pukul;
                document.getElementById('detailStatus').textContent = data.status;
                
                // Set warna status
                const statusElement = document.getElementById('detailStatus');
                if (data.status.includes('Tepat')) {
                    statusElement.className = 'text-sm font-semibold text-[#00A86B]';
                } else if (data.status.includes('Sedikit')) {
                    statusElement.className = 'text-sm font-semibold text-yellow-600';
                } else {
                    statusElement.className = 'text-sm font-semibold text-red-600';
                }
                
                // Load orangtua options
                console.log('Orangtua options:', data.orangtua_options);
                await loadOrangtuaOptionsForModal(data.orangtua_options || []);
                
                // Tampilkan modal
                document.getElementById('modalParaf').classList.remove('hidden');
                document.getElementById('modalParaf').classList.add('flex');
            } else {
                showMessage('error', result.message || 'Gagal memuat data');
            }
        } catch (error) {
            console.error('Error loading detail:', error);
            showMessage('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    // Load orangtua options untuk modal
    async function loadOrangtuaOptionsForModal(options) {
        try {
            const select = document.getElementById('namaOrtu');
            const otherInputDiv = document.getElementById('otherNameInput');
            
            // Clear existing options except first one
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            // Add options if available
            if (options && options.length > 0) {
                console.log('Loading orangtua options:', options);
                options.forEach(ortu => {
                    const option = document.createElement('option');
                    option.value = ortu.value;
                    option.textContent = ortu.label;
                    select.appendChild(option);
                });
            } else {
                console.log('No options provided, loading from API');
                // Load dari API jika tidak ada options
                await loadOrangtuaFromAPI();
            }
            
            // Selalu tambahkan opsi lainnya
            const otherOption = document.createElement('option');
            otherOption.value = 'orangtua_lainnya';
            otherOption.textContent = 'Orang Tua/Wali Lainnya';
            select.appendChild(otherOption);
            
            // Reset other input
            if (otherInputDiv) {
                otherInputDiv.classList.add('hidden');
                document.getElementById('otherNamaOrtu').value = '';
            }
            
        } catch (error) {
            console.error('Error loading orangtua options for modal:', error);
        }
    }

    // Load orangtua dari API
    async function loadOrangtuaFromAPI() {
        try {
            const response = await fetch('/paraf-ortu-bangun-pagi/orangtua/list');
            const result = await response.json();
            
            if (result.success) {
                const select = document.getElementById('namaOrtu');
                
                result.data.forEach(ortu => {
                    const option = document.createElement('option');
                    option.value = ortu.value;
                    option.textContent = ortu.label;
                    select.appendChild(option);
                });
                
                console.log('Loaded orangtua from API:', result.data.length);
            } else {
                console.error('Failed to load orangtua from API:', result.message);
            }
        } catch (error) {
            console.error('Error loading orangtua from API:', error);
        }
    }

    function tutupModalParaf() {
        document.getElementById('modalParaf').classList.add('hidden');
        document.getElementById('modalParaf').classList.remove('flex');
        currentBangunPagiId = null;
    }

    async function submitParaf(event) {
        event.preventDefault();
        
        const bangunPagiId = document.getElementById('parafBangunPagiId').value;
        let namaOrtu = document.getElementById('namaOrtu').value;
        const otherNamaOrtu = document.getElementById('otherNamaOrtu').value;
        const catatan = document.getElementById('catatan').value;
        
        console.log('Submitting paraf:', { bangunPagiId, namaOrtu, otherNamaOrtu });
        
        // Jika memilih "orangtua_lainnya", gunakan input teks
        if (namaOrtu === 'orangtua_lainnya') {
            if (!otherNamaOrtu?.trim()) {
                showMessage('error', 'Masukkan nama orang tua/wali');
                return;
            }
            namaOrtu = otherNamaOrtu.trim();
        }
        
        if (!namaOrtu.trim()) {
            showMessage('error', 'Pilih atau masukkan nama orang tua');
            return;
        }
        
        // Disable button untuk prevent double submit
        const submitBtn = document.getElementById('submitParafBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        
        try {
            const response = await fetch('{{ route("paraf-ortu-bangun-pagi.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    bangun_pagi_id: bangunPagiId,
                    nama_ortu: namaOrtu,
                    catatan: catatan
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showMessage('success', data.message);
                tutupModalParaf();
                
                // Simpan ID paraf untuk update UI
                lastParafId = data.paraf_id;
                
                // Update UI tanpa reload
                updateUIAfterParaf(bangunPagiId, data.paraf_id);
                
                // Update statistik tanpa reload
                updateStatistik();
            } else {
                if (data.message.includes('sudah ada')) {
                    showMessage('info', 'Data sudah diparaf sebelumnya');
                } else {
                    showMessage('error', data.message);
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            showMessage('error', 'Terjadi kesalahan: ' + error.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    // Update UI setelah paraf
    function updateUIAfterParaf(bangunPagiId, parafId = null) {
        // Update desktop row
        const desktopRow = document.getElementById(`row-${bangunPagiId}`);
        if (desktopRow) {
            // Ubah status dari "belum" menjadi "sudah"
            desktopRow.dataset.status = 'sudah';
            
            // Update background color
            desktopRow.className = desktopRow.className.replace('from-yellow-50/50 to-amber-50/50', 'from-[#00A86B]/5 to-[#0033A0]/5');
            desktopRow.classList.add('bg-gradient-to-r', 'from-[#00A86B]/5', 'to-[#0033A0]/5');
            
            // Update status badge
            const statusCell = desktopRow.querySelector(`td:nth-child(2)`);
            if (statusCell) {
                statusCell.innerHTML = `
                    <span class="status-badge success-badge">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Sudah
                    </span>
                `;
            }
            
            // Update order indicator
            const orderIndicator = desktopRow.querySelector('.order-indicator');
            if (orderIndicator) {
                orderIndicator.className = 'order-indicator order-sudah';
            }
            
            // Update status paraf
            const parafCell = desktopRow.querySelector(`td:nth-child(7)`);
            if (parafCell) {
                parafCell.innerHTML = `
                    <span class="status-badge success-badge">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Sudah Diparaf
                    </span>
                    <div class="text-xs text-[#00A86B] font-medium mt-1">
                        Baru saja
                    </div>
                `;
            }
            
            // Update aksi dengan link ke show
            const aksiCell = document.getElementById(`aksi-${bangunPagiId}`);
            if (aksiCell) {
                aksiCell.innerHTML = `
                    <div class="flex flex-col items-start gap-2">
                        <span class="text-xs text-[#00A86B] font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Valid
                        </span>
                        ${parafId ? `
                            <a href="{{ route('paraf-ortu-bangun-pagi.show', '') }}/${parafId}" 
                               class="text-xs text-[#0033A0] hover:text-[#002266] underline flex items-center gap-1 hover:gap-2 transition-all"
                               target="_blank">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                        ` : ''}
                    </div>
                `;
            }
            
            // Pindahkan row ke bagian bawah (data sudah diparaf)
            setTimeout(() => {
                reorderTableRows();
            }, 100);
        }
        
        // Update mobile card
        const mobileRow = document.getElementById(`mobile-row-${bangunPagiId}`);
        if (mobileRow) {
            mobileRow.dataset.status = 'sudah';
            mobileRow.className = mobileRow.className.replace('from-yellow-50/50 to-amber-50/50', 'from-[#00A86B]/5 to-[#0033A0]/5');
            
            // Update status badge
            const statusElement = mobileRow.querySelector('.status-badge');
            if (statusElement) {
                statusElement.className = 'status-badge success-badge';
                statusElement.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sudah Diparaf
                `;
            }
            
            // Update order indicator
            const orderIndicator = mobileRow.querySelector('.order-indicator');
            if (orderIndicator) {
                orderIndicator.className = 'order-indicator order-sudah';
            }
            
            // Update aksi
            const mobileAksi = document.getElementById(`mobile-aksi-${bangunPagiId}`);
            if (mobileAksi) {
                mobileAksi.innerHTML = `
                    <div class="flex flex-col items-center gap-2 pt-3 border-t border-gray-100">
                        <span class="text-xs text-[#00A86B] font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Valid
                        </span>
                        ${parafId ? `
                            <a href="{{ route('paraf-ortu-bangun-pagi.show', '') }}/${parafId}" 
                               class="text-xs text-[#0033A0] hover:text-[#002266] underline flex items-center gap-1"
                               target="_blank">
                                Lihat Detail Paraf
                            </a>
                        ` : ''}
                    </div>
                `;
            }
            
            // Tambahkan animasi highlight
            mobileRow.classList.add('highlight-new');
            setTimeout(() => {
                mobileRow.classList.remove('highlight-new');
            }, 2000);
        }
    }

    // Fungsi untuk mengurutkan ulang baris di table
    function reorderTableRows() {
        // Ambil semua row
        const rows = Array.from(document.querySelectorAll('tbody tr'));
        const mobileRows = Array.from(document.querySelectorAll('.lg\\:hidden .glass-card'));
        
        // Urutkan: belum diparaf dulu, lalu sudah diparaf
        rows.sort((a, b) => {
            const aStatus = a.dataset.status;
            const bStatus = b.dataset.status;
            
            if (aStatus === 'belum' && bStatus === 'sudah') return -1;
            if (aStatus === 'sudah' && bStatus === 'belum') return 1;
            return 0;
        });
        
        // Update nomor urut
        rows.forEach((row, index) => {
            const orderIndicator = row.querySelector('.order-indicator');
            if (orderIndicator) {
                orderIndicator.textContent = index + 1;
                orderIndicator.className = row.dataset.status === 'belum' ? 
                    'order-indicator order-belum' : 'order-indicator order-sudah';
                
                // Update data-order attribute
                row.dataset.order = index + 1;
            }
        });
        
        // Reorder mobile rows
        mobileRows.sort((a, b) => {
            const aStatus = a.dataset.status;
            const bStatus = b.dataset.status;
            
            if (aStatus === 'belum' && bStatus === 'sudah') return -1;
            if (aStatus === 'sudah' && bStatus === 'belum') return 1;
            return 0;
        });
        
        // Update mobile order indicators
        mobileRows.forEach((card, index) => {
            const orderIndicator = card.querySelector('.order-indicator');
            if (orderIndicator) {
                orderIndicator.textContent = index + 1;
                orderIndicator.className = card.dataset.status === 'belum' ? 
                    'order-indicator order-belum' : 'order-indicator order-sudah';
            }
        });
    }

    // Update statistik tanpa reload
    async function updateStatistik() {
        try {
            const tanggal = document.querySelector('input[name="tanggal"]').value;
            const response = await fetch(`/paraf-ortu-bangun-pagi/statistik/data?tanggal=${tanggal}`);
            const data = await response.json();
            
            if (data.success) {
                // Update statistik cards
                document.querySelectorAll('.glass-card')[1].querySelector('.text-2xl').textContent = data.sudahDiparaf;
                document.querySelectorAll('.glass-card')[2].querySelector('.text-2xl').textContent = data.belumDiparaf;
                
                // Update persentase
                const total = data.totalData;
                if (total > 0) {
                    const sudahPercent = document.querySelectorAll('.glass-card')[1].querySelector('.text-sm');
                    const belumPercent = document.querySelectorAll('.glass-card')[2].querySelector('.text-sm');
                    
                    sudahPercent.textContent = `${((data.sudahDiparaf/total)*100).toFixed(1)}% dari total`;
                    belumPercent.textContent = `${((data.belumDiparaf/total)*100).toFixed(1)}% dari total`;
                }
            }
        } catch (error) {
            console.error('Error updating statistik:', error);
        }
    }

    // Show Message
    function showMessage(type, message) {
        const bgColor = type === 'success' ? 
            'bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-[#00A86B]' : 
            type === 'info' ?
            'bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500' :
            'bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500';
        const textColor = type === 'success' ? 'text-gray-900' : 'text-gray-900';
        const iconColor = type === 'success' ? 'text-[#00A86B]' : 
                         type === 'info' ? 'text-blue-500' : 'text-red-500';
        const icon = type === 'success' 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : type === 'info' 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
        
        const messageHtml = `
            <div class="glass-card ${bgColor} rounded-xl p-4 mb-6 fade-in">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${icon}
                    </svg>
                    <p class="${textColor} font-medium text-sm">${message}</p>
                </div>
            </div>
        `;
        
        const container = document.getElementById('messageContainer');
        container.innerHTML = messageHtml;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            const messageEl = container.querySelector('.glass-card');
            if (messageEl) {
                messageEl.style.opacity = '0';
                messageEl.style.transition = 'opacity 0.5s';
                setTimeout(() => {
                    container.innerHTML = '';
                }, 500);
            }
        }, 5000);
    }

    // Event listener untuk dropdown orangtua
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('namaOrtu');
        const otherInputDiv = document.getElementById('otherNameInput');
        
        if (select) {
            select.addEventListener('change', function() {
                if (this.value === 'orangtua_lainnya') {
                    if (otherInputDiv) {
                        otherInputDiv.classList.remove('hidden');
                        document.getElementById('otherNamaOrtu').focus();
                    }
                } else {
                    if (otherInputDiv) {
                        otherInputDiv.classList.add('hidden');
                        document.getElementById('otherNamaOrtu').value = '';
                    }
                }
            });
        }

        // Close modal on outside click
        document.getElementById('modalParaf').addEventListener('click', function(e) {
            if (e.target === this) tutupModalParaf();
        });

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                tutupModalParaf();
            }
        });

        // Animasi untuk elements
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${0.1 * index}s`;
        });

        // Real-time search (Hanya untuk admin - periksa apakah ada input search)
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                
                // Desktop rows
                document.querySelectorAll('tbody tr').forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
                
                // Mobile cards
                document.querySelectorAll('.lg\\:hidden .glass-card').forEach(card => {
                    const text = card.textContent.toLowerCase();
                    card.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Filter by status (real-time)
        const statusFilter = document.querySelector('select[name="status"]');
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const status = this.value;
                
                document.querySelectorAll('tbody tr').forEach(row => {
                    const rowStatus = row.dataset.status;
                    
                    if (status === 'all' || 
                        (status === 'sudah' && rowStatus === 'sudah') ||
                        (status === 'belum' && rowStatus === 'belum')) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Mobile cards
                document.querySelectorAll('.lg\\:hidden .glass-card').forEach(card => {
                    const cardStatus = card.dataset.status;
                    
                    if (status === 'all' || 
                        (status === 'sudah' && cardStatus === 'sudah') ||
                        (status === 'belum' && cardStatus === 'belum')) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>
</body>
</html>
@endsection