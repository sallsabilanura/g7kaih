@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Makan Sehat - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }
        
        .status-filled {
            background: linear-gradient(135deg, #00A86B 0%, #008055 100%);
        }
        
        .status-empty {
            background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
        }
        
        .status-pending {
            background: linear-gradient(135deg, #FFD700 0%, #e6c200 100%);
        }

        .status-expired {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        .table-cell-hover:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .badge-nilai {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-weight: 600;
        }
        
        .badge-100 { background-color: #dcfce7; color: #166534; }
        .badge-70 { background-color: #fef3c7; color: #92400e; }
        .badge-50 { background-color: #fee2e2; color: #991b1b; }
        .badge-default { background-color: #e5e7eb; color: #374151; }

        .disabled-cell {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom styles untuk tema biru-hijau */
        .bg-primary-gradient {
            background: linear-gradient(135deg, #0033A0 0%, #002d8a 100%);
        }

        .bg-secondary-gradient {
            background: linear-gradient(135deg, #00A86B 0%, #008055 100%);
        }

        .text-primary {
            color: #0033A0;
        }

        .text-secondary {
            color: #00A86B;
        }

        .bg-primary-50 {
            background-color: #f0f7ff;
        }

        .bg-secondary-50 {
            background-color: #f0fff4;
        }

        .border-primary-200 {
            border-color: #bfdbfe;
        }

        .border-secondary-200 {
            border-color: #bbf7d0;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
        }

        .today-row {
            background: linear-gradient(90deg, rgba(0, 51, 160, 0.05) 0%, rgba(0, 168, 107, 0.05) 100%);
            border-left: 4px solid #0033A0;
        }

        .past-row {
            opacity: 0.9;
        }

        .today-badge {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: bold;
        }

        /* Responsive improvements */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .mobile-card {
            display: none;
        }
        
        .mobile-date-header {
            position: sticky;
            left: 0;
            background: white;
            z-index: 5;
        }
        
        @media (max-width: 1024px) {
            .table-header {
                min-width: 120px;
            }
            
            .table-cell {
                min-width: 150px;
            }
        }
        
        @media (max-width: 768px) {
            .desktop-table {
                display: none;
            }
            
            .mobile-card {
                display: block;
            }
            
            .mobile-date-header {
                font-size: 1.125rem;
                font-weight: 600;
            }
            
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filter-controls {
                flex-direction: column;
                gap: 1rem;
            }
            
            .action-buttons {
                justify-content: center;
                margin-top: 1rem;
            }
            
            .legend-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .card-padding {
                padding: 1rem;
            }
            
            .mobile-meal-card {
                padding: 0.75rem;
            }
            
            .mobile-meal-image {
                height: 4rem;
            }
        }

        .time-range-info {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .pengaturan-info {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 md:p-8 mb-4 sm:mb-6 border border-gray-200 slide-in">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 sm:gap-3 mb-2">
                        <div class="bg-primary-gradient p-2 rounded-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-primary">
                                Data Makan Sehat Harian
                            </h1>
                            <p class="text-gray-600 text-xs sm:text-sm md:text-base">
                                Rekap makan sehat per hari - {{ now()->translatedFormat('F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Sukses -->
            @if (session('success'))
            <div class="mt-3 sm:mt-4 bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 rounded-md flex items-start slide-in">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Alert Error -->
            @if (session('error'))
            <div class="mt-3 sm:mt-4 bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-md flex items-start slide-in">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400 mr-2 sm:mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-red-800 font-medium text-sm sm:text-base">{{ session('error') }}</p>
                </div>
            </div>
            @endif
        </div>


        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6 border border-gray-200 slide-in" style="animation-delay: 0.2s;">
            <div class="filter-container flex flex-col sm:flex-row gap-4 items-stretch sm:items-center justify-between">
                <div class="filter-controls flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <!-- Filter Bulan -->
                    <div class="w-full sm:w-auto">
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select id="month" name="month" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            @foreach(range(1, 12) as $month)
                            @php
                                $bulanList = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                            @endphp
                            <option value="{{ $month }}" {{ $month == $bulan ? 'selected' : '' }}>
                                {{ $bulanList[$month] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tahun -->
                    <div class="w-full sm:w-auto">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select id="year" name="year" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            @foreach(range(date('Y')-1, date('Y')+1) as $year)
                            <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Siswa (hanya untuk admin) -->
                    @if(!session('siswa_id'))
                    <div class="w-full sm:w-auto">
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                        <select id="siswa_id" name="siswa_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            @foreach($siswaList as $s)
                            <option value="{{ $s->id }}" {{ $siswa->id == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_lengkap }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div class="action-buttons flex gap-2 sm:gap-3 mt-2 sm:mt-0">
                    <button onclick="applyFilters()" class="flex-1 sm:flex-none bg-primary text-white px-3 sm:px-4 py-2 rounded-md hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Terapkan
                    </button>
                    <a href="{{ route('makan-sehat.create') }}" class="flex-1 sm:flex-none bg-secondary text-white px-3 sm:px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabel Harian - Tampilan Desktop -->
        <div class="desktop-table bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 slide-in" style="animation-delay: 0.3s;">
            <div class="p-4 sm:p-6 bg-gradient-primary text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-lg font-bold">Rekap Harian - {{ $namaBulan }} {{ $tahun }}</h2>
                    <div class="text-xs bg-white/20 px-3 py-1 rounded-full">
                        Hari ini diurutkan paling atas
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="w-full min-w-max">
                    <thead class="bg-primary-50 border-b-2 border-primary-200">
                        <tr>
                            <th class="px-3 sm:px-4 py-3 text-left text-xs font-bold text-primary uppercase tracking-wider sticky left-0 bg-primary-50 z-10 mobile-date-header">Tanggal</th>
                            
                            <!-- Header Dinamis berdasarkan pengaturan waktu -->
                            @foreach(['sarapan', 'makan_siang', 'makan_malam'] as $jenis)
                            @php
                                $pengaturan = $pengaturanWaktu->where('jenis_makanan', $jenis)->first();
                                $warna = [
                                    'sarapan' => ['bg' => 'blue', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                                    'makan_siang' => ['bg' => 'green', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                                    'makan_malam' => ['bg' => 'indigo', 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z']
                                ][$jenis];
                                $label = [
                                    'sarapan' => 'Sarapan',
                                    'makan_siang' => 'Makan Siang', 
                                    'makan_malam' => 'Makan Malam'
                                ][$jenis];
                            @endphp
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-bold text-primary uppercase tracking-wider table-header">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="bg-{{ $warna['bg'] }}-100 p-1 sm:p-2 rounded-full">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-{{ $warna['bg'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $warna['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <span>{{ $label }}</span>
                                    @if($pengaturan)
                                    <div class="time-range-info text-{{ $warna['bg'] }}-600 font-normal">
                                        {{ $pengaturan->waktu_100_start_formatted }} - {{ $pengaturan->waktu_70_end_formatted }}
                                    </div>
                                    @endif
                                </div>
                            </th>
                            @endforeach
                            
                            <th class="px-3 sm:px-4 py-3 text-center text-xs font-bold text-primary uppercase tracking-wider table-header">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="bg-green-100 p-1 sm:p-2 rounded-full">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span>Total</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paginatedData as $tanggal => $data)
                        @php
                            $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
                            $isPast = \Carbon\Carbon::parse($tanggal)->isPast() && !$isToday;
                            $carbonDate = \Carbon\Carbon::parse($tanggal);
                            
                            // Format hari Indonesia
                            $namaHariSingkat = [
                                'Sunday' => 'Min',
                                'Monday' => 'Sen',
                                'Tuesday' => 'Sel',
                                'Wednesday' => 'Rab',
                                'Thursday' => 'Kam',
                                'Friday' => 'Jum',
                                'Saturday' => 'Sab'
                            ][$carbonDate->format('l')];
                        @endphp
                        <tr class="{{ $isToday ? 'today-row' : ($isPast ? 'past-row' : 'hover:bg-primary-50') }} transition-colors">
                            <td class="px-3 sm:px-4 py-3 whitespace-nowrap text-sm font-medium text-primary sticky left-0 bg-white mobile-date-header">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold">{{ $carbonDate->format('d') }}</span>
                                        <span class="text-xs text-primary-600">{{ $namaHariSingkat }}</span>
                                        @if($isToday)
                                        <span class="today-badge">HARI INI</span>
                                        @endif
                                    </div>
                                    @if($isPast && $data['total_nilai'] == 0)
                                    <span class="text-xs text-red-500 mt-1">Belum diisi</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Sel Dinamis untuk setiap jenis makanan -->
                            @foreach(['sarapan', 'makan_siang', 'makan_malam'] as $jenis)
                            @php
                                $warna = [
                                    'sarapan' => ['bg' => 'blue', 'border' => 'blue'],
                                    'makan_siang' => ['bg' => 'green', 'border' => 'green'],
                                    'makan_malam' => ['bg' => 'indigo', 'border' => 'indigo']
                                ][$jenis];
                                $pengaturan = $pengaturanWaktu->where('jenis_makanan', $jenis)->first();
                            @endphp
                            <td class="px-2 sm:px-4 py-3 text-center table-cell">
                                @if($data[$jenis])
                                    <div class="table-cell-hover inline-block bg-gradient-to-br from-{{ $warna['bg'] }}-50 to-{{ $warna['bg'] }}-100 border-2 border-{{ $warna['border'] }}-300 rounded-lg p-2 sm:p-3 min-w-[120px] sm:min-w-[150px] transition-all duration-200 cursor-pointer group">
                                        @if($data[$jenis]->dokumentasi_foto)
                                            <img src="{{ $data[$jenis]->foto_url }}" alt="{{ $jenis }}" class="w-full h-16 sm:h-20 object-cover rounded-md mb-2">
                                        @else
                                            <div class="w-full h-16 sm:h-20 bg-{{ $warna['bg'] }}-200 rounded-md mb-2 flex items-center justify-center">
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-{{ $warna['bg'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <p class="text-xs font-semibold text-gray-700 mb-1 truncate">{{ $data[$jenis]->waktu_makan_formatted }}</p>
                                        <span class="badge-nilai badge-{{ $data[$jenis]->nilai }}">{{ $data[$jenis]->nilai }}</span>
                                        @if($pengaturan)
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if($data[$jenis]->nilai == $pengaturan->nilai_100)
                                                Tepat Waktu
                                            @elseif($data[$jenis]->nilai == $pengaturan->nilai_70)
                                                Sedikit Telat
                                            @else
                                                Terlambat
                                            @endif
                                        </p>
                                        @endif
                                        <div class="mt-2 flex gap-1 justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('makan-sehat.show', $data[$jenis]->id) }}" class="text-primary hover:text-blue-700" title="Lihat">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('makan-sehat.edit', $data[$jenis]->id) }}" class="text-secondary hover:text-green-700" title="Edit">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @elseif($data['status_' . $jenis] == 'belum_waktunya')
                                    <div class="inline-block bg-gradient-to-br from-{{ $warna['bg'] }}-50 to-{{ $warna['bg'] }}-100 border-2 border-dashed border-{{ $warna['border'] }}-300 rounded-lg p-2 sm:p-3 min-w-[120px] sm:min-w-[150px]">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto text-{{ $warna['bg'] }}-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-xs text-{{ $warna['bg'] }}-600 font-medium">Belum Waktunya</p>
                                    </div>
                                @elseif($data['status_' . $jenis] == 'tidak_bisa_diisi')
                                    <div class="inline-block bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-2 sm:p-3 min-w-[120px] sm:min-w-[150px] disabled-cell">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <p class="text-xs text-gray-500 font-medium">Tidak Bisa Diisi</p>
                                    </div>
                                @else
                                    <a href="{{ route('makan-sehat.create-by-type', $jenis) }}?tanggal={{ $tanggal }}" 
                                    class="inline-block bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 rounded-lg p-2 sm:p-3 min-w-[120px] sm:min-w-[150px] hover:border-{{ $warna['border'] }}-400 hover:from-{{ $warna['bg'] }}-50 hover:to-{{ $warna['bg'] }}-100 transition-all duration-200 group">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto text-gray-400 group-hover:text-{{ $warna['bg'] }}-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p class="text-xs text-gray-500 group-hover:text-{{ $warna['bg'] }}-600 font-medium transition-colors">Tambah Data</p>
                                    </a>
                                @endif
                            </td>
                            @endforeach

                            <!-- Total -->
                            <td class="px-2 sm:px-4 py-3 text-center">
                                <div class="inline-flex flex-col items-center gap-1">
                                    <span class="text-xl sm:text-2xl font-bold {{ $data['total_nilai'] >= 250 ? 'text-green-600' : ($data['total_nilai'] >= 150 ? 'text-yellow-600' : 'text-gray-400') }}">
                                        {{ $data['total_nilai'] }}
                                    </span>
                                    <span class="text-xs font-medium px-2 py-1 rounded-full {{ $data['total_nilai'] >= 250 ? 'bg-green-100 text-green-700' : ($data['total_nilai'] >= 150 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                        {{ $data['jumlah_makan'] }}/3
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-400 mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum ada data</h3>
                                <p class="text-gray-500 mb-3 sm:mb-4 text-sm">Belum ada data makan sehat untuk bulan ini.</p>
                                <a href="{{ route('makan-sehat.create') }}" 
                                class="inline-flex items-center bg-primary text-white font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Tambah Data Pertama
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($paginatedData->hasPages())
            <div class="bg-white px-3 sm:px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-gray-700">
                        Menampilkan 
                        <span class="font-medium">{{ ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + 1 }}</span>
                        -
                        <span class="font-medium">{{ min($paginatedData->currentPage() * $paginatedData->perPage(), $paginatedData->total()) }}</span>
                        dari
                        <span class="font-medium">{{ $paginatedData->total() }}</span>
                        hari
                        @if($paginatedData->currentPage() == 1)
                        <span class="text-green-600 font-bold">(Hari ini & harus diisi)</span>
                        @else
                        <span class="text-gray-500">(Hari sebelumnya)</span>
                        @endif
                    </div>
                    <div class="flex gap-1">
                        {{ $paginatedData->links() }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Legenda -->
            <div class="p-4 sm:p-6 bg-primary-50 border-t border-primary-200">
                <div class="legend-container grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 text-sm">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="bg-green-100 rounded-lg p-1 sm:p-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-xs sm:text-sm">Nilai 100</p>
                            <p class="text-xs text-gray-600">Makan tepat waktu</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="bg-yellow-100 rounded-lg p-1 sm:p-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-xs sm:text-sm">Nilai 70</p>
                            <p class="text-xs text-gray-600">Sedikit terlambat</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="bg-red-100 rounded-lg p-1 sm:p-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-xs sm:text-sm">Nilai 50</p>
                            <p class="text-xs text-gray-600">Terlambat</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="bg-blue-100 rounded-lg p-1 sm:p-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary text-xs sm:text-sm">Tambah Data</p>
                            <p class="text-xs text-gray-600">Klik untuk isi data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tampilan Kartu Mobile -->
        <div class="mobile-card bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 slide-in" style="animation-delay: 0.3s;">
            <div class="p-4 sm:p-6 bg-gradient-primary text-white">
                <div class="flex flex-col gap-3">
                    <h2 class="text-lg font-bold">Rekap Harian - {{ $namaBulan }} {{ $tahun }}</h2>
                    <div class="text-xs bg-white/20 px-3 py-1 rounded-full self-start">
                        Hari ini diurutkan paling atas
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($paginatedData as $tanggal => $data)
                @php
                    $isToday = \Carbon\Carbon::parse($tanggal)->isToday();
                    $isPast = \Carbon\Carbon::parse($tanggal)->isPast() && !$isToday;
                    $carbonDate = \Carbon\Carbon::parse($tanggal);
                    
                    // Format hari Indonesia lengkap
                    $namaHariPanjang = [
                        'Sunday' => 'Minggu',
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu'
                    ][$carbonDate->format('l')];
                    
                    // Format bulan Indonesia
                    $bulanIndonesia = [
                        'January' => 'Januari',
                        'February' => 'Februari',
                        'March' => 'Maret',
                        'April' => 'April',
                        'May' => 'Mei',
                        'June' => 'Juni',
                        'July' => 'Juli',
                        'August' => 'Agustus',
                        'September' => 'September',
                        'October' => 'Oktober',
                        'November' => 'November',
                        'December' => 'Desember'
                    ][$carbonDate->format('F')];
                @endphp
                <div class="p-4 {{ $isToday ? 'today-row' : '' }} transition-colors">
                    <div class="mobile-date-header mb-4 pb-3 border-b border-gray-200">
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-lg font-bold text-primary">{{ $carbonDate->format('d') }} {{ $bulanIndonesia }} {{ $carbonDate->format('Y') }}</span>
                                @if($isToday)
                                <span class="today-badge">HARI INI</span>
                                @endif
                            </div>
                            <span class="text-sm text-primary-600">{{ $namaHariPanjang }}</span>
                            @if($isPast && $data['total_nilai'] == 0)
                            <span class="text-xs text-red-500 mt-1">Belum diisi</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @foreach(['sarapan', 'makan_siang', 'makan_malam'] as $jenis)
                        @php
                            $warna = [
                                'sarapan' => ['bg' => 'blue', 'text' => 'blue'],
                                'makan_siang' => ['bg' => 'green', 'text' => 'green'],
                                'makan_malam' => ['bg' => 'indigo', 'text' => 'indigo']
                            ][$jenis];
                            $label = [
                                'sarapan' => 'Sarapan',
                                'makan_siang' => 'Makan Siang',
                                'makan_malam' => 'Makan Malam'
                            ][$jenis];
                            $pengaturan = $pengaturanWaktu->where('jenis_makanan', $jenis)->first();
                        @endphp
                        <div class="mobile-meal-card bg-gradient-to-br from-{{ $warna['bg'] }}-50 to-{{ $warna['bg'] }}-100 border-2 border-{{ $warna['bg'] }}-300 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                                    <div class="bg-{{ $warna['bg'] }}-100 p-1 rounded-full">
                                        <svg class="w-4 h-4 text-{{ $warna['bg'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ [
                                                'sarapan' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
                                                'makan_siang' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
                                                'makan_malam' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'
                                            ][$jenis] }}"></path>
                                        </svg>
                                    </div>
                                    {{ $label }}
                                </h3>
                            </div>
                            
                            @if($data[$jenis])
                                <div class="flex items-center gap-3">
                                    @if($data[$jenis]->dokumentasi_foto)
                                        <img src="{{ $data[$jenis]->foto_url }}" alt="{{ $label }}" class="mobile-meal-image w-16 h-16 object-cover rounded-md">
                                    @else
                                        <div class="mobile-meal-image w-16 h-16 bg-{{ $warna['bg'] }}-200 rounded-md flex items-center justify-center">
                                            <svg class="w-6 h-6 text-{{ $warna['bg'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700 mb-1">{{ $data[$jenis]->waktu_makan_formatted }}</p>
                                        <span class="badge-nilai badge-{{ $data[$jenis]->nilai }}">{{ $data[$jenis]->nilai }}</span>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('makan-sehat.show', $data[$jenis]->id) }}" class="text-primary hover:text-blue-700" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('makan-sehat.edit', $data[$jenis]->id) }}" class="text-secondary hover:text-green-700" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @elseif($data['status_' . $jenis] == 'belum_waktunya')
                                <div class="flex items-center justify-center gap-2 py-2">
                                    <svg class="w-5 h-5 text-{{ $warna['bg'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-{{ $warna['bg'] }}-600 font-medium">Belum Waktunya</p>
                                </div>
                            @elseif($data['status_' . $jenis] == 'tidak_bisa_diisi')
                                <div class="flex items-center justify-center gap-2 py-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    <p class="text-sm text-gray-500 font-medium">Tidak Bisa Diisi</p>
                                </div>
                            @else
                                <a href="{{ route('makan-sehat.create-by-type', $jenis) }}?tanggal={{ $tanggal }}" 
                                class="flex items-center justify-center gap-2 py-2 text-gray-500 hover:text-{{ $warna['bg'] }}-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-sm font-medium">Tambah Data</p>
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Mobile -->
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-700">Total Hari Ini</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-bold {{ $data['total_nilai'] >= 250 ? 'text-green-600' : ($data['total_nilai'] >= 150 ? 'text-yellow-600' : 'text-gray-400') }}">
                                    {{ $data['total_nilai'] }}
                                </span>
                                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $data['total_nilai'] >= 250 ? 'bg-green-100 text-green-700' : ($data['total_nilai'] >= 150 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                                    {{ $data['jumlah_makan'] }}/3
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="text-base font-medium text-gray-900 mb-2">Belum ada data</h3>
                    <p class="text-gray-500 mb-4 text-sm">Belum ada data makan sehat untuk bulan ini.</p>
                    <a href="{{ route('makan-sehat.create') }}" 
                    class="inline-flex items-center bg-primary text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Data Pertama
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Pagination Mobile -->
            @if($paginatedData->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col items-center justify-between gap-3">
                    <div class="text-sm text-gray-700 text-center">
                        Menampilkan 
                        <span class="font-medium">{{ ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + 1 }}</span>
                        -
                        <span class="font-medium">{{ min($paginatedData->currentPage() * $paginatedData->perPage(), $paginatedData->total()) }}</span>
                        dari
                        <span class="font-medium">{{ $paginatedData->total() }}</span>
                        hari
                        @if($paginatedData->currentPage() == 1)
                        <span class="text-green-600 font-bold block mt-1">(Hari ini & harus diisi)</span>
                        @else
                        <span class="text-gray-500 block mt-1">(Hari sebelumnya)</span>
                        @endif
                    </div>
                    <div class="flex gap-1">
                        {{ $paginatedData->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        // Animasi untuk elemen yang masuk
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.slide-in');
            animatedElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
            });
        });

        // Fungsi untuk menerapkan filter
        function applyFilters() {
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            const siswaId = document.getElementById('siswa_id') ? document.getElementById('siswa_id').value : '';
            
            let url = `?month=${month}&year=${year}`;
            if (siswaId) {
                url += `&siswa_id=${siswaId}`;
            }
            
            window.location.href = url;
        }

        // Perbarui waktu real-time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const currentTime = `${hours}:${minutes}`;
            
            console.log('Waktu sekarang:', currentTime);
        }

        setInterval(updateTime, 60000);
        updateTime();
    </script>
</body>
</html>
@endsection