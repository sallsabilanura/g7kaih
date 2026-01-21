@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hai, {{ $user->nama_lengkap ?? $user->name }}</h1>
                        <p class="text-sm text-gray-600 mt-1">Selamat datang di Dashboard Admin</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-sm text-gray-600">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-lg font-semibold text-gray-900">{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            {{-- Total Users Card --}}
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Total Users</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                <div class="mt-2 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-green-600">Aktif</span>
                        <span class="font-medium">{{ $userAktif }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-red-600">Nonaktif</span>
                        <span class="font-medium">{{ $userNonaktif }}</span>
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="mt-3 text-blue-600 text-sm font-medium hover:text-blue-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Total Siswa Card --}}
            <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-sm p-5 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.136a5.5 5.5 0 01-9.86 2.182M5.136 7.664l6.182 3.591m0 0l6.182-3.591m-6.182 3.591V3m6.182 9.318l-6.182 3.591m0-9.318l6.182 3.591"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Total Siswa</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                <div class="mt-2 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-blue-600">Laki-laki</span>
                        <span class="font-medium">{{ $siswaLaki }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-pink-600">Perempuan</span>
                        <span class="font-medium">{{ $siswaPerempuan }}</span>
                    </div>
                </div>
                <a href="{{ route('siswas.index') }}" class="mt-3 text-green-600 text-sm font-medium hover:text-green-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Total Kelas Card --}}
            <div class="bg-gradient-to-br from-white to-cyan-50 rounded-xl shadow-sm p-5 border-l-4 border-cyan-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-cyan-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Total Kelas</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalKelas }}</p>
                <div class="mt-2 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-green-600">Dengan Wali</span>
                        <span class="font-medium">{{ $kelasDenganWali }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-yellow-600">Tanpa Wali</span>
                        <span class="font-medium">{{ $kelasTanpaWali }}</span>
                    </div>
                </div>
                <a href="{{ route('kelas.index') }}" class="mt-3 text-cyan-600 text-sm font-medium hover:text-cyan-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            {{-- Total Guru Wali Card --}}
            <div class="bg-gradient-to-br from-white to-yellow-50 rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-600 text-sm mb-1">Total Guru Wali</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $totalGuruWali }}</p>
                <div class="mt-2 text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-blue-600">Laki-laki</span>
                        <span class="font-medium">{{ $guruLaki }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-pink-600">Perempuan</span>
                        <span class="font-medium">{{ $guruPerempuan }}</span>
                    </div>
                </div>
                <a href="{{ route('users.index', ['role' => 'guru_wali']) }}" class="mt-3 text-yellow-600 text-sm font-medium hover:text-yellow-700 flex items-center gap-1">
                    Kelola <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Distribusi Siswa per Kelas -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Distribusi Siswa per Kelas (Top 5)</h2>
                </div>
                <div class="p-6">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="siswaPerKelasChart"></canvas>
                    </div>
                    <div class="mt-4 flex flex-wrap justify-center gap-4 text-xs text-gray-600">
                        <span><i class="fas fa-circle text-blue-500"></i> {{ $totalKelas }} Total Kelas</span>
                        <span><i class="fas fa-circle text-green-500"></i> {{ $totalSiswa }} Total Siswa</span>
                        <span><i class="fas fa-circle text-cyan-500"></i> Rata-rata {{ $rataSiswaPerKelas }} Siswa/Kelas</span>
                    </div>
                </div>
            </div>

            <!-- Perbandingan Jenis Kelamin -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Perbandingan Jenis Kelamin</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-center text-sm font-semibold text-gray-700 mb-2">Siswa</h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="genderSiswaChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-center text-sm font-semibold text-gray-700 mb-2">Guru Wali</h3>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="genderGuruChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Tables Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Kelas dengan Siswa -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Kelas dengan Jumlah Siswa (Top 5)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kelas</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guru Wali</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kelasTop5 as $index => $kelas)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-semibold">{{ $kelas['nama_kelas'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                        {{ $kelas['jumlah_siswa'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $kelas['guru_wali'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $kelas['percentage'] }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600">{{ $kelas['percentage'] }}%</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Statistik Detail -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Statistik Detail</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Admin -->
                        <div class="bg-white border-l-4 border-red-500 rounded-lg shadow p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-red-600 uppercase mb-1">Admin</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $totalAdmin }}</h4>
                                    <p class="text-xs text-gray-500">Pengelola Sistem</p>
                                </div>
                                <i class="fas fa-user-shield text-3xl text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Orangtua -->
                        <div class="bg-white border-l-4 border-cyan-500 rounded-lg shadow p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-cyan-600 uppercase mb-1">Orangtua</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $totalOrangtua }}</h4>
                                    <div class="text-xs space-y-1">
                                        <p class="text-green-600"><i class="fas fa-check-circle"></i> {{ $orangtuaDenganSiswa }} dengan Siswa</p>
                                        <p class="text-yellow-600"><i class="fas fa-exclamation-circle"></i> {{ $orangtuaTanpaSiswa }} tanpa Siswa</p>
                                    </div>
                                </div>
                                <i class="fas fa-user-friends text-3xl text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Guru Tanpa Kelas -->
                        <div class="bg-white border-l-4 border-yellow-500 rounded-lg shadow p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-yellow-600 uppercase mb-1">Guru Tanpa Kelas</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $guruTanpaKelas }}</h4>
                                    <p class="text-xs text-gray-500">Belum ditugaskan</p>
                                </div>
                                <i class="fas fa-user-times text-3xl text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Kelas Tanpa Wali -->
                        <div class="bg-white border-l-4 border-gray-500 rounded-lg shadow p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Kelas Tanpa Wali</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ $kelasTanpaWali }}</h4>
                                    <p class="text-xs text-gray-500">Belum ada wali</p>
                                </div>
                                <i class="fas fa-chalkboard-teacher text-3xl text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Access --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Akses Cepat</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('users.index') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Kelola User</span>
                    </a>
                    <a href="{{ route('siswas.index') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 3.136a5.5 5.5 0 01-9.86 2.182M5.136 7.664l6.182 3.591m0 0l6.182-3.591m-6.182 3.591V3m6.182 9.318l-6.182 3.591m0-9.318l6.182 3.591"/>
                        </svg>
                        <span>Kelola Siswa</span>
                    </a>
                    <a href="{{ route('kelas.index') }}" class="px-6 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Kelola Kelas</span>
                    </a>
                    <button onclick="location.reload()" class="px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Refresh Dashboard</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Data untuk charts
    const kelasLabels = @json($kelasChartLabels);
    const kelasCounts = @json($kelasChartData);
    const siswaGenderData = [{{ $siswaLaki }}, {{ $siswaPerempuan }}];
    const guruGenderData = [{{ $guruLaki }}, {{ $guruPerempuan }}];

    // Chart 1: Distribusi Siswa per Kelas
    const ctx1 = document.getElementById('siswaPerKelasChart').getContext('2d');
    if (ctx1) {
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: kelasLabels,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: kelasCounts,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Chart 2: Gender Siswa (Pie)
    const ctx2 = document.getElementById('genderSiswaChart').getContext('2d');
    if (ctx2) {
        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: siswaGenderData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Chart 3: Gender Guru (Doughnut)
    const ctx3 = document.getElementById('genderGuruChart').getContext('2d');
    if (ctx3) {
        new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: guruGenderData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .chart-container {
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
</style>
@endsection