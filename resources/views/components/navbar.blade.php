<nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Left: Menu Toggle & Breadcrumb -->
            <div class="flex items-center space-x-4">
                <button id="sidebarToggle" class="lg:hidden text-gray-600 hover:text-primary-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <div class="hidden md:block">
                    <h2 class="text-xl font-semibold text-gray-800">
                        @if(session()->has('siswa_id'))
                            Dashboard Siswa
                        @elseif(session()->has('orangtua_id'))
                            Dashboard Orangtua
                        @else
                            @yield('page-title', 'Dashboard')
                        @endif
                    </h2>
                </div>
            </div>

            <!-- Right: User Menu -->
            <div class="flex items-center space-x-4">
                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                        <div class="flex items-center space-x-2">
                            @if(auth()->check())
                                <!-- Untuk user yang login via auth (admin/guru) -->
                                <div class="w-8 h-8 rounded-full bg-gradient-primary flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nama_lengkap ?? 'U', 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? auth()->user()->nama_lengkap ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if(auth()->user()->peran === 'admin')
                                            Administrator
                                        @elseif(auth()->user()->peran === 'guru_wali')
                                            Guru Wali
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', auth()->user()->peran)) }}
                                        @endif
                                    </p>
                                </div>
                            @elseif(session()->has('siswa_id'))
                                <!-- Untuk siswa yang login via session -->
                                <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(session('siswa_nama'), 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ session('siswa_nama') }}</p>
                                    <p class="text-xs text-gray-500">Siswa</p>
                                </div>
                            @elseif(session()->has('orangtua_id'))
                                <!-- Untuk orangtua yang login via session -->
                                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(session('orangtua_nama'), 0, 1)) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ session('orangtua_nama') }}</p>
                                    <p class="text-xs text-gray-500">Orangtua</p>
                                </div>
                            @else
                                <!-- Fallback jika tidak ada user -->
                                <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-white font-semibold">
                                    U
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium text-gray-700">User</p>
                                    <p class="text-xs text-gray-500">Guest</p>
                                </div>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-20">
                        
                        @if(auth()->check())
                          
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        @elseif(session()->has('siswa_id'))
                           
                            <hr class="my-1">
                            <form method="POST" action="{{ route('siswa.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        @elseif(session()->has('orangtua_id'))
                            
                            <hr class="my-1">
                            <form method="POST" action="{{ route('orangtua.logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                </button>
                            </form>
                        @else
                            <!-- Menu untuk guest -->
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush