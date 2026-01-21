@extends('layouts.app')

@section('title', 'Edit Data Orangtua')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Orangtua - 7 Kebiasaan Anak Indonesia Hebat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .upload-area {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: #0033A0;
            background-color: #f8fafc;
        }
        
        .upload-area.dragover {
            border-color: #00A86B;
            background-color: #f0fdf4;
        }
        
        .required-field::after {
            content: " *";
            color: #DC143C;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('data-orangtua.index') }}" 
                       class="group relative p-2 rounded-xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                        <i class="fas fa-arrow-left text-gray-600 group-hover:text-primary-500 transition-colors text-lg"></i>
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-primary-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent">
                            Edit Data Orangtua
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            Perbarui informasi data orangtua siswa
                        </p>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-500 to-accent-green flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-edit text-white text-xl"></i>
                </div>
            </div>

            <!-- Alert Messages -->
            @if (session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl flex items-start fade-in">
                <i class="fas fa-check-circle text-accent-green mr-3 text-lg mt-0.5"></i>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg mt-0.5"></i>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mt-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-lg mt-0.5"></i>
                <div>
                    <p class="text-red-800 font-semibold mb-2">Terdapat beberapa kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1 text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Info Required Fields -->
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-primary-50 border-l-4 border-primary-500 p-4 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-primary-500 mr-3 text-lg mt-0.5"></i>
                    <p class="text-blue-800">
                        Field yang ditandai dengan <span class="text-red-600 font-bold">*</span> wajib diisi
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.1s;">
            <div class="px-8 py-6 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Edit Data Orangtua</h2>
                        <p class="text-gray-500 text-sm">Field dengan tanda <span class="text-red-500">*</span> wajib diisi</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('data-orangtua.update', $orangtua) }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  class="p-6 md:p-8"
                  id="parentForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <!-- Data Ayah -->
                    <div class="fade-in" style="animation-delay: 0.2s;">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-100 to-primary-50 flex items-center justify-center">
                                <i class="fas fa-male text-primary-600 text-lg"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Data Ayah</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Ayah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                                    Nama Ayah
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="nama_ayah" 
                                        required 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Masukkan nama lengkap ayah"
                                        value="{{ old('nama_ayah', $orangtua->nama_ayah) }}"
                                    >
                                </div>
                                @error('nama_ayah')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Telepon Ayah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon Ayah
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="tel" 
                                        name="telepon_ayah" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Contoh: 081234567890"
                                        value="{{ old('telepon_ayah', $orangtua->telepon_ayah) }}"
                                    >
                                </div>
                                @error('telepon_ayah')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Pekerjaan Ayah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan Ayah
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="pekerjaan_ayah" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Contoh: Pegawai Swasta"
                                        value="{{ old('pekerjaan_ayah', $orangtua->pekerjaan_ayah) }}"
                                    >
                                </div>
                                @error('pekerjaan_ayah')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Pendidikan Ayah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendidikan Terakhir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                    </div>
                                    <select 
                                        name="pendidikan_ayah" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                    >
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                        <option value="D3" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'D3' ? 'selected' : '' }}>Diploma (D3)</option>
                                        <option value="S1" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                        <option value="S2" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                        <option value="S3" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah) == 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                                    </select>
                                </div>
                                @error('pendidikan_ayah')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Ayah -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="date" 
                                        name="tanggal_lahir_ayah" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                        value="{{ old('tanggal_lahir_ayah', $orangtua->tanggal_lahir_ayah) }}"
                                    >
                                </div>
                                @error('tanggal_lahir_ayah')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanda Tangan Ayah -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanda Tangan Ayah
                            </label>
                            
                            @if($orangtua->tanda_tangan_ayah)
                            <div class="mb-4 bg-gradient-to-r from-blue-50 to-primary-50 rounded-xl p-4 border border-blue-100">
                                <p class="text-sm font-medium text-primary-700 mb-3 flex items-center">
                                    <i class="fas fa-signature mr-2"></i>
                                    Tanda tangan saat ini:
                                </p>
                                <img src="{{ $orangtua->tanda_tangan_ayah_url }}" 
                                     alt="Tanda Tangan Ayah" 
                                     class="max-h-32 object-contain bg-white p-2 rounded-lg border border-blue-200 shadow-sm">
                            </div>
                            @endif

                            <div class="upload-area rounded-xl p-4" id="uploadAyah">
                                <input type="file" 
                                       name="tanda_tangan_ayah" 
                                       class="hidden" 
                                       id="tandaTanganAyah"
                                       accept="image/*">
                                <label for="tandaTanganAyah" class="cursor-pointer">
                                    <div class="flex flex-col items-center justify-center py-6" id="previewAyah">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-600 font-medium mb-1">
                                            {{ $orangtua->tanda_tangan_ayah ? 'Klik untuk mengubah' : 'Klik untuk upload' }}
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                    </div>
                                </label>
                            </div>
                            @if($orangtua->tanda_tangan_ayah)
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Kosongkan jika tidak ingin mengubah tanda tangan
                            </p>
                            @endif
                            @error('tanda_tangan_ayah')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div class="fade-in" style="animation-delay: 0.3s;">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-pink-100 to-rose-50 flex items-center justify-center">
                                <i class="fas fa-female text-pink-600 text-lg"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Data Ibu</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                                    Nama Ibu
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="nama_ibu" 
                                        required 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Masukkan nama lengkap ibu"
                                        value="{{ old('nama_ibu', $orangtua->nama_ibu) }}"
                                    >
                                </div>
                                @error('nama_ibu')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Telepon Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Telepon Ibu
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="tel" 
                                        name="telepon_ibu" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Contoh: 081234567890"
                                        value="{{ old('telepon_ibu', $orangtua->telepon_ibu) }}"
                                    >
                                </div>
                                @error('telepon_ibu')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                            <!-- Pekerjaan Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pekerjaan Ibu
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="pekerjaan_ibu" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm"
                                        placeholder="Contoh: Ibu Rumah Tangga"
                                        value="{{ old('pekerjaan_ibu', $orangtua->pekerjaan_ibu) }}"
                                    >
                                </div>
                                @error('pekerjaan_ibu')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Pendidikan Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendidikan Terakhir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                    </div>
                                    <select 
                                        name="pendidikan_ibu" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm select-arrow appearance-none"
                                    >
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                        <option value="D3" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'D3' ? 'selected' : '' }}>Diploma (D3)</option>
                                        <option value="S1" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                        <option value="S2" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                        <option value="S3" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu) == 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                                    </select>
                                </div>
                                @error('pendidikan_ibu')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="date" 
                                        name="tanggal_lahir_ibu" 
                                        class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                        value="{{ old('tanggal_lahir_ibu', $orangtua->tanggal_lahir_ibu) }}"
                                    >
                                </div>
                                @error('tanggal_lahir_ibu')
                                    <p class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanda Tangan Ibu -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanda Tangan Ibu
                            </label>
                            
                            @if($orangtua->tanda_tangan_ibu)
                            <div class="mb-4 bg-gradient-to-r from-pink-50 to-rose-50 rounded-xl p-4 border border-pink-100">
                                <p class="text-sm font-medium text-pink-700 mb-3 flex items-center">
                                    <i class="fas fa-signature mr-2"></i>
                                    Tanda tangan saat ini:
                                </p>
                                <img src="{{ $orangtua->tanda_tangan_ibu_url }}" 
                                     alt="Tanda Tangan Ibu" 
                                     class="max-h-32 object-contain bg-white p-2 rounded-lg border border-pink-200 shadow-sm">
                            </div>
                            @endif

                            <div class="upload-area rounded-xl p-4" id="uploadIbu">
                                <input type="file" 
                                       name="tanda_tangan_ibu" 
                                       class="hidden" 
                                       id="tandaTanganIbu"
                                       accept="image/*">
                                <label for="tandaTanganIbu" class="cursor-pointer">
                                    <div class="flex flex-col items-center justify-center py-6" id="previewIbu">
                                        <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-3"></i>
                                        <p class="text-sm text-gray-600 font-medium mb-1">
                                            {{ $orangtua->tanda_tangan_ibu ? 'Klik untuk mengubah' : 'Klik untuk upload' }}
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                    </div>
                                </label>
                            </div>
                            @if($orangtua->tanda_tangan_ibu)
                            <p class="text-xs text-gray-500 mt-2 text-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Kosongkan jika tidak ingin mengubah tanda tangan
                            </p>
                            @endif
                            @error('tanda_tangan_ibu')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="fade-in" style="animation-delay: 0.4s;">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-100 to-emerald-50 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-emerald-600 text-lg"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Alamat Lengkap</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2 required-field">
                                Alamat
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 pt-3 pointer-events-none">
                                    <i class="fas fa-home text-gray-400"></i>
                                </div>
                                <textarea 
                                    name="alamat" 
                                    rows="4"
                                    class="w-full pl-10 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 placeholder-gray-400 bg-white shadow-sm resize-none"
                                    placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Kelurahan, Kecamatan, Kota)"
                                    required
                                >{{ old('alamat', $orangtua->alamat) }}</textarea>
                            </div>
                            @error('alamat')
                                <p class="text-red-500 text-sm mt-2 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1 text-sm"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-col sm:flex-row gap-4 mt-12 pt-8 border-t border-gray-100 fade-in" style="animation-delay: 0.5s;">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group"
                    >
                        <i class="fas fa-save mr-3 group-hover:rotate-12 transition-transform"></i>
                        Perbarui Data
                    </button>
                    <a href="{{ route('data-orangtua.index') }}" 
                       class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3.5 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                        <i class="fas fa-times mr-2"></i>
                        Batalkan
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Card Modern -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mt-8 fade-in" style="animation-delay: 0.6s;">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-lightbulb text-primary-600 text-lg"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-3">Tips Pengisian</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Pastikan nama diisi sesuai dengan identitas resmi</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Nomor telepon diisi dengan format yang benar</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Alamat diisi dengan lengkap dan jelas</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <div class="w-5 h-5 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                            </div>
                            <p class="text-sm text-gray-600">Tanda tangan hanya diperlukan jika ada perubahan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animasi untuk form elements dengan delay bertahap
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.fade-in');
            formElements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // File upload preview untuk ayah
            const fileInputAyah = document.getElementById('tandaTanganAyah');
            const previewAyah = document.getElementById('previewAyah');
            const uploadAreaAyah = document.getElementById('uploadAyah');

            if (fileInputAyah) {
                fileInputAyah.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validasi ukuran file (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar! Maksimal 2MB');
                            this.value = '';
                            return;
                        }

                        // Validasi tipe file
                        if (!file.type.match('image.*')) {
                            alert('File harus berupa gambar!');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewAyah.innerHTML = `
                                <img src="${e.target.result}" class="max-h-32 object-contain mb-2 rounded-lg border">
                                <p class="text-sm text-green-600 font-medium mb-1">✓ File berhasil dipilih</p>
                                <p class="text-xs text-gray-500">Klik untuk mengubah</p>
                            `;
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Drag and drop untuk ayah
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadAreaAyah.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadAreaAyah.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    uploadAreaAyah.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    uploadAreaAyah.classList.add('dragover');
                }

                function unhighlight() {
                    uploadAreaAyah.classList.remove('dragover');
                }

                uploadAreaAyah.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInputAyah.files = files;
                    fileInputAyah.dispatchEvent(new Event('change'));
                }
            }

            // File upload preview untuk ibu (sama seperti ayah)
            const fileInputIbu = document.getElementById('tandaTanganIbu');
            const previewIbu = document.getElementById('previewIbu');
            const uploadAreaIbu = document.getElementById('uploadIbu');

            if (fileInputIbu) {
                fileInputIbu.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validasi ukuran file (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Ukuran file terlalu besar! Maksimal 2MB');
                            this.value = '';
                            return;
                        }

                        // Validasi tipe file
                        if (!file.type.match('image.*')) {
                            alert('File harus berupa gambar!');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewIbu.innerHTML = `
                                <img src="${e.target.result}" class="max-h-32 object-contain mb-2 rounded-lg border">
                                <p class="text-sm text-green-600 font-medium mb-1">✓ File berhasil dipilih</p>
                                <p class="text-xs text-gray-500">Klik untuk mengubah</p>
                            `;
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Drag and drop untuk ibu
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadAreaIbu.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadAreaIbu.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    uploadAreaIbu.addEventListener(eventName, unhighlight, false);
                });

                function highlight() {
                    uploadAreaIbu.classList.add('dragover');
                }

                function unhighlight() {
                    uploadAreaIbu.classList.remove('dragover');
                }

                uploadAreaIbu.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    fileInputIbu.files = files;
                    fileInputIbu.dispatchEvent(new Event('change'));
                }
            }

            // Form validation
            document.getElementById('parentForm').addEventListener('submit', function(e) {
                const namaAyah = document.querySelector('input[name="nama_ayah"]').value.trim();
                const namaIbu = document.querySelector('input[name="nama_ibu"]').value.trim();
                const alamat = document.querySelector('textarea[name="alamat"]').value.trim();
                
                if (!namaAyah || !namaIbu || !alamat) {
                    e.preventDefault();
                    alert('⚠️ Harap lengkapi semua field yang wajib diisi (ditandai dengan *)');
                    
                    // Scroll to first error
                    const firstEmpty = document.querySelector('input:invalid, textarea:invalid');
                    if (firstEmpty) {
                        firstEmpty.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstEmpty.focus();
                    }
                }
            });

            // Add smooth scroll for form errors
            window.addEventListener('load', function() {
                const errorElement = document.querySelector('.bg-gradient-to-r.from-red-50');
                if (errorElement) {
                    errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>
</body>
</html>
@endsection