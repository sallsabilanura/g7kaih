@extends('layouts.app')

@section('title', 'Detail Tanggapan Orangtua')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-emerald-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-emerald-600 p-8 shadow-xl">
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-6 md:mb-0">
                            <div class="flex items-center mb-4">
                                <a href="{{ route('tanggapan-orangtua.index') }}" class="mr-3 text-white hover:text-blue-100">
                                    <i class="fas fa-arrow-left text-xl"></i>
                                </a>
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">
                                        <i class="fas fa-comment-medical mr-3"></i>
                                        Detail Tanggapan
                                    </h1>
                                    <p class="text-blue-100 text-lg">
                                        {{ $tanggapanOrangtua->siswa->nama_lengkap ?? 'Siswa' }} - {{ $tanggapanOrangtua->periode }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-white text-sm">
                                    <i class="far fa-calendar-alt mr-2"></i>
                                    {{ $tanggapanOrangtua->tanggal_pengisian_formatted }}
                                </span>
                                @if($tanggapanOrangtua->tanda_tangan_digital)
                                    <span class="inline-flex items-center px-4 py-2 bg-emerald-500/80 backdrop-blur-sm rounded-full text-white text-sm">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        Sudah Tanda Tangan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 bg-amber-500/80 backdrop-blur-sm rounded-full text-white text-sm">
                                        <i class="far fa-clock mr-2"></i>
                                        Belum Tanda Tangan
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="hidden md:block">
                            <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-full border-4 border-white/20 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-emerald-500 mr-3 text-xl"></i>
                    <div>
                        <p class="text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Card Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Kolom 1: Informasi Siswa & Periode -->
                    <div class="space-y-6">
                        <!-- Info Siswa -->
                        <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-blue-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-child mr-2 text-blue-600"></i>
                                Informasi Siswa
                            </h3>
                            
                            <div class="flex items-start mb-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-emerald-500 rounded-xl flex items-center justify-center text-white">
                                        <i class="fas fa-user-graduate text-2xl"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $tanggapanOrangtua->siswa->nama_lengkap ?? 'N/A' }}</h4>
                                    <div class="grid grid-cols-2 gap-3 mt-3">
                                        <div class="bg-white rounded-lg p-3 border border-blue-100">
                                            <p class="text-xs text-gray-500 mb-1">
                                                <i class="fas fa-hashtag mr-1"></i> NIS
                                            </p>
                                            <p class="font-medium">{{ $tanggapanOrangtua->siswa->nis ?? '-' }}</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                            <p class="text-xs text-gray-500 mb-1">
                                                <i class="fas fa-school mr-1"></i> Kelas
                                            </p>
                                            <p class="font-medium">{{ $tanggapanOrangtua->kelas }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Orangtua -->
                        <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-emerald-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-check mr-2 text-emerald-600"></i>
                                Informasi Orangtua
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Nama Orangtua</p>
                                    <p class="font-bold text-gray-900 text-lg">{{ $tanggapanOrangtua->nama_orangtua }}</p>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ 
                                        $tanggapanOrangtua->tipe_orangtua == 'ayah' ? 'bg-blue-100 text-blue-800' : 
                                        ($tanggapanOrangtua->tipe_orangtua == 'ibu' ? 'bg-pink-100 text-pink-800' : 
                                        'bg-gray-100 text-gray-800') 
                                    }}">
                                        <i class="fas {{ 
                                            $tanggapanOrangtua->tipe_orangtua == 'ayah' ? 'fa-user-tie' : 
                                            ($tanggapanOrangtua->tipe_orangtua == 'ibu' ? 'fa-female' : 'fa-user-shield') 
                                        }} mr-1"></i>
                                        {{ ucfirst($tanggapanOrangtua->tipe_orangtua) }}
                                    </span>
                                </div>
                                
                                @if($tanggapanOrangtua->orangtua)
                                    <div class="pt-4 border-t border-emerald-200">
                                        <p class="text-sm text-gray-500 mb-2">Data lengkap orangtua:</p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-white rounded-lg p-3 border border-blue-100">
                                                <p class="text-xs text-gray-500 mb-1">Nama Ayah</p>
                                                <p class="font-medium">{{ $tanggapanOrangtua->orangtua->nama_ayah ?? '-' }}</p>
                                            </div>
                                            <div class="bg-white rounded-lg p-3 border border-emerald-100">
                                                <p class="text-xs text-gray-500 mb-1">Nama Ibu</p>
                                                <p class="font-medium">{{ $tanggapanOrangtua->orangtua->nama_ibu ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2: Periode & Status -->
                    <div class="space-y-6">
                        <!-- Periode -->
                        <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-cyan-200 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-cyan-600"></i>
                                Periode & Status
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="text-center">
                                    <div class="inline-flex flex-col items-center p-4 bg-gradient-to-br from-blue-600 to-emerald-600 rounded-xl text-white shadow-lg">
                                        <span class="text-2xl font-bold">{{ $tanggapanOrangtua->nama_bulan }}</span>
                                        <span class="text-lg opacity-90">{{ $tanggapanOrangtua->tahun }}</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="far fa-calendar-check mr-1"></i> Tanggal Pengisian
                                        </p>
                                        <p class="font-medium">{{ $tanggapanOrangtua->tanggal_pengisian_formatted }}</p>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="fas fa-stamp mr-1"></i> Status Tanda Tangan
                                        </p>
                                        <div class="flex items-center">
                                            @if($tanggapanOrangtua->tanda_tangan_digital)
                                                <span class="inline-flex items-center text-emerald-600 font-medium">
                                                    <i class="fas fa-check-circle mr-2"></i>
                                                    Sudah ditandatangani
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-amber-600 font-medium">
                                                    <i class="far fa-clock mr-2"></i>
                                                    Belum ditandatangani
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                        <p class="text-sm text-gray-500 mb-1">
                                            <i class="fas fa-clock mr-1"></i> Waktu
                                        </p>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <p class="text-xs text-gray-500">Dibuat</p>
                                                <p class="text-sm font-medium">{{ $tanggapanOrangtua->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            @if($tanggapanOrangtua->updated_at != $tanggapanOrangtua->created_at)
                                            <div>
                                                <p class="text-xs text-gray-500">Diperbarui</p>
                                                <p class="text-sm font-medium">{{ $tanggapanOrangtua->updated_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        @if($tanggapanOrangtua->tanda_tangan_digital)
                            <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-teal-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-signature mr-2 text-teal-600"></i>
                                    Tanda Tangan Digital
                                </h3>
                                
                                <div class="text-center">
                                    <div class="bg-white rounded-xl p-4 border border-gray-200 mb-4">
                                        @if(str_starts_with($tanggapanOrangtua->tanda_tangan_digital, 'data:image'))
                                            <img src="{{ $tanggapanOrangtua->tanda_tangan_digital }}" 
                                                 alt="Tanda Tangan" 
                                                 class="max-h-32 mx-auto">
                                        @else
                                            <div class="text-gray-500 py-8">
                                                <i class="fas fa-signature text-3xl mb-2"></i>
                                                <p>Tanda tangan dalam format digital</p>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ $tanggapanOrangtua->nama_orangtua }} • {{ $tanggapanOrangtua->tanggal_pengisian_formatted }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tanggapan Lengkap -->
                <div class="mt-8">
                    <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-comment-dots mr-2 text-blue-600"></i>
                            Tanggapan Orangtua
                        </h3>
                        
                        <div class="bg-white rounded-xl p-6 border border-gray-200">
                            <div class="prose max-w-none">
                                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $tanggapanOrangtua->tanggapan }}</p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    Dibaca: {{ ceil(str_word_count($tanggapanOrangtua->tanggapan) / 200) }} menit
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-font mr-2"></i>
                                    {{ str_word_count($tanggapanOrangtua->tanggapan) }} kata
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('tanggapan-orangtua.index') }}" 
                               class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-gray-700 bg-white rounded-xl hover:bg-gray-50 transition-all duration-300">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar
                            </a>
                            
                            <a href="{{ route('tanggapan-orangtua.edit', $tanggapanOrangtua->id) }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-300">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Tanggapan
                            </a>
                            
                            <button onclick="window.print()" 
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300">
                                <i class="fas fa-print mr-2"></i>
                                Cetak
                            </button>
                        </div>
                        
                        <form action="{{ route('tanggapan-orangtua.destroy', $tanggapanOrangtua->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Hapus tanggapan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-300">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Info -->
        @if($tanggapanOrangtua->siswa && $tanggapanOrangtua->siswa->tanggapanOrangtua->count() > 1)
            <div class="mt-8">
                <div class="bg-gradient-to-r from-blue-50 to-emerald-50 rounded-2xl border border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history mr-2 text-cyan-600"></i>
                        Tanggapan Lainnya
                    </h3>
                    
                    <div class="space-y-3">
                        @foreach($tanggapanOrangtua->siswa->tanggapanOrangtua->where('id', '!=', $tanggapanOrangtua->id)->take(2) as $related)
                            <a href="{{ route('tanggapan-orangtua.show', $related->id) }}" 
                               class="block bg-white rounded-xl p-4 border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-300">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $related->periode }}</p>
                                        <p class="text-sm text-gray-500">{{ $related->nama_orangtua }}</p>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
        }
        button, .bg-gradient-to-r, .rounded-2xl, .border, .shadow-xl {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    // Share via WhatsApp
    function shareWhatsApp() {
        const text = `Tanggapan Orangtua: {{ $tanggapanOrangtua->siswa->nama_lengkap }} - {{ $tanggapanOrangtua->periode }}`;
        const url = window.location.href;
        window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`, '_blank');
    }
    
    // Copy link to clipboard
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Link berhasil disalin ke clipboard!');
        });
    }
</script>
@endsection