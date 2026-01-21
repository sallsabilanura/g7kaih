@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Kegiatan Bermasyarakat - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .status-badge {
            position: relative;
            overflow: hidden;
        }
        
        .status-badge.approved::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #00A86B 0%, rgba(0, 168, 107, 0.3) 100%);
            border-radius: 0.75rem;
        }
        
        .status-badge.pending::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #FFD700 0%, rgba(255, 215, 0, 0.3) 100%);
            border-radius: 0.75rem;
        }

        .paraf-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0033A0 0%, rgba(0, 51, 160, 0.3) 100%);
            border-radius: 0.75rem;
        }

        .no-paraf-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #6B7280 0%, rgba(107, 114, 128, 0.3) 100%);
            border-radius: 0.75rem;
        }

        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #0033A0 0%, #00A86B 100%) border-box;
            border: 1.5px solid transparent;
        }

        .info-item {
            transition: all 0.3s ease;
        }
        
        .info-item:hover {
            transform: translateX(5px);
        }

        .image-hover-effect {
            transition: all 0.3s ease;
        }
        
        .image-hover-effect:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-xl p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('bermasyarakat.index') }}" 
                       class="group relative p-3 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-6 h-6 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Detail Kegiatan Bermasyarakat
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Informasi lengkap kegiatan sosial siswa
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($data->status == 'approved')
                    <span class="relative inline-flex items-center px-4 py-2 text-white rounded-xl text-sm font-semibold shadow-lg status-badge approved">
                        <span class="relative z-10 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            Sudah Dinilai
                        </span>
                    </span>
                    @else
                    <span class="relative inline-flex items-center px-4 py-2 text-white rounded-xl text-sm font-semibold shadow-lg status-badge pending">
                        <span class="relative z-10 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                            </svg>
                            Menunggu Penilaian
                        </span>
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Main Information --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Informasi Siswa --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                                <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                                <span>Informasi Siswa</span>
                            </h2>
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-green text-white rounded-full flex items-center justify-center font-bold text-lg">
                                {{ substr($data->siswa->nama_lengkap ?? $data->siswa->nama ?? '?', 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="info-item">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Nama Lengkap</label>
                                <div class="flex items-center gap-2 text-gray-900 font-semibold text-lg">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Siswa tidak ditemukan' }}
                                </div>
                            </div>
                            <div class="info-item">
                                <label class="block text-sm font-medium text-gray-500 mb-2">NIS</label>
                                <div class="flex items-center gap-2 text-gray-900 font-semibold text-lg">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    {{ $data->siswa->nis ?? '-' }}
                                </div>
                            </div>
                            @if($data->siswa->kelas)
                            <div class="info-item">
                                <label class="block text-sm font-medium text-gray-500 mb-2">Kelas</label>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex px-3 py-1 rounded-lg text-sm font-semibold bg-green-100 text-accent-green">
                                        {{ $data->siswa->kelas->nama_kelas }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Basic Information Card --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.15s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Informasi Waktu</span>
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="mb-2">
                                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-blue-100 to-primary-50 rounded-2xl flex items-center justify-center">
                                        <span class="text-2xl font-bold text-primary-600">{{ $data->tanggal->format('d') }}</span>
                                    </div>
                                </div>
                                <label class="block text-sm font-medium text-gray-500">Tanggal</label>
                                <div class="text-gray-900 font-semibold mt-1">{{ $data->tanggal->format('d F Y') }}</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-2">
                                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-green-100 to-accent-green/20 rounded-2xl flex items-center justify-center">
                                        <span class="text-xl font-bold text-accent-green">{{ $data->bulan }}</span>
                                    </div>
                                </div>
                                <label class="block text-sm font-medium text-gray-500">Bulan</label>
                                <div class="text-gray-900 font-semibold mt-1">{{ $data->bulan }}</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-2">
                                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-purple-100 to-secondary-50 rounded-2xl flex items-center justify-center">
                                        <span class="text-2xl font-bold text-secondary-600">{{ $data->tahun }}</span>
                                    </div>
                                </div>
                                <label class="block text-sm font-medium text-gray-500">Tahun</label>
                                <div class="text-gray-900 font-semibold mt-1">{{ $data->tahun }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kegiatan Information Card --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.2s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Informasi Kegiatan</span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-500">Nama Kegiatan</label>
                            <div class="flex items-center gap-3 text-xl font-bold text-gray-900 bg-gradient-to-r from-gray-50 to-blue-50 p-4 rounded-xl border border-gray-200">
                                <svg class="w-6 h-6 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                {{ $data->nama_kegiatan }}
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-500">Pesan & Kesan</label>
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-start gap-4">
                                    <svg class="w-6 h-6 text-primary-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <div class="space-y-3">
                                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $data->pesan_kesan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Images Card (HANYA 1 GAMBAR) --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.25s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Dokumentasi Kegiatan</span>
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            @if($data->gambar_kegiatan)
                                @php
                                    $gambar = is_array($data->gambar_kegiatan) ? $data->gambar_kegiatan[0] : $data->gambar_kegiatan;
                                @endphp
                                <div class="relative max-w-2xl mx-auto group">
                                    <img src="{{ Storage::url($gambar) }}" 
                                         alt="Dokumentasi Kegiatan" 
                                         class="w-full h-96 object-cover rounded-xl shadow-lg image-hover-effect">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/0 via-black/0 to-black/0 group-hover:from-black/0 group-hover:via-black/5 group-hover:to-black/20 rounded-xl transition-all duration-300"></div>
                                    <a href="{{ Storage::url($gambar) }}" 
                                       target="_blank" 
                                       class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm p-3 rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3-3H7"/>
                                        </svg>
                                    </a>
                                    <div class="absolute bottom-4 left-4 right-4 text-center">
                                        <span class="inline-block bg-black/50 backdrop-blur-sm text-white px-3 py-1 rounded-lg text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            Klik gambar untuk melihat ukuran penuh
                                        </span>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-500 mt-4">Gambar dokumentasi kegiatan</p>
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-gray-500">Tidak ada gambar dokumentasi</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Information --}}
            <div class="space-y-6">
                
                {{-- Status & Score Card --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.3s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Status & Penilaian</span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-500">Status Verifikasi</label>
                            @if($data->status == 'approved')
                            <div class="relative inline-flex items-center px-4 py-3 text-white rounded-xl text-sm font-semibold shadow-lg status-badge approved">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    Sudah Dinilai
                                </span>
                            </div>
                            @else
                            <div class="relative inline-flex items-center px-4 py-3 text-white rounded-xl text-sm font-semibold shadow-lg status-badge pending">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                    </svg>
                                    Menunggu Penilaian
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-500">Paraf Orang Tua</label>
                            @if($data->paraf_ortu)
                            <div class="relative inline-flex items-center px-4 py-3 text-white rounded-xl text-sm font-semibold shadow-lg paraf-badge">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    Sudah Diparaf
                                </span>
                            </div>
                            @else
                            <div class="relative inline-flex items-center px-4 py-3 text-white rounded-xl text-sm font-semibold shadow-lg no-paraf-badge">
                                <span class="relative z-10 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                    </svg>
                                    Belum Diparaf
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-500">Nilai</label>
                            @if($data->nilai)
                            <div class="text-center bg-gradient-to-br from-primary-500 to-accent-green text-white py-6 rounded-xl shadow-lg">
                                <div class="text-4xl font-bold">{{ $data->nilai }}</div>
                                <div class="text-primary-100 text-sm mt-1">/100</div>
                                <div class="text-xs text-primary-200 mt-2">Nilai Kegiatan</div>
                            </div>
                            @else
                            <div class="text-center bg-gradient-to-br from-gray-200 to-gray-300 text-gray-500 py-6 rounded-xl">
                                <div class="text-3xl font-bold">--</div>
                                <div class="text-gray-500 text-sm mt-1">/100</div>
                                <div class="text-xs text-gray-600 mt-2">Belum ada nilai</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- System Info Card --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.35s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Informasi Sistem</span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="info-item flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Dibuat
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $data->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-item flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Diupdate
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $data->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($data->creator)
                        <div class="info-item flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Dibuat oleh
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $data->creator->name }}</span>
                        </div>
                        @endif
                        @if($data->updater)
                        <div class="info-item flex justify-between items-center py-3">
                            <span class="text-sm text-gray-500 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Diupdate oleh
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $data->updater->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.4s;">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-50 to-blue-50">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <span>Aksi</span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('bermasyarakat.index') }}" 
                           class="w-full flex items-center justify-center gap-3 px-4 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md group">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Daftar
                        </a>
                        <a href="{{ route('bermasyarakat.edit', $data->id) }}" 
                           class="w-full flex items-center justify-center gap-3 px-4 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Data
                        </a>
                        <form action="{{ route('bermasyarakat.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-3 px-4 py-3.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-semibold transition-all duration-300 shadow-lg hover:shadow-xl group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus Kegiatan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Hover effect untuk info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.classList.add('bg-blue-50');
                });
                
                item.addEventListener('mouseleave', function() {
                    this.classList.remove('bg-blue-50');
                });
            });
        });
    </script>
</body>
</html>
@endsection