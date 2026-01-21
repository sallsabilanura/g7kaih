@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
      
        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        @if($totalNotifikasi > 0)
                        <div class="absolute -top-2 -right-2">
                            <div class="relative">
                                <div class="animate-ping absolute -inset-1 bg-red-500 rounded-full opacity-75"></div>
                                <div class="relative bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $totalNotifikasi }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hai, {{ Session::get('orangtua_nama', 'Orang Tua') }}</h1>
                        @if($orangtua && $orangtua->siswa)
                        <p class="text-sm text-gray-600 mt-1">Monitor Aktivitas Anak <span class="font-semibold text-blue-600">{{ $orangtua->siswa->nama_lengkap }}</span></p>
                        @endif
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-sm text-gray-600">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Notifikasi Alert --}}
        @if($totalNotifikasi > 0)
        <div class="mb-6">
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-red-100 p-3 rounded-lg animate-pulse">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.73 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-900 text-lg">Ada {{ $totalNotifikasi }} Aktivitas Menunggu Paraf</h3>
                        <p class="text-red-700 text-sm">Silakan berikan paraf untuk menandai aktivitas anak Anda</p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-red-200 grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Bangun Pagi --}}
                    @if($bangunPagiMenunggu > 0)
                    <div class="bg-white rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-yellow-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900">Bangun Pagi</h4>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $bangunPagiMenunggu }}</span>
                        </div>
                        <div class="space-y-2 mb-3">
                            @foreach($bangunPagiBelumParaf->take(2) as $item)
                            <div class="flex items-center justify-between text-sm bg-yellow-50 p-2 rounded">
                                <span>{{ $item->tanggal_formatted }}</span>
                                <span class="font-medium text-yellow-700">{{ $item->pukul_formatted ?? '-' }}</span>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('paraf-ortu-bangun-pagi.index') }}" class="block w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">Paraf Sekarang</a>
                    </div>
                    @endif

                    {{-- Kegiatan Sosial --}}
                    @if($bermasyarakatMenunggu > 0)
                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-blue-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900">Kegiatan Sosial</h4>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $bermasyarakatMenunggu }}</span>
                        </div>
                        <div class="space-y-2 mb-3">
                            @foreach($bermasyarakatBelumParaf->take(2) as $item)
                            <div class="flex items-center justify-between text-sm bg-blue-50 p-2 rounded">
                                <span class="truncate max-w-[120px]">{{ $item->nama_kegiatan ?? 'Kegiatan' }}</span>
                                <span class="text-xs text-blue-700">{{ $item->tanggal_formatted }}</span>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('paraf-ortu-bermasyarakat.index') }}" class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">Paraf Sekarang</a>
                    </div>
                    @endif

                    {{-- Ibadah Sholat --}}
                    @if($beribadahMenunggu > 0)
                    <div class="bg-white rounded-lg p-4 border border-emerald-200">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="bg-emerald-100 p-2 rounded-lg">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-900">Ibadah Sholat</h4>
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $beribadahMenunggu }}</span>
                        </div>
                        <div class="space-y-2 mb-3 max-h-32 overflow-y-auto">
                            @forelse($beribadahBelumParaf->take(3) as $item)
                            <div class="bg-emerald-50 p-2 rounded">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $item['tanggal'] ?? '-' }}</span>
                                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">{{ $item['count'] ?? 0 }} sholat</span>
                                </div>
                                @if(isset($item['sholat_list']) && count($item['sholat_list']) > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($item['sholat_list'] as $s)
                                    <span class="text-xs bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded">{{ $s['label'] ?? '' }} {{ $s['waktu'] ?? '' }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @empty
                            <p class="text-sm text-gray-500 text-center py-2">Tidak ada data</p>
                            @endforelse
                        </div>
                        <a href="{{ route('paraf-ortu-beribadah.index') }}" class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium py-2 rounded-lg transition-colors">Paraf Sekarang</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="mb-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-green-900 text-lg">Semua Aktivitas Sudah Diparaf</h3>
                        <p class="text-green-700 text-sm">Tidak ada aktivitas yang menunggu paraf Anda saat ini.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Bangun Pagi Card --}}
            <div class="bg-gradient-to-br from-white to-yellow-50 rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    @if($bangunPagiMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $bangunPagiMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Bangun Pagi</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $bangunPagiMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu Paraf</p>
                <a href="{{ route('paraf-ortu-bangun-pagi.index') }}" class="mt-3 text-yellow-600 text-sm font-medium hover:text-yellow-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Kegiatan Sosial Card --}}
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    @if($bermasyarakatMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $bermasyarakatMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Kegiatan Sosial</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $bermasyarakatMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu Paraf</p>
                <a href="{{ route('paraf-ortu-bermasyarakat.index') }}" class="mt-3 text-blue-600 text-sm font-medium hover:text-blue-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Ibadah Sholat Card --}}
            <div class="bg-gradient-to-br from-white to-emerald-50 rounded-xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-emerald-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    @if($beribadahMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $beribadahMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Ibadah Sholat</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $beribadahMenunggu }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu Paraf</p>
                <a href="{{ route('paraf-ortu-beribadah.index') }}" class="mt-3 text-emerald-600 text-sm font-medium hover:text-emerald-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Total Bulan Ini Card --}}
            <div class="bg-gradient-to-br from-white to-violet-50 rounded-xl shadow-sm p-5">
                <div class="bg-violet-100 p-3 rounded-lg w-fit mb-4">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-gray-600 text-sm mb-2">Total Bulan Ini</h3>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Bangun Pagi</span>
                        <span class="font-bold text-gray-900">{{ $bangunPagiBulanIni }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kegiatan</span>
                        <span class="font-bold text-gray-900">{{ $bermasyarakatBulanIni }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ibadah</span>
                        <span class="font-bold text-gray-900">{{ $beribadahBulanIni }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menu Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Paraf Bangun Pagi --}}
            <div class="bg-gradient-to-br from-white to-yellow-50 border {{ $bangunPagiMenunggu > 0 ? 'border-yellow-400 ring-2 ring-yellow-200' : 'border-yellow-100' }} rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    @if($bangunPagiMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-bounce">{{ $bangunPagiMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Paraf Bangun Pagi</h3>
                <p class="text-sm text-gray-600 mb-4">Verifikasi waktu bangun pagi anak</p>
                <a href="{{ route('paraf-ortu-bangun-pagi.index') }}" class="block w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white text-center py-2.5 rounded-lg font-medium transition-all">Kelola Paraf</a>
            </div>

            {{-- Paraf Kegiatan Sosial --}}
            <div class="bg-gradient-to-br from-white to-blue-50 border {{ $bermasyarakatMenunggu > 0 ? 'border-blue-400 ring-2 ring-blue-200' : 'border-blue-100' }} rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    @if($bermasyarakatMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-bounce">{{ $bermasyarakatMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Paraf Kegiatan Sosial</h3>
                <p class="text-sm text-gray-600 mb-4">Verifikasi kegiatan sosial anak</p>
                <a href="{{ route('paraf-ortu-bermasyarakat.index') }}" class="block w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-center py-2.5 rounded-lg font-medium transition-all">Kelola Paraf</a>
            </div>

            {{-- Paraf Ibadah Sholat --}}
            <div class="bg-gradient-to-br from-white to-emerald-50 border {{ $beribadahMenunggu > 0 ? 'border-emerald-400 ring-2 ring-emerald-200' : 'border-emerald-100' }} rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-emerald-100 p-3 rounded-lg">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    @if($beribadahMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full animate-bounce">{{ $beribadahMenunggu }}</span>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Paraf Ibadah Sholat</h3>
                <p class="text-sm text-gray-600 mb-4">Verifikasi ibadah sholat anak</p>
                <div class="space-y-1 mb-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Menunggu</span>
                        <span class="font-bold {{ $beribadahMenunggu > 0 ? 'text-red-600' : 'text-gray-600' }}">{{ $beribadahMenunggu }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Sudah Paraf</span>
                        <span class="font-bold text-green-600">{{ $beribadahSudah }}</span>
                    </div>
                </div>
                <a href="{{ route('paraf-ortu-beribadah.index') }}" class="block w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white text-center py-2.5 rounded-lg font-medium transition-all">Kelola Paraf</a>
            </div>

            {{-- Profil & Lainnya --}}
            <div class="bg-gradient-to-br from-white to-slate-50 border border-slate-100 rounded-xl shadow-sm p-6">
                <div class="bg-slate-100 p-3 rounded-lg w-fit mb-4">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Profil & Lainnya</h3>
                <p class="text-sm text-gray-600 mb-4">Kelola profil dan informasi</p>
                <div class="space-y-2">
                    <a href="{{ route('orangtua.profil-anak') }}" class="block p-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm font-medium text-gray-700 text-center transition-colors">Profil Anak</a>
                    <a href="{{ route('orangtua.profile') }}" class="block p-2 bg-gray-50 hover:bg-gray-100 rounded-lg text-sm font-medium text-gray-700 text-center transition-colors">Profil Saya</a>
                    <form method="POST" action="{{ route('orangtua.logout') }}">
                        @csrf
                        <button type="submit" class="w-full p-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm font-medium transition-colors">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($totalNotifikasi > 0)
<script>
    // Auto refresh setiap 60 detik jika ada notifikasi
    setTimeout(function() { 
        location.reload(); 
    }, 60000);
</script>
@endif
@endsection