<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SMP UTAMA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #0ea5e9;
            --success: #10b981;
            --error: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(79, 70, 229, 0.12), 0 8px 20px rgba(79, 70, 229, 0.06);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Header dengan gradient */
        .header-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
            position: relative;
        }

        .header-gradient::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 -10px 20px rgba(79, 70, 229, 0.1);
        }

        .logo-container {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            background: white;
            border-radius: 1rem;
            padding: 0.75rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 0.75rem;
        }

        .school-info {
            margin-top: 1.25rem;
            position: relative;
            z-index: 2;
        }

        .school-name {
            color: white;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .school-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            margin-top: 0.25rem;
            font-weight: 400;
        }

        /* Konten utama */
        .main-content {
            padding: 2rem 1.5rem 1.5rem;
        }

        /* Toggle login type - Horizontal cards */
        .type-selector {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .type-card {
            flex: 1;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 0.875rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            text-align: center;
        }

        .type-card.active {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.15);
            transform: translateY(-2px);
        }

        .type-icon {
            width: 44px;
            height: 44px;
            margin: 0 auto 0.75rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .type-card:nth-child(1) .type-icon {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .type-card:nth-child(2) .type-icon {
            background: linear-gradient(135deg, var(--secondary) 0%, #0284c7 100%);
            color: white;
        }

        .type-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            font-size: 0.95rem;
        }

        .type-desc {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Method tabs */
        .method-tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 0.875rem;
            padding: 0.25rem;
            margin-bottom: 1.5rem;
        }

        .method-tab {
            flex: 1;
            padding: 0.75rem;
            border-radius: 0.625rem;
            border: none;
            background: transparent;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .method-tab.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.15);
        }

        /* Form inputs */
        .input-group {
            margin-bottom: 1.25rem;
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.125rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.25s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .input-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            margin-top: 0.5rem;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Scanner section */
        .scanner-section {
            display: none;
        }

        .scanner-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .scanner-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
            70% { box-shadow: 0 0 0 12px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }

        .scanner-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.75rem;
        }

        .scanner-desc {
            color: #64748b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .scanner-container {
            border: 2px dashed #cbd5e1;
            border-radius: 0.75rem;
            overflow: hidden;
            background: #f8fafc;
            height: 220px;
            position: relative;
            margin-bottom: 1rem;
        }

        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: #475569;
            white-space: nowrap;
        }

        /* Status messages */
        .status-message {
            padding: 0.875rem;
            border-radius: 0.625rem;
            margin-bottom: 1rem;
            display: none;
            font-size: 0.875rem;
            font-weight: 500;
            align-items: center;
            gap: 0.5rem;
        }

        .status-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-error {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .status-loading {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Manual fallback */
        .manual-fallback {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .manual-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .manual-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .manual-input {
            flex: 1;
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .manual-submit {
            padding: 0.75rem 1.25rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .manual-submit:hover {
            background: var(--primary-dark);
        }

        /* Footer */
        .login-footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-item {
            font-size: 0.75rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .help-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .help-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .main-content {
                padding: 1.5rem 1rem 1rem;
            }
            
            .header-gradient {
                padding: 1.5rem 1.5rem 1rem;
            }
            
            .logo-container {
                width: 80px;
                height: 80px;
            }
            
            .scanner-container {
                height: 200px;
            }
        }

        /* Reader styles */
        #reader {
            width: 100% !important;
            height: 100% !important;
        }

        #reader__scan_region {
            width: 100% !important;
            height: 100% !important;
        }

        #reader__dashboard_section {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <!-- Header dengan Gradient -->
            <div class="header-gradient">
                <div class="logo-container">
                    <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo SMP UTAMA" class="logo">
                </div>
                <div class="school-info">
                    <h1 class="school-name">SMP UTAMA</h1>
                    <p class="school-subtitle">Portal Login Terintegrasi</p>
                </div>
            </div>

            <!-- Konten Utama -->
            <div class="main-content">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="status-message status-error" id="errorMessage">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Pilihan Jenis Login -->
                <div class="type-selector">
                    <div class="type-card active" onclick="switchLoginType('siswa')" id="siswaTypeCard">
                        <div class="type-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="type-name">Siswa</div>
                        <div class="type-desc">Login dengan NIS</div>
                    </div>
                    
                    <div class="type-card" onclick="switchLoginType('orangtua')" id="orangtuaTypeCard">
                        <div class="type-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="type-name">Orangtua</div>
                        <div class="type-desc">Login dengan NIS Anak</div>
                    </div>
                </div>

                <!-- Pilihan Metode Login -->
                <div class="method-tabs">
                    <button class="method-tab active" onclick="switchTab('manual')" id="manualTab">
                        <i class="fas fa-keyboard"></i>
                        <span>Manual</span>
                    </button>
                    <button class="method-tab" onclick="switchTab('barcode')" id="barcodeTab">
                        <i class="fas fa-qrcode"></i>
                        <span>QR Code</span>
                    </button>
                </div>

                <!-- Form Login Manual -->
                <div id="manualForm">
                    <form method="POST" action="{{ route('siswa.login.submit') }}" id="loginForm">
                        @csrf
                        <input type="hidden" name="login_type" id="loginTypeInput" value="siswa">
                        
                        <!-- Input untuk Siswa -->
                        <div class="input-group" id="siswaInput">
                            <label class="input-label" for="nis">
                                Nomor Induk Siswa (NIS)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="text" name="nis" id="nis" value="{{ old('nis') }}" 
                                       class="form-input" placeholder="Contoh: 2023001" autofocus>
                            </div>
                            <div class="input-hint">
                                <i class="fas fa-info-circle"></i>
                                <span>Masukkan NIS yang terdaftar</span>
                            </div>
                        </div>

                        <!-- Input untuk Orangtua -->
                        <div class="input-group hidden" id="orangtuaInput">
                            <label class="input-label" for="nis_anak">
                                NIS Anak (Siswa)
                            </label>
                            <div class="input-wrapper">
                                <i class="fas fa-child input-icon"></i>
                                <input type="text" name="nis_anak" id="nis_anak" value="{{ old('nis_anak') }}" 
                                       class="form-input" placeholder="Contoh: 2023001">
                            </div>
                            <div class="input-hint">
                                <i class="fas fa-info-circle"></i>
                                <span>Masukkan NIS anak Anda yang terdaftar</span>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span id="submitButtonText">Masuk sebagai Siswa</span>
                        </button>
                    </form>
                </div>

                <!-- Scanner QR Code -->
                <div id="barcodeForm" class="scanner-section">
                    <div class="scanner-header">
                        <div class="scanner-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="scanner-title" id="scanTitle">Scan QR Code Siswa</div>
                        <div class="scanner-desc" id="scanInstruction">
                            Arahkan kamera ke QR Code NIS Siswa
                        </div>
                    </div>

                    <div class="scanner-container">
                        <div id="reader"></div>
                        <div class="scanner-overlay">Arahkan kamera ke QR Code</div>
                    </div>

                    <!-- Status Scanner -->
                    <div id="scanStatus" class="status-message"></div>

                    <!-- Input Manual Fallback -->
                    <div class="manual-fallback">
                        <div class="manual-title">
                            <i class="fas fa-keyboard"></i>
                            <span>Masukkan NIS manual</span>
                        </div>
                        <div class="manual-input-group">
                            <input type="text" id="barcodeManual" class="manual-input" placeholder="Ketik NIS">
                            <button type="button" onclick="submitBarcode()" class="manual-submit">
                                Login
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="login-footer">
                    <div class="footer-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ date('H:i') }}</span>
                    </div>
                    <div class="footer-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Sistem Aman</span>
                    </div>
                    <div class="footer-item">
                        <a href="#" class="help-link">
                            <i class="fas fa-question-circle"></i>
                            <span>Bantuan</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let html5QrCode = null;
        let isScanning = false;
        let isProcessing = false;
        let currentLoginType = 'siswa';

        // Tampilkan error jika ada
        @if ($errors->any())
            setTimeout(() => {
                document.getElementById('errorMessage').style.display = 'flex';
            }, 300);
        @endif

        function switchLoginType(type) {
            currentLoginType = type;
            
            // Update UI
            const siswaCard = document.getElementById('siswaTypeCard');
            const orangtuaCard = document.getElementById('orangtuaTypeCard');
            const siswaInput = document.getElementById('siswaInput');
            const orangtuaInput = document.getElementById('orangtuaInput');
            const submitButtonText = document.getElementById('submitButtonText');
            const scanTitle = document.getElementById('scanTitle');
            const scanInstruction = document.getElementById('scanInstruction');
            
            if (type === 'siswa') {
                siswaCard.classList.add('active');
                orangtuaCard.classList.remove('active');
                siswaInput.classList.remove('hidden');
                orangtuaInput.classList.add('hidden');
                submitButtonText.textContent = 'Masuk sebagai Siswa';
                scanTitle.textContent = 'Scan QR Code Siswa';
                scanInstruction.textContent = 'Arahkan kamera ke QR Code NIS Siswa';
                document.getElementById('loginTypeInput').value = 'siswa';
                
                if (document.getElementById('manualForm').style.display !== 'none') {
                    setTimeout(() => document.getElementById('nis').focus(), 100);
                }
            } else {
                siswaCard.classList.remove('active');
                orangtuaCard.classList.add('active');
                siswaInput.classList.add('hidden');
                orangtuaInput.classList.remove('hidden');
                submitButtonText.textContent = 'Masuk sebagai Orangtua';
                scanTitle.textContent = 'Scan QR Code Anak';
                scanInstruction.textContent = 'Arahkan kamera ke QR Code NIS Anak';
                document.getElementById('loginTypeInput').value = 'orangtua';
                
                if (document.getElementById('manualForm').style.display !== 'none') {
                    setTimeout(() => document.getElementById('nis_anak').focus(), 100);
                }
            }
            
            hideStatus();
        }

        function switchTab(tab) {
            const manualTab = document.getElementById('manualTab');
            const barcodeTab = document.getElementById('barcodeTab');
            const manualForm = document.getElementById('manualForm');
            const barcodeForm = document.getElementById('barcodeForm');
            
            if (tab === 'manual') {
                manualTab.classList.add('active');
                barcodeTab.classList.remove('active');
                manualForm.style.display = 'block';
                barcodeForm.style.display = 'none';
                
                stopScanner();
                
                // Focus on appropriate input
                setTimeout(() => {
                    if (currentLoginType === 'siswa') {
                        document.getElementById('nis').focus();
                    } else {
                        document.getElementById('nis_anak').focus();
                    }
                }, 100);
            } else {
                manualTab.classList.remove('active');
                barcodeTab.classList.add('active');
                manualForm.style.display = 'none';
                barcodeForm.style.display = 'block';
                
                setTimeout(startScanner, 300);
            }
            
            hideStatus();
        }

        function startScanner() {
            if (isScanning) return;
            
            const reader = document.getElementById('reader');
            reader.innerHTML = '';
            
            html5QrCode = new Html5Qrcode("reader");
            
            const config = {
                fps: 10,
                qrbox: { width: 200, height: 200 },
                aspectRatio: 1.0
            };
            
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    if (!isProcessing) {
                        isProcessing = true;
                        if (navigator.vibrate) navigator.vibrate(100);
                        showStatus('QR Code terdeteksi! Memproses...', 'loading');
                        loginWithBarcode(decodedText);
                    }
                },
                () => {}
            ).then(() => {
                isScanning = true;
            }).catch(err => {
                console.error('Scanner error:', err);
                showStatus('Gagal mengakses kamera. Pastikan izin kamera diberikan.', 'error');
            });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    html5QrCode.clear();
                });
            }
        }

        function submitBarcode() {
            const barcode = document.getElementById('barcodeManual').value.trim();
            if (barcode) {
                isProcessing = true;
                loginWithBarcode(barcode);
            } else {
                showStatus('Masukkan NIS terlebih dahulu', 'error');
            }
        }

        function loginWithBarcode(nis) {
            showStatus('Memproses login...', 'loading');
            
            if (isScanning && html5QrCode) {
                html5QrCode.pause();
            }
            
            fetch('{{ route("siswa.login.barcode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    nis: nis,
                    login_type: currentLoginType 
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus('✓ Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showStatus('✗ ' + (data.message || 'Login gagal. Periksa NIS Anda.'), 'error');
                    isProcessing = false;
                    if (isScanning && html5QrCode) {
                        setTimeout(() => html5QrCode.resume(), 2000);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStatus('✗ Terjadi kesalahan. Coba lagi.', 'error');
                isProcessing = false;
                if (isScanning && html5QrCode) {
                    setTimeout(() => html5QrCode.resume(), 2000);
                }
            });
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('scanStatus');
            statusDiv.innerHTML = `
                <i class="fas fa-${type === 'loading' ? 'spinner fa-spin' : 
                                 type === 'success' ? 'check-circle' : 
                                 'exclamation-circle'}"></i>
                <span>${message}</span>
            `;
            statusDiv.className = `status-message status-${type}`;
            statusDiv.style.display = 'flex';
        }

        function hideStatus() {
            document.getElementById('scanStatus').style.display = 'none';
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const activeInput = currentLoginType === 'siswa' ? 
                document.getElementById('nis') : document.getElementById('nis_anak');
                
            if (!activeInput.value.trim()) {
                e.preventDefault();
                showStatus('Harap masukkan NIS terlebih dahulu', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
        });

        // Handle Enter key
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('nis').focus();
            
            // Enter key for manual input
            document.getElementById('barcodeManual').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    submitBarcode();
                }
            });
            
            // Enter key for form inputs
            ['nis', 'nis_anak'].forEach(id => {
                document.getElementById(id).addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('loginForm').submit();
                    }
                });
            });
        });

        // Cleanup on unload
        window.addEventListener('beforeunload', stopScanner);
    </script>
</body>
</html>