<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code - {{ $siswa->nama_lengkap }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        .qr-container {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .qr-container:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .qr-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 51, 160, 0.9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            opacity: 0;
            border-radius: 0.5rem;
            transition: opacity 0.3s ease;
        }

        .qr-container:hover .qr-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="glass-card rounded-2xl shadow-xl p-8 max-w-lg w-full text-center fade-in">
        <!-- Header dengan avatar -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-r from-primary-500 to-accent-green flex items-center justify-center text-white font-bold text-2xl mb-4">
                {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-accent-green bg-clip-text text-transparent mb-2">
                    {{ $siswa->nama_lengkap }}
                </h1>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                        <span>NIS: <strong>{{ $siswa->nis }}</strong></span>
                    </div>
                    <span class="hidden sm:block text-gray-300">•</span>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span>Kelas: <strong>{{ $siswa->kelas->nama_kelas ?? 'Belum Ada' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- QR Code dengan overlay clickable -->
        <div class="relative mb-8">
            <div class="qr-container" onclick="downloadQRCode()">
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 p-6 rounded-xl border-2 border-dashed border-gray-200 inline-block">
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" 
                         alt="QR Code {{ $siswa->nama_lengkap }}" 
                         class="w-64 h-64 mx-auto">
                </div>
                <div class="qr-overlay">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <p class="font-semibold text-lg">Klik untuk Download</p>
                    <p class="text-sm opacity-90 mt-1">{{ $fileName }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-3">Klik QR Code di atas untuk mendownload</p>
        </div>
        
        <!-- Informasi QR Code -->
        <div class="bg-gradient-to-r from-blue-50 to-primary-50 rounded-xl p-4 mb-8">
            <div class="flex items-center justify-center mb-2">
                <svg class="w-5 h-5 text-primary-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-semibold text-gray-900">Informasi QR Code</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full bg-accent-green mr-2"></div>
                    <span class="text-gray-600">Kode: <span class="font-mono font-bold">{{ $siswa->nis }}</span></span>
                </div>
                <div class="flex items-center">
                    <div class="w-2 h-2 rounded-full bg-primary-500 mr-2"></div>
                    <span class="text-gray-600">Format: PNG 300x300px</span>
                </div>
            </div>
        </div>
        
        <!-- Tombol Aksi -->
        <div class="space-y-4">
            <a href="{{ route('siswas.barcode.download', $siswa) }}" 
               id="downloadLink"
               class="block bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center group">
                <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download QR Code
                <span class="ml-2 text-xs bg-white/20 px-2 py-1 rounded">
                    {{ $fileName }}
                </span>
            </a>
            
            <a href="{{ route('siswas.index') }}" 
               class="block bg-gray-100 text-gray-700 font-semibold py-3.5 px-6 rounded-xl shadow-sm hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 text-center group hover:bg-gray-200">
                <span class="relative">
                    Kembali ke Daftar Siswa
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-gray-400 group-hover:w-full transition-all duration-300"></span>
                </span>
            </a>
        </div>

        <!-- Nama file yang akan di-download -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                File akan di-download sebagai: <code class="bg-gray-100 px-2 py-1 rounded">{{ $fileName }}</code>
            </p>
        </div>
    </div>

    <script>
        // Fungsi untuk download QR Code saat diklik
        function downloadQRCode() {
            document.getElementById('downloadLink').click();
        }

        // Animasi untuk elements
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                element.style.animationDelay = `${0.1 * index}s`;
            });

            // Auto download setelah 2 detik jika diinginkan
            // setTimeout(downloadQRCode, 2000);
        });

        // Konfirmasi jika ingin auto-download
        window.onload = function() {
            if (confirm('Apakah Anda ingin mendownload QR Code secara otomatis?')) {
                setTimeout(downloadQRCode, 1000);
            }
        };
    </script>
</body>
</html>