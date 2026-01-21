@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelas Saya - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 51, 160, 0.08);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .shimmer-bg {
            background: linear-gradient(90deg, 
                rgba(0, 51, 160, 0.05) 0%, 
                rgba(0, 168, 107, 0.05) 50%, 
                rgba(0, 51, 160, 0.05) 100%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite linear;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-emerald-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header dengan gradient biru-hijau -->
        <div class="glass-card rounded-2xl p-6 mb-8 fade-in-up">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-500 to-accent-green flex items-center justify-center shadow-lg float-animation">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold gradient-text mb-1">
                            Kelas Saya
                        </h1>
                        <p class="text-gray-600 text-sm">
                            Kelas yang Anda ampu sebagai guru wali
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if(!$kelas)
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center fade-in-up">
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-primary-100 to-accent-green/20 flex items-center justify-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Memiliki Kelas</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto text-sm">
                Anda belum ditugaskan sebagai wali kelas. Silakan hubungi administrator untuk penugasan kelas.
            </p>
            <a href="{{ route('dashboard.guruwali') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
        @else
      

        <!-- Daftar Siswa Table -->
        <div class="glass-card rounded-2xl overflow-hidden fade-in-up" style="animation-delay: 0.6s">
            <div class="p-6 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Siswa</h3>
                            <p class="text-gray-500 text-sm">{{ $kelas->siswas->count() }} siswa ditemukan</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari nama siswa..." 
                               id="searchInput"
                               class="w-full lg:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900 text-sm">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm">Nama Siswa</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm">NIS</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm">Jenis Kelamin</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($kelas->siswas as $siswa)
                        <tr class="hover:bg-gradient-to-r from-primary-50/30 to-accent-green/10 transition-all duration-200">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-green flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $siswa->nama_lengkap }}</span>
                                        <span class="text-xs text-gray-500">NISN: {{ $siswa->nisn }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="font-mono font-semibold text-gray-700 text-sm">{{ $siswa->nis }}</span>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                                    {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($kelas->siswas->count() === 0)
            <div class="p-12 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Siswa</h4>
                <p class="text-gray-600 mb-6 text-sm">Belum ada siswa yang terdaftar di kelas ini.</p>
                <button class="inline-flex items-center gap-2 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Siswa
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animasi untuk fade-in elements
            const elements = document.querySelectorAll('.fade-in-up');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Hover effect for table rows
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', () => {
                    row.classList.add('bg-gradient-to-r', 'from-primary-50/30', 'to-accent-green/10');
                });
                row.addEventListener('mouseleave', () => {
                    row.classList.remove('bg-gradient-to-r', 'from-primary-50/30', 'to-accent-green/10');
                });
            });
        });
    </script>
</body>
</html>
@endsection