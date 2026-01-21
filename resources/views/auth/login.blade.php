<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Monitoring 7 Kebiasaan Generasi Emas</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f1f5f9;
    }
    
    .login-card {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    
    .form-input {
      transition: all 0.3s ease;
    }
    
    .form-input:focus {
      transform: translateY(-1px);
    }
    
    .login-btn {
      transition: all 0.3s ease;
    }
    
    .login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 51, 160, 0.25);
    }

    .right-bg {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      position: relative;
      overflow: hidden;
    }

    .right-bg::before {
      content: '';
      position: absolute;
      inset: 0;
      background: 
        radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.15), transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(15, 23, 42, 0.4), transparent 60%);
      z-index: 1;
    }

    .right-bg img {
      position: relative;
      z-index: 2;
      width: 100%;
      height: auto;
      object-fit: cover;
    }
    
    .text-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      z-index: 10;
      padding: 2rem;
    }
  </style>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              500: '#0033A0',
              600: '#002d8a',
              700: '#002673',
            },
          }
        }
      }
    }
  </script>
</head>
<body class="antialiased">
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-5xl login-card bg-white rounded-2xl">
      <div class="flex flex-col lg:flex-row">
        
        <!-- BAGIAN KIRI - FORM LOGIN -->
        <div class="lg:w-1/2 p-8 lg:p-12">
          <div class="flex items-center space-x-3 mb-8">
            <div class="h-10 w-10 rounded-lg overflow-hidden">
              <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo Sekolah" class="h-10 w-10 object-cover">
            </div>
            <h1 class="text-lg font-semibold text-gray-800">SMP UTAMA</h1>
          </div>

          <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
          <p class="text-gray-600 mb-8">Masukkan informasi di bawah ini untuk melakukan login.</p>

          <!-- Pesan status login -->
          <div id="loginStatus" class="mb-4 p-3 rounded-lg hidden"></div>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-5">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                  </svg>
                </div>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                  class="form-input w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400"
                  placeholder="nama@email.com">
              </div>
              @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-5">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                </div>
                <input type="password" id="password" name="password" required
                  class="form-input w-full border border-gray-300 rounded-lg pl-10 pr-10 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400"
                  placeholder="********">
                <button type="button" id="togglePassword" class="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </div>
              @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <!-- Ingat Saya & Lupa Password -->
            <div class="flex items-center justify-between mb-6 text-sm">
              <label class="flex items-center">
                <input type="checkbox" name="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                <span class="ml-2 text-gray-600">Ingat saya</span>
              </label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                  Lupa password?
                </a>
              @endif
            </div>

            <!-- Tombol -->
            <button type="submit" class="login-btn w-full bg-primary-600 text-white font-semibold py-3 rounded-lg hover:bg-primary-700">
              Masuk Sekarang
            </button>
          </form>
        </div>

        <!-- BAGIAN KANAN - BACKGROUND DENGAN GAMBAR -->
        <div class="lg:w-1/2 right-bg flex items-end justify-center relative rounded-r-2xl">
          <!-- TEXT OVERLAY DI ATAS GAMBAR -->
          <div class="text-overlay text-white">
            <h3 class="text-2xl font-bold mb-2">Monitoring</h3>
            <h2 class="text-4xl font-bold mb-5">7 Kebiasaan<br/>Generasi Emas</h2>
            <p class="text-sm text-gray-200">Platform terintegrasi untuk memantau dan<br/>mengembangkan kebiasaan positif siswa<br/>menuju Indonesia Emas 2045.</p>
          </div>
          
          <!-- GAMBAR TETAP DI POSISI BAWAH -->
          <img src="{{ asset('assets/img/ramaise.png') }}" alt="Siswa SMP Utama Depok">
        </div>
        
      </div>
    </div>
  </div>

  <script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      this.innerHTML = type === 'password' 
        ? `<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
          </svg>`
        : `<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
          </svg>`;
    });

    // Tombol registrasi
    document.getElementById('registerButton').addEventListener('click', function() {
      alert('Fitur registrasi akan segera tersedia!');
    });
  </script>
</body>
</html>