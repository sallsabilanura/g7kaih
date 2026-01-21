@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nilai Manual - Bermasyarakat - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .gradient-border {
            position: relative;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #0033A0 0%, #00A86B 100%) border-box;
            border: 1.5px solid transparent;
        }

        .select-arrow {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230033A0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
        }

        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 160, 0.1);
            outline: none;
        }

        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(0, 51, 160, 0.02) 0%, rgba(0, 168, 107, 0.02) 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent mb-2">
                        Nilai Manual - Bermasyarakat
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Manajemen penilaian kegiatan sosial dan bermasyarakat
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @php
                        $totalData = $data->total() ?? 0;
                        $pendingCount = $data->where('status', 'pending')->count() ?? 0;
                        $approvedCount = $data->where('status', 'approved')->count() ?? 0;
                    @endphp
                   
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-accent-green mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-gray-900 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600 ml-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            @endif

            @if (session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if (session('warning'))
            <div class="mt-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-gray-900 font-medium">{{ session('warning') }}</p>
                </div>
            </div>
            @endif
        </div>


        <!-- Main Table Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.3s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Kegiatan Bermasyarakat</h2>
                            <p class="text-gray-500 text-sm">
                                Total {{ $totalData }} data ditemukan
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               id="searchInput"
                               placeholder="Cari nama siswa atau kegiatan..." 
                               class="w-full lg:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900 text-sm">
                        <div class="absolute right-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Kegiatan & Pesan</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Tanggal</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Gambar</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Nilai</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($data as $item)
                        @php
                            // Pastikan gambar_kegiatan adalah array
                            $gambarArray = is_array($item->gambar_kegiatan) ? $item->gambar_kegiatan : 
                                         (is_string($item->gambar_kegiatan) ? json_decode($item->gambar_kegiatan, true) : []);
                            $gambarArray = $gambarArray ?? [];
                            $gambarCount = count($gambarArray);
                        @endphp
                        <tr class="table-row-hover transition-all duration-200 {{ !$item->nilai ? 'bg-yellow-50/30' : '' }}" 
                            data-status="{{ $item->status }}"
                            data-search="{{ strtolower($item->siswa?->nama_lengkap . ' ' . $item->siswa?->nama . ' ' . $item->nama_kegiatan) }}">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-green flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($item->siswa->nama_lengkap ?: $item->siswa->nama ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $item->siswa->nama_lengkap ?: $item->siswa->nama ?? 'Siswa tidak ditemukan' }}</span>
                                        <span class="text-xs text-gray-500">NIS: {{ $item->siswa->nis ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    <p class="text-gray-900 text-sm font-medium">{{ Str::limit($item->nama_kegiatan, 40) }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-2">
                                        {{ Str::limit($item->pesan_kesan, 80) }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-gray-900 text-sm">{{ $item->tanggal->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $item->bulan }} {{ $item->tahun }}
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    @foreach(array_slice($gambarArray, 0, 3) as $gambarIndex => $gambar)
                                    <a href="{{ Storage::url($gambar) }}" target="_blank" class="relative group" title="Gambar Kegiatan {{ $gambarIndex + 1 }}">
                                        <img src="{{ Storage::url($gambar) }}" 
                                             class="w-8 h-8 object-cover rounded-lg border border-gray-200 hover:scale-110 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-primary-500/10 opacity-0 group-hover:opacity-100 rounded-lg transition-opacity duration-200"></div>
                                    </a>
                                    @endforeach
                                    @if($gambarCount > 3)
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400" title="+{{ $gambarCount - 3 }} gambar">
                                        <span class="text-xs font-bold">+{{ $gambarCount - 3 }}</span>
                                    </div>
                                    @endif
                                    @if($gambarCount == 0)
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400" title="Tidak ada gambar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                @if($item->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                    </svg>
                                    Dinilai
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                    </svg>
                                    Pending
                                </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($item->nilai)
                                <div class="bg-gradient-to-br from-primary-500 to-accent-green text-white px-3 py-2 rounded-lg shadow-sm">
                                    <div class="text-lg font-bold text-center">{{ $item->nilai }}</div>
                                    <div class="text-xs text-primary-100 text-center">/100</div>
                                </div>
                                @else
                                <div class="bg-gray-100 text-gray-400 px-3 py-2 rounded-lg">
                                    <div class="text-lg font-bold text-center">--</div>
                                    <div class="text-xs text-center">/100</div>
                                </div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                    <button type="button" 
                                        onclick="openModal({{ $item->id }}, '{{ $item->siswa ? addslashes($item->siswa->nama_lengkap ?: $item->siswa->nama) : 'Siswa' }}', '{{ addslashes($item->nama_kegiatan) }}', '{{ $item->nilai ?? '' }}', '{{ addslashes($item->catatan_admin ?? '') }}')"
                                        class="{{ !$item->nilai ? 'bg-gradient-to-r from-accent-green to-emerald-600 text-white' : 'bg-gradient-to-r from-primary-500 to-primary-600 text-white' }} 
                                            font-medium text-sm px-3 py-2 rounded-lg hover:shadow-md transition-colors flex items-center gap-1">
                                        @if(!$item->nilai)
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Nilai
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                        @endif
                                    </button>
                                    @if($item->nilai)
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('nilai-manual-bermasyarakat.reset', $item->id) }}" method="POST" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin mereset nilai untuk {{ $item->siswa->nama_lengkap ?: $item->siswa->nama }}?')" 
                                                class="text-red-600 hover:text-red-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Reset
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary-50 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Data</h3>
                                <p class="text-gray-500 text-sm">Semua data kegiatan bermasyarakat sudah dinilai atau belum ada data yang masuk.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

         

            <!-- Pagination -->
            @if(method_exists($data, 'hasPages') && $data->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Filter Buttons --}}
        <div class="flex justify-center gap-2 mt-6">
            <button id="filterAll" class="px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl font-medium hover:from-primary-600 hover:to-primary-700 transition-colors text-sm">
                Semua ({{ $totalData }})
            </button>
            <button id="filterPending" class="px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl font-medium hover:from-yellow-600 hover:to-orange-600 transition-colors text-sm">
                Pending ({{ $pendingCount }})
            </button>
            <button id="filterDinilai" class="px-4 py-2.5 bg-gradient-to-r from-accent-green to-emerald-600 text-white rounded-xl font-medium hover:from-accent-green/90 hover:to-emerald-700 transition-colors text-sm">
                Dinilai ({{ $approvedCount }})
            </button>
        </div>
    </div>

    {{-- Modal Beri/Edit Nilai --}}
    <div id="nilaiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            {{-- Backdrop --}}
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeModal()"></div>
            
            {{-- Modal Content --}}
            <div class="relative inline-block w-full max-w-md p-0 overflow-hidden text-left align-middle transition-all transform glass-card shadow-2xl rounded-2xl">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-primary-600 to-accent-green px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-white" id="modalTitle">Beri Nilai</h3>
                        </div>
                        <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Form --}}
                <form id="nilaiForm" method="POST" action="">
                    @csrf
                    <div class="px-6 py-5">
                        {{-- Info Siswa --}}
                        <div class="mb-4 p-3 bg-gradient-to-r from-primary-50 to-blue-50 rounded-lg">
                            <p class="text-xs text-gray-500 font-semibold mb-1">Siswa</p>
                            <h4 class="text-base font-bold text-gray-900" id="modalSiswaName">-</h4>
                        </div>

                        {{-- Info Kegiatan --}}
                        <div class="mb-4 p-3 bg-gradient-to-r from-accent-green/10 to-emerald-50 rounded-lg">
                            <p class="text-xs text-gray-500 font-semibold mb-1">Kegiatan</p>
                            <p class="text-sm font-medium text-gray-800" id="modalKegiatan">-</p>
                        </div>

                        {{-- Input Nilai --}}
                        <div class="mb-4">
                            <label for="nilaiInput" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nilai <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="nilaiInput" 
                                   name="nilai" 
                                   min="0" 
                                   max="100" 
                                   required
                                   placeholder="0-100"
                                   class="w-full px-4 py-3 text-xl font-bold text-center border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300">
                            <p class="mt-1 text-xs text-gray-500 text-center">Masukkan nilai antara 0 - 100</p>
                        </div>

                        {{-- Catatan Admin --}}
                        <div class="mb-2">
                            <label for="catatanAdmin" class="block text-sm font-semibold text-gray-700 mb-2">
                                Catatan Admin (Opsional)
                            </label>
                            <textarea id="catatanAdmin" 
                                      name="catatan_admin"
                                      rows="2"
                                      placeholder="Masukkan catatan untuk siswa..."
                                      class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"></textarea>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 flex gap-2">
                        <button type="button" onclick="closeModal()" 
                            class="flex-1 px-3 py-2.5 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit" id="submitBtn"
                            class="flex-1 px-3 py-2.5 bg-gradient-to-r from-primary-500 to-accent-green text-white rounded-lg text-sm font-semibold hover:from-primary-600 hover:to-accent-green/90 transition-all duration-300 shadow hover:shadow-md flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
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

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const desktopRows = document.querySelectorAll('tbody tr');
                const mobileCards = document.querySelectorAll('.lg\\:hidden .glass-card');
                
                desktopRows.forEach(row => {
                    const searchData = row.getAttribute('data-search');
                    if (searchData && searchData.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                mobileCards.forEach(card => {
                    const searchData = card.getAttribute('data-search');
                    if (searchData && searchData.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });

    // Base URL untuk form action
    const baseUrl = '{{ route("nilai-manual-bermasyarakat.simpan", ":id") }}';

    // Open Modal
    function openModal(id, siswaName, kegiatan, currentNilai, catatanAdmin = '') {
        const modal = document.getElementById('nilaiModal');
        const form = document.getElementById('nilaiForm');
        const modalTitle = document.getElementById('modalTitle');
        
        // Set form action dengan ID yang benar
        form.action = baseUrl.replace(':id', id);
        
        // Set modal content
        document.getElementById('modalSiswaName').textContent = siswaName;
        document.getElementById('modalKegiatan').textContent = kegiatan;
        document.getElementById('nilaiInput').value = currentNilai || '';
        document.getElementById('catatanAdmin').value = catatanAdmin || '';
        
        // Update title berdasarkan apakah edit atau beri nilai baru
        modalTitle.textContent = currentNilai ? 'Edit Nilai' : 'Beri Nilai';
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus input
        setTimeout(() => {
            document.getElementById('nilaiInput').focus();
            document.getElementById('nilaiInput').select();
        }, 100);
    }

    // Close Modal
    function closeModal() {
        const modal = document.getElementById('nilaiModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset form
        document.getElementById('nilaiForm').reset();
    }

    // Form validation
    document.getElementById('nilaiForm').addEventListener('submit', function(e) {
        const nilaiInput = document.getElementById('nilaiInput');
        const nilai = parseInt(nilaiInput.value);
        
        if (isNaN(nilai) || nilai < 0 || nilai > 100) {
            e.preventDefault();
            nilaiInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
            alert('Nilai harus berupa angka antara 0 - 100!');
            nilaiInput.focus();
            nilaiInput.select();
            return false;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;
        submitBtn.disabled = true;
        
        // Reset button state if form submission fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Filter functionality
    document.getElementById('filterAll').addEventListener('click', function() {
        filterData('all');
    });

    document.getElementById('filterPending').addEventListener('click', function() {
        filterData('pending');
    });

    document.getElementById('filterDinilai').addEventListener('click', function() {
        filterData('approved');
    });

    function filterData(status) {
        const desktopRows = document.querySelectorAll('tbody tr');
        const mobileCards = document.querySelectorAll('.lg\\:hidden .glass-card');
        
        desktopRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            if (status === 'all' || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        mobileCards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'all' || cardStatus === status) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Auto hide alert after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
</body>
</html>
@endsection