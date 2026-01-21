<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
               <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo Sekolah" class="h-10 w-10 object-contain">
                <span class="font-bold text-lg text-gray-800">
                    SMP Utama 
                </span>
            </div>

            <!-- Menu Navigasi -->
            <div class="hidden md:flex space-x-6 font-medium text-gray-700">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition">Beranda</a>
                <a href="#tentang" class="hover:text-blue-600 transition">Tentang</a>
                <a href="#program" class="hover:text-blue-600 transition">Program</a>
                <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
            </div>

            <!-- Tombol Aksi -->
            <div class="hidden md:flex items-center space-x-4">
                
                  <a href="{{ route('login') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Masuk
                </a>
            </div>

            <!-- Tombol Menu Mobile -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ url('/') }}" class="block text-gray-700 hover:text-blue-600">Beranda</a>
            <a href="#tentang" class="block text-gray-700 hover:text-blue-600">Tentang</a>
            <a href="#program" class="block text-gray-700 hover:text-blue-600">Program</a>
            <a href="#kontak" class="block text-gray-700 hover:text-blue-600">Kontak</a>
            <hr class="my-2">
            <a href="{{ route('login') }}" class="block text-blue-600 font-semibold">Masuk</a>
            <a href="{{ route('register') }}" class="block bg-blue-600 text-white px-4 py-2 rounded-lg text-center font-semibold hover:bg-blue-700">
                Daftar
            </a>
        </div>
    </div>

    <!-- Script untuk toggle mobile menu -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</nav>
