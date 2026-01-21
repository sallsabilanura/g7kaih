
@extends('layouts.app')

@section('title', 'Data Orangtua')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($orangtua) ? 'Edit' : 'Tambah' }} Data Orangtua - SMP Utama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .required-field::after {
            content: " *";
            color: #DC143C;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50/30 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <i class="fas fa-{{ isset($orangtua) ? 'user-edit' : 'user-plus' }} text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ isset($orangtua) ? 'Edit' : 'Tambah' }} Data Orangtua</h1>
                        <p class="text-gray-600 mt-1">{{ isset($orangtua) ? 'Perbarui' : 'Lengkapi' }} informasi data orangtua siswa</p>
                    </div>
                </div>
                <a href="{{ route('data-orangtua.index') }}" 
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl transition-all duration-200 font-medium">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form action="{{ isset($orangtua) ? route('data-orangtua.update') : route('data-orangtua.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data" 
                  id="parentForm">
                @csrf
                @if(isset($orangtua))
                    @method('PUT')
                @endif

                <!-- Alert Messages -->
                <div class="p-6 space-y-4">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl"></i>
                                <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-3 text-xl mt-0.5"></i>
                                <div>
                                    <p class="text-red-800 font-semibold mb-2">Terdapat beberapa kesalahan:</p>
                                    <ul class="list-disc list-inside space-y-1 text-red-700">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Info Required Fields -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 text-xl mt-0.5"></i>
                            <p class="text-blue-800">
                                Field yang ditandai dengan <span class="text-red-600 font-bold">*</span> wajib diisi
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Data Ayah -->
                        <div class="space-y-5">
                            <div class="flex items-center gap-3 pb-4 border-b-2 border-blue-500">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-male text-blue-600 text-lg"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Data Ayah</h3>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 required-field">Nama Ayah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="nama_ayah" 
                                           value="{{ old('nama_ayah', $orangtua->nama_ayah ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="Masukkan nama lengkap ayah"
                                           required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon Ayah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="tel" 
                                           name="telepon_ayah" 
                                           value="{{ old('telepon_ayah', $orangtua->telepon_ayah ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="contoh: 08123456789">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ayah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="pekerjaan_ayah" 
                                           value="{{ old('pekerjaan_ayah', $orangtua->pekerjaan_ayah ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                           placeholder="contoh: Pegawai Swasta">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Terakhir Ayah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                    </div>
                                    <select name="pendidikan_ayah" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none bg-white">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D3" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_ayah', $orangtua->pendidikan_ayah ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir Ayah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="tanggal_lahir_ayah" 
                                           value="{{ old('tanggal_lahir_ayah', $orangtua->tanggal_lahir_ayah ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Upload Tanda Tangan Ayah -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan Ayah</label>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="preview-container-ayah">
                                                @if(isset($orangtua) && $orangtua->tanda_tangan_ayah)
                                                    <img src="{{ $orangtua->tanda_tangan_ayah_url }}" alt="Tanda Tangan Ayah" class="max-h-32 object-contain mb-2">
                                                    <p class="text-sm text-gray-600">Klik untuk mengubah</p>
                                                @else
                                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                                @endif
                                            </div>
                                            <input type="file" 
                                                   name="tanda_tangan_ayah" 
                                                   class="hidden" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 'preview-container-ayah')">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Ibu -->
                        <div class="space-y-5">
                            <div class="flex items-center gap-3 pb-4 border-b-2 border-pink-500">
                                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center">
                                    <i class="fas fa-female text-pink-600 text-lg"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Data Ibu</h3>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2 required-field">Nama Ibu</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="nama_ibu" 
                                           value="{{ old('nama_ibu', $orangtua->nama_ibu ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                                           placeholder="Masukkan nama lengkap ibu"
                                           required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon Ibu</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-phone text-gray-400"></i>
                                    </div>
                                    <input type="tel" 
                                           name="telepon_ibu" 
                                           value="{{ old('telepon_ibu', $orangtua->telepon_ibu ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                                           placeholder="contoh: 08123456789">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ibu</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-briefcase text-gray-400"></i>
                                    </div>
                                    <input type="text" 
                                           name="pekerjaan_ibu" 
                                           value="{{ old('pekerjaan_ibu', $orangtua->pekerjaan_ibu ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all"
                                           placeholder="contoh: Ibu Rumah Tangga">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Terakhir Ibu</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-graduation-cap text-gray-400"></i>
                                    </div>
                                    <select name="pendidikan_ibu" 
                                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all appearance-none bg-white">
                                        <option value="">Pilih Pendidikan</option>
                                        <option value="SD" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D3" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('pendidikan_ibu', $orangtua->pendidikan_ibu ?? '') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-gray-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir Ibu</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input type="date" 
                                           name="tanggal_lahir_ibu" 
                                           value="{{ old('tanggal_lahir_ibu', $orangtua->tanggal_lahir_ibu ?? '') }}"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all">
                                </div>
                            </div>

                            <!-- Upload Tanda Tangan Ibu -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan Ibu</label>
                                <div class="space-y-3">
                                    <div class="flex items-center justify-center w-full">
                                        <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="preview-container-ibu">
                                                @if(isset($orangtua) && $orangtua->tanda_tangan_ibu)
                                                    <img src="{{ $orangtua->tanda_tangan_ibu_url }}" alt="Tanda Tangan Ibu" class="max-h-32 object-contain mb-2">
                                                    <p class="text-sm text-gray-600">Klik untuk mengubah</p>
                                                @else
                                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag & drop</p>
                                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                                @endif
                                            </div>
                                            <input type="file" 
                                                   name="tanda_tangan_ibu" 
                                                   class="hidden" 
                                                   accept="image/*"
                                                   onchange="previewImage(this, 'preview-container-ibu')">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Section -->
                    <div class="mt-8 p-6 bg-gradient-to-r from-green-50/50 to-teal-50/50 rounded-xl border border-green-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-green-600 text-lg"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Alamat Lengkap</h3>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2 required-field">Alamat</label>
                            <div class="relative">
                                <textarea name="alamat" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos)" 
                                          required>{{ old('alamat', $orangtua->alamat ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('data-orangtua.index') }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-gradient-to-r from-green-500 to-teal-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-save"></i>
                            <span>{{ isset($orangtua) ? 'Perbarui' : 'Simpan' }} Data</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
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

        // Preview image function
        function previewImage(input, containerId) {
            const container = document.getElementById(containerId);
            const file = input.files[0];
            
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('⚠️ File harus berupa gambar!');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    container.innerHTML = `
                        <img src="${e.target.result}" class="max-h-32 object-contain mb-2">
                        <p class="text-sm text-gray-600">✓ File berhasil dipilih</p>
                        <p class="text-xs text-gray-500 mt-1">Klik untuk mengubah</p>
                    `;
                }
                reader.readAsDataURL(file);
            }
        }

        // Add smooth scroll for form errors
        window.addEventListener('load', function() {
            const errorElement = document.querySelector('.bg-red-50');
            if (errorElement) {
                errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
@endsection