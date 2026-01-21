@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengaturan Waktu Tidur - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .gradient-primary {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
        }

        .gradient-primary-text {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .color-preview {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 8px;
            border: 1px solid #e5e7eb;
        }

        .draggable-item {
            cursor: move;
            transition: all 0.3s ease;
        }

        .draggable-item.dragging {
            opacity: 0.5;
            transform: scale(0.98);
        }

        .drop-zone {
            min-height: 20px;
            transition: all 0.3s ease;
        }

        .drop-zone.drag-over {
            background-color: rgba(0, 123, 255, 0.1);
            border-color: #007bff;
        }
        
        .disabled-input {
            background-color: #f9fafb !important;
            color: #6b7280 !important;
            cursor: not-allowed !important;
        }
        
        .readonly-badge {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #374151;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .locked-field {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fbbf24;
            padding: 12px;
            border-radius: 8px;
            position: relative;
        }
        
        .locked-icon {
            position: absolute;
            top: 8px;
            right: 8px;
            color: #d97706;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Debug Alert -->
        @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat {{ count($errors) }} kesalahan:</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('pengaturan-waktu-tidur-cepat.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-primary-500 transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold gradient-primary-text">
                            Edit Pengaturan Waktu Tidur
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Nama pengaturan dan kategori tidak dapat diubah setelah dibuat
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 gradient-primary rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Edit Data Pengaturan</h2>
                        <p class="text-gray-500 text-sm">Perbarui pengaturan yang sudah ada</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('pengaturan-waktu-tidur-cepat.update') }}" method="POST" class="p-8" id="settingForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <!-- Informasi Pengaturan -->
                    <div class="fade-in" style="animation-delay: 0.15s;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Pengaturan (READONLY) -->
                            <div class="locked-field">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Nama Pengaturan
                                    </label>
                                    <svg class="locked-icon w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="nama_pengaturan" 
                                    readonly
                                    class="disabled-input w-full px-4 py-3 border border-yellow-200 rounded-lg bg-yellow-50 text-gray-900"
                                    value="{{ old('nama_pengaturan', $pengaturan->nama_pengaturan) }}"
                                >
                                <p class="text-xs text-yellow-600 mt-2">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    Nama pengaturan terkunci dan tidak dapat diubah
                                </p>
                            </div>
                            
                            <!-- Deskripsi (EDITABLE) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi *
                                </label>
                                <textarea 
                                    name="deskripsi" 
                                    rows="2"
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                    placeholder="Contoh: Pengaturan untuk siswa SD kelas 1-3"
                                >{{ old('deskripsi', $pengaturan->deskripsi) }}</textarea>
                                <p class="text-xs text-gray-500 mt-2">Deskripsi dapat diperbarui</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <label class="flex items-center p-4 bg-gradient-to-r from-primary-50 to-accent-green/20 rounded-xl hover:bg-primary-100 transition-colors cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" 
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                   {{ old('is_active', $pengaturan->is_active) == '1' ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700 font-medium">Aktifkan pengaturan ini</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2 ml-1">Jika dinonaktifkan, sistem tidak akan menggunakan pengaturan ini untuk perhitungan nilai</p>
                    </div>

                    <!-- Kategori Waktu -->
                    <div class="fade-in" style="animation-delay: 0.25s;">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
                                <h3 class="text-lg font-semibold text-gray-900">Kategori Waktu Tidur</h3>
                                <span class="readonly-badge">
                                    Nama kategori terkunci
                                </span>
                            </div>
                            @php
                                // Dapatkan kategori dari JSON
                                $kategoriArray = is_array($pengaturan->kategori_waktu) 
                                    ? $pengaturan->kategori_waktu 
                                    : json_decode($pengaturan->kategori_waktu, true);
                                $kategoriCount = is_array($kategoriArray) ? count($kategoriArray) : 0;
                            @endphp
                            
                            @if($kategoriCount < 5)
                            <button type="button" 
                                    onclick="tambahKategori()"
                                    class="flex items-center space-x-2 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Kategori</span>
                            </button>
                            @endif
                        </div>
                        
                        <!-- Container Kategori (Sortable) -->
                        <div id="kategoriContainer" class="space-y-4">
                            <!-- Kategori akan ditambahkan di sini secara dinamis dari data existing -->
                            @php
                                $kategoriArray = is_array($pengaturan->kategori_waktu) 
                                    ? $pengaturan->kategori_waktu 
                                    : json_decode($pengaturan->kategori_waktu, true);
                            @endphp
                            
                            @if(is_array($kategoriArray))
                                @foreach($kategoriArray as $index => $kategori)
                                <div class="kategori-item draggable-item bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-300 transition-all duration-300">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="drag-handle cursor-move p-2 text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zM7 4h6v12H7V4z"/>
                                                </svg>
                                            </div>
                                            <h4 class="text-md font-semibold text-gray-900 kategori-nama">{{ $kategori['nama'] ?? 'Kategori' }}</h4>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="readonly-badge text-xs">
                                                Nama terkunci
                                            </span>
                                            @if($kategoriCount > 1)
                                            <button type="button" 
                                                    onclick="hapusKategori(this, {{ $kategori['id'] ?? $index }})"
                                                    class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Hidden input untuk kategori ID -->
                                    <input type="hidden" name="kategori[{{ $index }}][id]" value="{{ $kategori['id'] ?? ($index + 1) }}">
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <!-- Nama Kategori (READONLY) -->
                                        <div class="locked-field">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nama Kategori
                                            </label>
                                            <input 
                                                type="text" 
                                                name="kategori[{{ $index }}][nama]" 
                                                readonly
                                                class="disabled-input w-full px-3 py-2 border border-yellow-200 rounded-lg bg-yellow-50"
                                                value="{{ old('kategori.'.$index.'.nama', $kategori['nama'] ?? '') }}"
                                            >
                                        </div>
                                        
                                        <!-- Waktu Mulai (EDITABLE) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Waktu Mulai *
                                            </label>
                                            <input 
                                                type="time" 
                                                name="kategori[{{ $index }}][waktu_start]" 
                                                required 
                                                class="waktu-start w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                                value="{{ old('kategori.'.$index.'.waktu_start', $kategori['waktu_start'] ?? '20:00') }}"
                                            >
                                        </div>
                                        
                                        <!-- Waktu Selesai (EDITABLE) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Waktu Selesai *
                                            </label>
                                            <input 
                                                type="time" 
                                                name="kategori[{{ $index }}][waktu_end]" 
                                                required 
                                                class="waktu-end w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                                value="{{ old('kategori.'.$index.'.waktu_end', $kategori['waktu_end'] ?? '21:00') }}"
                                            >
                                        </div>
                                        
                                        <!-- Nilai (EDITABLE) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Nilai (0-100) *
                                            </label>
                                            <input 
                                                type="number" 
                                                name="kategori[{{ $index }}][nilai]" 
                                                min="0" 
                                                max="100"
                                                required 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                                value="{{ old('kategori.'.$index.'.nilai', $kategori['nilai'] ?? 100) }}"
                                            >
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Warna (EDITABLE) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Warna
                                            </label>
                                            <div class="flex items-center space-x-2">
                                                <input 
                                                    type="color" 
                                                    name="kategori[{{ $index }}][warna]" 
                                                    class="warna-picker w-10 h-10 cursor-pointer rounded-lg border border-gray-300"
                                                    value="{{ old('kategori.'.$index.'.warna', $kategori['warna'] ?? '#10B981') }}"
                                                >
                                                <span class="color-preview" style="background-color: {{ $kategori['warna'] ?? '#10B981' }}"></span>
                                                <input 
                                                    type="text" 
                                                    class="warna-text flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                                    placeholder="#10B981"
                                                    value="{{ old('kategori.'.$index.'.warna', $kategori['warna'] ?? '#10B981') }}"
                                                >
                                            </div>
                                        </div>
                                        
                                        <!-- Urutan (READONLY but updated by drag-drop) -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Urutan
                                            </label>
                                            <input 
                                                type="number" 
                                                name="kategori[{{ $index }}][urutan]" 
                                                min="1"
                                                required 
                                                readonly
                                                class="urutan-input disabled-input w-full px-3 py-2 border border-gray-300 rounded-lg"
                                                value="{{ old('kategori.'.$index.'.urutan', $kategori['urutan'] ?? $index + 1) }}"
                                            >
                                        </div>
                                        
                                        <!-- Status Aktif (EDITABLE) -->
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input 
                                                    type="checkbox" 
                                                    name="kategori[{{ $index }}][is_active]" 
                                                    value="1"
                                                    class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                    {{ (old('kategori.'.$index.'.is_active', $kategori['is_active'] ?? true) == true) ? 'checked' : '' }}
                                                >
                                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Pesan Validasi -->
                                    <div class="validasi-pesan mt-3 text-xs text-red-500 hidden"></div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Template Kategori Baru (Hidden) -->
                        <template id="kategoriTemplate">
                            <div class="kategori-item draggable-item bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-300 transition-all duration-300">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="drag-handle cursor-move p-2 text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zM7 4h6v12H7V4z"/>
                                            </svg>
                                        </div>
                                        <h4 class="text-md font-semibold text-gray-900 kategori-nama">Kategori Baru</h4>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="readonly-badge text-xs">
                                            Nama terkunci
                                        </span>
                                        <button type="button" 
                                                onclick="hapusKategori(this)"
                                                class="text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                    <!-- Nama Kategori (READONLY) -->
                                    <div class="locked-field">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Kategori
                                        </label>
                                        <input 
                                            type="text" 
                                            name="kategori[NEW_INDEX][nama]" 
                                            readonly
                                            class="kategori-nama-input disabled-input w-full px-3 py-2 border border-yellow-200 rounded-lg bg-yellow-50"
                                            placeholder="Contoh: Tepat Waktu"
                                        >
                                    </div>
                                    
                                    <!-- Waktu Mulai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Waktu Mulai *
                                        </label>
                                        <input 
                                            type="time" 
                                            name="kategori[NEW_INDEX][waktu_start]" 
                                            required 
                                            class="waktu-start w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                            value="22:00"
                                        >
                                    </div>
                                    
                                    <!-- Waktu Selesai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Waktu Selesai *
                                        </label>
                                        <input 
                                            type="time" 
                                            name="kategori[NEW_INDEX][waktu_end]" 
                                            required 
                                            class="waktu-end w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                            value="23:00"
                                        >
                                    </div>
                                    
                                    <!-- Nilai -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nilai (0-100) *
                                        </label>
                                        <input 
                                            type="number" 
                                            name="kategori[NEW_INDEX][nilai]" 
                                            min="0" 
                                            max="100"
                                            required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                            value="50"
                                        >
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Warna -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Warna
                                        </label>
                                        <div class="flex items-center space-x-2">
                                            <input 
                                                type="color" 
                                                name="kategori[NEW_INDEX][warna]" 
                                                class="warna-picker w-10 h-10 cursor-pointer rounded-lg border border-gray-300"
                                                value="#6B7280"
                                            >
                                            <span class="color-preview"></span>
                                            <input 
                                                type="text" 
                                                class="warna-text flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-500"
                                                placeholder="#6B7280"
                                            >
                                        </div>
                                    </div>
                                    
                                    <!-- Urutan -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Urutan
                                        </label>
                                        <input 
                                            type="number" 
                                            name="kategori[NEW_INDEX][urutan]" 
                                            min="1"
                                            required 
                                            readonly
                                            class="urutan-input disabled-input w-full px-3 py-2 border border-gray-300 rounded-lg"
                                            value="99"
                                        >
                                    </div>
                                    
                                    <!-- Status Aktif -->
                                    <div class="flex items-center pt-6">
                                        <label class="flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="kategori[NEW_INDEX][is_active]" 
                                                value="1"
                                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                checked
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Pesan Validasi -->
                                <div class="validasi-pesan mt-3 text-xs text-red-500 hidden"></div>
                            </div>
                        </template>
                        
                        <!-- Drop Zone untuk reordering -->
                        <div class="drop-zone mt-4 border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hidden">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">Lepaskan di sini untuk mengubah urutan</p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.35s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Perbarui Pengaturan
                        </span>
                    </button>
                    <a href="{{ route('pengaturan-waktu-tidur-cepat.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batalkan
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                        </span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let kategoriCounter = {{ $kategoriCount }};
        let sortableInstance = null;
        const kategoriNames = ['Tepat Waktu', 'Cukup Tepat', 'Terlambat', 'Sangat Terlambat', 'Tidak Tepat'];

        document.addEventListener('DOMContentLoaded', function() {
            // Setup warna picker untuk kategori existing
            document.querySelectorAll('.kategori-item').forEach(item => {
                const colorPicker = item.querySelector('.warna-picker');
                const colorText = item.querySelector('.warna-text');
                const colorPreview = item.querySelector('.color-preview');
                
                if (colorPicker && colorText && colorPreview) {
                    // Set initial values
                    colorPreview.style.backgroundColor = colorPicker.value;
                    
                    // Add event listeners
                    colorPicker.addEventListener('input', function() {
                        colorText.value = this.value;
                        colorPreview.style.backgroundColor = this.value;
                    });
                    
                    colorText.addEventListener('input', function() {
                        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                            colorPicker.value = this.value;
                            colorPreview.style.backgroundColor = this.value;
                        }
                    });
                    
                    // Setup validasi waktu
                    const waktuStart = item.querySelector('.waktu-start');
                    const waktuEnd = item.querySelector('.waktu-end');
                    const validasiPesan = item.querySelector('.validasi-pesan');
                    
                    if (waktuStart && waktuEnd && validasiPesan) {
                        function validateKategoriTime() {
                            if (!waktuStart.value || !waktuEnd.value) {
                                return true;
                            }
                            
                            const start = waktuStart.value;
                            const end = waktuEnd.value;
                            
                            if (start === end) {
                                validasiPesan.textContent = 'Waktu mulai dan selesai tidak boleh sama';
                                validasiPesan.classList.remove('hidden');
                                return false;
                            }
                            
                            const startHour = parseInt(start.split(':')[0]);
                            const endHour = parseInt(end.split(':')[0]);
                            let diff = endHour - startHour;
                            
                            if (diff < 0) {
                                diff += 24;
                            }
                            
                            if (diff > 12) {
                                validasiPesan.textContent = 'Rentang waktu maksimal 12 jam';
                                validasiPesan.classList.remove('hidden');
                                return false;
                            }
                            
                            validasiPesan.classList.add('hidden');
                            return true;
                        }
                        
                        waktuStart.addEventListener('change', validateKategoriTime);
                        waktuEnd.addEventListener('change', validateKategoriTime);
                    }
                }
            });
            
            // Inisialisasi drag and drop
            initSortable();
            
            updateUrutan();
        });

        function tambahKategori() {
            const template = document.getElementById('kategoriTemplate');
            const container = document.getElementById('kategoriContainer');
            
            if (container.querySelectorAll('.kategori-item').length >= 5) {
                alert('Maksimal 5 kategori per pengaturan');
                return;
            }
            
            // Clone template
            const newKategori = template.content.cloneNode(true);
            const kategoriDiv = newKategori.querySelector('.kategori-item');
            
            // Update index
            const index = kategoriCounter++;
            const inputs = kategoriDiv.querySelectorAll('input, select, textarea');
            
            // Set nama kategori berdasarkan urutan (TIDAK BISA DIUBAH)
            const existingCount = container.querySelectorAll('.kategori-item').length;
            const namaKategori = kategoriNames[existingCount] || `Kategori ${existingCount + 1}`;
            
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace('NEW_INDEX', 'new_' + index);
                }
                
                // Set nama kategori (READONLY)
                if (input.name && input.name.includes('nama')) {
                    input.value = namaKategori;
                    input.readOnly = true;
                }
            });
            
            // Update nama di header
            const namaHeader = kategoriDiv.querySelector('.kategori-nama');
            namaHeader.textContent = namaKategori;
            
            // Setup color picker
            const colorPicker = kategoriDiv.querySelector('.warna-picker');
            const colorText = kategoriDiv.querySelector('.warna-text');
            const colorPreview = kategoriDiv.querySelector('.color-preview');
            
            colorPicker.addEventListener('input', function() {
                colorText.value = this.value;
                colorPreview.style.backgroundColor = this.value;
            });
            
            colorText.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    colorPicker.value = this.value;
                    colorPreview.style.backgroundColor = this.value;
                }
            });
            
            // Set initial color preview
            colorPreview.style.backgroundColor = colorPicker.value;
            colorText.value = colorPicker.value;
            
            // Setup validasi waktu
            const waktuStart = kategoriDiv.querySelector('.waktu-start');
            const waktuEnd = kategoriDiv.querySelector('.waktu-end');
            const validasiPesan = kategoriDiv.querySelector('.validasi-pesan');
            
            function validateKategoriTime() {
                if (!waktuStart.value || !waktuEnd.value) {
                    return true;
                }
                
                const start = waktuStart.value;
                const end = waktuEnd.value;
                
                if (start === end) {
                    validasiPesan.textContent = 'Waktu mulai dan selesai tidak boleh sama';
                    validasiPesan.classList.remove('hidden');
                    return false;
                }
                
                const startHour = parseInt(start.split(':')[0]);
                const endHour = parseInt(end.split(':')[0]);
                let diff = endHour - startHour;
                
                if (diff < 0) {
                    diff += 24;
                }
                
                if (diff > 12) {
                    validasiPesan.textContent = 'Rentang waktu maksimal 12 jam';
                    validasiPesan.classList.remove('hidden');
                    return false;
                }
                
                validasiPesan.classList.add('hidden');
                return true;
            }
            
            waktuStart.addEventListener('change', validateKategoriTime);
            waktuEnd.addEventListener('change', validateKategoriTime);
            
            // Tambahkan ke container
            container.appendChild(newKategori);
            
            // Update urutan
            updateUrutan();
            
            // Re-init sortable
            if (sortableInstance) {
                sortableInstance.destroy();
            }
            initSortable();
        }

        function hapusKategori(button, kategoriId = null) {
            const container = document.getElementById('kategoriContainer');
            const kategoriItems = container.querySelectorAll('.kategori-item');
            
            if (kategoriItems.length <= 1) {
                alert('Minimal harus ada 1 kategori');
                return;
            }
            
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
                const kategoriDiv = button.closest('.kategori-item');
                
                // Untuk kategori existing, tambahkan input hidden untuk menandai hapus
                if (kategoriId) {
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'kategori_deleted[]';
                    deleteInput.value = kategoriId;
                    kategoriDiv.appendChild(deleteInput);
                    
                    // Sembunyikan kategori (tidak hapus sepenuhnya untuk mempertahankan data)
                    kategoriDiv.style.opacity = '0.5';
                    kategoriDiv.querySelectorAll('input, select, textarea').forEach(input => {
                        input.disabled = true;
                    });
                } else {
                    // Untuk kategori baru, hapus langsung
                    kategoriDiv.remove();
                }
                
                updateUrutan();
            }
        }

        function initSortable() {
            const container = document.getElementById('kategoriContainer');
            const dropZone = container.nextElementSibling;
            
            sortableInstance = new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'dragging',
                dragClass: 'draggable-item',
                onStart: function() {
                    dropZone.classList.remove('hidden');
                },
                onEnd: function() {
                    dropZone.classList.add('hidden');
                    updateUrutan();
                },
                onAdd: function() {
                    updateUrutan();
                }
            });
        }

        function updateUrutan() {
            const container = document.getElementById('kategoriContainer');
            const kategoriItems = container.querySelectorAll('.kategori-item');
            
            kategoriItems.forEach((item, index) => {
                const urutanInput = item.querySelector('.urutan-input');
                if (urutanInput) {
                    urutanInput.value = index + 1;
                }
            });
        }

        // Validasi form sebelum submit
        document.getElementById('settingForm').addEventListener('submit', function(e) {
            const kategoriItems = document.querySelectorAll('.kategori-item');
            const enabledKategoriItems = Array.from(kategoriItems).filter(item => 
                item.style.opacity !== '0.5'
            );
            
            if (enabledKategoriItems.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 kategori aktif');
                return;
            }
            
            // Validasi deskripsi
            const deskripsi = document.querySelector('textarea[name="deskripsi"]').value;
            if (!deskripsi.trim()) {
                e.preventDefault();
                alert('Deskripsi harus diisi');
                return;
            }
            
            // Validasi waktu tidak tumpang tindih
            const ranges = [];
            let isValid = true;
            
            enabledKategoriItems.forEach((item, index) => {
                const start = item.querySelector('.waktu-start');
                const end = item.querySelector('.waktu-end');
                
                if (start && end && start.value && end.value) {
                    ranges.push({
                        index: index + 1,
                        start: start.value,
                        end: end.value
                    });
                }
            });
            
            // Cek tumpang tindih
            for (let i = 0; i < ranges.length; i++) {
                for (let j = i + 1; j < ranges.length; j++) {
                    if (checkTimeOverlap(ranges[i], ranges[j])) {
                        alert(`Kategori ${ranges[i].index} dan ${ranges[j].index} memiliki waktu yang tumpang tindih`);
                        isValid = false;
                        break;
                    }
                }
                if (!isValid) break;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });

        function checkTimeOverlap(range1, range2) {
            const start1 = range1.start.split(':').map(Number);
            const end1 = range1.end.split(':').map(Number);
            const start2 = range2.start.split(':').map(Number);
            const end2 = range2.end.split(':').map(Number);
            
            const start1Min = start1[0] * 60 + start1[1];
            const end1Min = end1[0] * 60 + end1[1];
            const start2Min = start2[0] * 60 + start2[1];
            const end2Min = end2[0] * 60 + end2[1];
            
            const isOvernight1 = end1Min < start1Min;
            const isOvernight2 = end2Min < start2Min;
            
            if (isOvernight1 && isOvernight2) {
                return true;
            } else if (isOvernight1) {
                return start2Min < end1Min || end2Min >= start1Min;
            } else if (isOvernight2) {
                return start1Min < end2Min || end1Min >= start2Min;
            } else {
                return start1Min < end2Min && start2Min < end1Min;
            }
        }
    </script>
</body>
</html>
@endsection