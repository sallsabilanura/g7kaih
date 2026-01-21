<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekap Pemantauan 7 Kebiasaan - {{ $rekap->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 20mm 15mm 20mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #333;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 120px;
            font-weight: normal;
            vertical-align: top;
        }
        .info-separator {
            display: table-cell;
            width: 15px;
            text-align: center;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            vertical-align: top;
        }
        .checkbox-inline {
            display: inline-block;
            margin-right: 15px;
            margin-bottom: 5px;
        }
        .checkbox-box {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            text-align: center;
            line-height: 12px;
            font-size: 10pt;
            font-weight: bold;
            margin-right: 3px;
            vertical-align: middle;
        }
        .checkbox-box.checked {
            background-color: #e8e8e8;
        }
        .checkbox-label {
            vertical-align: middle;
            font-size: 9pt;
        }
        table.main-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.main-table th,
        table.main-table td {
            border: 1px solid #000;
            padding: 10px 8px;
            text-align: left;
            vertical-align: middle;
        }
        table.main-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 9pt;
        }
        table.main-table td {
            font-size: 9pt;
        }
        table.main-table td.center {
            text-align: center;
        }
        table.main-table td.number {
            text-align: center;
            width: 40px;
        }
        .check-mark {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            text-align: center;
            line-height: 16px;
            font-size: 12pt;
            font-weight: bold;
        }
        .check-mark.checked::after {
            content: "✓";
        }
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-container {
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .signature-title {
            font-size: 10pt;
            margin-bottom: 5px;
        }
        .signature-role {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signature-image {
            height: 60px;
            margin: 10px auto;
        }
        .signature-image img {
            max-height: 60px;
            max-width: 150px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin: 60px 30px 5px 30px;
        }
        .signature-line.with-image {
            margin-top: 10px;
        }
        .signature-name {
            font-size: 10pt;
            font-weight: bold;
        }
        .signature-note {
            font-size: 8pt;
            color: #666;
            margin-top: 3px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }
        .period-info {
            background-color: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .period-info strong {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .status-badge.terbiasa {
            background-color: #d4edda;
            color: #155724;
        }
        .status-badge.belum {
            background-color: #f8d7da;
            color: #721c24;
        }
        .summary-box {
            background-color: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 10px 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .summary-box h4 {
            font-size: 10pt;
            margin-bottom: 5px;
            color: #004085;
        }
        .summary-box p {
            font-size: 9pt;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Pemantauan</h1>
        <h2>Tujuh Kebiasaan Anak Indonesia Hebat</h2>
        <p>Periode: {{ $rekap->bulan }} {{ $rekap->tahun }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nama Siswa</span>
            <span class="info-separator">:</span>
            <span class="info-value"><strong>{{ $rekap->nama_lengkap }}</strong></span>
        </div>
        
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span class="info-separator">:</span>
            <span class="info-value">
                @for($i = 1; $i <= 6; $i++)
                    <span class="checkbox-inline">
                        <span class="checkbox-box @if($rekap->kelas == $i) checked @endif">
                            @if($rekap->kelas == $i) ✓ @endif
                        </span>
                        <span class="checkbox-label">Kelas {{ $i }}</span>
                    </span>
                @endfor
            </span>
        </div>

        <div class="info-row">
            <span class="info-label">Bulan</span>
            <span class="info-separator">:</span>
            <span class="info-value">
                @php
                    $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                @endphp
                @foreach($bulanList as $bulan)
                    <span class="checkbox-inline">
                        <span class="checkbox-box @if($rekap->bulan == $bulan) checked @endif">
                            @if($rekap->bulan == $bulan) ✓ @endif
                        </span>
                        <span class="checkbox-label">{{ $bulan }}</span>
                    </span>
                @endforeach
            </span>
        </div>

        @if($rekap->guru_kelas)
        <div class="info-row">
            <span class="info-label">Wali Kelas</span>
            <span class="info-separator">:</span>
            <span class="info-value">{{ $rekap->guru_kelas }}</span>
        </div>
        @endif
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 40px;">No.</th>
                <th rowspan="2" style="width: 45%;">Tujuh Kebiasaan Anak Indonesia Hebat</th>
                <th colspan="2">Penerapan Tujuh Kebiasaan<br><small>(Beri tanda ceklis ✓)</small></th>
            </tr>
            <tr>
                <th style="width: 25%;">Belum Terbiasa</th>
                <th style="width: 25%;">Sudah Terbiasa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $kebiasaanItems = [
                    ['no' => 1, 'nama' => 'Bangun Pagi', 'field' => 'bangun_pagi_status'],
                    ['no' => 2, 'nama' => 'Beribadah', 'field' => 'beribadah_status'],
                    ['no' => 3, 'nama' => 'Berolahraga', 'field' => 'berolahraga_status'],
                    ['no' => 4, 'nama' => 'Makan Sehat dan Bergizi', 'field' => 'makan_sehat_status'],
                    ['no' => 5, 'nama' => 'Gemar Belajar', 'field' => 'gemar_belajar_status'],
                    ['no' => 6, 'nama' => 'Bermasyarakat', 'field' => 'bermasyarakat_status'],
                    ['no' => 7, 'nama' => 'Tidur Cepat', 'field' => 'tidur_cepat_status'],
                ];
                $totalTerbiasa = 0;
            @endphp
            
            @foreach($kebiasaanItems as $item)
                @php
                    $isTerbiasa = $rekap->{$item['field']} == 'sudah_terbiasa';
                    if ($isTerbiasa) $totalTerbiasa++;
                @endphp
                <tr>
                    <td class="number">{{ $item['no'] }}</td>
                    <td>{{ $item['nama'] }}</td>
                    <td class="center">
                        <span class="check-mark @if(!$isTerbiasa) checked @endif"></span>
                    </td>
                    <td class="center">
                        <span class="check-mark @if($isTerbiasa) checked @endif"></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <h4>Ringkasan Pemantauan</h4>
        <p>
            Total kebiasaan yang sudah terbiasa: <strong>{{ $totalTerbiasa }} dari 7 kebiasaan</strong>
            @if($totalTerbiasa == 7)
                - <span class="status-badge terbiasa">Excellent!</span>
            @elseif($totalTerbiasa >= 5)
                - <span class="status-badge terbiasa">Baik</span>
            @elseif($totalTerbiasa >= 3)
                - <span class="status-badge belum">Perlu Ditingkatkan</span>
            @else
                - <span class="status-badge belum">Perlu Perhatian Khusus</span>
            @endif
        </p>
    </div>

    @if($rekap->catatan)
    <div style="margin-top: 15px;">
        <strong>Catatan:</strong>
        <p style="margin-top: 5px; padding: 8px; background: #f9f9f9; border-left: 3px solid #ccc;">
            {{ $rekap->catatan }}
        </p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-title">Mengetahui,</div>
                <div class="signature-role">Orangtua/Wali Siswa</div>
                
                @if(isset($tandaTanganOrangtua) && $tandaTanganOrangtua)
                    <div class="signature-image">
                        <img src="{{ $tandaTanganOrangtua }}" alt="Tanda Tangan Orangtua">
                    </div>
                    <div class="signature-line with-image"></div>
                @else
                    <div class="signature-line"></div>
                @endif
                
                <div class="signature-name">
                    @if($rekap->orangtua_siswa)
                        {{ $rekap->orangtua_siswa }}
                    @elseif($rekap->siswa && $rekap->siswa->orangtua)
                        {{ $rekap->siswa->orangtua->nama_ayah ?? $rekap->siswa->orangtua->nama_ibu ?? '(..................................)' }}
                    @else
                        (..................................)
                    @endif
                </div>
                <div class="signature-note">Orangtua/Wali</div>
            </div>
            
            <div class="signature-box">
                <div class="signature-title">Menyetujui,</div>
                <div class="signature-role">Guru Kelas</div>
                
                @if(isset($tandaTanganGuru) && $tandaTanganGuru)
                    <div class="signature-image">
                        <img src="{{ $tandaTanganGuru }}" alt="Tanda Tangan Guru">
                    </div>
                    <div class="signature-line with-image"></div>
                @else
                    <div class="signature-line"></div>
                @endif
                
                <div class="signature-name">
                    @if($rekap->guru_kelas)
                        {{ $rekap->guru_kelas }}
                    @else
                        (..................................)
                    @endif
                </div>
                <div class="signature-note">Wali Kelas {{ $rekap->kelas }}</div>
            </div>
        </div>
    </div>

    @if($rekap->tanggal_persetujuan)
    <div style="margin-top: 20px; text-align: right; font-size: 9pt;">
        <p>Tanggal Persetujuan: {{ \Carbon\Carbon::parse($rekap->tanggal_persetujuan)->format('d F Y') }}</p>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</p>
        <p>Rekap Pemantauan 7 Kebiasaan Anak Indonesia Hebat - {{ $rekap->nama_lengkap }}</p>
    </div>
</body>
</html>