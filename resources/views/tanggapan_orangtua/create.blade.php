@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Tanggapan Orangtua - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .user-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .badge-blue {
            background: linear-gradient(135deg, #0033A0 0%, #2D68C4 100%);
            color: white;
        }

        .badge-green {
            background: linear-gradient(135deg, #00A86B 0%, #2ECC71 100%);
            color: white;
        }

        .signature-canvas {
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            touch-action: none;
            background: white;
        }

        .select-arrow {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .signature-preview {
            background: linear-gradient(45deg, #f8fafc 0%, #f1f5f9 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Debug Alert -->
        @if ($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat {{ count($errors) }} error:</h3>
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
        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl fade-in">
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
                    <a href="{{ route('tanggapan-orangtua.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#0033A0] transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-[#0033A0] rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent">
                            Tambah Tanggapan Baru
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Isi tanggapan perkembangan anak Anda
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Siswa Card -->
        @if($siswas->count() > 0)
            @php $siswa = $siswas->first(); @endphp
            <div class="glass-card rounded-2xl shadow-lg mb-8 p-6 fade-in" style="animation-delay: 0.1s;">
                <div class="flex items-start space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $siswa->nama_lengkap }}</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">NIS</p>
                                <p class="text-sm font-medium text-gray-900">{{ $siswa->nis }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Kelas</p>
                                <p class="text-sm font-medium text-gray-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                            </div>
                            @if($siswa->orangtua)
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Ayah</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $siswa->orangtua->nama_ayah ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Ibu</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $siswa->orangtua->nama_ibu ?? '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.2s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Tanggapan Orangtua</h2>
                        <p class="text-gray-500 text-sm">Semua field wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('tanggapan-orangtua.store') }}" method="POST" id="tanggapanForm" class="p-8">
                @csrf
                
                <!-- Hidden input untuk siswa_id -->
                <input type="hidden" name="siswa_id" value="{{ $siswas->first()->id ?? '' }}">
                
                <div class="space-y-8">
                    <!-- Periode Section -->
                    <div class="fade-in" style="animation-delay: 0.25s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-1.5 h-6 bg-[#0033A0] rounded-full mr-3"></span>
                            Periode
                        </h3>
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Bulan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Bulan *
                                    </label>
                                    <select 
                                        name="bulan" 
                                        id="bulan" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                    >
                                        <option value="">Pilih Bulan</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('bulan', $bulanSekarang) == $i ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('bulan')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Tahun -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tahun *
                                    </label>
                                    <select 
                                        name="tahun" 
                                        id="tahun" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                    >
                                        <option value="">Pilih Tahun</option>
                                        @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                                            <option value="{{ $i }}" {{ old('tahun', $tahunSekarang) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('tahun')
                                        <p class="text-red-500 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Pengisian -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pengisian *
                                </label>
                                <input 
                                    type="date" 
                                    name="tanggal_pengisian" 
                                    id="tanggal_pengisian" 
                                    value="{{ old('tanggal_pengisian', date('Y-m-d')) }}" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                >
                                @error('tanggal_pengisian')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data Orangtua Section -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-1.5 h-6 bg-[#00A86B] rounded-full mr-3"></span>
                            Data Orangtua
                        </h3>
                        <div class="space-y-6">
                            <!-- Nama Orangtua -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Anda *
                                </label>
                                <div class="relative">
                                    <select 
                                        name="nama_orangtua" 
                                        id="nama_orangtua" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                    >
                                        <option value="">Pilih nama Anda</option>
                                        @if($siswas->first() && $siswas->first()->orangtua)
                                            @if($siswas->first()->orangtua->nama_ayah)
                                                <option value="{{ $siswas->first()->orangtua->nama_ayah }}" 
                                                        data-tipe="ayah"
                                                        {{ old('nama_orangtua') == $siswas->first()->orangtua->nama_ayah ? 'selected' : '' }}>
                                                    {{ $siswas->first()->orangtua->nama_ayah }} (Ayah)
                                                </option>
                                            @endif
                                            @if($siswas->first()->orangtua->nama_ibu)
                                                <option value="{{ $siswas->first()->orangtua->nama_ibu }}" 
                                                        data-tipe="ibu"
                                                        {{ old('nama_orangtua') == $siswas->first()->orangtua->nama_ibu ? 'selected' : '' }}>
                                                    {{ $siswas->first()->orangtua->nama_ibu }} (Ibu)
                                                </option>
                                            @endif
                                        @endif
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                    <button type="button" id="tambahManual" 
                                            class="absolute right-10 top-1/2 transform -translate-y-1/2 text-[#0033A0] hover:text-[#00257A] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Klik ikon + untuk menambah nama manual</p>
                                @error('nama_orangtua')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Hubungan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Hubungan *
                                </label>
                                <select 
                                    name="tipe_orangtua" 
                                    id="tipe_orangtua" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                >
                                    <option value="">Pilih hubungan</option>
                                    <option value="ayah" {{ old('tipe_orangtua') == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ old('tipe_orangtua') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="wali" {{ old('tipe_orangtua') == 'wali' ? 'selected' : '' }}>Wali</option>
                                </select>
                                @error('tipe_orangtua')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tanggapan Section -->
                    <div class="fade-in" style="animation-delay: 0.35s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-1.5 h-6 bg-[#0033A0] rounded-full mr-3"></span>
                            Tanggapan
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Isi Tanggapan *
                            </label>
                            <textarea 
                                name="tanggapan" 
                                id="tanggapan" 
                                rows="8"
                                required 
                                placeholder="Tuliskan tanggapan Anda tentang perkembangan anak..."
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#0033A0] transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm resize-none"
                            >{{ old('tanggapan') }}</textarea>
                            <div class="flex justify-between items-center mt-3">
                                <p class="text-xs text-gray-500">
                                    Minimal 10 karakter
                                </p>
                                <div class="text-xs text-gray-500">
                                    <span id="charCount">0</span> karakter
                                </div>
                            </div>
                            @error('tanggapan')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tanda Tangan Section -->
                    <div class="fade-in" style="animation-delay: 0.4s;">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="w-1.5 h-6 bg-[#00A86B] rounded-full mr-3"></span>
                            Tanda Tangan
                        </h3>
                        <div class="space-y-6">
                            <!-- Canvas Area -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Buat Tanda Tangan Digital
                                </label>
                                <div class="signature-preview rounded-xl p-4 border-2 border-dashed border-gray-200">
                                    <canvas id="signatureCanvas" width="600" height="200" class="w-full h-40 signature-canvas"></canvas>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">
                                    Gambar tanda tangan Anda di area di atas (opsional)
                                </p>
                            </div>

                            <!-- Tombol Aksi Tanda Tangan -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <button type="button" id="clearSignature" 
                                        class="px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </span>
                                </button>
                                <button type="button" id="saveSignature" 
                                        class="px-4 py-3 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white rounded-xl hover:shadow-lg transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Simpan TTD
                                    </span>
                                </button>
                                <button type="button" id="skipSignature" 
                                        class="px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        Lewati
                                    </span>
                                </button>
                            </div>

                            <!-- Preview Tanda Tangan -->
                            <textarea name="tanda_tangan_digital" id="tanda_tangan_digital" class="hidden">{{ old('tanda_tangan_digital') }}</textarea>
                            
                            <div id="signaturePreview" class="hidden fade-in">
                                <p class="text-sm font-medium text-gray-700 mb-3">Preview Tanda Tangan:</p>
                                <div class="signature-preview rounded-xl p-4">
                                    <img id="signaturePreviewImage" src="" alt="Tanda tangan" class="max-h-20 mx-auto">
                                </div>
                                <p class="text-xs text-green-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Tanda tangan berhasil disimpan
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Card -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-xl p-5 fade-in" style="animation-delay: 0.45s;">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-amber-800">Penting</h4>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>• Pastikan semua data yang diisi sudah benar</p>
                                    <p>• Hanya dapat mengisi 1 tanggapan per bulan</p>
                                    <p>• Tanggapan tidak dapat diedit setelah 3 hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi Form -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.5s;">
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="flex-1 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <span class="relative flex items-center">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Simpan Tanggapan
                        </span>
                    </button>
                    <a href="{{ route('tanggapan-orangtua.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batalkan
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                        </span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Card Modern -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.6s;">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#0033A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-3">Panduan Pengisian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0033A0]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Pastikan data siswa sudah benar</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#00A86B]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Tanggapan minimal 10 karakter</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0033A0]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Tanda tangan digital opsional</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#00A86B]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Hanya 1 tanggapan per bulan per anak</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Signature Pad Library -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    
    <script>
        // Animasi untuk form elements dengan delay bertahap
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.fade-in');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Efek hover untuk input
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('ring-2', 'ring-[#0033A0]/20');
                });
                
                input.addEventListener('blur', function() {
                    this.classList.remove('ring-2', 'ring-[#0033A0]/20');
                });
            });
        });

        // Initialize Signature Pad
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signatureCanvas');
            if (canvas.getContext) {
                const signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)',
                    minWidth: 0.5,
                    maxWidth: 1.5,
                    throttle: 16
                });

                // Clear signature
                document.getElementById('clearSignature').addEventListener('click', function() {
                    signaturePad.clear();
                    document.getElementById('tanda_tangan_digital').value = '';
                    document.getElementById('signaturePreview').classList.add('hidden');
                });

                // Save signature
                document.getElementById('saveSignature').addEventListener('click', function() {
                    if (signaturePad.isEmpty()) {
                        alert('Silakan buat tanda tangan terlebih dahulu!');
                        return;
                    }
                    
                    const data = signaturePad.toDataURL('image/png');
                    document.getElementById('tanda_tangan_digital').value = data;
                    
                    // Show preview
                    document.getElementById('signaturePreviewImage').src = data;
                    document.getElementById('signaturePreview').classList.remove('hidden');
                    
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl shadow-lg z-50 fade-in';
                    successMsg.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Tanda tangan berhasil disimpan!
                        </div>
                    `;
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                });

                // Skip signature
                document.getElementById('skipSignature').addEventListener('click', function() {
                    if (confirm('Lewati tanda tangan? (Opsional)')) {
                        document.getElementById('tanda_tangan_digital').value = '';
                        signaturePad.clear();
                        document.getElementById('signaturePreview').classList.add('hidden');
                    }
                });
            }

            // Auto-select tipe orangtua
            document.getElementById('nama_orangtua').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const tipe = selectedOption.getAttribute('data-tipe');
                if (tipe) {
                    document.getElementById('tipe_orangtua').value = tipe;
                }
                
                // Jika pilihan "lainnya", tampilkan input manual
                if (this.value === 'lainnya') {
                    const manualName = prompt('Masukkan nama Anda:', '');
                    if (manualName && manualName.trim()) {
                        // Tambah option baru
                        const newOption = document.createElement('option');
                        newOption.value = manualName;
                        newOption.textContent = manualName;
                        this.add(newOption);
                        this.value = manualName;
                        
                        // Set tipe to wali
                        document.getElementById('tipe_orangtua').value = 'wali';
                    } else {
                        this.value = '';
                    }
                }
            });

            // Tambah manual
            document.getElementById('tambahManual').addEventListener('click', function() {
                const manualName = prompt('Masukkan nama Anda:', '');
                if (manualName && manualName.trim()) {
                    const select = document.getElementById('nama_orangtua');
                    const option = document.createElement('option');
                    option.value = manualName;
                    option.textContent = manualName;
                    option.selected = true;
                    select.add(option);
                    
                    document.getElementById('tipe_orangtua').value = 'wali';
                    
                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-xl shadow-lg z-50 fade-in';
                    successMsg.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Nama berhasil ditambahkan!
                        </div>
                    `;
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                }
            });

            // Character count
            const tanggapanTextarea = document.getElementById('tanggapan');
            const charCount = document.getElementById('charCount');
            
            tanggapanTextarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
                if (this.value.length < 10) {
                    charCount.classList.add('text-red-500');
                    charCount.classList.remove('text-green-600');
                } else {
                    charCount.classList.remove('text-red-500');
                    charCount.classList.add('text-green-600');
                }
            });
            
            // Initialize
            charCount.textContent = tanggapanTextarea.value.length;

            // Form validation
            document.getElementById('tanggapanForm').addEventListener('submit', function(e) {
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;
                const tanggapan = tanggapanTextarea.value;
                const submitBtn = document.getElementById('submitBtn');
                
                if (!bulan || !tahun) {
                    e.preventDefault();
                    showError('Harap pilih bulan dan tahun!');
                    return;
                }
                
                if (tanggapan.length < 10) {
                    e.preventDefault();
                    showError('Tanggapan minimal 10 karakter!');
                    return;
                }
                
                // Show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                `;
            });

            function showError(message) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-lg z-50 fade-in';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        ${message}
                    </div>
                `;
                document.body.appendChild(errorDiv);
                setTimeout(() => errorDiv.remove(), 3000);
            }

            // Check existing response
            function checkExistingResponse() {
                const siswaId = {{ $siswas->first()->id ?? 0 }};
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;
                
                if (siswaId && bulan && tahun) {
                    fetch("{{ route('tanggapan-orangtua.check') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            siswa_id: siswaId,
                            bulan: bulan,
                            tahun: tahun
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            showWarning('⚠️ Sudah ada tanggapan untuk periode ini!');
                        }
                    });
                }
            }
            
            function showWarning(message) {
                const warningDiv = document.createElement('div');
                warningDiv.className = 'fixed top-4 right-4 bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded-xl shadow-lg z-50 fade-in';
                warningDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        ${message}
                    </div>
                `;
                document.body.appendChild(warningDiv);
                setTimeout(() => warningDiv.remove(), 5000);
            }
            
            document.getElementById('bulan').addEventListener('change', checkExistingResponse);
            document.getElementById('tahun').addEventListener('change', checkExistingResponse);
        });
    </script>
</body>
</html>
@endsection