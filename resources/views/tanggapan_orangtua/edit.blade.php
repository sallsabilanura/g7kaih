@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Tanggapan Orangtua - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .edit-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
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
                    <a href="{{ route('tanggapan-orangtua.show', $tanggapanOrangtua->id) }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-[#0033A0] transition-colors" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-[#0033A0] rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-[#0033A0] to-[#00A86B] bg-clip-text text-transparent">
                            Edit Tanggapan
                        </h1>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full edit-badge text-xs font-semibold">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Mode Edit
                            </span>
                            <p class="text-gray-500 text-sm">
                                ID: {{ $tanggapanOrangtua->id }} • {{ $tanggapanOrangtua->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <div class="text-xs text-gray-500 mb-1">Status</div>
                        @if($tanggapanOrangtua->tanda_tangan_digital)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-[#00A86B]">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sudah TTD
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Belum TTD
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Siswa & Tanggapan Card -->
        <div class="glass-card rounded-2xl shadow-lg mb-8 p-6 fade-in" style="animation-delay: 0.1s;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Info Siswa -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="w-1.5 h-6 bg-[#0033A0] rounded-full mr-3"></span>
                        Data Siswa
                    </h3>
                    @php $siswa = $siswas->first(); @endphp
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-xl">
                                {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</h4>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-20">NIS:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $siswa->nis }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-20">Kelas:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Tanggapan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="w-1.5 h-6 bg-[#00A86B] rounded-full mr-3"></span>
                        Info Tanggapan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Periode:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg badge-blue text-xs font-semibold">
                                {{ date('F', mktime(0, 0, 0, $tanggapanOrangtua->bulan, 1)) }} {{ $tanggapanOrangtua->tahun }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Pengisian:</span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($tanggapanOrangtua->tanggal_pengisian)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Orangtua:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $tanggapanOrangtua->nama_orangtua }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Hubungan:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold 
                                {{ $tanggapanOrangtua->tipe_orangtua == 'ayah' ? 'bg-blue-100 text-[#0033A0]' : 
                                   ($tanggapanOrangtua->tipe_orangtua == 'ibu' ? 'bg-pink-100 text-pink-800' : 
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($tanggapanOrangtua->tipe_orangtua) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.2s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-[#0033A0] to-[#00A86B] rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Edit Tanggapan</h2>
                        <p class="text-gray-500 text-sm">Perbarui data tanggapan orangtua</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('tanggapan-orangtua.update', $tanggapanOrangtua->id) }}" method="POST" id="tanggapanForm" class="p-8">
                @csrf
                @method('PUT')
                
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
                                            <option value="{{ $i }}" {{ old('bulan', $tanggapanOrangtua->bulan) == $i ? 'selected' : '' }}>
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
                                            <option value="{{ $i }}" {{ old('tahun', $tanggapanOrangtua->tahun) == $i ? 'selected' : '' }}>
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
                                    value="{{ old('tanggal_pengisian', $tanggapanOrangtua->tanggal_pengisian) }}" 
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
                                                        {{ old('nama_orangtua', $tanggapanOrangtua->nama_orangtua) == $siswas->first()->orangtua->nama_ayah ? 'selected' : '' }}>
                                                    {{ $siswas->first()->orangtua->nama_ayah }} (Ayah)
                                                </option>
                                            @endif
                                            @if($siswas->first()->orangtua->nama_ibu)
                                                <option value="{{ $siswas->first()->orangtua->nama_ibu }}" 
                                                        data-tipe="ibu"
                                                        {{ old('nama_orangtua', $tanggapanOrangtua->nama_orangtua) == $siswas->first()->orangtua->nama_ibu ? 'selected' : '' }}>
                                                    {{ $siswas->first()->orangtua->nama_ibu }} (Ibu)
                                                </option>
                                            @endif
                                        @endif
                                        <!-- Tambahkan option dari data lama jika tidak ada di orangtua -->
                                        @if(!empty($tanggapanOrangtua->nama_orangtua) && 
                                            (!$siswas->first()->orangtua || 
                                             ($siswas->first()->orangtua && 
                                              $tanggapanOrangtua->nama_orangtua != $siswas->first()->orangtua->nama_ayah && 
                                              $tanggapanOrangtua->nama_orangtua != $siswas->first()->orangtua->nama_ibu)))
                                            <option value="{{ $tanggapanOrangtua->nama_orangtua }}" 
                                                    data-tipe="{{ $tanggapanOrangtua->tipe_orangtua }}"
                                                    selected>
                                                {{ $tanggapanOrangtua->nama_orangtua }} ({{ ucfirst($tanggapanOrangtua->tipe_orangtua) }})
                                            </option>
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
                                    <option value="ayah" {{ old('tipe_orangtua', $tanggapanOrangtua->tipe_orangtua) == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ old('tipe_orangtua', $tanggapanOrangtua->tipe_orangtua) == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="wali" {{ old('tipe_orangtua', $tanggapanOrangtua->tipe_orangtua) == 'wali' ? 'selected' : '' }}>Wali</option>
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
                            >{{ old('tanggapan', $tanggapanOrangtua->tanggapan) }}</textarea>
                            <div class="flex justify-between items-center mt-3">
                                <p class="text-xs text-gray-500">
                                    Minimal 10 karakter
                                </p>
                                <div class="text-xs text-gray-500">
                                    <span id="charCount">{{ strlen($tanggapanOrangtua->tanggapan) }}</span> karakter
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
                                    @if($tanggapanOrangtua->tanda_tangan_digital)
                                        <span class="text-xs text-green-600 ml-2">(Sudah ada tanda tangan tersimpan)</span>
                                    @endif
                                </label>
                                <div class="signature-preview rounded-xl p-4 border-2 border-dashed border-gray-200">
                                    <canvas id="signatureCanvas" width="600" height="200" class="w-full h-40 signature-canvas"></canvas>
                                </div>
                                <p class="text-xs text-gray-500 mt-3">
                                    Gambar tanda tangan Anda di area di atas untuk memperbarui (kosongkan jika tidak ingin mengubah)
                                </p>
                            </div>

                            <!-- Preview Tanda Tangan Lama -->
                            @if($tanggapanOrangtua->tanda_tangan_digital)
                            <div id="oldSignaturePreview" class="fade-in">
                                <p class="text-sm font-medium text-gray-700 mb-3">Tanda Tangan Saat Ini:</p>
                                <div class="signature-preview rounded-xl p-4">
                                    <img src="{{ $tanggapanOrangtua->tanda_tangan_digital }}" alt="Tanda tangan saat ini" class="max-h-20 mx-auto">
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-xs text-green-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Tanda tangan tersimpan
                                    </p>
                                    <button type="button" id="hapusTTD" 
                                            class="text-xs text-red-600 hover:text-red-800 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Hapus TTD
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- Tombol Aksi Tanda Tangan -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <button type="button" id="clearSignature" 
                                        class="px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus Baru
                                    </span>
                                </button>
                                <button type="button" id="saveSignature" 
                                        class="px-4 py-3 bg-gradient-to-r from-[#0033A0] to-[#00A86B] text-white rounded-xl hover:shadow-lg transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                        </svg>
                                        Simpan Baru
                                    </span>
                                </button>
                                <button type="button" id="skipSignature" 
                                        class="px-4 py-3 border border-gray-300 text-gray-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300 font-medium text-sm">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                        Pertahankan
                                    </span>
                                </button>
                            </div>

                            <!-- Preview Tanda Tangan Baru -->
                            <textarea name="tanda_tangan_digital" id="tanda_tangan_digital" class="hidden">{{ old('tanda_tangan_digital', $tanggapanOrangtua->tanda_tangan_digital) }}</textarea>
                            
                            <div id="signaturePreview" class="hidden fade-in">
                                <p class="text-sm font-medium text-gray-700 mb-3">Preview Tanda Tangan Baru:</p>
                                <div class="signature-preview rounded-xl p-4">
                                    <img id="signaturePreviewImage" src="" alt="Tanda tangan baru" class="max-h-20 mx-auto">
                                </div>
                                <p class="text-xs text-green-600 mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Tanda tangan baru berhasil disimpan
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
                                <h4 class="text-sm font-semibold text-amber-800">Informasi Edit</h4>
                                <div class="mt-2 text-sm text-amber-700">
                                    <p>• Perubahan akan memperbarui data tanggapan secara permanen</p>
                                    <p>• Pastikan data yang diubah sudah benar</p>
                                    <p>• Cek kembali periode untuk menghindari duplikasi</p>
                                    @if($tanggapanOrangtua->created_at->diffInDays(now()) > 3)
                                        <p class="text-red-600 font-medium">• ⚠️ Masa edit (3 hari) telah berlalu</p>
                                    @else
                                        <p>• Sisa waktu edit: {{ 3 - $tanggapanOrangtua->created_at->diffInDays(now()) }} hari</p>
                                    @endif
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
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Perbarui Data
                        </span>
                    </button>
                    <a href="{{ route('tanggapan-orangtua.show', $tanggapanOrangtua->id) }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <span class="relative">
                            Batal Edit
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
                    <h3 class="font-semibold text-gray-900 mb-3">Aturan Pengeditan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0033A0]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Data dapat diedit maksimal 3 hari setelah dibuat</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#00A86B]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Pastikan tidak ada duplikasi periode dengan data lain</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#0033A0]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Tanda tangan dapat diperbarui atau dipertahankan</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#00A86B]"></div>
                            </div>
                            <p class="text-sm text-gray-600">Semua perubahan akan tercatat dalam sistem</p>
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
            let signaturePad = null;
            
            if (canvas.getContext) {
                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255, 255, 255)',
                    penColor: 'rgb(0, 0, 0)',
                    minWidth: 0.5,
                    maxWidth: 1.5,
                    throttle: 16
                });

                // Clear new signature
                document.getElementById('clearSignature').addEventListener('click', function() {
                    if (signaturePad) {
                        signaturePad.clear();
                    }
                    document.getElementById('signaturePreview').classList.add('hidden');
                });

                // Save new signature
                document.getElementById('saveSignature').addEventListener('click', function() {
                    if (!signaturePad || signaturePad.isEmpty()) {
                        alert('Silakan buat tanda tangan baru terlebih dahulu!');
                        return;
                    }
                    
                    const data = signaturePad.toDataURL('image/png');
                    document.getElementById('tanda_tangan_digital').value = data;
                    
                    // Show preview
                    document.getElementById('signaturePreviewImage').src = data;
                    document.getElementById('signaturePreview').classList.remove('hidden');
                    
                    // Hide old signature preview
                    const oldPreview = document.getElementById('oldSignaturePreview');
                    if (oldPreview) {
                        oldPreview.classList.add('hidden');
                    }
                    
                    // Show success message
                    showMessage('Tanda tangan baru berhasil disimpan!', 'success');
                });

                // Skip signature (keep existing)
                document.getElementById('skipSignature').addEventListener('click', function() {
                    if (confirm('Pertahankan tanda tangan yang ada?')) {
                        if (signaturePad) {
                            signaturePad.clear();
                        }
                        document.getElementById('signaturePreview').classList.add('hidden');
                        showMessage('Tanda tangan akan dipertahankan', 'info');
                    }
                });

                // Hapus tanda tangan yang ada
                const hapusTTDBtn = document.getElementById('hapusTTD');
                if (hapusTTDBtn) {
                    hapusTTDBtn.addEventListener('click', function() {
                        if (confirm('Hapus tanda tangan yang tersimpan? Tindakan ini tidak dapat dibatalkan.')) {
                            document.getElementById('tanda_tangan_digital').value = '';
                            document.getElementById('oldSignaturePreview').classList.add('hidden');
                            showMessage('Tanda tangan akan dihapus', 'warning');
                        }
                    });
                }
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
                        newOption.setAttribute('data-tipe', 'wali');
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
                    option.setAttribute('data-tipe', 'wali');
                    select.add(option);
                    
                    document.getElementById('tipe_orangtua').value = 'wali';
                    
                    showMessage('Nama berhasil ditambahkan!', 'success');
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
            
            // Initialize character count color
            if (tanggapanTextarea.value.length < 10) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.add('text-green-600');
            }

            // Form validation
            document.getElementById('tanggapanForm').addEventListener('submit', function(e) {
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;
                const tanggapan = tanggapanTextarea.value;
                const submitBtn = document.getElementById('submitBtn');
                
                // Cek masa edit
                const createdAt = new Date('{{ $tanggapanOrangtua->created_at }}');
                const now = new Date();
                const diffTime = Math.abs(now - createdAt);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays > 3) {
                    e.preventDefault();
                    showMessage('⚠️ Masa edit (3 hari) telah berlalu. Tidak dapat mengedit data.', 'error');
                    return;
                }
                
                if (!bulan || !tahun) {
                    e.preventDefault();
                    showMessage('Harap pilih bulan dan tahun!', 'error');
                    return;
                }
                
                if (tanggapan.length < 10) {
                    e.preventDefault();
                    showMessage('Tanggapan minimal 10 karakter!', 'error');
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
                        Memperbarui...
                    </span>
                `;
            });

            function showMessage(message, type = 'info') {
                const colors = {
                    success: 'bg-green-100 border-green-400 text-green-700',
                    error: 'bg-red-100 border-red-400 text-red-700',
                    warning: 'bg-amber-100 border-amber-400 text-amber-700',
                    info: 'bg-blue-100 border-blue-400 text-blue-700'
                };
                
                const icons = {
                    success: `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>`,
                    error: `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>`,
                    warning: `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>`,
                    info: `<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>`
                };
                
                const messageDiv = document.createElement('div');
                messageDiv.className = `fixed top-4 right-4 ${colors[type]} border px-4 py-3 rounded-xl shadow-lg z-50 fade-in`;
                messageDiv.innerHTML = `
                    <div class="flex items-center">
                        ${icons[type]}
                        ${message}
                    </div>
                `;
                document.body.appendChild(messageDiv);
                setTimeout(() => messageDiv.remove(), 3000);
            }

            // Check existing response (exclude current)
            function checkExistingResponse() {
                const siswaId = {{ $siswas->first()->id ?? 0 }};
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;
                const currentId = {{ $tanggapanOrangtua->id }};
                
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
                            tahun: tahun,
                            exclude_id: currentId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            showMessage('⚠️ Sudah ada tanggapan lain untuk periode ini!', 'warning');
                        }
                    });
                }
            }
            
            document.getElementById('bulan').addEventListener('change', checkExistingResponse);
            document.getElementById('tahun').addEventListener('change', checkExistingResponse);
        });
    </script>
</body>
</html>
@endsection