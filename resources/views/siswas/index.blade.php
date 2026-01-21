@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Siswa - 7 Kebiasaan Anak Indonesia Hebat</title>
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

        .select-arrow {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%230033A0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
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
                        Daftar Siswa
                    </h1>
                    <p class="text-gray-500 text-sm">
                        Kelola data siswa dan kelas
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Tombol Download QR Code All -->
                    <a href="{{ route('siswas.barcode.download-all') }}"
                       class="bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        Download QR
                    </a>
                    
                    <!-- Tombol Import Excel -->
                    <button onclick="openImportModal()"
                           class="bg-gradient-to-r from-purple-500 to-pink-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Import Excel
                    </button>
                    
                    <!-- Tombol Tambah Siswa -->
                    <a href="{{ route('siswas.create') }}" 
                       class="bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Siswa
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

      
        <!-- Modal Import Excel -->
        <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden fade-in">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full glass-card">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Import dari Excel</h3>
                                <p class="text-gray-500 text-sm">Upload file excel siswa</p>
                            </div>
                        </div>
                        <button onclick="closeImportModal()" 
                                class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('siswas.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel</label>
                            <div class="relative">
                                <input type="file" name="file" accept=".xlsx,.xls,.csv" 
                                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 bg-white shadow-sm"
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Format yang didukung: .xlsx, .xls, .csv</p>
                        </div>
                        
                        <div class="bg-gradient-to-r from-blue-50 to-primary-50 border border-blue-100 rounded-xl p-4">
                            <h4 class="font-semibold text-primary-900 text-sm mb-3">Format File Excel:</h4>
                            <div class="space-y-2">
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom A: <strong>nisn</strong> (Harus unik)</span>
                                </div>
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom B: <strong>nis</strong> (Harus unik)</span>
                                </div>
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom C: <strong>nama_lengkap</strong></span>
                                </div>
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom D: <strong>kelas</strong> (opsional)</span>
                                </div>
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom E: <strong>tanggal_lahir</strong> (YYYY-MM-DD)</span>
                                </div>
                                <div class="flex items-center text-xs text-primary-800">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-2"></div>
                                    <span>Kolom F: <strong>alamat</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 pt-2">
                            <a href="{{ route('siswas.download-template') }}" 
                               class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-3 rounded-xl hover:shadow-lg transition-all duration-300 text-center font-semibold text-sm">
                                Download Template
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white px-4 py-3 rounded-xl hover:shadow-lg transition-all duration-300 font-semibold text-sm">
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in" style="animation-delay: 0.3s;">
            <!-- Table Header -->
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Daftar Lengkap Siswa</h2>
                            <p class="text-gray-500 text-sm">Total {{ $siswas->total() }} siswa ditemukan</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" 
                                   placeholder="Cari nama atau NISN..." 
                                   class="w-full lg:w-64 pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900 text-sm">
                        </div>
                        <select class="px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 transition-all duration-300 text-gray-900 text-sm select-arrow bg-white">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-500/10 to-accent-green/10">
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">No</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Siswa</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Identitas</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Kelas</th>
                            <th class="p-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($siswas as $siswa)
                        <tr class="table-row-hover transition-all duration-200">
                            <td class="p-4 font-medium text-gray-600 text-sm">
                                {{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-xl student-avatar flex items-center justify-center text-white font-bold text-base mr-3">
                                        {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-900 text-sm block">{{ $siswa->nama_lengkap }}</span>
                                        <span class="text-xs text-gray-500">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="space-y-1">
                                    <div class="flex items-center">
                                        <span class="text-xs text-gray-500 w-12">NISN:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $siswa->nisn }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-xs text-gray-500 w-12">NIS:</span>
                                        <span class="text-sm font-medium text-gray-900">{{ $siswa->nis }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($siswa->kelas)
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-primary-100 to-blue-50 flex items-center justify-center text-primary-600 font-bold text-xs mr-2">
                                            {{ strtoupper(substr($siswa->kelas->nama_kelas, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-green-100 text-accent-green">
                                                {{ $siswa->kelas->nama_kelas }}
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">{{ $siswa->kelas->guruWali->nama_lengkap ?? '-' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">
                                        Belum Ada Kelas
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('siswas.barcode.download', $siswa) }}" 
                                       class="text-green-600 hover:text-green-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition-colors"
                                       title="Download QR Code">
                                        QR
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('siswas.edit', $siswa) }}" 
                                       class="text-primary-600 hover:text-primary-800 font-medium text-sm px-3 py-2 rounded-lg hover:bg-primary-50 transition-colors">
                                        Edit
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('siswas.destroy', $siswa) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin menghapus siswa {{ $siswa->nama_lengkap }}?')" 
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
                @foreach ($siswas as $siswa)
                <div class="glass-card rounded-xl border border-gray-200 p-4 fade-in">
                    <div class="flex items-start mb-4">
                        <div class="w-12 h-12 rounded-xl student-avatar flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $siswa->nama_lengkap }}</h3>
                            <p class="text-xs text-gray-500 mb-1">
                                {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </p>
                            
                            <!-- Identitas -->
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <p class="text-xs text-gray-500">NISN</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->nisn }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <p class="text-xs text-gray-500">NIS</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $siswa->nis }}</p>
                                </div>
                            </div>
                            
                            <!-- Kelas -->
                            @if($siswa->kelas)
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-primary-100 to-blue-50 flex items-center justify-center text-primary-600 font-bold text-xs mr-2">
                                        {{ strtoupper(substr($siswa->kelas->nama_kelas, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-900">{{ $siswa->kelas->nama_kelas }}</p>
                                        <p class="text-xs text-gray-500">{{ $siswa->kelas->guruWali->nama_lengkap ?? '-' }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-600">
                                        Belum Ada Kelas
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('siswas.barcode.download', $siswa) }}" 
                           class="flex-1 text-center bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                            QR
                        </a>
                        <a href="{{ route('siswas.edit', $siswa) }}" 
                           class="flex-1 text-center bg-gradient-to-r from-primary-500 to-primary-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                            Edit
                        </a>
                        <form action="{{ route('siswas.destroy', $siswa) }}" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus siswa {{ $siswa->nama_lengkap }}?')" 
                                    class="w-full text-center bg-gradient-to-r from-red-500 to-red-600 text-white font-medium py-2.5 rounded-lg hover:shadow-lg transition-all duration-300 text-sm">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($siswas->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $siswas->firstItem() ?? 0 }} - {{ $siswas->lastItem() ?? 0 }} dari {{ $siswas->total() }} siswa
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $siswas->links() }}
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

            // Filter by kelas
            const kelasFilter = document.querySelector('select');
            if (kelasFilter) {
                kelasFilter.addEventListener('change', function(e) {
                    const selectedKelas = e.target.value;
                    const rows = document.querySelectorAll('tbody tr, .lg\\:hidden .glass-card');
                    
                    rows.forEach(row => {
                        if (!selectedKelas) {
                            row.style.display = '';
                        } else {
                            const kelasInfo = row.querySelector('.bg-green-100, .bg-gray-100');
                            if (kelasInfo && kelasInfo.textContent.includes(e.target.options[e.target.selectedIndex].text)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });

        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }

        // Close modal ketika klik di luar
        document.getElementById('importModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });
    </script>
</body>
</html>
@endsection