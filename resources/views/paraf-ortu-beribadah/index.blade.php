@extends('layouts.app')

@section('title', 'Paraf Orangtua - Beribadah')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }

    /* Glass Card Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Custom Select Arrow */
    .select-arrow {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230033A0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
    }

    /* Focus Styles */
    input:focus, select:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
        outline: none;
    }

    /* Loading Spinner */
    .loading-spinner {
        border: 2px solid #f3f3f3;
        border-top: 2px solid #2563eb;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Custom Checkbox */
    .checkbox-custom {
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .checkbox-custom:checked {
        background-color: #2563eb;
        border-color: #2563eb;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: center;
    }

    /* Custom Radio */
    .radio-custom {
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .radio-custom:checked {
        border-color: #2563eb;
        background-color: white;
        box-shadow: inset 0 0 0 4px #2563eb;
    }

    /* Stat Cards */
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    /* Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    .badge-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .badge-light {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }

    /* Modal Overlay */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(4px);
        z-index: 40;
        animation: fadeIn 0.3s ease-out;
    }

    /* Table Styles */
    .table-row-hover:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }

    /* Toast */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: fadeIn 0.3s ease-out;
    }

    .toast-success {
        background-color: #10b981;
        color: white;
    }

    .toast-error {
        background-color: #ef4444;
        color: white;
    }
</style>

<div class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <!-- Breadcrumb & Menu -->
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-blue-600 to-green-500 bg-clip-text text-transparent mb-2">
                        @if($userInfo['isOrangtua'])
                            Dashboard Orangtua
                        @elseif($userInfo['isSiswa'])
                            Dashboard Siswa
                        @else
                            Paraf Orangtua - Laporan Beribadah
                        @endif
                    </h1>
                    <div class="flex items-center text-gray-500 text-sm">
                        @if($userInfo['isOrangtua'])
                            <a href="{{ route('orangtua.dashboard') }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                Dashboard Orangtua
                            </a>
                        @elseif($userInfo['isSiswa'])
                            <a href="{{ route('siswa.dashboard') }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                Dashboard Siswa
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                Dashboard
                            </a>
                        @endif
                        <span class="mx-2">/</span>
                        <span class="text-gray-600">Paraf Beribadah</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($userInfo['isOrangtua'])
                        <a href="{{ route('orangtua.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    @elseif($userInfo['isSiswa'])
                        <a href="{{ route('siswa.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    @endif
                </div>
            </div>

            <!-- Info Box berdasarkan User -->
            @if($userInfo['isOrangtua'])
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 p-4 rounded-xl fade-in">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-2">Informasi Orangtua</h5>
                        <p class="text-gray-700">
                            <strong>Halo, {{ $userInfo['orangtua']->nama_ayah ?? $userInfo['orangtua']->nama_ibu ?? Session::get('orangtua_nama', 'Orangtua') }}!</strong><br>
                            Anda sedang melihat data <strong>anak Anda</strong>: 
                            <strong>{{ $userInfo['siswaAnak']->nama_lengkap ?? '' }}</strong> 
                            (NIS: {{ $userInfo['siswaAnak']->nis ?? '' }}, 
                            Kelas: {{ $userInfo['siswaAnak']->kelas->nama_kelas ?? '-' }})
                        </p>
                    </div>
                </div>
            </div>
            @elseif($userInfo['isSiswa'])
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 p-4 rounded-xl fade-in">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h5 class="font-bold text-gray-900 mb-2">Informasi Siswa</h5>
                        <p class="text-gray-700">
                            <strong>Halo, {{ $userInfo['siswaAnak']->nama_lengkap ?? 'Siswa' }}!</strong><br>
                            Anda sedang melihat data beribadah <strong>Anda sendiri</strong>.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Flash Messages -->
            @if (session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-gray-900 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-gray-900 font-medium">{{ session('error') }}</p>
            </div>
            @endif
        </div>

        <!-- Filter Section -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <!-- Tanggal -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" value="{{ $selectedTanggal }}" max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 transition-all duration-300 text-gray-900">
                </div>
                
                <!-- Cari Siswa -->
                <div class="md:col-span-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" placeholder="Nama atau NIS siswa..." value="{{ $search }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 transition-all duration-300 text-gray-900"
                               @if($userInfo['isOrangtua'] || $userInfo['isSiswa']) disabled @endif>
                        <div class="absolute right-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    @if($userInfo['isOrangtua'] || $userInfo['isSiswa'])
                    <p class="text-xs text-gray-500 mt-1">Pencarian dinonaktifkan</p>
                    @endif
                </div>
                
                <!-- Status Paraf -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Paraf</label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 transition-all duration-300 text-gray-900 select-arrow appearance-none">
                        <option value="">Semua Status</option>
                        <option value="belum" {{ $status == 'belum' ? 'selected' : '' }}>Belum Diparaf</option>
                        <option value="sebagian" {{ $status == 'sebagian' ? 'selected' : '' }}>Sebagian Diparaf</option>
                        <option value="sudah" {{ $status == 'sudah' ? 'selected' : '' }}>Sudah Diparaf</option>
                    </select>
                </div>
                
                <!-- Tombol Filter -->
                <div class="md:col-span-2 flex items-end gap-2">
                    <button type="button" onclick="applyFilter()" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold px-5 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </button>
                    <button type="button" onclick="resetFilter()" class="bg-gray-100 text-gray-700 font-semibold px-5 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Menunggu Paraf -->
            <div class="stat-card bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $belumParaf }}</h4>
                        <p class="text-gray-600">Menunggu Paraf</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Sebagian Paraf -->
            <div class="stat-card bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $sebagianParaf }}</h4>
                        <p class="text-gray-600">Sebagian Paraf</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Sudah Diparaf -->
            <div class="stat-card bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900 mb-1">{{ $semuaParaf }}</h4>
                        <p class="text-gray-600">Sudah Diparaf</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618 4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in">
            @if($beribadahs->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-3">
                    @if($userInfo['isOrangtua'])
                        Tidak ada data beribadah untuk anak Anda
                    @elseif($userInfo['isSiswa'])
                        Tidak ada data beribadah untuk Anda
                    @else
                        Tidak ada data beribadah
                    @endif
                </h3>
                <p class="text-gray-500 mb-6">
                    pada tanggal {{ \Carbon\Carbon::parse($selectedTanggal)->format('d/m/Y') }}
                </p>
                <a href="{{ route('paraf-ortu-beribadah.index') }}?tanggal={{ date('Y-m-d') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow hover:shadow-md transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Lihat Tanggal Hari Ini
                </a>
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-50 to-green-50">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">NO</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">SISWA</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">SHOLAT BELUM PARAF</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $counter = 1; @endphp
                        @foreach($beribadahs as $beribadah)
                        @php
                            $siswa = $beribadah->siswa;
                            if (!$siswa) continue;
                            
                            // Format tanggal
                            $tanggalFormatted = '-';
                            if ($beribadah->tanggal) {
                                try {
                                    $tanggalFormatted = \Carbon\Carbon::parse($beribadah->tanggal)->format('d/m/Y');
                                } catch (\Exception $e) {
                                    $tanggalFormatted = $beribadah->tanggal;
                                }
                            }
                            
                            // Cari sholat yang belum diparaf
                            $unparafedSholats = [];
                            $parafedSholats = [];
                            $sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
                            
                            foreach ($sholats as $sholat) {
                                $waktuField = $sholat . '_waktu';
                                $parafField = $sholat . '_paraf';
                                $parafNamaField = $sholat . '_paraf_nama';
                                
                                if ($beribadah->$waktuField) {
                                    // Format waktu
                                    $waktu = $beribadah->$waktuField;
                                    if (is_string($waktu)) {
                                        try {
                                            $waktu = \Carbon\Carbon::parse($waktu)->format('H:i');
                                        } catch (\Exception $e) {
                                            $waktu = substr($waktu, 0, 5);
                                        }
                                    }
                                    
                                    $label = $sholatLabels[$sholat] ?? $sholat;
                                    $parafNama = $beribadah->$parafNamaField;
                                    
                                    if (!$beribadah->$parafField) {
                                        $unparafedSholats[] = [
                                            'nama' => $label,
                                            'waktu' => $waktu
                                        ];
                                    } else {
                                        $parafedSholats[] = $label . ($parafNama ? " ✓" : "");
                                    }
                                }
                            }
                            
                            $hasUnparafedSholats = !empty($unparafedSholats);
                            $canParaf = $userInfo['isOrangtua'] || $userInfo['isAdmin'] || $userInfo['isGuruWali'];
                        @endphp
                        <tr class="table-row-hover">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ $counter++ }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $siswa->nama_lengkap }}</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</span>
                                            <span class="text-xs text-gray-500">Tanggal: {{ $tanggalFormatted }}</span>
                                            @if($siswa->kelas)
                                            <span class="text-xs text-gray-500">Kelas: {{ $siswa->kelas->nama_kelas }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                @if(empty($unparafedSholats) && !empty($parafedSholats))
                                    <div class="inline-flex items-center gap-2">
                                        <span class="badge badge-success">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Semua sudah diparaf
                                        </span>
                                    </div>
                                    @if(!empty($parafedSholats))
                                        <div class="mt-2">
                                            <p class="text-xs text-gray-600">
                                                <span class="text-green-600 font-medium">Sudah:</span>
                                                {{ implode(', ', $parafedSholats) }}
                                            </p>
                                        </div>
                                    @endif
                                @elseif(!empty($unparafedSholats))
                                    <div class="space-y-2">
                                        @foreach($unparafedSholats as $sholatData)
                                            <div class="badge badge-warning">
                                                {{ $sholatData['nama'] }} ({{ $sholatData['waktu'] }})
                                            </div>
                                        @endforeach
                                        
                                        @if(!empty($parafedSholats))
                                            <div class="mt-2">
                                                <p class="text-xs text-green-600 font-medium">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Sudah: {{ implode(', ', $parafedSholats) }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Belum ada sholat yang diisi</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    @if($canParaf)
                                    <button type="button" onclick="showParafModal({{ $beribadah->id }})"
                                            class="bg-gradient-to-r {{ $hasUnparafedSholats ? 'from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700' : 'from-gray-300 to-gray-400 cursor-not-allowed' }} text-white font-medium text-sm px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2"
                                            {{ !$hasUnparafedSholats ? 'disabled' : '' }}
                                            title="{{ !$hasUnparafedSholats ? 'Semua sholat sudah diparaf' : 'Beri paraf' }}">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ $hasUnparafedSholats ? 'Beri Paraf' : 'Sudah Paraf' }}
                                    </button>
                                    @endif
                                    
                                    <button type="button" onclick="showDetailModal({{ $beribadah->id }})"
                                            class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium text-sm px-4 py-2 rounded-lg transition-all duration-300 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Paraf -->
<div id="parafModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeParafModal()"></div>
        
        <div class="relative inline-block w-full max-w-2xl p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            <div class="bg-gradient-to-r from-blue-600 to-green-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-white">Beri Paraf Beribadah</h3>
                    </div>
                    <button type="button" onclick="closeParafModal()" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-0">
                <div id="modalLoading" class="px-6 py-12 text-center">
                    <div class="loading-spinner mx-auto mb-4" style="width: 40px; height: 40px;"></div>
                    <p class="text-gray-500">Memuat data...</p>
                </div>
                <div id="modalContent" class="hidden">
                    <!-- Data akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        
        <div class="relative inline-block w-full max-w-4xl p-0 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">Detail Beribadah</h3>
                    <button type="button" onclick="closeDetailModal()" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-0">
                <div id="detailLoading" class="px-6 py-12 text-center">
                    <div class="loading-spinner mx-auto mb-4" style="width: 40px; height: 40px;"></div>
                    <p class="text-gray-500">Memuat data...</p>
                </div>
                <div id="detailContent" class="hidden p-6">
                    <!-- Data akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <button type="button" onclick="closeDetailModal()" class="w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
// Konstanta dan variabel global
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const sholatLabels = @json($sholatLabels);
const userInfo = @json($userInfo);
const baseUrl = '{{ url("/") }}';

// Debug info
console.log('Page loaded with user info:', userInfo);

// ==================== FUNGSI UTAMA ====================

// Fungsi untuk apply filter
function applyFilter() {
    console.log('Applying filter...');
    
    const tanggal = document.getElementById('tanggal').value;
    const search = document.getElementById('search').value;
    const status = document.getElementById('status').value;
    
    // Build URL dengan parameter
    let url = '{{ route("paraf-ortu-beribadah.index") }}?';
    const params = [];
    
    if (tanggal) {
        params.push(`tanggal=${encodeURIComponent(tanggal)}`);
    }
    
    if (search && (userInfo.isAdmin || userInfo.isGuruWali)) {
        params.push(`search=${encodeURIComponent(search)}`);
    }
    
    if (status) {
        params.push(`status=${encodeURIComponent(status)}`);
    }
    
    if (params.length > 0) {
        url += params.join('&');
    }
    
    console.log('Redirecting to:', url);
    window.location.href = url;
}

// Fungsi untuk reset filter
function resetFilter() {
    console.log('Resetting filter...');
    window.location.href = '{{ route("paraf-ortu-beribadah.index") }}';
}

// Fungsi untuk show modal paraf
function showParafModal(beribadahId) {
    console.log('Opening paraf modal for ID:', beribadahId);
    
    // Reset modal state
    document.getElementById('modalContent').innerHTML = '';
    document.getElementById('modalContent').classList.add('hidden');
    document.getElementById('modalLoading').classList.remove('hidden');
    
    // Show modal
    document.getElementById('parafModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load data
    loadParafModalData(beribadahId);
}

// Fungsi untuk show modal detail
function showDetailModal(beribadahId) {
    console.log('Opening detail modal for ID:', beribadahId);
    
    // Reset modal state
    document.getElementById('detailContent').innerHTML = '';
    document.getElementById('detailContent').classList.add('hidden');
    document.getElementById('detailLoading').classList.remove('hidden');
    
    // Show modal
    document.getElementById('detailModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load data
    loadDetailModalData(beribadahId);
}

// Fungsi untuk close modal paraf
function closeParafModal() {
    console.log('Closing paraf modal');
    document.getElementById('parafModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Fungsi untuk close modal detail
function closeDetailModal() {
    console.log('Closing detail modal');
    document.getElementById('detailModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ==================== FUNGSI LOAD DATA ====================

// Load data untuk modal paraf
async function loadParafModalData(beribadahId) {
    try {
        console.log('Loading paraf data for ID:', beribadahId);
        
        // Gunakan route yang benar
        const url = `{{ route('paraf-ortu-beribadah.get-detail', '') }}/${beribadahId}`;
        console.log('Fetching from URL:', url);
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            let errorMsg = `HTTP error! status: ${response.status}`;
            if (response.status === 403) {
                errorMsg = 'Anda tidak berhak mengakses data ini';
            } else if (response.status === 404) {
                errorMsg = 'Data tidak ditemukan';
            }
            throw new Error(errorMsg);
        }
        
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.success) {
            // Hide loading, show content
            document.getElementById('modalLoading').classList.add('hidden');
            const modalContent = document.getElementById('modalContent');
            modalContent.classList.remove('hidden');
            modalContent.innerHTML = createParafForm(result.data);
            
            // Initialize form
            initializeParafForm();
        } else {
            throw new Error(result.message || 'Gagal mengambil data');
        }
        
    } catch (error) {
        console.error('Error loading modal data:', error);
        
        // Show error message in modal
        document.getElementById('modalLoading').classList.add('hidden');
        const modalContent = document.getElementById('modalContent');
        modalContent.classList.remove('hidden');
        
        modalContent.innerHTML = `
            <div class="p-8 text-center">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Gagal Memuat Data</h3>
                <p class="text-gray-500 text-sm mb-6">${error.message}</p>
                <div class="flex gap-2 justify-center">
                    <button onclick="closeParafModal()" class="px-4 py-2 bg-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-300">
                        Tutup
                    </button>
                    <button onclick="loadParafModalData(${beribadahId})" class="px-4 py-2 bg-blue-500 rounded-lg text-white font-medium hover:bg-blue-600">
                        Coba Lagi
                    </button>
                </div>
            </div>
        `;
    }
}

// Load data untuk modal detail
async function loadDetailModalData(beribadahId) {
    try {
        console.log('Loading detail data for ID:', beribadahId);
        
        const url = `{{ route('paraf-ortu-beribadah.get-detail', '') }}/${beribadahId}`;
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Hide loading, show content
            document.getElementById('detailLoading').classList.add('hidden');
            const detailContent = document.getElementById('detailContent');
            detailContent.classList.remove('hidden');
            detailContent.innerHTML = createDetailContent(result.data);
        } else {
            throw new Error(result.message || 'Gagal mengambil data');
        }
        
    } catch (error) {
        console.error('Error loading detail data:', error);
        
        // Show error message
        document.getElementById('detailLoading').classList.add('hidden');
        const detailContent = document.getElementById('detailContent');
        detailContent.classList.remove('hidden');
        
        detailContent.innerHTML = `
            <div class="p-8 text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-700 mb-2">Gagal Memuat Data</h3>
                <p class="text-gray-500 text-sm">${error.message}</p>
            </div>
        `;
    }
}

// ==================== FUNGSI CREATE HTML ====================

// Create form untuk modal paraf
function createParafForm(data) {
    console.log('Creating paraf form with data:', data);
    
    const sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
    let availableSholats = 0;
    
    // Count available sholats
    sholats.forEach(sholat => {
        if (data.sholat_data[sholat] && data.sholat_data[sholat].waktu !== '-' && !data.sholat_data[sholat].sudah_paraf) {
            availableSholats++;
        }
    });
    
    let html = `
        <form id="parafForm" method="POST" action="{{ route('paraf-ortu-beribadah.store') }}">
            @csrf
            <div class="space-y-6 p-6">
                <!-- Header Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Siswa</label>
                        <p class="font-semibold text-gray-900">${data.siswa_nama}</p>
                        <p class="text-sm text-gray-600">NIS: ${data.siswa_nis} | Kelas: ${data.siswa_kelas}</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <p class="font-semibold text-gray-900">${data.tanggal}</p>
                        <p class="text-sm text-gray-600">${data.hari}</p>
                    </div>
                </div>

                <!-- Hidden input for beribadahs_id -->
                <input type="hidden" name="beribadahs_id" value="${data.id}">

                <!-- Nama Orangtua -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Nama Orangtua <span class="text-red-500">*</span>
                    </label>
                    <div id="orangtuaOptions" class="space-y-2">
    `;
    
    if (data.orangtua_options && data.orangtua_options.length > 0) {
        data.orangtua_options.forEach((option, index) => {
            html += `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer" 
                     onclick="selectOrangtuaOption('ortu_${index}')">
                    <input type="radio" name="nama_ortu" value="${option.value}" 
                           id="ortu_${index}" data-type="${option.type}" 
                           class="radio-custom" ${index === 0 ? 'checked' : ''}>
                    <label for="ortu_${index}" class="flex-1 cursor-pointer">
                        <span class="font-medium text-gray-900">${option.label}</span>
                    </label>
                </div>
            `;
        });
    } else {
        html += `
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <input type="radio" name="nama_ortu" value="orangtua_lainnya" 
                       id="ortu_0" data-type="lainnya" class="radio-custom" checked>
                <label for="ortu_0" class="flex-1">
                    <span class="font-medium text-gray-900">Orang Tua/Wali</span>
                </label>
            </div>
        `;
    }
    
    html += `
                    </div>
                    <div id="namaLainnyaContainer" class="mt-3 hidden">
                        <label for="nama_ortu_lainnya" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Orang Tua/Wali <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_ortu_lainnya" 
                               placeholder="Masukkan nama orang tua/wali..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-blue-500 transition-all duration-300 text-gray-900">
                    </div>
                    <p id="ortuError" class="mt-2 text-sm text-red-600 hidden">Pilih nama orang tua</p>
                </div>

                <!-- Pilih Sholat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Pilih Sholat untuk Diparaf <span class="text-red-500">*</span>
                    </label>
    `;
    
    if (availableSholats === 0) {
        html += `
            <div class="p-4 bg-green-50 rounded-lg text-center">
                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-green-700 font-medium">Semua sholat sudah diparaf</p>
                <p class="text-sm text-gray-600 mt-1">Tidak ada sholat yang membutuhkan paraf</p>
            </div>
        `;
    } else {
        html += `
            <div id="sholatList" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        `;
        
        sholats.forEach(sholat => {
            const sholatData = data.sholat_data[sholat] || {};
            const label = sholatData.label || sholatLabels[sholat] || sholat;
            const waktu = sholatData.waktu || '-';
            const sudahParaf = sholatData.sudah_paraf || false;
            const nilai = sholatData.nilai || '0';
            
            if (waktu !== '-' && !sudahParaf) {
                html += `
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer" 
                         onclick="toggleSholatCheckbox('sholat_${sholat}')">
                        <input type="checkbox" name="jenis_sholat[]" value="${sholat}" 
                               id="sholat_${sholat}" class="checkbox-custom" checked>
                        <label for="sholat_${sholat}" class="flex-1 cursor-pointer">
                            <span class="font-medium text-gray-900">${label}</span><br>
                            <span class="text-sm text-gray-500">${waktu} | Nilai: ${nilai}</span>
                        </label>
                    </div>
                `;
            } else if (waktu !== '-' && sudahParaf) {
                html += `
                    <div class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg opacity-75">
                        <input type="checkbox" disabled class="checkbox-custom">
                        <label class="flex-1">
                            <span class="font-medium text-gray-500">${label}</span><br>
                            <span class="text-sm text-gray-400">
                                ${waktu} | ✓ ${sholatData.paraf_nama || 'Sudah diparaf'}
                            </span>
                        </label>
                    </div>
                `;
            } else {
                html += `
                    <div class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg opacity-75">
                        <input type="checkbox" disabled class="checkbox-custom">
                        <label class="flex-1">
                            <span class="font-medium text-gray-500">${label}</span><br>
                            <span class="text-sm text-gray-400">Belum dilaksanakan</span>
                        </label>
                    </div>
                `;
            }
        });
        
        html += `
            </div>
            <p id="sholatError" class="mt-2 text-sm text-red-600 hidden">Pilih minimal satu sholat</p>
        `;
    }
    
    html += `
                </div>

              
            <div class="px-6 py-4 bg-gray-50 flex gap-2">
                <button type="button" onclick="closeParafModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition-colors">
                    Batal
                </button>
                <button type="submit" id="submitParafBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-green-500 text-white rounded-xl text-sm font-semibold hover:from-blue-600 hover:to-green-600 transition-all duration-300 shadow hover:shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span id="submitBtnText">Simpan Paraf</span>
                </button>
            </div>
        </form>
    `;
    
    return html;
}

// Create content untuk modal detail
function createDetailContent(data) {
    const sholats = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];
    
    let html = `
        <div class="space-y-6">
            <!-- Header Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Siswa -->
                <div class="p-5 bg-gradient-to-r from-blue-50 to-green-50 rounded-xl">
                    <h6 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Informasi Siswa
                    </h6>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Nama</span>
                            <span class="text-sm font-semibold text-gray-900">${data.siswa_nama}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">NIS</span>
                            <span class="text-sm font-semibold text-gray-900">${data.siswa_nis}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Kelas</span>
                            <span class="text-sm font-semibold text-gray-900">${data.siswa_kelas}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal</span>
                            <span class="text-sm font-semibold text-gray-900">${data.tanggal}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Hari</span>
                            <span class="text-sm font-semibold text-gray-900">${data.hari}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik -->
                <div class="p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                    <h6 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistik
                    </h6>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Sholat</span>
                            <span class="badge badge-info">${data.total_sholat || 0}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Nilai</span>
                            <span class="badge badge-info">${data.total_nilai || 0}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Rata-rata Nilai</span>
                            <span class="badge badge-info">
                                ${data.total_sholat > 0 ? (data.total_nilai / data.total_sholat).toFixed(1) : '0'}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Sholat -->
            <div>
                <h6 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Detail Sholat
                </h6>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Sholat</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Waktu</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Nilai</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Kategori</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Status Paraf</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Paraf Oleh</th>
                                <th class="p-3 text-left font-semibold text-gray-700 text-sm">Waktu Paraf</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
    `;
    
    sholats.forEach(sholat => {
        const sholatData = data.sholat_data[sholat] || {};
        const label = sholatData.label || sholatLabels[sholat] || sholat;
        
        let statusBadge = '';
        if (sholatData.sudah_paraf) {
            statusBadge = '<span class="badge badge-success">✓ Sudah</span>';
        } else if (sholatData.waktu !== '-') {
            statusBadge = '<span class="badge badge-warning">○ Belum</span>';
        } else {
            statusBadge = '<span class="badge badge-light">-</span>';
        }
        
        html += `
            <tr class="hover:bg-gray-50">
                <td class="p-3 font-medium text-gray-900">${label}</td>
                <td class="p-3 text-sm text-gray-700">${sholatData.waktu || '-'}</td>
                <td class="p-3 text-sm text-gray-700">${sholatData.nilai || '-'}</td>
                <td class="p-3 text-sm text-gray-700">${sholatData.kategori || '-'}</td>
                <td class="p-3">${statusBadge}</td>
                <td class="p-3 text-sm text-gray-700">${sholatData.paraf_nama || '-'}</td>
                <td class="p-3 text-sm text-gray-700">${sholatData.paraf_waktu || '-'}</td>
            </tr>
        `;
    });
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    return html;
}

// ==================== FUNGSI HELPER ====================

// Select orangtua option
function selectOrangtuaOption(id) {
    const radio = document.getElementById(id);
    if (radio) {
        radio.checked = true;
        radio.dispatchEvent(new Event('change'));
    }
}

// Toggle checkbox sholat
function toggleSholatCheckbox(id) {
    const checkbox = document.getElementById(id);
    if (checkbox && !checkbox.disabled) {
        checkbox.checked = !checkbox.checked;
    }
}

// Initialize paraf form
function initializeParafForm() {
    const parafForm = document.getElementById('parafForm');
    if (!parafForm) return;
    
    // Add submit event
    parafForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitParafForm();
    });
    
    // Add change event to orangtua radios
    const orangtuaRadios = document.querySelectorAll('input[name="nama_ortu"]');
    orangtuaRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const namaLainnyaContainer = document.getElementById('namaLainnyaContainer');
            if (this.dataset.type === 'lainnya' && this.checked) {
                namaLainnyaContainer.classList.remove('hidden');
                // Focus on input
                document.getElementById('nama_ortu_lainnya')?.focus();
            } else {
                namaLainnyaContainer.classList.add('hidden');
            }
        });
        
        // Trigger change on load
        if (radio.checked) {
            radio.dispatchEvent(new Event('change'));
        }
    });
}

// Submit paraf form
async function submitParafForm() {
    console.log('Submitting paraf form...');
    
    try {
        // Get form data
        const form = document.getElementById('parafForm');
        const formData = new FormData(form);
        
        // Validate sholat selection
        const sholatCheckboxes = document.querySelectorAll('input[name="jenis_sholat[]"]:checked:not(:disabled)');
        if (sholatCheckboxes.length === 0) {
            showError('Pilih minimal satu sholat untuk diparaf');
            const sholatError = document.getElementById('sholatError');
            if (sholatError) sholatError.classList.remove('hidden');
            return;
        }
        
        // Validate orangtua selection
        const orangtuaRadio = document.querySelector('input[name="nama_ortu"]:checked');
        if (!orangtuaRadio) {
            showError('Pilih nama orangtua');
            const ortuError = document.getElementById('ortuError');
            if (ortuError) ortuError.classList.remove('hidden');
            return;
        }
        
        // Handle "lainnya" option
        let namaOrtu = orangtuaRadio.value;
        if (namaOrtu === 'orangtua_lainnya') {
            const namaLainnyaInput = document.getElementById('nama_ortu_lainnya');
            if (!namaLainnyaInput || !namaLainnyaInput.value.trim()) {
                showError('Masukkan nama orang tua/wali');
                namaLainnyaInput?.focus();
                return;
            }
            namaOrtu = namaLainnyaInput.value.trim();
            formData.set('nama_ortu', namaOrtu);
        }
        
        console.log('Submitting data:', {
            beribadahs_id: formData.get('beribadahs_id'),
            nama_ortu: formData.get('nama_ortu'),
            jenis_sholat: formData.getAll('jenis_sholat[]'),
            catatan: formData.get('catatan')
        });
        
        // Disable submit button and show loading
        const submitBtn = document.getElementById('submitParafBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        const originalText = submitBtnText.textContent;
        submitBtn.disabled = true;
        submitBtnText.innerHTML = '<span class="loading-spinner mr-2"></span>Menyimpan...';
        
        // Send request
        const response = await fetch('{{ route("paraf-ortu-beribadah.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const result = await response.json();
        console.log('Submit response:', result);
        
        if (result.success) {
            showSuccess(result.message);
            setTimeout(() => {
                closeParafModal();
                location.reload();
            }, 1500);
        } else {
            if (result.errors) {
                // Handle validation errors
                let errorMessages = [];
                for (const field in result.errors) {
                    errorMessages = errorMessages.concat(result.errors[field]);
                }
                showError(errorMessages.join('<br>'));
            } else {
                showError(result.message || 'Gagal menyimpan paraf');
            }
        }
        
    } catch (error) {
        console.error('Error submitting form:', error);
        showError('Terjadi kesalahan: ' + error.message);
    } finally {
        // Re-enable submit button
        const submitBtn = document.getElementById('submitParafBtn');
        const submitBtnText = document.getElementById('submitBtnText');
        if (submitBtn) submitBtn.disabled = false;
        if (submitBtnText) submitBtnText.textContent = 'Simpan Paraf';
    }
}

// ==================== NOTIFICATION FUNCTIONS ====================

// Show success toast
function showSuccess(message) {
    showToast(message, 'success');
}

// Show error toast
function showError(message) {
    showToast(message, 'error');
}

// Show toast notification
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
    
    // Create new toast
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} fade-in`;
    
    const icon = type === 'success' 
        ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    
    toast.innerHTML = `
        ${icon}
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s ease';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }, 5000);
}

// ==================== EVENT LISTENERS ====================

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing...');
    
    // Enter key untuk search
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilter();
            }
        });
    }
    
    // Change event untuk tanggal
    const tanggalInput = document.getElementById('tanggal');
    if (tanggalInput) {
        tanggalInput.addEventListener('change', applyFilter);
    }
    
    // Escape key untuk modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeParafModal();
            closeDetailModal();
        }
    });
    
    // Click outside modal to close
    document.addEventListener('click', function(e) {
        if (e.target.id === 'parafModal') {
            closeParafModal();
        }
        if (e.target.id === 'detailModal') {
            closeDetailModal();
        }
    });
    
    // Auto hide flash messages
    setTimeout(() => {
        document.querySelectorAll('[class*="bg-gradient-to-r"]').forEach(alert => {
            if (alert.textContent.includes('success') || alert.textContent.includes('error')) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        });
    }, 5000);
    
    console.log('Initialization complete');
});
</script>
@endsection