@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 rounded-xl p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Paraf Orang Tua</h1>
                    <p class="text-gray-600">Detail informasi paraf orang tua untuk checklist bangun pagi</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('paraf-ortu-bangun-pagi.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Detail Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Informasi Siswa -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Siswa
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Siswa:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->siswa->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">NIS:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->siswa->nis ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kelas:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Informasi Checklist -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Checklist
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->tanggal_formatted ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Pukul Bangun:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->pukul_formatted ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nilai:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->nilai ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kategori:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->bangunPagi->kategori_waktu_label ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Paraf -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Detail Paraf -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Informasi Paraf
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Orang Tua:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->nama_ortu }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu Paraf:</span>
                    <span class="font-medium text-gray-900">{{ $parafOrtu->waktu_paraf_formatted }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium">
                        @if($parafOrtu->status == 'terverifikasi')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                                Belum Verifikasi
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan & Catatan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Tanda Tangan & Catatan
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan:</label>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-900 font-mono">{{ $parafOrtu->tanda_tangan }}</p>
                    </div>
                </div>
                @if($parafOrtu->catatan)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan:</label>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-900">{{ $parafOrtu->catatan }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection