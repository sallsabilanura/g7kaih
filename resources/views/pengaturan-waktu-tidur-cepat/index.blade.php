@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengaturan Waktu Tidur Cepat - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .gradient-primary {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
        }

        .gradient-primary-text {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 rounded-xl gradient-primary flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold gradient-primary-text mb-2">
                            Pengaturan Waktu Tidur
                        </h1>
                        <p class="text-gray-500 text-sm">
                            Kelola pengaturan waktu tidur dengan fleksibilitas penuh
                        </p>
                    </div>
                </div>
                
                @if(!$sudahAda)
                <a href="{{ route('pengaturan-waktu-tidur-cepat.create') }}" 
                   class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center lg:justify-start w-full lg:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Pengaturan Baru
                </a>
                @endif
            </div>

            <!-- Alert Messages -->
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

            @if (session('error'))
            <div class="mt-4 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if (session('info'))
            <div class="mt-4 bg-gradient-to-r from-blue-50 to-primary-50 border-l-4 border-primary-500 p-4 rounded-xl flex items-start fade-in">
                <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-gray-900 font-medium">{{ session('info') }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Tampilan jika belum ada pengaturan -->
        @if(!$sudahAda)
        <div class="glass-card rounded-2xl shadow-lg p-12 text-center fade-in">
            <!-- Icon -->
            <div class="bg-gradient-to-br from-primary-50 to-accent-green/20 rounded-2xl p-8 w-32 h-32 mx-auto mb-8 flex items-center justify-center">
                <svg class="w-16 h-16 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </div>
            
            <!-- Teks -->
            <h2 class="text-3xl font-bold gradient-primary-text mb-4">
                Belum Ada Pengaturan Waktu
            </h2>
            <p class="text-gray-600 mb-10 text-lg max-w-2xl mx-auto">
                Buat pengaturan waktu tidur dengan fleksibilitas penuh - tanpa batasan apapun!
            </p>
            
            <!-- Tombol Buat Pengaturan -->
            <a href="{{ route('pengaturan-waktu-tidur-cepat.create') }}" 
               class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 inline-flex items-center gap-3 text-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Pengaturan Pertama
            </a>
        </div>
        @else
        <!-- Tampilan jika sudah ada pengaturan -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in">
            <!-- Header Card -->
            <div class="px-8 py-8 gradient-primary">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl p-5">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $pengaturan->nama_pengaturan }}</h2>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 mt-2">
                                <span class="inline-flex px-4 py-1.5 rounded-full text-sm font-semibold {{ $pengaturan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $pengaturan->is_active ? '✓ Aktif' : '✗ Nonaktif' }}
                                </span>
                                @if($pengaturan->deskripsi)
                                <span class="text-white text-opacity-90 text-sm">
                                    {{ $pengaturan->deskripsi }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('pengaturan-waktu-tidur-cepat.edit') }}" 
                           class="bg-white text-primary-600 font-semibold px-5 py-2.5 rounded-xl hover:bg-primary-50 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('pengaturan-waktu-tidur-cepat.reset') }}" method="POST" class="inline">
                            @csrf 
                            <button type="submit" 
                                    class="bg-white text-gray-700 font-semibold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition-colors flex items-center gap-2"
                                    onclick="return confirm('Reset pengaturan ke default?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Daftar Kategori -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Kategori Waktu Tidur</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($pengaturan->kategori_waktu as $index => $kategori)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $kategori['warna'] }}"></div>
                                            <span class="text-sm font-medium text-gray-900">{{ $kategori['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $kategori['waktu_start'] }} - {{ $kategori['waktu_end'] }}
                                        @if($kategori['waktu_end'] < $kategori['waktu_start'])
                                        <span class="ml-2 text-xs text-gray-500">(melewati tengah malam)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $kategori['nilai'] >= 80 ? 'bg-green-100 text-green-800' : 
                                              ($kategori['nilai'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $kategori['nilai'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ ($kategori['is_active'] ?? true) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ($kategori['is_active'] ?? true) ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

               
                <!-- Delete Button -->
                <div class="mt-8 pt-8 border-t border-gray-200 fade-in" style="animation-delay: 0.4s;">
                    <form action="{{ route('pengaturan-waktu-tidur-cepat.destroy') }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold py-3.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 group"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengaturan waktu tidur cepat? Tindakan ini tidak dapat dibatalkan.')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Pengaturan
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
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