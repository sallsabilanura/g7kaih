@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kegiatan Bermasyarakat - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .student-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .table-row-hover:hover {
            background: linear-gradient(90deg, rgba(0, 51, 160, 0.02) 0%, rgba(0, 168, 107, 0.02) 100%);
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
            border-radius: 9999px;
        }
        
        .status-badge.pending::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #FFD700 0%, rgba(255, 215, 0, 0.3) 100%);
            border-radius: 9999px;
        }

        .paraf-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #0033A0 0%, rgba(0, 51, 160, 0.3) 100%);
            border-radius: 9999px;
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
                        Kegiatan Bermasyarakat
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Catatan Kegiatan Sosial Siswa
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Tombol Tambah Data -->
                    <a href="{{ route('bermasyarakat.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Kegiatan
                    </a>
                </div>
            </div>

            <!-- Alert Success -->
            @if (session('success'))
            <div class="mt-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-accent-green mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif
        </div>

       
        <!-- Data Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($data as $item)
            @php
                // Safely handle gambar_kegiatan - bisa string atau array
                $gambarKegiatan = $item->gambar_kegiatan;
                
                // Jika string, coba decode JSON
                if (is_string($gambarKegiatan)) {
                    $decoded = json_decode($gambarKegiatan, true);
                    $gambarKegiatan = $decoded ?: [$gambarKegiatan]; // Jika decode gagal, buat array
                }
                
                // Pastikan array
                if (!is_array($gambarKegiatan)) {
                    $gambarKegiatan = [];
                }
                
                // Ambil gambar pertama
                $firstImage = !empty($gambarKegiatan) ? $gambarKegiatan[0] : null;
                $jumlahGambar = count($gambarKegiatan);
            @endphp
            
            <div class="glass-card rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group fade-in">
                <!-- Card Header dengan Gambar -->
                <div class="relative h-48 bg-gradient-to-br from-primary-500/20 to-accent-green/20">
                    @if($firstImage)
                        <img src="{{ Storage::url($firstImage) }}" class="w-full h-full object-cover" alt="Kegiatan">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 right-3">
                        @if($item->status == 'approved')
                        <span class="relative inline-flex items-center px-3 py-1 text-white rounded-full text-xs font-semibold shadow-lg status-badge approved">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Dinilai
                            </span>
                        </span>
                        @else
                        <span class="relative inline-flex items-center px-3 py-1 text-white rounded-full text-xs font-semibold shadow-lg status-badge pending">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pending
                            </span>
                        </span>
                        @endif
                    </div>

                    <!-- Paraf Badge -->
                    <div class="absolute top-3 left-3">
                        @if($item->sudah_ttd_ortu)
                        <span class="relative inline-flex items-center px-3 py-1 text-white rounded-full text-xs font-semibold shadow-lg paraf-badge">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Paraf
                            </span>
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 bg-gray-500/80 text-white rounded-full text-xs font-semibold shadow-lg">
                            ⏳ Menunggu Paraf
                        </span>
                        @endif
                    </div>

                    <!-- Nilai Badge -->
                    @if($item->nilai)
                    <div class="absolute bottom-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-2 rounded-xl shadow-lg">
                        <div class="text-2xl font-bold text-primary-600">{{ $item->nilai }}</div>
                        <div class="text-xs text-gray-500 -mt-1">/100</div>
                    </div>
                    @endif

                    <!-- Judul di bottom -->
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <h3 class="text-lg font-bold text-white line-clamp-2 drop-shadow-lg">{{ $item->nama_kegiatan }}</h3>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    <!-- Info Siswa -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-green text-white rounded-full flex items-center justify-center font-bold text-sm">
                            {{ substr($item->siswa->nama_lengkap ?? $item->siswa->nama ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">
                                {{ $item->siswa->nama_lengkap ?? $item->siswa->nama ?? 'Siswa tidak ditemukan' }}
                            </h4>
                            <p class="text-xs text-gray-500">NIS: {{ $item->siswa->nis ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Info Tanggal -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $item->tanggal->format('d/m/Y') }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 bg-primary-100 text-primary-700 rounded-lg text-xs font-medium">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $item->bulan }} {{ $item->tahun }}
                        </span>
                    </div>

                    <!-- Pesan Kesan -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Pesan & Kesan</p>
                        <p class="text-sm text-gray-600 line-clamp-3">{{ $item->pesan_kesan }}</p>
                    </div>

                    <!-- Jumlah Gambar -->
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-1">Dokumentasi</p>
                        <div class="flex items-center gap-2 text-sm text-primary-600 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $jumlahGambar > 0 ? $jumlahGambar : 'Tidak ada' }} Gambar
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('bermasyarakat.show', $item->id) }}" 
                           class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-200 transition-colors">
                            Detail
                        </a>
                        <a href="{{ route('bermasyarakat.edit', $item->id) }}" 
                           class="flex-1 text-center px-3 py-2 bg-amber-100 text-amber-600 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('bermasyarakat.destroy', $item->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-600 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="glass-card rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-primary-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Data</h3>
                    <p class="text-gray-500 mb-6">Belum ada catatan kegiatan bermasyarakat. Mulai dengan menambahkan data baru!</p>
                    <a href="{{ route('bermasyarakat.create') }}" 
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-500 to-accent-green text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Data Pertama
                    </a>
                </div>
            </div>
            @endforelse
        </div>

    </div>

    <script>
        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });
        });
    </script>
</body>
</html>
@endsection