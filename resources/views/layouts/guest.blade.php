<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMP Utama Depok') }} - Platform Generasi Emas 2026</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-blue: #1e40af;
            --primary-purple: #7c3aed;
            --accent-yellow: #f59e0b;
            --accent-orange: #ea580c;
            --primary-gradient: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: #ffffff;
            overflow-x: hidden;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }
        
        /* Hero Section - Warna Lebih Menyatu */
        .hero-section {
            background: var(--primary-gradient);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .hero-bg-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.4) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(139, 92, 246, 0.4) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.2) 0%, transparent 60%);
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.25;
        }
        
        .shape-1 {
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            top: 15%;
            left: 10%;
            animation: float 15s ease-in-out infinite;
        }
        
        .shape-2 {
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #8b5cf6 0%, #c4b5fd 100%);
            bottom: 15%;
            right: 10%;
            animation: float 20s ease-in-out infinite reverse;
        }
        
        .shape-3 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #3b82f6 0%, #93c5fd 100%);
            top: 60%;
            left: 70%;
            animation: float 12s ease-in-out infinite 2s;
        }
        
        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
            }
            33% { 
                transform: translateY(-30px) rotate(120deg); 
            }
            66% { 
                transform: translateY(20px) rotate(240deg); 
            }
        }
        
        /* CTA Buttons */
        .cta-button-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .cta-button-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.7s ease;
        }
        
        .cta-button-primary:hover::before {
            left: 100%;
        }
        
        .cta-button-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.5);
        }
        
        .cta-button-secondary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .cta-button-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }
        
        .cta-button-secondary:hover::before {
            left: 100%;
        }
        
        .cta-button-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.15);
        }
        
        /* Student Image Section - Ditingkatkan */
        .student-image-wrapper {
            position: relative;
            perspective: 1000px;
        }
        
        .student-image-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transform-style: preserve-3d;
            transform: rotateY(-5deg) rotateX(5deg);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            animation: float-image 6s ease-in-out infinite;
        }
        
        @keyframes float-image {
            0%, 100% { 
                transform: rotateY(-5deg) rotateX(5deg) translateY(0); 
            }
            50% { 
                transform: rotateY(-5deg) rotateX(5deg) translateY(-15px); 
            }
        }
        
        .student-image-container:hover {
            transform: rotateY(0deg) rotateX(0deg) translateY(-10px);
            box-shadow: 
                0 30px 60px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        
        .student-image {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            transform: translateZ(20px);
        }
        
        .student-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            mix-blend-mode: overlay;
            z-index: 1;
            pointer-events: none;
        }
        
        .student-image img {
            width: 100%;
            height: auto;
            display: block;
            filter: brightness(1.05) contrast(1.1);
            transition: transform 0.6s ease;
        }
        
        .student-image-container:hover .student-image img {
            transform: scale(1.03);
        }
        
        .image-glow {
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 40px;
            background: radial-gradient(ellipse at center, rgba(139, 92, 246, 0.3) 0%, transparent 70%);
            filter: blur(15px);
            z-index: -1;
        }
        
        /* Sections */
        .section-title {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Habit Cards - Diperbarui sesuai icon pemerintah */
        .habit-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .habit-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .habit-icon-container {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            background: linear-gradient(135deg, currentColor 0%, var(--icon-color-dark) 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .habit-icon {
            font-size: 32px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            z-index: 2;
        }
        
        .habit-icon-container::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(45deg) translate(-50%, -50%);
            top: 0;
            left: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .habit-card:hover .habit-icon-container::before {
            opacity: 1;
        }
        
        .habit-number {
            width: 32px;
            height: 32px;
            background: white;
            border: 2px solid currentColor;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            position: absolute;
            top: -8px;
            right: -8px;
            z-index: 3;
            color: var(--icon-color);
        }
        
        /* Feature Section */
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }
        
        .step-list {
            list-style: none;
            padding: 0;
        }
        
        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .step-item:hover {
            transform: translateX(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        /* Benefit Cards */
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 3px solid var(--accent-yellow);
            transition: all 0.3s ease;
        }
        
        .benefit-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }
        
        .benefit-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .benefit-icon i {
            color: white;
            width: 18px;
            height: 18px;
        }
        
        /* Impact Section */
        .impact-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }
        
        .impact-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }
        
        .impact-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .impact-icon i {
            width: 28px;
            height: 28px;
        }

        /* Testimonial Section */
        .testimonial-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 24px;
        }

        .quote-icon {
            color: #f59e0b;
            opacity: 0.2;
            font-size: 48px;
            line-height: 1;
        }

        /* FAQ Section */
        .faq-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .faq-question {
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #f8fafc;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 0 1.5rem 1.25rem 1.5rem;
            max-height: 500px;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }
        
        /* Scroll to Top Button */
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        
        .scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .scroll-to-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
        }
        
        .scroll-to-top i {
            color: white;
            font-size: 20px;
        }
        
        /* Animations */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }
        
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem !important;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .scroll-to-top {
                width: 45px;
                height: 45px;
                bottom: 20px;
                right: 20px;
            }

            .impact-card {
                padding: 1.5rem;
            }
            
            .student-image-container {
                transform: rotateY(0) rotateX(0);
                animation: float-image-mobile 6s ease-in-out infinite;
            }
            
            @keyframes float-image-mobile {
                0%, 100% { 
                    transform: translateY(0); 
                }
                50% { 
                    transform: translateY(-10px); 
                }
            }
        }
        
        /* Custom Icons for Government Habits */
        .icon-sunrise::before { content: "🌅"; }
        .icon-pray::before { content: "🙏"; }
        .icon-exercise::before { content: "🏃"; }
        .icon-food::before { content: "🍎"; }
        .icon-book::before { content: "📚"; }
        .icon-community::before { content: "👥"; }
        .icon-sleep::before { content: "😴"; }
        
        .custom-icon {
            font-size: 32px;
            line-height: 1;
            display: inline-block;
            transform: scale(1.2);
        }
    </style>
</head>
<body class="bg-white font-sans antialiased">

    <!-- Header / Navbar -->
    @include('components.homepage.navbar')

    <!-- Hero Section - Warna Menyatu -->
    <section class="min-h-screen flex items-center justify-center hero-section">
        <!-- Background Elements -->
        <div class="hero-bg-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Content -->
            <div class="text-white space-y-8 fade-in-up">
                <div class="space-y-6">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight hero-title">
                        Wujudkan Karakter Unggul 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-yellow-400 to-yellow-500 animate-pulse-slow">
                            Generasi Emas 
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-white/90 leading-relaxed max-w-2xl">
                        Platform monitoring <strong class="text-yellow-300">7 Kebiasaan Anak Indonesia Hebat</strong> — 
                        Membangun kebiasaan positif setiap hari untuk membentuk karakter unggul siswa SMP Utama.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="siswa/login" 
                       class="cta-button-primary inline-flex items-center justify-center gap-3 text-gray-900 px-7 py-3.5 rounded-xl font-semibold text-base relative overflow-hidden group">
                        <span class="relative z-10">Masuk Sekarang</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 relative z-10"></i>
                    </a>
                    <a href="#program" 
                       class="cta-button-secondary inline-flex items-center justify-center gap-3 text-white px-7 py-3.5 rounded-xl font-semibold text-base relative overflow-hidden group">
                        <span class="relative z-10">Pelajari Program</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 relative z-10"></i>
                    </a>
                </div>
            </div>

            <!-- Right Content - Student Image -->
            <div class="relative fade-in-up" style="animation-delay: 0.2s;">
                <div class="student-image-wrapper">
                    <div class="student-image-container">
                        <div class="student-image">
                            <img src="{{ asset('assets/img/siswasmpku.png') }}" alt="Siswa SMP Utama Depok" 
                                 class="w-full h-auto object-cover">
                        </div>
                    </div>
                    <div class="image-glow"></div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i data-lucide="chevrons-down" class="w-6 h-6 text-white/70"></i>
        </div>
    </section>

   <!-- 7 Kebiasaan Section dengan Gambar -->
    <section id="program" class="py-20 px-6 bg-gradient-to-br from-white via-blue-50 to-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    7 Kebiasaan Anak 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                        Indonesia Hebat
                    </span>
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kebiasaan positif yang membentuk karakter unggul untuk mencapai Indonesia Emas 
                </p>
            </div>

            <!-- Habits Grid dengan Gambar -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Habit 1: Bangun Pagi -->
                <div class="habit-card" style="--icon-color: #ea580c; --icon-color-dark: #c2410c;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/BangunPagi.png') }}" alt="Bangun Pagi" class="habit-image">
                        <div class="habit-number">1</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Bangun Pagi</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Melatih kedisiplinan dan meningkatkan kemampuan mengelola waktu untuk meraih kesuksesan.
                        </p>
                    </div>
                </div>

                <!-- Habit 2: Beribadah -->
                <div class="habit-card" style="--icon-color: #0d9488; --icon-color-dark: #0f766e;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/Beribadah.png') }}" alt="Beribadah" class="habit-image">
                        <div class="habit-number">2</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Beribadah</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Fondasi karakter positif yang mendekatkan diri kepada Tuhan dan meningkatkan nilai moral.
                        </p>
                    </div>
                </div>

                <!-- Habit 3: Berolahraga -->
                <div class="habit-card" style="--icon-color: #2563eb; --icon-color-dark: #1d4ed8;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/Berolahraga.png') }}" alt="Berolahraga" class="habit-image">
                        <div class="habit-number">3</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Berolahraga</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Menjaga kesehatan fisik dan mental, meningkatkan kebugaran dan nilai sportivitas.
                        </p>
                    </div>
                </div>

                <!-- Habit 4: Makan Sehat -->
                <div class="habit-card" style="--icon-color: #dc2626; --icon-color-dark: #b91c1c;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/MakanSehat.png') }}" alt="Makan Sehat" class="habit-image">
                        <div class="habit-number">4</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Makan Sehat & Bergizi</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Memenuhi nutrisi tubuh untuk mendukung kehidupan sehat dan memaksimalkan potensi diri.
                        </p>
                    </div>
                </div>

                <!-- Habit 5: Gemar Belajar -->
                <div class="habit-card" style="--icon-color: #7c3aed; --icon-color-dark: #6d28d9;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/Membaca.png') }}" alt="Gemar Belajar" class="habit-image">
                        <div class="habit-number">5</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Gemar Belajar</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Mengembangkan diri, menumbuhkan kreativitas dan menemukan pengetahuan baru.
                        </p>
                    </div>
                </div>

                <!-- Habit 6: Bermasyarakat -->
                <div class="habit-card" style="--icon-color: #f59e0b; --icon-color-dark: #d97706;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/Bermasyarakat.png') }}" alt="Bermasyarakat" class="habit-image">
                        <div class="habit-number">6</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Bermasyarakat</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Menumbuhkan nilai gotong royong, kerja sama, dan tanggung jawab terhadap lingkungan.
                        </p>
                    </div>
                </div>

                <!-- Habit 7: Tidur Cepat -->
                <div class="habit-card md:col-span-2 lg:col-span-1" style="--icon-color: #4f46e5; --icon-color-dark: #4338ca;">
                    <div class="habit-image-wrapper">
                        <img src="{{ asset('assets/img/Istirahat.png') }}" alt="Tidur Cepat" class="habit-image">
                        <div class="habit-number">7</div>
                    </div>
                    <div class="flex flex-col items-center gap-2 mb-3">
                        <h3 class="text-lg font-bold text-gray-800">Tidur Cepat</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Tidur 9-11 jam untuk usia 6-13 tahun agar organ tubuh pulih dan mental terjaga optimal.
                        </p>
                    </div>
                </div>
            </div>
    </section>

    <!-- Cara Kerja Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 section-title">
                    Cara Menggunakan Platform
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Sistem sederhana untuk membangun kebiasaan positif setiap hari
                </p>
            </div>

            <div class="feature-grid">
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-8 flex items-center justify-center">
                    <i data-lucide="clipboard-check" class="w-32 h-32 text-blue-500"></i>
                </div>
                <div>
                    <ul class="step-list">
                        <li class="step-item">
                            <div class="step-number">1</div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 mb-1">Login ke Akun</h3>
                                <p class="text-sm text-gray-600">Masuk dengan username dan password dari sekolah</p>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">2</div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 mb-1">Isi Checklist Harian</h3>
                                <p class="text-sm text-gray-600">Centang kebiasaan yang sudah dilakukan hari ini</p>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">3</div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 mb-1">Pantau Perkembangan</h3>
                                <p class="text-sm text-gray-600">Lihat statistik dan konsistensi kebiasaan Anda</p>
                            </div>
                        </li>
                        <li class="step-item">
                            <div class="step-number">4</div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800 mb-1">Evaluasi Bersama</h3>
                                <p class="text-sm text-gray-600">Orang tua dan guru memantau perkembangan karakter</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Manfaat Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 section-title">
                    Manfaat Program
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Dampak positif dari konsistensi menjalankan 7 kebiasaan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Untuk Siswa</h3>
                    <div class="space-y-3">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="shield-check"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Karakter Kuat</h4>
                                <p class="text-gray-600 text-xs">Membentuk kepribadian disiplin dan bertanggung jawab</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="trending-up"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Prestasi Meningkat</h4>
                                <p class="text-gray-600 text-xs">Kebiasaan baik mendukung pencapaian akademik</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="heart"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Kesehatan Optimal</h4>
                                <p class="text-gray-600 text-xs">Tubuh dan mental terjaga dengan pola hidup sehat</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="smile"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Mental Positif</h4>
                                <p class="text-gray-600 text-xs">Rutinitas terstruktur membentuk mindset optimis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Untuk Orang Tua</h3>
                    <div class="space-y-3">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="eye"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Monitoring Real-time</h4>
                                <p class="text-gray-600 text-xs">Pantau perkembangan anak secara langsung dari rumah</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="users"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Kolaborasi Sekolah</h4>
                                <p class="text-gray-600 text-xs">Sinergi orang tua dan guru dalam pembentukan karakter</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="file-text"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Laporan Berkala</h4>
                                <p class="text-gray-600 text-xs">Terima laporan perkembangan mingguan dan bulanan</p>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i data-lucide="message-circle"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm">Komunikasi Efektif</h4>
                                <p class="text-gray-600 text-xs">Dialog produktif tentang perkembangan anak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 section-title">
                    Dampak Pembentukan Karakter
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    8 Karakter utama bangsa yang terbentuk melalui 7 kebiasaan
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-blue-400 to-blue-600">
                        <i data-lucide="heart" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Religius</h3>
                    <p class="text-xs text-gray-600 text-center">Taat beribadah dan bermoral</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-green-400 to-green-600">
                        <i data-lucide="sparkles" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Bermoral</h3>
                    <p class="text-xs text-gray-600 text-center">Berakhlak mulia dan etis</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-red-400 to-red-600">
                        <i data-lucide="heart-pulse" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Sehat</h3>
                    <p class="text-xs text-gray-600 text-center">Fisik dan mental prima</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-purple-400 to-purple-600">
                        <i data-lucide="brain" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Cerdas</h3>
                    <p class="text-xs text-gray-600 text-center">Kreatif dan inovatif</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-yellow-400 to-yellow-600">
                        <i data-lucide="zap" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Kerja Keras</h3>
                    <p class="text-xs text-gray-600 text-center">Pantang menyerah</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-indigo-400 to-indigo-600">
                        <i data-lucide="clock" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Disiplin</h3>
                    <p class="text-xs text-gray-600 text-center">Tertib dan teratur</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-orange-400 to-orange-600">
                        <i data-lucide="user-check" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Mandiri</h3>
                    <p class="text-xs text-gray-600 text-center">Bertanggung jawab</p>
                </div>

                <div class="impact-card">
                    <div class="impact-icon bg-gradient-to-br from-teal-400 to-teal-600">
                        <i data-lucide="hand-heart" class="text-white"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-center mb-1">Bermanfaat</h3>
                    <p class="text-xs text-gray-600 text-center">Berguna bagi sesama</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 section-title">
                    Pengalaman Mereka
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Testimoni siswa dan orang tua pengguna platform
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="testimonial-card">
                    <div class="quote-icon text-center mb-4">"</div>
                    <p class="text-gray-600 text-sm mb-6 italic leading-relaxed">
                        Platform ini membantu saya lebih disiplin. Sekarang saya rutin bangun pagi dan berolahraga. Nilai rapor juga meningkat!
                    </p>
                    <div class="testimonial-avatar bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                        A
                    </div>
                    <h4 class="font-bold text-gray-800 text-center mt-3">Aditya</h4>
                    <p class="text-xs text-gray-500 text-center">Siswa Kelas 8B</p>
                </div>

                <div class="testimonial-card">
                    <div class="quote-icon text-center mb-4">"</div>
                    <p class="text-gray-600 text-sm mb-6 italic leading-relaxed">
                        Sebagai orang tua, saya merasa terbantu bisa memantau kebiasaan anak setiap hari. Komunikasi dengan guru juga lebih mudah.
                    </p>
                    <div class="testimonial-avatar bg-gradient-to-br from-purple-400 to-purple-600 text-white">
                        S
                    </div>
                    <h4 class="font-bold text-gray-800 text-center mt-3">Ibu Siti</h4>
                    <p class="text-xs text-gray-500 text-center">Orang Tua Siswa</p>
                </div>

                <div class="testimonial-card">
                    <div class="quote-icon text-center mb-4">"</div>
                    <p class="text-gray-600 text-sm mb-6 italic leading-relaxed">
                        Setelah 3 bulan mengikuti program ini, anak saya jadi lebih mandiri dan bertanggung jawab. Perubahan yang luar biasa!
                    </p>
                    <div class="testimonial-avatar bg-gradient-to-br from-green-400 to-green-600 text-white">
                        B
                    </div>
                    <h4 class="font-bold text-gray-800 text-center mt-3">Bapak Budi</h4>
                    <p class="text-xs text-gray-500 text-center">Orang Tua Siswa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 section-title">
                    Pertanyaan Umum
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Jawaban atas pertanyaan yang sering diajukan
                </p>
            </div>

            <div class="space-y-4">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana cara siswa memulai menggunakan platform?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Siswa akan menerima username dan password dari sekolah. Cukup login ke platform dan mulai mengisi checklist kebiasaan harian. Panduan lengkap tersedia di dashboard.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Apakah orang tua bisa memantau perkembangan anak?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Ya, orang tua memiliki akses khusus untuk melihat laporan harian, mingguan, dan bulanan. Orang tua juga bisa memberikan paraf digital pada jurnal kebiasaan anak.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Berapa lama waktu yang dibutuhkan untuk isi checklist?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Sangat cepat! Hanya butuh 1-2 menit untuk mencentang 7 kebiasaan yang sudah dilakukan. Platform dirancang sederhana dan user-friendly.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Apa yang terjadi jika siswa lupa mengisi checklist?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Tidak ada sanksi. Program ini fokus pada pembentukan kebiasaan positif, bukan punishment. Guru dan orang tua akan membantu mengingatkan dan memotivasi siswa.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Apakah ada biaya untuk menggunakan platform ini?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Platform ini gratis untuk seluruh siswa SMP Utama Depok. Ini merupakan program sekolah untuk mendukung pembentukan karakter siswa menuju Indonesia Emas 2045.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana cara guru memantau perkembangan siswa?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p class="text-gray-600 text-sm">
                            Guru memiliki dashboard khusus untuk melihat statistik seluruh siswa di kelasnya. Data ditampilkan dalam bentuk grafik dan tabel yang mudah dipahami untuk evaluasi berkala.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-blue-600 to-purple-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-72 h-72 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl"></div>
            <div class="absolute bottom-0 right-0 w-72 h-72 bg-orange-400 rounded-full mix-blend-multiply filter blur-xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Mulai Perjalanan Menuju Karakter Unggul
            </h2>
            <p class="text-lg text-white/90 mb-8 max-w-2xl mx-auto">
                Bergabunglah dengan ratusan siswa SMP Utama Depok yang telah membangun kebiasaan positif untuk masa depan gemilang.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="siswa/login" 
                   class="inline-flex items-center justify-center gap-3 bg-white text-blue-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-50 transition-all hover:transform hover:-translate-y-1">
                    <span>Masuk ke Platform</span>
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="#program" 
                   class="inline-flex items-center justify-center gap-3 bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/10 transition-all">
                    <span>Lihat Program</span>
                    <i data-lucide="info" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Scroll to Top Button -->
    <div class="scroll-to-top" id="scrollToTop">
        <i data-lucide="arrow-up"></i>
    </div>

    <!-- Footer -->
    @include('components.homepage.footer')

    <!-- JavaScript -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
        
        // Scroll to Top Functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });
        
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Scroll Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const fadeInObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.fade-in-up').forEach(el => {
            fadeInObserver.observe(el);
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // FAQ Toggle Function
        function toggleFaq(element) {
            const faqItem = element.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
            
            // Reinitialize icons
            lucide.createIcons();
        }
    </script>

</body>
</html>