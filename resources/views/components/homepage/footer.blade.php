<footer class="relative bg-gradient-to-br from-blue-900 via-purple-900 to-indigo-900 text-white overflow-hidden">
    <!-- Wave Pattern Background -->
    <div class="absolute top-0 left-0 w-full h-24 overflow-hidden">
        <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" 
                  class="fill-blue-800 opacity-90"></path>
        </svg>
    </div>
    
    <!-- Floating Elements - Matching Hero Section -->
    <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    
    <!-- Additional Floating Elements -->
    <div class="absolute top-10 left-5 w-4 h-4 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute top-20 right-10 w-6 h-6 bg-blue-400 rounded-full opacity-30 animate-bounce"></div>
    <div class="absolute bottom-32 left-10 w-3 h-3 bg-purple-400 rounded-full opacity-25 animate-ping"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-3 gap-10 z-10">
        <!-- Tentang Sekolah -->
        <div class="transform transition-transform duration-300 hover:translate-y-1">
            <div class="bg-gradient-to-br from-blue-800 to-purple-800 rounded-2xl p-6 border border-white/20 shadow-xl">
                <h3 class="text-white font-bold text-xl mb-4 flex items-center">
                    <div class="rounded-full overflow-hidden border-2 border-white/20 mr-3 shadow-lg">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo Sekolah" class="h-10 w-10 object-cover">
                    </div>
                    SMP Utama 
                </h3>
                <p class="text-sm leading-relaxed text-white/80">
                    Sekolah yang berkomitmen membentuk generasi berkarakter melalui program 
                    <strong class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500">7 Kebiasaan Anak Indonesia Hebat</strong>. 
                    Bersama kami wujudkan siswa berprestasi dan berakhlak mulia.
                </p>
                
                <!-- Social Media -->
                <div class="flex space-x-3 mt-4">
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-all duration-300 transform hover:scale-110 border border-white/20">
                        <i data-lucide="facebook" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-pink-500 transition-all duration-300 transform hover:scale-110 border border-white/20">
                        <i data-lucide="instagram" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-blue-400 transition-all duration-300 transform hover:scale-110 border border-white/20">
                        <i data-lucide="twitter" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white/10 rounded-full flex items-center justify-center hover:bg-red-500 transition-all duration-300 transform hover:scale-110 border border-white/20">
                        <i data-lucide="youtube" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigasi Cepat -->
        <div class="transform transition-transform duration-300 hover:translate-y-1">
            <div class="bg-gradient-to-br from-blue-800 to-purple-800 rounded-2xl p-6 border border-white/20 shadow-xl">
                <h3 class="text-white font-bold text-xl mb-4 flex items-center">
                    <i data-lucide="navigation" class="w-5 h-5 mr-2 text-yellow-400"></i>
                    Navigasi Cepat
                </h3>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="hover:text-white transition-all duration-300 flex items-center group py-2 px-3 rounded-lg hover:bg-white/10">
                            <div class="w-8 h-8 bg-blue-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500/50 transition-colors">
                                <i data-lucide="home" class="w-4 h-4 text-blue-300"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform text-white/80">Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="#program" class="hover:text-white transition-all duration-300 flex items-center group py-2 px-3 rounded-lg hover:bg-white/10">
                            <div class="w-8 h-8 bg-green-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-500/50 transition-colors">
                                <i data-lucide="target" class="w-4 h-4 text-green-300"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform text-white/80">Program 7 Kebiasaan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="hover:text-white transition-all duration-300 flex items-center group py-2 px-3 rounded-lg hover:bg-white/10">
                            <div class="w-8 h-8 bg-purple-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-500/50 transition-colors">
                                <i data-lucide="log-in" class="w-4 h-4 text-purple-300"></i>
                            </div>
                            <span class="group-hover:translate-x-1 transition-transform text-white/80">Login</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Kontak -->
        <div class="transform transition-transform duration-300 hover:translate-y-1">
            <div class="bg-gradient-to-br from-blue-800 to-purple-800 rounded-2xl p-6 border border-white/20 shadow-xl">
                <h3 class="text-white font-bold text-xl mb-4 flex items-center">
                    <i data-lucide="phone" class="w-5 h-5 mr-2 text-yellow-400"></i>
                    Hubungi Kami
                </h3>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start group">
                        <div class="w-10 h-10 bg-yellow-500/30 rounded-lg flex items-center justify-center mr-3 mt-1 group-hover:bg-yellow-500/50 transition-colors">
                            <i data-lucide="map-pin" class="w-5 h-5 text-yellow-300"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors pt-1 text-white/80">Jl. Raya Utama No. 45, Depok, Jawa Barat</span>
                    </li>
                    <li class="flex items-center group">
                        <div class="w-10 h-10 bg-blue-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-500/50 transition-colors">
                            <i data-lucide="phone" class="w-5 h-5 text-blue-300"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors text-white/80">(021) 765-4321</span>
                    </li>
                    <li class="flex items-center group">
                        <div class="w-10 h-10 bg-red-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-red-500/50 transition-colors">
                            <i data-lucide="mail" class="w-5 h-5 text-red-300"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors text-white/80">info@smputama.sch.id</span>
                    </li>
                    <li class="flex items-center group">
                        <div class="w-10 h-10 bg-purple-500/30 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-500/50 transition-colors">
                            <i data-lucide="globe" class="w-5 h-5 text-purple-300"></i>
                        </div>
                        <span class="group-hover:text-white transition-colors text-white/80">www.smputama.sch.id</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Copyright - Menjadi satu dengan gradasi utama -->
    <div class="relative py-8 z-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center">
                <p class="text-sm text-white/80 mb-2">
                    © {{ date('Y') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-500 font-bold">SMP Utama</span>. 
                    Semua hak dilindungi.
                </p>
                <p class="text-xs text-white/60">
                    Membentuk Generasi Emas Indonesia 2045
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>