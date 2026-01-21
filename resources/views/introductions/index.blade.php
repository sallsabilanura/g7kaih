@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Diri Siswa - 7 Kebiasaan Anak Indonesia Hebat</title>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 sm:py-8 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 sm:p-8 mb-6 border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                            Data Diri Siswa
                        </h1>
                    </div>
                    <p class="text-gray-600 text-sm sm:text-base">
                        Kelola data pengenalan diri siswa
                    </p>
                </div>
                <a href="{{ route('introductions.create') }}" 
                   class="inline-flex items-center justify-center bg-accent-green text-white font-semibold px-4 sm:px-6 py-2 sm:py-3 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-accent-green focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Data
                </a>
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

        <!-- Table Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="p-4 sm:p-6 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">Daftar Data Diri Siswa</h2>
                </div>
            </div>

            <!-- Table Desktop -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-primary text-white">
                            <th class="p-3 sm:p-4 text-left font-semibold text-sm">No</th>
                            <th class="p-3 sm:p-4 text-left font-semibold text-sm">Nama Siswa</th>
                            <th class="p-3 sm:p-4 text-left font-semibold text-sm">Hobi</th>
                            <th class="p-3 sm:p-4 text-left font-semibold text-sm">Cita-cita</th>
                            <th class="p-3 sm:p-4 text-left font-semibold text-sm">Kesukaan</th>
                            <th class="p-3 sm:p-4 text-center font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($introductions as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-3 sm:p-4 font-medium text-gray-600 text-sm">{{ $loop->iteration }}</td>
                            <td class="p-3 sm:p-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-gradient-primary flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ strtoupper(substr($item->siswa->nama_lengkap ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-gray-900 text-sm font-medium">{{ $item->siswa->nama_lengkap ?? 'N/A' }}</p>
                                        <p class="text-gray-500 text-xs">{{ $item->siswa->nisn ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 sm:p-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice(explode(',', $item->hobi), 0, 2) as $hobi)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ trim($hobi) }}
                                        </span>
                                    @endforeach
                                    @if(count(explode(',', $item->hobi)) > 2)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            +{{ count(explode(',', $item->hobi)) - 2 }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-3 sm:p-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                                    </svg>
                                    <span class="text-gray-900 text-sm">{{ $item->cita_cita }}</span>
                                </div>
                            </td>
                            <td class="p-3 sm:p-4">
                                <div class="space-y-1 text-xs text-gray-600">
                                    <p class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="font-medium">Olahraga:</span> {{ $item->olahraga_kesukaan }}
                                    </p>
                                    <p class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span class="font-medium">Pelajaran:</span> {{ $item->pelajaran_kesukaan }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-3 sm:p-4">
                                <div class="flex items-center justify-center gap-1 sm:gap-2">
                                    <a href="{{ route('introductions.show', $item->id) }}" 
                                       class="inline-flex items-center bg-blue-500 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all text-xs sm:text-sm font-medium">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </a>
                                    <a href="{{ route('introductions.edit', $item->id) }}" 
                                       class="inline-flex items-center bg-primary-500 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 transition-all text-xs sm:text-sm font-medium">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    
                                    <!-- PERBAIKAN: Cek apakah user terautentikasi dan memiliki role admin -->
                                    @auth
                                        @if(auth()->user()->role === 'admin')
                                        <form action="{{ route('introductions.destroy', $item->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Yakin hapus data ini?')" 
                                                    class="inline-flex items-center bg-secondary-500 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-secondary-600 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-1 transition-all text-xs sm:text-sm font-medium">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="font-medium">Belum ada data introduction</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cards Mobile -->
            <div class="lg:hidden p-4 space-y-4">
                @forelse ($introductions as $item)
                <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200">
                    <div class="mb-3">
                        <!-- Nama Siswa -->
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-primary flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($item->siswa->nama_lengkap ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $item->siswa->nama_lengkap ?? 'N/A' }}</h3>
                                <p class="text-xs text-gray-500">{{ $item->siswa->nisn ?? '-' }}</p>
                            </div>
                        </div>
                        
                        <!-- Hobi -->
                        <div class="mb-2">
                            <p class="text-xs text-gray-500 font-semibold mb-1">Hobi:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach(explode(',', $item->hobi) as $hobi)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ trim($hobi) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Cita-cita -->
                        <div class="mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                            </svg>
                            <p class="text-sm text-gray-900"><span class="font-semibold">Cita-cita:</span> {{ $item->cita_cita }}</p>
                        </div>
                        
                        <!-- Detail Kesukaan -->
                        <div class="bg-gray-50 rounded-lg p-3 space-y-1 text-xs">
                            <p class="text-gray-700"><span class="font-semibold">Olahraga:</span> {{ $item->olahraga_kesukaan }}</p>
                            <p class="text-gray-700"><span class="font-semibold">Makanan:</span> {{ $item->makanan_kesukaan }}</p>
                            <p class="text-gray-700"><span class="font-semibold">Buah:</span> {{ $item->buah_kesukaan }}</p>
                            <p class="text-gray-700"><span class="font-semibold">Pelajaran:</span> {{ $item->pelajaran_kesukaan }}</p>
                            
                            <!-- Perbaikan untuk Warna (3 warna) -->
                            <div class="flex items-center gap-2 mt-2">
                                <span class="font-semibold text-gray-700">Warna:</span>
                                <div class="flex gap-1">
                                    @if(is_array($item->warna_kesukaan))
                                        @foreach($item->warna_kesukaan as $warna)
                                            <div class="w-6 h-6 rounded border border-gray-300" 
                                                 style="background-color: {{ $warna }}" 
                                                 title="{{ $warna }}"></div>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('introductions.show', $item->id) }}" 
                           class="flex-1 inline-flex items-center justify-center bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600 transition-all text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat
                        </a>
                        <a href="{{ route('introductions.edit', $item->id) }}" 
                           class="flex-1 inline-flex items-center justify-center bg-primary-500 text-white px-3 py-2 rounded-md hover:bg-primary-600 transition-all text-sm font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        
                        <!-- PERBAIKAN: Cek apakah user terautentikasi dan memiliki role admin -->
                        @auth
                            @if(auth()->user()->role === 'admin')
                            <form action="{{ route('introductions.destroy', $item->id) }}" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Yakin hapus data ini?')" 
                                        class="w-full inline-flex items-center justify-center bg-secondary-500 text-white px-3 py-2 rounded-md hover:bg-secondary-600 transition-all text-sm font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        @endauth
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada data introduction</p>
                    <a href="{{ route('introductions.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                        Tambah data pertama
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
@endsection