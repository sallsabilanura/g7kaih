@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="glass-card rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl primary-gradient flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent mb-2">
                        Detail Paraf Ibadah Sholat
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Detail lengkap ibadah sholat dan status paraf orang tua
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('paraf-ortu-beribadah.index') }}" 
                   class="bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-r from-[#0033A0] to-[#00A86B] flex items-center justify-center text-white font-bold text-2xl">
                    {{ strtoupper(substr($beribadah->siswa->nama_lengkap ?? $beribadah->siswa->nama ?? '?', 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $beribadah->siswa->nama_lengkap ?? $beribadah->siswa->nama }}</h3>
                    <div class="space-y-1 mt-2">
                        <p class="text-sm text-gray-600">NIS: {{ $beribadah->siswa->nis }}</p>
                        @if($beribadah->siswa->kelas)
                            <p class="text-sm text-gray-600">Kelas: {{ $beribadah->siswa->kelas->nama_kelas ?? $beribadah->siswa->kelas->nama }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-r from-[#0033A0]/20 to-[#00A86B]/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#0033A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tanggal Ibadah</h3>
                    <p class="text-gray-600 mt-2">{{ \Carbon\Carbon::parse($beribadah->tanggal)->format('d F Y') }}</p>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($beribadah->tanggal)->locale('id')->dayName }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card rounded-2xl shadow-lg p-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl bg-gradient-to-r from-[#0033A0]/20 to-[#00A86B]/20 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#00A86B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Status Paraf</h3>
                    @php
                        $sholatCount = 0;
                        $parafCount = 0;
                        foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $sholat) {
                            if ($beribadah->{$sholat . '_waktu'}) {
                                $sholatCount++;
                                if ($beribadah->{$sholat . '_paraf'}) {
                                    $parafCount++;
                                }
                            }
                        }
                    @endphp
                    <div class="mt-2">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Progress Paraf:</span>
                            <span class="text-sm font-semibold {{ $parafCount == $sholatCount ? 'text-[#00A86B]' : ($parafCount > 0 ? 'text-[#0033A0]' : 'text-yellow-600') }}">
                                {{ $parafCount }}/{{ $sholatCount }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full {{ $parafCount == $sholatCount ? 'bg-gradient-to-r from-[#00A86B] to-[#00A86B]/80' : 'bg-gradient-to-r from-[#0033A0] to-[#0033A0]/80' }}" 
                                 style="width: {{ $sholatCount > 0 ? ($parafCount / $sholatCount * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sholat Details -->
    <div class="glass-card rounded-2xl shadow-lg overflow-hidden mb-8">
        <div class="px-6 py-5 bg-gradient-to-r from-[#0033A0]/10 to-[#00A86B]/10 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Detail Sholat</h2>
                    <p class="text-gray-500 text-sm">Status paraf untuk setiap sholat yang sudah diisi</p>
                </div>
            </div>
        </div>
        
        <div class="divide-y divide-gray-100">
            @foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $sholat)
                <div class="p-6 hover:bg-gradient-to-r hover:from-[#0033A0]/5 hover:to-[#00A86B]/5 transition-all duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $sholatLabels[$sholat] }}</h3>
                                    @if($beribadah->{$sholat . '_waktu'})
                                        @if($beribadah->{$sholat . '_paraf'})
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-[#00A86B]/20 to-[#00A86B]/10 text-[#00A86B] border border-[#00A86B]/20">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Sudah Diparaf
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-500/20 to-yellow-500/10 text-yellow-600 border border-yellow-500/20">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Menunggu Paraf
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-gray-500/20 to-gray-500/10 text-gray-600 border border-gray-500/20">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Belum Diisi
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 01118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 font-medium">Status</p>
                                        <p class="text-sm text-gray-900 font-semibold">
                                            @if($beribadah->{$sholat . '_waktu'})
                                                <span class="text-[#00A86B]">✓ Sudah Sholat</span>
                                            @else
                                                <span class="text-gray-500">Belum sholat</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                @if($beribadah->{$sholat . '_waktu'})
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-[#0033A0]/10 to-[#00A86B]/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#0033A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium">Waktu Sholat</p>
                                            <p class="text-sm text-gray-900 font-mono font-semibold">{{ $beribadah->{$sholat . '_waktu'} }}</p>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($beribadah->{$sholat . '_paraf'})
                                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-[#00A86B]/5 to-[#00A86B]/2 rounded-xl">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-[#00A86B]/20 to-[#00A86B]/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#00A86B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium">Paraf oleh</p>
                                            <p class="text-sm text-gray-900 font-semibold">{{ $beribadah->{$sholat . '_nama_ortu'} }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-[#0033A0]/5 to-[#0033A0]/2 rounded-xl">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-[#0033A0]/20 to-[#0033A0]/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#0033A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 font-medium">Waktu Paraf</p>
                                            <p class="text-sm text-gray-900 font-mono font-semibold">
                                                {{ \Carbon\Carbon::parse($beribadah->{$sholat . '_waktu_paraf'})->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row lg:flex-col gap-3">
                            @if($beribadah->{$sholat . '_waktu'} && !$beribadah->{$sholat . '_paraf'})
                                <!-- Form untuk memaraf sholat tertentu -->
                                <form action="{{ route('paraf-ortu-beribadah.approveSholat', $beribadah->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="sholat" value="{{ $sholat }}">
                                    <input type="hidden" name="nama_ortu" value="{{ auth()->user()->name ?? 'Orang Tua' }}">
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 text-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin memaraf sholat {{ $sholatLabels[$sholat] }}?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Paraf Sholat
                                    </button>
                                </form>
                            @elseif($beribadah->{$sholat . '_paraf'})
                                <!-- Form untuk menghapus paraf -->
                                <form action="{{ route('paraf-ortu-beribadah.removeParaf', [$beribadah->id, $sholat]) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 text-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus paraf sholat {{ $sholatLabels[$sholat] }}?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Hapus Paraf
                                    </button>
                                </form>
                            @else
                                <div class="w-full px-5 py-3 bg-gray-100 rounded-xl text-center">
                                    <span class="text-gray-400 text-sm font-medium">Tidak dapat diparaf</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    @php
        $hasUnparafed = false;
        foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $sholat) {
            if ($beribadah->{$sholat . '_waktu'} && !$beribadah->{$sholat . '_paraf'}) {
                $hasUnparafed = true;
                break;
            }
        }
    @endphp

    @if($hasUnparafed)
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-[#0033A0]/20 to-[#00A86B]/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#0033A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Paraf Cepat</h3>
                        <p class="text-gray-600 text-sm">Paraf semua sholat yang belum diparaf sekaligus</p>
                    </div>
                </div>
                <form action="{{ route('paraf-ortu-beribadah.approveAll', $beribadah->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2"
                            onclick="return confirm('Apakah Anda yakin ingin memaraf semua sholat yang sudah diisi?')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Paraf Semua Sholat
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Summary Card -->
    <div class="glass-card rounded-2xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
            <h3 class="text-lg font-semibold text-gray-900">Ringkasan Paraf</h3>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @php
                $sholatData = [];
                foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $sholat) {
                    $sholatData[$sholat] = [
                        'label' => $sholatLabels[$sholat],
                        'diisi' => !empty($beribadah->{$sholat . '_waktu'}),
                        'diparaf' => $beribadah->{$sholat . '_paraf'}
                    ];
                }
            @endphp
            
            @foreach($sholatData as $sholat => $data)
                <div class="glass-card p-5 rounded-xl border {{ $data['diisi'] ? ($data['diparaf'] ? 'border-[#00A86B]/30 bg-gradient-to-r from-[#00A86B]/5 to-[#00A86B]/2' : 'border-yellow-500/30 bg-gradient-to-r from-yellow-500/5 to-yellow-500/2') : 'border-gray-200 bg-gray-50' }}">
                    <div class="text-center">
                        <div class="text-sm font-semibold text-gray-600 mb-3">{{ $data['label'] }}</div>
                        <div class="text-3xl font-bold mb-3 {{ $data['diisi'] ? ($data['diparaf'] ? 'text-[#00A86B]' : 'text-yellow-600') : 'text-gray-400' }}">
                            @if($data['diisi'])
                                @if($data['diparaf'])
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-[#00A86B]/20 to-[#00A86B]/10 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-[#00A86B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-r from-yellow-500/20 to-yellow-500/10 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-gray-200 to-gray-300 flex items-center justify-center mx-auto">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $data['diisi'] ? ($data['diparaf'] ? 'bg-[#00A86B]/10 text-[#00A86B]' : 'bg-yellow-500/10 text-yellow-600') : 'bg-gray-100 text-gray-500' }}">
                            @if($data['diisi'])
                                @if($data['diparaf'])
                                    ✓ Sudah Diparaf
                                @else
                                    ⏳ Menunggu Paraf
                                @endif
                            @else
                                ✗ Belum Diisi
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

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

    .primary-gradient {
        background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
    }

    .table-row-hover:hover {
        background: linear-gradient(90deg, rgba(0, 51, 160, 0.02) 0%, rgba(0, 168, 107, 0.02) 100%);
    }
</style>
@endsection