<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Dibuat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin: 0;
        }
        .header p {
            color: #6b7280;
            margin-top: 8px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .credentials-box {
            background: linear-gradient(135deg, #eff6ff 0%, #ecfdf5 100%);
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .credentials-box h3 {
            color: #1e40af;
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 16px;
        }
        .credential-item {
            display: flex;
            margin-bottom: 12px;
            padding: 12px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .credential-label {
            font-weight: 600;
            color: #374151;
            width: 120px;
            flex-shrink: 0;
        }
        .credential-value {
            color: #1f2937;
            font-family: 'Courier New', monospace;
            background-color: #f3f4f6;
            padding: 4px 8px;
            border-radius: 4px;
            word-break: break-all;
        }
        .warning-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .warning-box p {
            margin: 0;
            color: #92400e;
            font-size: 14px;
        }
        .warning-box strong {
            color: #78350f;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .footer p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
            <p>7 Kebiasaan Anak Indonesia Hebat</p>
        </div>
        
        <div class="content">
            <p class="greeting">Halo <strong>{{ $user->nama_lengkap }}</strong>,</p>
            
            <p>Akun Anda telah berhasil dibuat oleh Administrator. Berikut adalah informasi login Anda:</p>
            
            <div class="credentials-box">
                <h3>📋 Informasi Login</h3>
                
                <div class="credential-item">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Username:</span>
                    <span class="credential-value">{{ $user->username }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
                
                <div class="credential-item">
                    <span class="credential-label">Peran:</span>
                    <span class="credential-value">{{ $user->peran == 'guru_wali' ? 'Guru Wali' : 'Admin' }}</span>
                </div>
            </div>
            
            <div class="warning-box">
                <p>⚠️ <strong>Penting:</strong> Demi keamanan akun Anda, segera ubah password setelah login pertama kali.</p>
            </div>
            
            <p>Silakan login ke sistem menggunakan informasi di atas.</p>
            
            <center>
                <a href="{{ config('app.url') }}/login" class="button">Login Sekarang</a>
            </center>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem.</p>
            <p>Jika Anda tidak merasa mendaftar, silakan abaikan email ini.</p>
            <p>&copy; {{ date('Y') }} 7 Kebiasaan Anak Indonesia Hebat</p>
        </div>
    </div>
</body>
</html>