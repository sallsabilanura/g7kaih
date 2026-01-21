@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rekap Pemantauan 7 Kebiasaan Anak Indonesia Hebat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e40af', // Biru
                        'secondary': '#059669', // Hijau
                        'primary-light': '#3b82f6',
                        'secondary-light': '#10b981',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl md:text-3xl font-bold flex items-center">
                        <i class="fas fa-chart-line mr-3"></i>
                        Rekap Pemantauan 7 Kebiasaan
                    </h1>
                    <p class="text-blue-100 mt-2">Anak Indonesia Hebat</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rekap-pemantauan.statistics') }}" 
                       class="bg-white text-primary px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i> Statistik
                    </a>
                    <a href="{{ route('rekap-pemantauan.create') }}" 
                       class="bg-secondary text-white px-4 py-2 rounded-lg font-medium hover:bg-secondary-light transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Data
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Filter Section -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-filter text-primary mr-2"></i> Filter Data
            </h2>
            <form method="GET" action="{{ route('rekap-pemantauan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Nama Siswa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                    <input type="text" name="nama" value="{{ request('nama') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition"
                           placeholder="Cari nama...">
                </div>
                
                <!-- Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasOptions as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">Semua Bulan</option>
                        @foreach($bulanOptions as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                                {{ $bulan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunOptions as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Button -->
                <div class="md:col-span-4 flex justify-end space-x-3">
                    <button type="submit" 
                            class="bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-light transition flex items-center">
                        <i class="fas fa-search mr-2"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('rekap-pemantauan.index') }}" 
                       class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition flex items-center">
                        <i class="fas fa-redo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Stats Summary -->
        @if($rekap->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Data</p>
                        <p class="text-3xl font-bold text-primary">{{ $rekap->total() }}</p>
                    </div>
                    <div class="bg-primary text-white p-3 rounded-full">
                        <i class="fas fa-database text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata Kebiasaan Terbentuk</p>
                        <p class="text-3xl font-bold text-secondary">
                            {{ number_format($rekap->avg('total_terbiasa'), 1) }}/7
                        </p>
                    </div>
                    <div class="bg-secondary text-white p-3 rounded-full">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Halaman</p>
                        <p class="text-3xl font-bold text-indigo-700">
                            {{ $rekap->currentPage() }}/{{ $rekap->lastPage() }}
                        </p>
                    </div>
                    <div class="bg-indigo-600 text-white p-3 rounded-full">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($rekap->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-blue-100 rounded-full mb-6">
                    <i class="fas fa-clipboard-list text-primary text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Data</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Belum ada data rekap pemantauan yang tersimpan. Mulai dengan menambahkan data baru.
                </p>
                <a href="{{ route('rekap-pemantauan.create') }}" 
                   class="inline-flex items-center bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-primary-light transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Data Pertama
                </a>
            </div>
            @else
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Siswa
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Kelas & Periode
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Progress
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rekap as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Siswa -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-primary text-white rounded-full flex items-center justify-center font-bold">
                                        {{ substr($item->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->nama_lengkap }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->guru_kelas ?? 'Belum ada guru' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Kelas & Periode -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 font-medium">
                                    Kelas {{ $item->kelas }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $item->bulan }} {{ $item->tahun }}
                                </div>
                                @if($item->tanggal_persetujuan)
                                <div class="text-xs text-gray-400 mt-1">
                                    Disetujui: {{ date('d/m/Y', strtotime($item->tanggal_persetujuan)) }}
                                </div>
                                @endif
                            </td>
                            
                            <!-- Progress -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 mb-1">
                                    {{ $item->total_terbiasa }} dari 7 kebiasaan
                                </div>
                                <div class="progress-bar bg-gray-200">
                                    <div class="bg-secondary h-full" style="width: {{ ($item->total_terbiasa / 7) * 100 }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ number_format(($item->total_terbiasa / 7) * 100, 1) }}% terbentuk
                                </div>
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = '';
                                    if($item->total_terbiasa == 7) {
                                        $statusClass = 'bg-green-100 text-green-800';
                                    } elseif($item->total_terbiasa >= 5) {
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                    } elseif($item->total_terbiasa >= 3) {
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                    } else {
                                        $statusClass = 'bg-red-100 text-red-800';
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $item->progress_status }}
                                </span>
                            </td>
                            
                            <!-- Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('rekap-pemantauan.show', $item->id) }}" 
                                       class="text-primary hover:text-primary-light transition"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('rekap-pemantauan.edit', $item->id) }}" 
                                       class="text-secondary hover:text-secondary-light transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('rekap-pemantauan.export-pdf', $item->id) }}" 
                                       class="text-red-600 hover:text-red-800 transition"
                                       title="Export PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <form action="{{ route('rekap-pemantauan.destroy', $item->id) }}" 
                                          method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-gray-500 hover:text-red-600 transition"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-700 mb-4 md:mb-0">
                        Menampilkan <span class="font-medium">{{ $rekap->firstItem() }}</span> 
                        sampai <span class="font-medium">{{ $rekap->lastItem() }}</span> 
                        dari <span class="font-medium">{{ $rekap->total() }}</span> data
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Previous Button -->
                        @if($rekap->onFirstPage())
                            <span class="px-3 py-1 bg-gray-200 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $rekap->previousPageUrl() }}" 
                               class="px-3 py-1 bg-primary text-white rounded-lg hover:bg-primary-light transition">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Page Numbers -->
                        <div class="flex space-x-1">
                            @foreach(range(1, $rekap->lastPage()) as $page)
                                @if($page == $rekap->currentPage())
                                    <span class="px-3 py-1 bg-primary text-white rounded-lg font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $rekap->url($page) }}" 
                                       class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        
                        <!-- Next Button -->
                        @if($rekap->hasMorePages())
                            <a href="{{ $rekap->nextPageUrl() }}" 
                               class="px-3 py-1 bg-primary text-white rounded-lg hover:bg-primary-light transition">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Export Section -->
        @if($rekap->isNotEmpty())
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-export text-primary mr-2"></i> Export Data
            </h3>
            <p class="text-gray-600 mb-4">Export data rekap pemantauan berdasarkan kelas dan periode:</p>
            <form action="{{ route('rekap-pemantauan.export-rekap-kelas') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        @foreach($kelasOptions as $kelas)
                            <option value="{{ $kelas }}">Kelas {{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        @foreach($bulanOptions as $bulan)
                            <option value="{{ $bulan }}">{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition">
                        @foreach($tahunOptions as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-primary text-white px-6 py-2 rounded-lg font-medium hover:bg-primary-light transition flex items-center justify-center">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

   

    <!-- Notification Script -->
    @if(session('success'))
    <div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('notification').remove()" class="ml-4">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.getElementById('notification');
            if (notification) notification.remove();
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <div id="error-notification" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <span>{{ session('error') }}</span>
        <button onclick="document.getElementById('error-notification').remove()" class="ml-4">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.getElementById('error-notification');
            if (notification) notification.remove();
        }, 5000);
    </script>
    @endif
</body>
</html>
@endsection