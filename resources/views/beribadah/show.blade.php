@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Ibadah</h1>
                    <p class="text-gray-600">{{ $beribadah->siswa->nama ?? '-' }} - {{ $beribadah->tanggal_formatted }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('beribadah.edit', $beribadah->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('beribadah.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Info Siswa & Total -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-4">Informasi Siswa</h3>
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 bg-emerald-100 rounded-full flex items-center justify-center">
                    <span class="text-emerald-600 font-bold text-xl">{{ substr($beribadah->siswa->nama ?? 'N', 0, 1) }}</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $beribadah->siswa->nama ?? '-' }}</p>
                    <p class="text-sm text-gray-500">NIS: {{ $beribadah->siswa->nis ?? '-' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-gray-900 mb-4">Tanggal</h3>
            <p class="text-2xl font-bold text-gray-900">{{ $beribadah->tanggal_formatted }}</p>
            <p class="text-gray-600">{{ $beribadah->nama_hari }}, {{ $beribadah->bulan }} {{ $beribadah->tahun }}</p>
        </div>
        <div class="bg-emerald-50 rounded-xl shadow-lg p-6">
            <h3 class="font-bold text-emerald-900 mb-4">Total Nilai</h3>
            <p class="text-4xl font-bold text-emerald-600">{{ $beribadah->total_nilai }}</p>
            <p class="text-emerald-700">{{ $beribadah->total_sholat }}/5 sholat dilaksanakan</p>
        </div>
    </div>

    <!-- Sholat 5 Waktu dengan Paraf -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="p-4 bg-emerald-100">
            <h3 class="font-bold text-emerald-900">Detail Sholat 5 Waktu & Paraf Orang Tua</h3>
        </div>
        <div class="p-6">
            @php
                $sholatColors = [
                    'subuh' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-300', 'header' => 'from-blue-400 to-indigo-500'],
                    'dzuhur' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-300', 'header' => 'from-yellow-400 to-orange-500'],
                    'ashar' => ['bg' => 'bg-orange-50', 'border' => 'border-orange-300', 'header' => 'from-orange-400 to-red-500'],
                    'maghrib' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-300', 'header' => 'from-purple-400 to-pink-500'],
                    'isya' => ['bg' => 'bg-gray-100', 'border' => 'border-gray-400', 'header' => 'from-gray-600 to-gray-800'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($jenisSholat as $sholat)
                    @php
                        $color = $sholatColors[$sholat];
                        $waktu = $beribadah->{$sholat . '_waktu'};
                        $nilai = $beribadah->{$sholat . '_nilai'};
                        $kategori = $beribadah->{$sholat . '_kategori'};
                        $paraf = $beribadah->{$sholat . '_paraf'};
                        $parafNama = $beribadah->{$sholat . '_paraf_nama'};
                        $parafWaktu = $beribadah->{$sholat . '_paraf_waktu'};
                        $peng = $pengaturan[$sholat] ?? null;
                    @endphp
                    <div class="{{ $color['bg'] }} {{ $color['border'] }} border-2 rounded-xl overflow-hidden">
                        <!-- Header Sholat -->
                        <div class="bg-gradient-to-r {{ $color['header'] }} p-3">
                            <h4 class="font-bold text-white text-lg">{{ $sholatLabels[$sholat] }}</h4>
                        </div>
                        
                        <div class="p-4">
                            @if($waktu)
                                <!-- Info Waktu & Nilai -->
                                <div class="mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600 text-sm">Waktu Sholat</span>
                                        <span class="font-mono font-bold text-lg">{{ \Carbon\Carbon::parse($waktu)->format('H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600 text-sm">Nilai</span>
                                        @php
                                            $badgeColor = $kategori == 'tepat_waktu' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $badgeColor }}">{{ $nilai }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 text-sm">Status</span>
                                        <span class="text-sm font-medium {{ $kategori == 'tepat_waktu' ? 'text-green-600' : 'text-yellow-600' }}">
                                            {{ $kategori == 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Paraf Orang Tua -->
                                <div class="border-t pt-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-2">Paraf Orang Tua</p>
                                    @if($paraf)
                                        <div class="bg-green-100 border border-green-300 rounded-lg p-3">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="text-green-800 font-semibold">Sudah Diparaf</span>
                                            </div>
                                            <p class="text-sm text-green-700"><strong>Nama:</strong> {{ $parafNama }}</p>
                                            <p class="text-xs text-green-600">{{ $parafWaktu ? $parafWaktu->format('d/m/Y H:i') : '-' }}</p>
                                            
                                            <!-- Tombol Batalkan Paraf -->
                                            <form action="{{ route('beribadah.batal-paraf', [$beribadah->id, $sholat]) }}" method="POST" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Batalkan paraf?')" class="text-xs text-red-600 hover:text-red-800 underline">
                                                    Batalkan Paraf
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Form Paraf -->
                                        <form action="{{ route('beribadah.paraf-ortu', [$beribadah->id, $sholat]) }}" method="POST">
                                            @csrf
                                            <div class="space-y-2">
                                                <input type="text" name="nama_ortu" placeholder="Nama Orang Tua" required
                                                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Paraf
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-gray-400 text-sm">Belum Sholat</p>
                                    <p class="text-gray-300 text-xs">Tidak ada data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Keterangan -->
    @if($beribadah->keterangan)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="font-bold text-gray-900 mb-2">Keterangan</h3>
        <p class="text-gray-700">{{ $beribadah->keterangan }}</p>
    </div>
    @endif
</div>
@endsection