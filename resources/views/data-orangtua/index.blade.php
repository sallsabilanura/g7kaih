@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="glass-card rounded-xl p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Logo dan Info -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-600 to-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                Data Orangtua
                            </span>
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                {{ date('d F Y') }}
                            </span>
                        </div>
                        <h1 class="text-lg font-bold text-gray-900">
                            Data Orangtua Siswa
                        </h1>
                        <p class="text-xs text-gray-600">
                            Informasi lengkap data orangtua siswa
                        </p>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-3 py-2 rounded-lg transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="hidden sm:inline text-xs">Dashboard</span>
                    </a>
                    @if($orangtua)
                    <a href="{{ route('data-orangtua.edit', $orangtua) }}" 
                       class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-600 to-emerald-600 hover:from-blue-700 hover:to-emerald-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow-sm hover:shadow transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="text-xs">Edit Data</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="glass-card rounded-xl p-3 mb-6">
            <div class="flex items-start gap-2">
                <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="glass-card rounded-xl p-3 mb-6">
            <div class="flex items-start gap-2">
                <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($orangtua)
        <!-- Main Content - Data Orangtua -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Father's Data Card -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-white">Data Ayah</h2>
                                <p class="text-white/80 text-xs">Informasi lengkap ayah siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                Nama Lengkap
                            </label>
                            <div class="bg-blue-50 rounded-lg p-3 border border-blue-100">
                                <p class="text-gray-900 font-medium text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $orangtua->nama_ayah }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Contact & Birth Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Telepon
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $orangtua->telepon_ayah ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Tanggal Lahir
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $orangtua->tanggal_lahir_ayah ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job & Education -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Pekerjaan
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $orangtua->pekerjaan_ayah ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Pendidikan
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        </svg>
                                        {{ $orangtua->pendidikan_ayah ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Father's Signature -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">
                                Tanda Tangan
                            </label>
                            
                            @if($orangtua->tanda_tangan_ayah)
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                                    <div class="mb-2 flex items-center justify-between">
                                        <p class="text-xs font-medium text-blue-700 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Tanda tangan tersedia
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-blue-200">
                                        <img src="{{ $orangtua->tanda_tangan_ayah_url }}" 
                                             alt="Tanda Tangan Ayah" 
                                             class="w-full h-24 object-contain">
                                    </div>
                                </div>
                            @else
                                <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-xs">Belum ada tanda tangan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mother's Data Card -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-white">Data Ibu</h2>
                                <p class="text-white/80 text-xs">Informasi lengkap ibu siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">
                                Nama Lengkap
                            </label>
                            <div class="bg-emerald-50 rounded-lg p-3 border border-emerald-100">
                                <p class="text-gray-900 font-medium text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    {{ $orangtua->nama_ibu }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Contact & Birth Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Telepon
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $orangtua->telepon_ibu ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Tanggal Lahir
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $orangtua->tanggal_lahir_ibu ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job & Education -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Pekerjaan
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $orangtua->pekerjaan_ibu ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">
                                    Pendidikan
                                </label>
                                <div class="bg-white rounded-lg p-2 border border-gray-200">
                                    <p class="text-gray-800 text-sm flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                        </svg>
                                        {{ $orangtua->pendidikan_ibu ?: 'Tidak tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mother's Signature -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-2">
                                Tanda Tangan
                            </label>
                            
                            @if($orangtua->tanda_tangan_ibu)
                                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                                    <div class="mb-2 flex items-center justify-between">
                                        <p class="text-xs font-medium text-emerald-700 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Tanda tangan tersedia
                                        </p>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-emerald-200">
                                        <img src="{{ $orangtua->tanda_tangan_ibu_url }}" 
                                             alt="Tanda Tangan Ibu" 
                                             class="w-full h-24 object-contain">
                                    </div>
                                </div>
                            @else
                                <div class="border border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                                    <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-xs">Belum ada tanda tangan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- No Data Message -->
        <div class="glass-card rounded-xl p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-medium text-gray-900 mb-2">Belum Ada Data Orangtua</h3>
            <p class="text-xs text-gray-500 mb-4 max-w-md mx-auto">
                Data orangtua Anda belum terdaftar. Silakan tambahkan data orangtua untuk dapat mengakses fitur-fitur yang tersedia.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('data-orangtua.create') }}" 
                   class="inline-flex items-center justify-center gap-1 bg-gradient-to-r from-blue-600 to-emerald-600 hover:from-blue-700 hover:to-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm hover:shadow transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Data Orangtua</span>
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-lg {
        font-size: 1rem;
    }
    
    .w-10 {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .w-5 {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-6 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    
    .gap-6 {
        gap: 1.5rem;
    }
    
    .gap-4 {
        gap: 1rem;
    }
    
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .p-4 {
        padding: 0.75rem;
    }
    
    .p-3 {
        padding: 0.5rem;
    }
    
    .text-xs {
        font-size: 0.7rem;
    }
    
    .text-sm {
        font-size: 0.8rem;
    }
    
    .rounded-xl {
        border-radius: 0.5rem;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .px-3 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    
    .py-2 {
        padding-top: 0.375rem;
        padding-bottom: 0.375rem;
    }
}

/* Touch friendly buttons on mobile */
@media (hover: none) and (pointer: coarse) {
    button, a {
        min-height: 44px;
    }
}

/* Prevent zoom on input focus on mobile */
@media (max-width: 768px) {
    input, select, textarea {
        font-size: 16px !important;
    }
}
</style>
@endsection