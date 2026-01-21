@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="min-h-screen bg-gradient-to-br via-white to-emerald-50 pt-16 pb-8">
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Dashboard Guru Wali</h1>
                <p class="text-gray-600">Selamat datang, <span class="font-semibold text-blue-600">{{ $user->nama_lengkap }}</span></p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Hari ini</p>
                    <p class="text-base font-semibold text-gray-900" id="current-date">-- --- ----</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-blue-500 to-emerald-500 flex items-center justify-center shadow">
                    <span class="text-white font-bold text-sm md:text-base">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($kelas)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
            <!-- Total Siswa -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Total Siswa</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $kelas->siswas->count() }}</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full" 
                         style="width: {{ min(($kelas->siswas->count()/40)*100, 100) }}%"></div>
                </div>
            </div>

            <!-- Laki-laki -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Laki-laki</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $laki = $kelas->siswas->where('jenis_kelamin', 'L')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full" 
                         style="width: {{ $kelas->siswas->count() > 0 ? ($laki/$kelas->siswas->count())*100 : 0 }}%"></div>
                </div>
            </div>

            <!-- Perempuan -->
            <div class="bg-white rounded-xl p-4 md:p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-gray-600 text-sm font-medium mb-1">Perempuan</p>
                        <p class="text-2xl md:text-3xl font-bold text-gray-900">{{ $perempuan = $kelas->siswas->where('jenis_kelamin', 'P')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-lg bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-green-500 rounded-full" 
                         style="width: {{ $kelas->siswas->count() > 0 ? ($perempuan/$kelas->siswas->count())*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            <!-- Kelas Info Card -->
            <div class="bg-white rounded-xl p-5 md:p-6 shadow-sm border border-gray-100">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 rounded-xl bg-gradient-to-br from-blue-500 to-emerald-500 flex items-center justify-center shadow">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">{{ $kelas->nama_kelas }}</h2>
                    <p class="text-gray-600 text-sm">Kelas yang Anda ampu</p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 md:p-4 rounded-lg bg-blue-50 border border-blue-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-md bg-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium text-sm">Jumlah Siswa</span>
                        </div>
                        <span class="font-bold text-blue-600">{{ $kelas->siswas->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 md:p-4 rounded-lg bg-emerald-50 border border-emerald-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-md bg-emerald-500 flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium text-sm">Guru Wali</span>
                        </div>
                        <span class="font-bold text-emerald-600 text-sm truncate ml-2">{{ $kelas->guruWali->nama_lengkap ?? 'Belum ada' }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 md:p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-md bg-gradient-to-br from-blue-500 to-emerald-500 flex items-center justify-center">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700 font-medium text-sm">Status Kelas</span>
                        </div>
                        <span class="inline-flex px-2 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>

           
        </div>
        @else
        <!-- State when no class assigned -->
        <div class="bg-white rounded-xl p-6 md:p-8 shadow-sm border border-gray-100 text-center max-w-2xl mx-auto">
            <div class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-4 rounded-xl bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center">
                <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Kelas yang Diampu</h3>
            <p class="text-gray-600 mb-6">Anda belum ditugaskan sebagai wali kelas. Silakan hubungi administrator.</p>
            <a href="{{ route('dashboard.admin') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium hover:shadow transition-shadow">
                Kembali ke Dashboard
            </a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Format tanggal
    function formatDate() {
        const now = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const dayName = days[now.getDay()];
        const date = now.getDate();
        const monthName = months[now.getMonth()];
        const year = now.getFullYear();
        
        document.getElementById('current-date').textContent = `${dayName}, ${date} ${monthName} ${year}`;
    }
    
    // Update tanggal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', formatDate);
</script>
@endpush
</body>
</html>
@endsection