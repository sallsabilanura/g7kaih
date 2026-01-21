@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Simple Welcome Header -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <!-- Left Side: Welcome Message -->
                <div class="mb-6 md:mb-0 md:mr-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                        Selamat Datang, Siswa
                    </h1>
                    <p class="text-gray-600">
                        Kamu login sebagai <span class="font-medium text-blue-600">Siswa SMK Informatika Utama</span>
                    </p>
                </div>
                
                <!-- Right Side: Simple Stats -->
                <div class="flex items-center space-x-6">
                    <!-- Date Card -->
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-1">
                            {{ \Carbon\Carbon::now()->format('d') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::now()->translatedFormat('M Y') }}
                        </div>
                    </div>
                    
                    <!-- Status Indicator -->
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection