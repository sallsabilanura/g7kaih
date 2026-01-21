@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Kelas - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .class-avatar {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .teacher-avatar {
            background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
            color: white;
            font-weight: bold;
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
                        Daftar Kelas
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola data kelas dan guru wali
                    </p>
                </div>
                <a href="{{ route('kelas.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Kelas Baru
                </a>
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



        <!-- Main Table Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.3s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Lengkap Kelas</h2>
                            <p class="text-gray-500 text-sm">Total {{ $kelas->total() }} kelas ditemukan</p>
                        </div>
                    </div>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                               placeholder="Cari nama kelas..." 
                               class="w-full lg:w-64 pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900 text-sm">
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Kelas</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Guru Wali</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Statistik</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($kelas as $item)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ ($kelas->currentPage() - 1) * $kelas->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl class-avatar flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($item->nama_kelas, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $item->nama_kelas }}</span>
                                        <span class="text-xs text-gray-500">Kode: {{ $item->kode_kelas ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full teacher-avatar flex items-center justify-center text-white font-bold text-xs mr-3">
                                        {{ strtoupper(substr($item->guruWali->nama_lengkap ?? 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-gray-900 text-sm font-medium">{{ $item->guruWali->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->guruWali->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $item->siswas->count() }} Siswa</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-accent-green mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $item->tahun_ajaran ?? '2024/2025' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('kelas.show', $item) }}" 
                                       class="text-primary-600 hover:text-primary-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-primary-50 transition-colors">
                                        Detail
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('kelas.edit', $item) }}" 
                                       class="text-accent-green hover:text-accent-green/80 font-medium text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition-colors">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('kelas.destroy', $item) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus kelas {{ $item->nama_kelas }}?')" 
                                                class="text-red-600 hover:text-red-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden p-4 space-y-4">
                @foreach ($kelas as $item)
                <div class="glass-card rounded-xl border border-gray-200 p-4 fade-in">
                    <div class="flex items-start mb-4">
                        <div class="w-12 h-12 rounded-xl class-avatar flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($item->nama_kelas, 0, 2)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $item->nama_kelas }}</h3>
                            <p class="text-xs text-gray-500 mb-3">Kode: {{ $item->kode_kelas ?? 'N/A' }}</p>
                            
                            <!-- Guru Wali -->
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 rounded-full teacher-avatar flex items-center justify-center text-white font-bold text-xs mr-2">
                                    {{ strtoupper(substr($item->guruWali->nama_lengkap ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-gray-900 text-xs font-medium">{{ $item->guruWali->nama_lengkap ?? 'Belum Ditentukan' }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $item->guruWali->email ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <!-- Statistik -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-gradient-to-r from-blue-50 to-primary-50 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Jumlah Siswa</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->siswas->count() }} Siswa</p>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-accent-green/20 rounded-lg p-3">
                                    <p class="text-xs text-gray-500 mb-1">Tahun Ajaran</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $item->tahun_ajaran ?? '2024/2025' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('kelas.show', $item) }}" 
                           class="flex-1 text-center bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                            Detail
                        </a>
                        <a href="{{ route('kelas.edit', $item) }}" 
                           class="flex-1 text-center bg-gradient-to-r from-accent-green to-emerald-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                            Edit
                        </a>
                        <form action="{{ route('kelas.destroy', $item) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus kelas {{ $item->nama_kelas }}?')" 
                                    class="w-full text-center bg-gradient-to-r from-red-500 to-red-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($kelas->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $kelas->firstItem() ?? 0 }} - {{ $kelas->lastItem() ?? 0 }} dari {{ $kelas->total() }} kelas
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $kelas->links() }}
                    </div>
                </div>
            </div>
            @endif
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
            const searchInput = document.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('tbody tr, .lg\\:hidden .glass-card');
                    
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
        });
    </script>
</body>
</html>
@endsection