@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-600 to-accent-green flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent mb-2">
                            Paraf Orang Tua - Kegiatan Bermasyarakat
                        </h1>
                        <p class="text-gray-500 text-sm">
                            Verifikasi dan paraf kegiatan sosial siswa
                        </p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('bermasyarakat.index') }}" 
                       class="bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Kegiatan
                    </a>
                </div>
            </div>
        </div>

     <!-- Filter Card -->
<div class="glass-card rounded-2xl shadow-lg p-6 mb-8">
    <form method="GET" action="{{ route('paraf-ortu-bermasyarakat.index') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-{{ auth()->check() && auth()->user()->peran === 'admin' ? '4' : '3' }} gap-4">
            <!-- Tanggal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $selectedTanggal }}" 
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all">
            </div>

            <!-- Search (HANYA untuk Admin) -->
            @if(auth()->check() && auth()->user()->peran === 'admin')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Cari Siswa/Kegiatan
                    <span class="ml-1 text-xs px-2 py-0.5 bg-primary-100 text-primary-700 rounded-full">Admin</span>
                </label>
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama, NIS, atau kegiatan..." 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all pr-10">
                    <div class="absolute right-3 top-3.5 text-primary-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Paraf</label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all">
                    <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="sudah" {{ $statusFilter == 'sudah' ? 'selected' : '' }}>Sudah Diparaf</option>
                    <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Diparaf</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-600 to-accent-green text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                    Terapkan Filter
                </button>
                <button type="button" onclick="resetFilter()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-300">
                    Reset
                </button>
            </div>
        </div>
    </form>

    <!-- Ordering Info -->
    <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <div class="flex items-center gap-1">
                <div class="order-indicator order-belum">1</div>
                <span>Belum Diparaf (Paling Atas)</span>
            </div>
            <div class="flex items-center gap-1 ml-4">
                <div class="order-indicator order-sudah">2</div>
                <span>Sudah Diparaf (Di Bawah)</span>
            </div>
        </div>
        
        @if(auth()->check() && auth()->user()->peran === 'admin')
        <div class="mt-2 text-xs text-primary-600 bg-primary-50 p-2 rounded-lg border border-primary-100">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Fitur pencarian khusus untuk Administrator</span>
            </div>
        </div>
        @endif
    </div>
</div>

        <!-- Data Table -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden">
            <!-- Table Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-primary-600 to-accent-green rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Kegiatan Bermasyarakat</h2>
                            <p class="text-gray-500 text-sm">
                                Menampilkan {{ $bermasyarakatList->firstItem() ?? 0 }} - {{ $bermasyarakatList->lastItem() ?? 0 }} dari {{ $bermasyarakatList->total() }} data
                                <span class="ml-3 px-2 py-1 rounded-full text-xs {{ $belumDiparaf > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $belumDiparaf }} belum, {{ $sudahDiparaf }} sudah diparaf
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span>Belum Diparaf</span>
                            <div class="w-3 h-3 rounded-full bg-green-500 ml-4"></div>
                            <span>Sudah Diparaf</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($bermasyarakatList->isEmpty())
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Data</h3>
                    <p class="text-gray-500 mb-6">Belum ada kegiatan bermasyarakat untuk tanggal yang dipilih.</p>
                </div>
            @else
                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-primary-50 to-accent-green/10">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Siswa & Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Paraf Ortu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bermasyarakatList as $index => $item)
                                <tr class="transition-all duration-200 {{ $item->sudah_ttd_ortu ? 'bg-gradient-to-r from-accent-green/5 to-primary-50/50' : 'bg-gradient-to-r from-yellow-50/50 to-amber-50/50' }}" 
                                    id="row-{{ $item->id }}"
                                    data-status="{{ $item->sudah_ttd_ortu ? 'sudah' : 'belum' }}">
                                    <!-- Nomor dengan indikator -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="order-indicator {{ $item->sudah_ttd_ortu ? 'order-sudah' : 'order-belum' }}">
                                                {{ ($bermasyarakatList->currentPage() - 1) * $bermasyarakatList->perPage() + $index + 1 }}
                                            </div>
                                            @if(!$item->sudah_ttd_ortu)
                                                <span class="text-xs text-yellow-600 font-bold ml-2">PRIORITAS</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <!-- Status Paraf -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->sudah_ttd_ortu)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Sudah
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Belum
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Data Siswa & Kegiatan -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if($item->thumbnail)
                                                    <img class="h-12 w-12 rounded-xl object-cover border-2 border-gray-200" 
                                                         src="{{ Storage::exists('bermasyarakat/' . $item->thumbnail) ? asset('storage/bermasyarakat/' . $item->thumbnail) : asset('storage/' . $item->thumbnail) }}" 
                                                         alt="Thumbnail kegiatan"
                                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name='+encodeURIComponent('{{ $item->nama_kegiatan }}')+'&background=0033A0&color=fff&size=128';">
                                                @else
                                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-primary-100 to-accent-green/20 flex items-center justify-center border-2 border-gray-200">
                                                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $item->siswa->nama_lengkap ?? $item->siswa->nama ?? 'Siswa tidak ditemukan' }}
                                                </div>
                                                <div class="text-sm text-gray-900 font-medium">
                                                    {{ $item->nama_kegiatan }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    NIS: {{ $item->siswa->nis ?? '-' }}
                                                    @if($item->siswa && $item->siswa->kelas)
                                                        | Kelas: {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- Tanggal -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">{{ $item->tanggal_formatted }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->nama_hari }}</div>
                                    </td>
                                    
                                    <!-- Status Kegiatan -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status == 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Dinilai
                                            </span>
                                            @if($item->nilai)
                                                <div class="text-xs text-gray-600 mt-1">Nilai: {{ $item->nilai }}</div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <!-- Status Paraf -->
                                    <td class="px-6 py-4 whitespace-nowrap" id="paraf-status-{{ $item->id }}">
                                        @if($item->sudah_ttd_ortu)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Sudah
                                            </span>
                                            @if($item->parafOrtu)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $item->parafOrtu->waktu_paraf_formatted ?? '' }}
                                                </div>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 cursor-pointer hover:bg-gray-200 transition-colors"
                                                  onclick="showParafModal({{ $item->id }})">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Beri Paraf
                                            </span>
                                            <div class="text-xs text-yellow-600 mt-1">
                                                Butuh validasi
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" id="aksi-{{ $item->id }}">
                                        <div class="flex flex-col space-y-2">
                                            <a href="{{ route('bermasyarakat.show', $item->id) }}" 
                                               class="text-primary-600 hover:text-primary-900 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                            @if($item->sudah_ttd_ortu && $item->parafOrtu)
                                                <button onclick="batalkanParaf({{ $item->parafOrtu->id }})" 
                                                        class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Batalkan
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bermasyarakatList->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $bermasyarakatList->firstItem() ?? 0 }} - {{ $bermasyarakatList->lastItem() ?? 0 }} dari {{ $bermasyarakatList->total() }} data
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $bermasyarakatList->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal Paraf -->
<div id="parafModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Beri Paraf Orang Tua</h3>
                <button onclick="hideParafModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-1" id="modalDetail"></p>
        </div>
        
        <form id="parafForm" class="p-6 space-y-4">
            @csrf
            <input type="hidden" id="bermasyarakat_id" name="bermasyarakat_id">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua</label>
                <select id="nama_ortu" name="nama_ortu" required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all text-sm">
                    <option value="">Pilih Orang Tua</option>
                </select>
            </div>
            
            <!-- Input untuk orangtua lainnya -->
            <div id="otherNameInput" class="hidden mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="text-red-500">*</span> Nama Orang Tua/Wali
                </label>
                <input type="text" id="otherNamaOrtu" 
                       placeholder="Masukkan nama lengkap"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="3"
                          class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-600 focus:border-primary-600 transition-all text-sm"
                          placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>
        </form>
        
        <div class="p-6 border-t border-gray-200 flex space-x-3">
            <button type="button" onclick="hideParafModal()"
                    class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 font-semibold">
                Batal
            </button>
            <button type="button" onclick="submitParaf()" id="submitParafBtn"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-primary-600 to-accent-green text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 font-semibold">
                Simpan Paraf
            </button>
        </div>
    </div>
</div>

<script>
// Data sementara
let currentBermasyarakatId = null;

// Reset Filter
function resetFilter() {
    document.getElementById('filterForm').reset();
    document.querySelector('input[name="tanggal"]').value = '{{ date("Y-m-d") }}';
    document.getElementById('filterForm').submit();
}

// Paraf modal functions
async function showParafModal(id) {
    currentBermasyarakatId = id;
    
    try {
        const response = await fetch(`/paraf-ortu-bermasyarakat/detail/${id}`);
        const result = await response.json();
        
        if (result.success) {
            const data = result.data;
            document.getElementById('modalDetail').innerHTML = `
                <strong>${data.nama_kegiatan}</strong> - ${data.siswa_nama} (${data.siswa_nis})<br>
                <small class="text-gray-400">${data.tanggal} • ${data.hari}</small>
            `;
            document.getElementById('bermasyarakat_id').value = id;
            
            // Populate orang tua options
            await loadOrangtuaOptionsForModal(data.orangtua_options || []);
            
            document.getElementById('parafModal').classList.remove('hidden');
            document.getElementById('parafModal').classList.add('flex');
        } else {
            showMessage('error', 'Gagal memuat data: ' + result.message);
        }
    } catch (error) {
        showMessage('error', 'Terjadi kesalahan: ' + error.message);
    }
}

// Load orangtua options untuk modal
async function loadOrangtuaOptionsForModal(options) {
    try {
        const select = document.getElementById('nama_ortu');
        const otherInputDiv = document.getElementById('otherNameInput');
        
        // Clear existing options except first one
        while (select.options.length > 1) {
            select.remove(1);
        }
        
        // Add options if available
        if (options && options.length > 0) {
            options.forEach(ortu => {
                const option = document.createElement('option');
                option.value = ortu.value;
                option.textContent = ortu.label;
                select.appendChild(option);
            });
        } else {
            // Load dari API jika tidak ada options
            await loadOrangtuaFromAPI();
        }
        
        // Selalu tambahkan opsi lainnya
        const otherOption = document.createElement('option');
        otherOption.value = 'orangtua_lainnya';
        otherOption.textContent = 'Orang Tua/Wali Lainnya';
        select.appendChild(otherOption);
        
        // Reset other input
        if (otherInputDiv) {
            otherInputDiv.classList.add('hidden');
            document.getElementById('otherNamaOrtu').value = '';
        }
        
    } catch (error) {
        console.error('Error loading orangtua options for modal:', error);
    }
}

// Load orangtua dari API
async function loadOrangtuaFromAPI() {
    try {
        const response = await fetch('/paraf-ortu-bermasyarakat/orangtua/list');
        const result = await response.json();
        
        if (result.success) {
            const select = document.getElementById('nama_ortu');
            
            result.data.forEach(ortu => {
                const option = document.createElement('option');
                option.value = ortu.value;
                option.textContent = ortu.label;
                select.appendChild(option);
            });
        } else {
            console.error('Failed to load orangtua from API:', result.message);
        }
    } catch (error) {
        console.error('Error loading orangtua from API:', error);
    }
}

function hideParafModal() {
    document.getElementById('parafModal').classList.add('hidden');
    document.getElementById('parafModal').classList.remove('flex');
    currentBermasyarakatId = null;
}

async function submitParaf() {
    const bermasyarakatId = document.getElementById('bermasyarakat_id').value;
    let namaOrtu = document.getElementById('nama_ortu').value;
    const otherNamaOrtu = document.getElementById('otherNamaOrtu').value;
    const catatan = document.getElementById('catatan').value;
    
    // Jika memilih "orangtua_lainnya", gunakan input teks
    if (namaOrtu === 'orangtua_lainnya') {
        if (!otherNamaOrtu?.trim()) {
            showMessage('error', 'Masukkan nama orang tua/wali');
            return;
        }
        namaOrtu = otherNamaOrtu.trim();
    }
    
    if (!namaOrtu.trim()) {
        showMessage('error', 'Pilih atau masukkan nama orang tua');
        return;
    }
    
    // Disable button untuk prevent double submit
    const submitBtn = document.getElementById('submitParafBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    
    try {
        const response = await fetch('{{ route("paraf-ortu-bermasyarakat.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                bermasyarakat_id: bermasyarakatId,
                nama_ortu: namaOrtu,
                catatan: catatan
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('success', result.message);
            hideParafModal();
            
            // Update UI tanpa reload
            updateUIAfterParaf(bermasyarakatId, result.paraf_id);
            
            // Update statistik tanpa reload
            updateStatistik();
        } else {
            if (result.message.includes('sudah ada')) {
                showMessage('info', 'Data sudah diparaf sebelumnya');
            } else {
                showMessage('error', result.message);
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    } catch (error) {
        showMessage('error', 'Terjadi kesalahan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

// Update UI setelah paraf
function updateUIAfterParaf(bermasyarakatId, parafId = null) {
    // Update desktop row
    const row = document.getElementById(`row-${bermasyarakatId}`);
    if (row) {
        // Ubah status dari "belum" menjadi "sudah"
        row.dataset.status = 'sudah';
        
        // Update background color
        row.className = row.className.replace('from-yellow-50/50 to-amber-50/50', 'from-accent-green/5 to-primary-50/50');
        row.classList.add('bg-gradient-to-r', 'from-accent-green/5', 'to-primary-50/50');
        
        // Update status paraf
        const statusCell = row.querySelector(`td:nth-child(2)`);
        if (statusCell) {
            statusCell.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sudah
                </span>
            `;
        }
        
        // Update order indicator
        const orderIndicator = row.querySelector('.order-indicator');
        if (orderIndicator) {
            orderIndicator.className = 'order-indicator order-sudah';
        }
        
        // Update status paraf
        const parafCell = document.getElementById(`paraf-status-${bermasyarakatId}`);
        if (parafCell) {
            parafCell.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Sudah
                </span>
                <div class="text-xs text-green-600 font-medium mt-1">
                    Baru saja
                </div>
            `;
        }
        
        // Update aksi dengan link ke show
        const aksiCell = document.getElementById(`aksi-${bermasyarakatId}`);
        if (aksiCell) {
            aksiCell.innerHTML = `
                <div class="flex flex-col space-y-2">
                    <a href="{{ route('bermasyarakat.show', $item->id) }}" 
                       class="text-primary-600 hover:text-primary-900 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Detail
                    </a>
                    ${parafId ? `
                        <button onclick="batalkanParaf(${parafId})" 
                                class="text-red-600 hover:text-red-900 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan
                        </button>
                    ` : ''}
                </div>
            `;
        }
        
        // Pindahkan row ke bagian bawah (data sudah diparaf)
        setTimeout(() => {
            reorderTableRows();
        }, 100);
    }
}

// Fungsi untuk mengurutkan ulang baris di table
function reorderTableRows() {
    // Ambil semua row
    const rows = Array.from(document.querySelectorAll('tbody tr'));
    
    // Urutkan: belum diparaf dulu, lalu sudah diparaf
    rows.sort((a, b) => {
        const aStatus = a.dataset.status;
        const bStatus = b.dataset.status;
        
        if (aStatus === 'belum' && bStatus === 'sudah') return -1;
        if (aStatus === 'sudah' && bStatus === 'belum') return 1;
        return 0;
    });
    
    // Update nomor urut
    rows.forEach((row, index) => {
        const orderIndicator = row.querySelector('.order-indicator');
        if (orderIndicator) {
            orderIndicator.textContent = index + 1;
            orderIndicator.className = row.dataset.status === 'belum' ? 
                'order-indicator order-belum' : 'order-indicator order-sudah';
        }
    });
}

// Batalkan paraf
async function batalkanParaf(parafId) {
    if (!confirm('Yakin ingin membatalkan paraf ini?')) return;
    
    try {
        const response = await fetch(`/paraf-ortu-bermasyarakat/batalkan/${parafId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showMessage('success', 'Paraf berhasil dibatalkan!');
            location.reload();
        } else {
            showMessage('error', 'Gagal membatalkan paraf: ' + result.message);
        }
    } catch (error) {
        showMessage('error', 'Terjadi kesalahan: ' + error.message);
    }
}

// Update statistik tanpa reload
async function updateStatistik() {
    try {
        const tanggal = document.querySelector('input[name="tanggal"]').value;
        const response = await fetch(`/paraf-ortu-bermasyarakat/statistik/data?tanggal=${tanggal}`);
        const data = await response.json();
        
        if (data.success) {
            // Update statistik cards
            document.getElementById('totalData').textContent = data.totalData;
            document.getElementById('sudahDiparaf').textContent = data.sudahDiparaf;
            document.getElementById('belumDiparaf').textContent = data.belumDiparaf;
        }
    } catch (error) {
        console.error('Error updating statistik:', error);
    }
}

// Show Message
function showMessage(type, message) {
    const bgColor = type === 'success' ? 
        'bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green' : 
        type === 'info' ?
        'bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4 border-blue-500' :
        'bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500';
    const textColor = type === 'success' ? 'text-gray-900' : 'text-gray-900';
    const iconColor = type === 'success' ? 'text-accent-green' : 
                     type === 'info' ? 'text-blue-500' : 'text-red-500';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        : type === 'info' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
    
    const messageHtml = `
        <div class="glass-card ${bgColor} rounded-xl p-4 mb-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
                <p class="${textColor} font-medium text-sm">${message}</p>
            </div>
        </div>
    `;
    
    // Create message container if doesn't exist
    let container = document.getElementById('messageContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'messageContainer';
        container.className = 'container mx-auto px-4 mb-6';
        document.querySelector('.min-h-screen').insertBefore(container, document.querySelector('.container'));
    }
    
    container.innerHTML = messageHtml;
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        const messageEl = container.querySelector('.glass-card');
        if (messageEl) {
            messageEl.style.opacity = '0';
            messageEl.style.transition = 'opacity 0.5s';
            setTimeout(() => {
                container.innerHTML = '';
            }, 500);
        }
    }, 5000);
}

// Event listener untuk dropdown orangtua
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('nama_ortu');
    const otherInputDiv = document.getElementById('otherNameInput');
    
    if (select) {
        select.addEventListener('change', function() {
            if (this.value === 'orangtua_lainnya') {
                if (otherInputDiv) {
                    otherInputDiv.classList.remove('hidden');
                    document.getElementById('otherNamaOrtu').focus();
                }
            } else {
                if (otherInputDiv) {
                    otherInputDiv.classList.add('hidden');
                    document.getElementById('otherNamaOrtu').value = '';
                }
            }
        });
    }

    // Close modal on outside click
    document.getElementById('parafModal').addEventListener('click', function(e) {
        if (e.target === this) hideParafModal();
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideParafModal();
        }
    });

    // Order indicators
    const orderIndicator = document.createElement('style');
    orderIndicator.innerHTML = `
        .order-indicator {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .order-belum {
            background: #fbbf24;
            color: #92400e;
        }
        
        .order-sudah {
            background: #10b981;
            color: white;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    `;
    document.head.appendChild(orderIndicator);
});
</script>
@endsection