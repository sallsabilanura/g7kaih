@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Pengguna - 7 Kebiasaan Anak Indonesia Hebat</title>
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
                        Daftar Pengguna
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola data guru wali dan admin sistem
                    </p>
                </div>
                <a href="{{ route('users.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    Tambah Pengguna Baru
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
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Lengkap Pengguna</h2>
                            <p class="text-gray-500 text-sm">Total {{ $users->total() }} pengguna ditemukan</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="text" 
                               placeholder="Cari nama atau email..." 
                               class="w-full lg:w-64 px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900 text-sm">
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Pengguna</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Kontak</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Peran</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Status</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $user->nama_lengkap }}</span>
                                        <span class="text-xs text-gray-500">@ {{ $user->username }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    <p class="text-gray-900 text-sm">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                                    {{ $user->peran == 'guru_wali' ? 'bg-green-100 text-accent-green' : 'bg-blue-100 text-primary-600' }}">
                                    {{ $user->peran == 'guru_wali' ? 'Guru Wali' : 'Admin' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                                    {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                  
                                   
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus pengguna {{ $user->nama_lengkap }}?')" 
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
                @foreach ($users as $user)
                <div class="glass-card rounded-xl border border-gray-200 p-4 fade-in">
                    <div class="flex items-start mb-4">
                        <div class="w-12 h-12 rounded-xl user-avatar flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $user->nama_lengkap }}</h3>
                            <p class="text-xs text-gray-500 mb-1">@ {{ $user->username }}</p>
                            <p class="text-gray-900 text-sm">{{ $user->email }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs text-gray-500">
                                    {{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-xs text-gray-500">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between mb-3">
                        <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                            {{ $user->peran == 'guru_wali' ? 'bg-green-100 text-accent-green' : 'bg-blue-100 text-primary-600' }}">
                            {{ $user->peran == 'guru_wali' ? 'Guru Wali' : 'Admin' }}
                        </span>
                        <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-semibold 
                            {{ $user->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    
                    <div class="flex gap-3 pt-3 border-t border-gray-100">
                      
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus pengguna {{ $user->nama_lengkap }}?')" 
                                    class="w-full text-center bg-red-500 text-white font-medium py-2.5 rounded-lg hover:bg-red-600 transition-colors text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $users->links() }}
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