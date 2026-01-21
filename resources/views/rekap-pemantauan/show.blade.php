@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Data - Rekap 7 Kebiasaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e40af',
                        'secondary': '#059669',
                        'primary-light': '#3b82f6',
                        'secondary-light': '#10b981',
                        'danger': '#dc2626',
                        'danger-light': '#ef4444',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #059669 100%);
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            stroke-dasharray: 283;
            stroke-dashoffset: 283;
            transition: stroke-dashoffset 0.5s ease;
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
                        <i class="fas fa-eye mr-3"></i>
                        Detail Data Rekap
                    </h1>
                    <p class="text-blue-100 mt-2">7 Kebiasaan Anak Indonesia Hebat</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('rekap-pemantauan.index') }}" 
                       class="bg-white text-primary px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <a href="{{ route('rekap-pemantauan.edit', $rekap->id) }}" 
                       class="bg-secondary text-white px-4 py-2 rounded-lg font-medium hover:bg-secondary-light transition flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('rekap-pemantauan.export-pdf', $rekap->id) }}" 
                       class="bg-danger text-white px-4 py-2 rounded-lg font-medium hover:bg-danger-light transition flex items-center"
                       target="_blank">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Info -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="w-20 h-20 bg-primary text-white rounded-full flex items-center justify-center font-bold text-2xl mr-4">
                            {{ substr($rekap->nama_lengkap, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $rekap->nama_lengkap }}</h2>
                            <p class="text-gray-600">Kelas {{ $rekap->kelas }}</p>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-calendar-alt text-gray-400 mr-2"></i>
                                <span class="text-gray-500">{{ $rekap->bulan }} {{ $rekap->tahun }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Circle -->
                    <div class="relative">
                        <svg class="w-32 h-32 progress-ring" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#e5e7eb" stroke-width="8"/>
                            <circle id="progressCircle" cx="50" cy="50" r="45" fill="none" stroke="#059669" stroke-width="8" 
                                    stroke-linecap="round" class="progress-ring-circle"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold text-gray-800">{{ $rekap->total_terbiasa }}/7</span>
                            <span class="text-sm text-gray-600">Kebiasaan</span>
                        </div>
                    </div>
                </div>
                
                <!-- Status Summary -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-secondary">{{ $rekap->total_terbiasa }}</div>
                        <div class="text-sm text-gray-600">Sudah Terbiasa</div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-danger">{{ 7 - $rekap->total_terbiasa }}</div>
                        <div class="text-sm text-gray-600">Belum Terbiasa</div>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-primary">{{ round(($rekap->total_terbiasa / 7) * 100) }}%</div>
                        <div class="text-sm text-gray-600">Progress</div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                        <div class="text-lg font-bold text-purple-700">{{ $rekap->progress_status }}</div>
                        <div class="text-sm text-gray-600">Status</div>
                    </div>
                </div>
            </div>

            <!-- 7 Kebiasaan Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Kebiasaan List -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-secondary text-white p-3 rounded-full mr-4">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">7 Kebiasaan Detail</h2>
                            <p class="text-gray-600">Status setiap kebiasaan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($kebiasaanList as $kebiasaan)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="{{ $kebiasaan['icon'] }} text-primary text-xl mr-4"></div>
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $kebiasaan['label'] }}</h3>
                                    <p class="text-sm text-gray-500">{{ $kebiasaan['description'] }}</p>
                                </div>
                            </div>
                            <div>
                                @if($kebiasaan['status'] === 'sudah_terbiasa')
                                    <span class="status-badge bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Sudah Terbiasa
                                    </span>
                                @else
                                    <span class="status-badge bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Belum Terbiasa
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Informasi Siswa -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="bg-primary text-white p-3 rounded-full mr-4">
                            <i class="fas fa-info-circle text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Informasi Detail</h2>
                            <p class="text-gray-600">Data lengkap siswa</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Data Siswa -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-user-graduate text-primary mr-2"></i> Data Siswa
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                                    <p class="font-medium text-gray-800">{{ $rekap->nama_lengkap }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Kelas</p>
                                    <p class="font-medium text-gray-800">Kelas {{ $rekap->kelas }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Periode</p>
                                    <p class="font-medium text-gray-800">{{ $rekap->bulan }} {{ $rekap->tahun }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Input</p>
                                    <p class="font-medium text-gray-800">{{ $rekap->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Persetujuan -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-file-signature text-primary mr-2"></i> Persetujuan
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Guru Kelas</p>
                                    <p class="font-medium text-gray-800">{{ $rekap->guru_kelas ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Orangtua Siswa</p>
                                    <p class="font-medium text-gray-800">{{ $rekap->orangtua_siswa ?? '-' }}</p>
                                </div>
                                @if($rekap->tanggal_persetujuan)
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Persetujuan</p>
                                    <p class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($rekap->tanggal_persetujuan)->format('d/m/Y') }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Catatan -->
                        @if($rekap->catatan)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-sticky-note text-primary mr-2"></i> Catatan
                            </h3>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-gray-700">{{ $rekap->catatan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Visual Progress -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-purple-600 text-white p-3 rounded-full mr-4">
                        <i class="fas fa-chart-bar text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Visualisasi Progress</h2>
                        <p class="text-gray-600">Grafik perkembangan kebiasaan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Progress Bar Chart -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress per Kebiasaan</h3>
                        <div class="space-y-4">
                            @foreach($kebiasaanList as $kebiasaan)
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>{{ $kebiasaan['label'] }}</span>
                                    <span>
                                        @if($kebiasaan['status'] === 'sudah_terbiasa')
                                            <span class="text-secondary">Sudah Terbiasa</span>
                                        @else
                                            <span class="text-danger">Belum Terbiasa</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $kebiasaan['status'] === 'sudah_terbiasa' ? 'bg-secondary' : 'bg-danger' }}" 
                                         style="width: {{ $kebiasaan['status'] === 'sudah_terbiasa' ? '100%' : '50%' }}"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Summary -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>
                        <div class="bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200 rounded-lg p-6">
                            <div class="text-center">
                                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-md mb-4">
                                    <span class="text-3xl font-bold text-primary">{{ $rekap->total_terbiasa }}</span>
                                </div>
                                <h4 class="text-xl font-bold text-gray-800 mb-2">
                                    {{ $rekap->total_terbiasa }} dari 7 Kebiasaan
                                </h4>
                                <p class="text-gray-600 mb-4">
                                    @if($rekap->total_terbiasa == 7)
                                        Selamat! Semua kebiasaan sudah terbentuk dengan baik.
                                    @elseif($rekap->total_terbiasa >= 5)
                                        Baik! Hanya {{ 7 - $rekap->total_terbiasa }} kebiasaan yang perlu diperbaiki.
                                    @elseif($rekap->total_terbiasa >= 3)
                                        Cukup! Masih ada {{ 7 - $rekap->total_terbiasa }} kebiasaan yang perlu ditingkatkan.
                                    @else
                                        Perlu perhatian! Masih banyak kebiasaan yang belum terbentuk.
                                    @endif
                                </p>
                                <div class="text-sm text-gray-500">
                                    Terakhir diperbarui: {{ $rekap->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col md:flex-row justify-between items-center bg-white rounded-xl shadow-lg p-6">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-history mr-2 text-primary"></i>
                        <span class="text-sm">Data ini dibuat pada {{ $rekap->created_at->format('d F Y') }}</span>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('rekap-pemantauan.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                    <a href="{{ route('rekap-pemantauan.edit', $rekap->id) }}"
                       class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-primary-light transition flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                    <a href="{{ route('rekap-pemantauan.export-pdf', $rekap->id) }}"
                       class="px-6 py-3 bg-secondary text-white rounded-lg font-medium hover:bg-secondary-light transition flex items-center"
                       target="_blank">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

   
    <!-- JavaScript untuk progress circle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update progress circle
            const progressCircle = document.getElementById('progressCircle');
            const progress = ({{ $rekap->total_terbiasa }} / 7) * 100;
            const circumference = 2 * Math.PI * 45;
            const offset = circumference - (progress / 100) * circumference;
            
            progressCircle.style.strokeDasharray = circumference;
            progressCircle.style.strokeDashoffset = offset;
        });
    </script>
</body>
</html>

@endsection