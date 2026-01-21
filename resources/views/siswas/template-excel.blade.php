<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Template Import Data Siswa - 7 Kebiasaan Anak Indonesia Hebat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        
        h1 {
            color: #0033A0;
            text-align: center;
            margin-bottom: 5px;
        }
        
        h2 {
            color: #00A86B;
            text-align: center;
            margin-top: 0;
            margin-bottom: 30px;
        }
        
        h3 {
            color: #333;
            border-bottom: 2px solid #0033A0;
            padding-bottom: 5px;
            margin-top: 30px;
        }
        
        h4 {
            color: #555;
            margin-bottom: 10px;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #0033A0;
            color: white;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .instruction-box {
            background-color: #f0f8ff;
            border-left: 4px solid #00A86B;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 5px 5px 0;
        }
        
        .warning-box {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .example-box {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .required {
            color: red;
            font-weight: bold;
        }
        
        .note {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .step {
            margin-bottom: 10px;
        }
        
        .step-number {
            display: inline-block;
            background-color: #0033A0;
            color: white;
            width: 24px;
            height: 24px;
            text-align: center;
            line-height: 24px;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .kelas-table {
            width: 80%;
            margin: 20px auto;
        }
        
        .data-table {
            width: 100%;
            margin-top: 20px;
        }
        
        .empty-row {
            background-color: #fafafa;
        }
        
        .header-cell {
            background-color: #2196F3;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>TEMPLATE IMPORT DATA SISWA</h1>
    <h2>7 Kebiasaan Anak Indonesia Hebat</h2>
    
    <div class="instruction-box">
        <h3>📋 PETUNJUK PENGISIAN:</h3>
        <div class="step">
            <span class="step-number">1</span>
            Isi semua kolom dengan data siswa yang sesuai
        </div>
        <div class="step">
            <span class="step-number">2</span>
            Kolom NISN dan NIS harus <strong>UNIK</strong> (tidak boleh sama dengan data yang sudah ada)
        </div>
        <div class="step">
            <span class="step-number">3</span>
            Format tanggal: <strong>YYYY-MM-DD</strong> (contoh: 2010-05-15)
        </div>
        <div class="step">
            <span class="step-number">4</span>
            Untuk kolom kelas_id, isi dengan <strong>ID kelas</strong> yang tersedia di bawah
        </div>
        <div class="step">
            <span class="step-number">5</span>
            Data contoh bisa dihapus sebelum mengisi data Anda sendiri
        </div>
        <div class="step">
            <span class="step-number">6</span>
            Simpan file dalam format <strong>.xlsx atau .xls</strong> sebelum upload
        </div>
    </div>
    
    <div class="warning-box">
        <h3>⚠️ PERHATIAN:</h3>
        <p>Kolom dengan tanda <span class="required">*</span> adalah <strong>WAJIB DIISI</strong>.</p>
        <p>Pastikan format data sesuai dengan ketentuan untuk menghindari error saat import.</p>
    </div>
    
    <div>
        <h3>📚 DAFTAR KELAS YANG TERSEDIA</h3>
        <p><em>Gunakan <strong>ID Kelas</strong> ini untuk mengisi kolom <strong>kelas_id</strong>:</em></p>
        
        <table class="kelas-table">
            <thead>
                <tr>
                    <th width="20%">ID Kelas</th>
                    <th width="40%">Nama Kelas</th>
                    <th width="40%">Wali Kelas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasList as $kelas)
                <tr>
                    <td align="center"><strong>{{ $kelas->id }}</strong></td>
                    <td>{{ $kelas->nama_kelas }}</td>
                    <td>{{ $kelas->guruWali->nama_lengkap ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" align="center">Belum ada data kelas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="example-box">
        <h3>📝 CONTOH FORMAT DATA SISWA:</h3>
        <p><em>Mulai isi data dari baris ke-3 (setelah contoh data)</em></p>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th class="header-cell">nisn <span class="required">*</span></th>
                    <th class="header-cell">nis <span class="required">*</span></th>
                    <th class="header-cell">nama_lengkap <span class="required">*</span></th>
                    <th class="header-cell">kelas_id</th>
                    <th class="header-cell">tanggal_lahir <span class="required">*</span></th>
                    <th class="header-cell">alamat <span class="required">*</span></th>
                    <th class="header-cell">jenis_kelamin</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contoh Data 1 -->
                <tr style="background-color: #e8f5e9;">
                    <td>1234567890</td>
                    <td>20240001</td>
                    <td>Ahmad Fauzi</td>
                    <td>{{ $kelasList->first()->id ?? '1' }}</td>
                    <td>2010-05-15</td>
                    <td>Jl. Merdeka No. 123, Jakarta</td>
                    <td>L</td>
                </tr>
                <!-- Contoh Data 2 -->
                <tr style="background-color: #e8f5e9;">
                    <td>0987654321</td>
                    <td>20240002</td>
                    <td>Siti Nurhaliza</td>
                    <td>{{ $kelasList->first()->id ?? '1' }}</td>
                    <td>2010-08-20</td>
                    <td>Jl. Sudirman No. 45, Bandung</td>
                    <td>P</td>
                </tr>
                <!-- Baris Kosong untuk diisi (10 baris) -->
                @for($i = 1; $i <= 10; $i++)
                <tr class="empty-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>
        
        <p><small><em>Catatan: Hapus baris contoh jika tidak diperlukan sebelum mengisi data Anda sendiri</em></small></p>
    </div>
    
    <div class="note">
        <h4>📋 KETERANGAN KOLOM:</h4>
        <table width="100%" style="border: none;">
            <tr>
                <td width="30%"><strong>nisn <span class="required">*</span></strong></td>
                <td width="70%">: NISN (Nomor Induk Siswa Nasional) - Wajib, Unik, Max 20 karakter</td>
            </tr>
            <tr>
                <td><strong>nis <span class="required">*</span></strong></td>
                <td>: NIS (Nomor Induk Sekolah) - Wajib, Unik, Max 20 karakter</td>
            </tr>
            <tr>
                <td><strong>nama_lengkap <span class="required">*</span></strong></td>
                <td>: Nama lengkap siswa - Wajib, Max 255 karakter</td>
            </tr>
            <tr>
                <td><strong>kelas_id</strong></td>
                <td>: ID kelas (gunakan ID dari tabel di atas)</td>
            </tr>
            <tr>
                <td><strong>tanggal_lahir <span class="required">*</span></strong></td>
                <td>: Tanggal lahir - Wajib, Format: YYYY-MM-DD</td>
            </tr>
            <tr>
                <td><strong>alamat <span class="required">*</span></strong></td>
                <td>: Alamat lengkap - Wajib, Max 500 karakter</td>
            </tr>
            <tr>
                <td><strong>jenis_kelamin</strong></td>
                <td>: L (Laki-laki) atau P (Perempuan)</td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 30px; padding: 15px; background-color: #f5f5f5; border-radius: 5px;">
        <h4>💡 TIPS IMPORT:</h4>
        <ul>
            <li>Gunakan <strong>Microsoft Excel</strong> atau <strong>Google Sheets</strong> untuk membuka template</li>
            <li>Isi data dengan teliti dan perhatikan format tanggal (YYYY-MM-DD)</li>
            <li>Pastikan NISN dan NIS tidak duplikat dengan data yang sudah ada</li>
            <li>Simpan file dengan nama yang mudah diidentifikasi</li>
            <li>File yang sudah diisi harus disimpan dalam format <strong>.xlsx</strong> atau <strong>.xls</strong></li>
        </ul>
        
        <p style="text-align: center; margin-top: 20px; font-style: italic;">
            Template ini dibuat secara otomatis oleh sistem<br>
            <strong>7 Kebiasaan Anak Indonesia Hebat</strong><br>
            Tanggal: {{ date('d-m-Y H:i:s') }}
        </p>
    </div>
</body>
</html>