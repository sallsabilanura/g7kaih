@extends('layouts.app')

@section('title', 'Detail Data Berolahraga')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8 max-w-4xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 mb-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Detail Data Berolahraga
                    </h1>
                </div>
                <p class="text-gray-600 text-sm sm:text-base">
                    Informasi lengkap kegiatan berolahraga siswa
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('berolahraga.index') }}" 
                   class="inline-flex items-center justify-center bg-gray-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <a href="{{ route('berolahraga.edit', $berolahraga) }}" 
                   class="inline-flex items-center justify-center bg-primary-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-primary-600 transition-all duration-200 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Detail Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="p-4 sm:p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h2 class="text-lg font-bold text-gray-900">Informasi Berolahraga</h2>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Siswa -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Data Siswa</h3>
                    
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-primary flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($berolahraga->siswa->nama_lengkap ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-gray-900 font-medium">{{ $berolahraga->siswa->nama_lengkap ?? 'N/A' }}</p>
                            <p class="text-gray-500 text-sm">NIS: {{ $berolahraga->siswa->nis ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium">{{ $berolahraga->siswa->kelas->nama_kelas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Kelamin:</span>
                            <span class="font-medium">{{ $berolahraga->siswa->jenis_kelamin ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Olahraga -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Detail Olahraga</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tanggal:</span>
                            <span class="font-medium">{{ $berolahraga->tanggal_formatted }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Waktu Mulai:</span>
                            <span class="font-medium">{{ $berolahraga->mulai_olahraga }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Waktu Selesai:</span>
                            <span class="font-medium">{{ $berolahraga->selesai_olahraga }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $berolahraga->durasi }} menit
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Bulan:</span>
                            <span class="font-medium">{{ $berolahraga->bulan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Video -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumentasi Video</h3>
                
                @if($berolahraga->dokumentasi_video)
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <div class="flex-1">
                            <p class="text-gray-600 mb-2">Video dokumentasi kegiatan olahraga tersedia:</p>
                            <div class="flex gap-3">
                                <a href="{{ route('berolahraga.view-video', $berolahraga) }}" 
                                   target="_blank"
                                   class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Lihat Video
                                </a>
                                <a href="{{ route('berolahraga.download-video', $berolahraga) }}" 
                                   class="inline-flex items-center bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Video
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">Tidak ada dokumentasi video</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end gap-3">
        <form action="{{ route('berolahraga.destroy', $berolahraga) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" 
                    class="inline-flex items-center bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-all duration-200 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Data
            </button>
        </form>
    </div>
</div>
@endsection