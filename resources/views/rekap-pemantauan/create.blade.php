@extends('layouts.app')

@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'primary': '#1e40af',
                    'secondary': '#059669',
                    'primary-light': '#3b82f6',
                    'secondary-light': '#10b981',
                    'danger': '#dc2626',
                }
            }
        }
    }
</script>
<style>
    .gradient-bg { background: linear-gradient(135deg, #1e40af 0%, #059669 100%); }
    .kebiasaan-card { transition: all 0.3s ease; border-left: 4px solid transparent; }
    .kebiasaan-card.active { border-left-color: #059669; background-color: #f0fdf4; }
    .kebiasaan-card.inactive { border-left-color: #dc2626; background-color: #fef2f2; }
    .status-badge { padding: 8px 16px; border-radius: 8px; font-weight: 600; display: inline-block; }
    .status-badge.sudah { background-color: #dcfce7; color: #166534; border: 2px solid #22c55e; }
    .status-badge.belum { background-color: #fee2e2; color: #991b1b; border: 2px solid #ef4444; }
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 24px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }
    .toast-notification.warning { background-color: #f59e0b; }
    .toast-notification.info { background-color: #3b82f6; }
    .toast-notification.error { background-color: #dc2626; }
    .toast-notification.success { background-color: #059669; }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>

<header class="gradient-bg text-white shadow-lg">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold flex items-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Tambah Data Rekap
                </h1>
                <p class="text-blue-100 mt-2">7 Kebiasaan Anak Indonesia Hebat</p>
            </div>
            <a href="{{ route('rekap-pemantauan.index') }}" 
               class="bg-white text-primary px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
</header>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Error!</strong> {{ session('error') }}
        </div>
        @endif
        
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
        @endif
        
        @if(!$kelasGuru)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Perhatian!</strong> Anda belum ditugaskan sebagai wali kelas. Hubungi admin untuk penugasan.
        </div>
        @else

        <form action="{{ route('rekap-pemantauan.store') }}" method="POST" id="rekapForm">
            @csrf
            
            <input type="hidden" name="siswa_id" id="siswa_id" value="{{ old('siswa_id') }}">
            <input type="hidden" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}">
            <input type="hidden" name="kelas" id="kelas" value="{{ old('kelas') }}">
            <input type="hidden" name="guru_kelas" id="guru_kelas" value="{{ $user->nama_lengkap }}">
            
            <input type="hidden" name="bangun_pagi_status" id="bangun_pagi_status" value="{{ old('bangun_pagi_status', 'belum_terbiasa') }}">
            <input type="hidden" name="beribadah_status" id="beribadah_status" value="{{ old('beribadah_status', 'belum_terbiasa') }}">
            <input type="hidden" name="berolahraga_status" id="berolahraga_status" value="{{ old('berolahraga_status', 'belum_terbiasa') }}">
            <input type="hidden" name="makan_sehat_status" id="makan_sehat_status" value="{{ old('makan_sehat_status', 'belum_terbiasa') }}">
            <input type="hidden" name="gemar_belajar_status" id="gemar_belajar_status" value="{{ old('gemar_belajar_status', 'belum_terbiasa') }}">
            <input type="hidden" name="bermasyarakat_status" id="bermasyarakat_status" value="{{ old('bermasyarakat_status', 'belum_terbiasa') }}">
            <input type="hidden" name="tidur_cepat_status" id="tidur_cepat_status" value="{{ old('tidur_cepat_status', 'belum_terbiasa') }}">
            
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-primary text-white p-3 rounded-full mr-4">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pilih Siswa</h2>
                        <p class="text-gray-600">Pilih siswa dari kelas Anda</p>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600 text-sm">Kelas:</span>
                            <p class="font-semibold text-gray-800">{{ $kelasGuru->nama_kelas }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Wali Kelas:</span>
                            <p class="font-semibold text-gray-800">{{ $user->nama_lengkap }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Pilih Siswa
                        </label>
                        <select id="siswa_select" name="siswa_select" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition @error('siswa_id') border-red-500 @enderror">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" 
                                        data-nama="{{ $siswa->nama_lengkap }}"
                                        data-nis="{{ $siswa->nis ?? '-' }}"
                                        data-kelas="{{ $siswa->kelas ? $siswa->kelas->nama_kelas : '' }}"
                                        data-ayah="{{ $siswa->orangtua->nama_ayah ?? '-' }}"
                                        data-ibu="{{ $siswa->orangtua->nama_ibu ?? '-' }}"
                                        {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_lengkap }} - {{ $siswa->nis ?? 'No NIS' }}
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Bulan
                        </label>
                        <select name="bulan" id="bulan" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition @error('bulan') border-red-500 @enderror">
                            <option value="">Pilih Bulan</option>
                            @foreach($bulanOptions as $bulan)
                                <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                            @endforeach
                        </select>
                        @error('bulan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Tahun
                        </label>
                        <select name="tahun" id="tahun" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition @error('tahun') border-red-500 @enderror">
                            <option value="">Pilih Tahun</option>
                            @foreach($tahunOptions as $tahun)
                                <option value="{{ $tahun }}" {{ old('tahun', date('Y')) == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                        </select>
                        @error('tahun')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="siswaInfoCard" class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200 {{ old('siswa_id') ? '' : 'hidden' }}">
                    <h4 class="font-semibold text-green-800 mb-3"><i class="fas fa-check-circle mr-2"></i>Siswa Terpilih</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><span class="text-gray-600">Nama:</span> <span class="font-medium text-gray-800" id="infoNama">-</span></div>
                        <div><span class="text-gray-600">NIS:</span> <span class="font-medium text-gray-800" id="infoNis">-</span></div>
                        <div><span class="text-gray-600">Kelas:</span> <span class="font-medium text-gray-800" id="infoKelas">-</span></div>
                        <div><span class="text-gray-600">Ayah:</span> <span class="font-medium text-gray-800" id="infoAyah">-</span></div>
                        <div><span class="text-gray-600">Ibu:</span> <span class="font-medium text-gray-800" id="infoIbu">-</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-secondary text-white p-3 rounded-full mr-4">
                        <i class="fas fa-star text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">7 Kebiasaan Anak Indonesia Hebat</h2>
                        <p class="text-gray-600">Status ditentukan otomatis berdasarkan nilai rata-rata (≥85 = Sudah Terbiasa)</p>
                    </div>
                    <div class="ml-auto">
                        <div class="bg-blue-50 text-primary px-4 py-2 rounded-lg font-semibold">
                            <span id="selectedCount">0</span>/7
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-lock text-amber-600 mr-3 mt-1"></i>
                        <div>
                            <p class="font-semibold text-amber-800">Status Kebiasaan Otomatis</p>
                            <p class="text-sm text-amber-700">Status 7 kebiasaan dihitung otomatis dari data pemantauan harian siswa. Anda tidak dapat mengubah status secara manual untuk menjaga keakuratan data.</p>
                        </div>
                    </div>
                </div>

                <div id="kebiasaanPlaceholder" class="text-center py-8 text-gray-500 {{ old('siswa_id') ? 'hidden' : '' }}">
                    <i class="fas fa-user-clock text-5xl mb-4 text-gray-300"></i>
                    <p class="text-lg">Pilih siswa, bulan, dan tahun terlebih dahulu</p>
                    <p class="text-sm">Status kebiasaan akan muncul setelah data siswa dimuat</p>
                </div>

                <div id="kebiasaanContainer" class="{{ old('siswa_id') ? '' : 'hidden' }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $kebiasaanList = [
                                ['id' => 'bangun_pagi', 'label' => 'Bangun Pagi', 'desc' => 'Melatih kedisiplinan dan mengelola waktu', 'icon' => 'fa-sun'],
                                ['id' => 'beribadah', 'label' => 'Beribadah', 'desc' => 'Mendekatkan hubungan dengan Tuhan', 'icon' => 'fa-pray'],
                                ['id' => 'berolahraga', 'label' => 'Berolahraga', 'desc' => 'Menjaga kesehatan fisik dan mental', 'icon' => 'fa-running'],
                                ['id' => 'makan_sehat', 'label' => 'Makan Sehat & Bergizi', 'desc' => 'Investasi kesehatan jangka panjang', 'icon' => 'fa-apple-alt'],
                                ['id' => 'gemar_belajar', 'label' => 'Gemar Belajar', 'desc' => 'Mengembangkan diri dan kreativitas', 'icon' => 'fa-book-reader'],
                                ['id' => 'bermasyarakat', 'label' => 'Bermasyarakat', 'desc' => 'Gotong royong dan kerja sama', 'icon' => 'fa-hands-helping'],
                                ['id' => 'tidur_cepat', 'label' => 'Tidur Cepat', 'desc' => 'Memulihkan tubuh dan mental', 'icon' => 'fa-bed'],
                            ];
                        @endphp

                        @foreach($kebiasaanList as $kebiasaan)
                        <div class="kebiasaan-card p-4 border border-gray-200 rounded-lg cursor-pointer" 
                             id="card-{{ $kebiasaan['id'] }}"
                             onclick="showLockedNotification()">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start">
                                    <div class="bg-gray-100 p-3 rounded-full mr-4">
                                        <i class="fas {{ $kebiasaan['icon'] }} text-primary text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $kebiasaan['label'] }}</h3>
                                        <p class="text-sm text-gray-500 mb-2">{{ $kebiasaan['desc'] }}</p>
                                        <p class="text-xs" id="nilai_{{ $kebiasaan['id'] }}">
                                            <span class="text-gray-400">Nilai: Belum dimuat</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-lock text-gray-400 mr-2 text-xs"></i>
                                    <div id="status_badge_{{ $kebiasaan['id'] }}" class="status-badge belum">
                                        Belum Terbiasa
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium">Progress Kebiasaan</span>
                            <span id="progressText" class="font-semibold">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div id="progressBar" class="h-4 rounded-full transition-all duration-500 bg-red-500" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Target: Semua 7 kebiasaan mencapai status "Sudah Terbiasa"</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                    <div class="bg-purple-600 text-white p-3 rounded-full mr-4">
                        <i class="fas fa-file-signature text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Persetujuan</h2>
                        <p class="text-gray-600">Informasi persetujuan orangtua</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orangtua</label>
                        <select name="orangtua_siswa" id="orangtua_siswa"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary">
                            <option value="">-- Pilih Siswa Dahulu --</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Persetujuan</label>
                        <input type="date" name="tanggal_persetujuan"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                               value="{{ old('tanggal_persetujuan', date('Y-m-d')) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary"
                                  placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div id="ttdPreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Tanda Tangan Orangtua:</p>
                    <img id="ttdImg" src="" alt="TTD" class="max-h-20 border rounded">
                </div>
            </div>

            <div class="flex justify-between items-center bg-white rounded-xl shadow-lg p-6">
                <span class="text-sm text-gray-600"><i class="fas fa-info-circle mr-1 text-primary"></i> Pastikan data sudah benar</span>
                <div class="flex space-x-4">
                    <a href="{{ route('rekap-pemantauan.index') }}" class="px-6 py-3 border text-gray-700 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" id="submitBtn" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-light">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>

<script>
var baseUrl = '{{ url("/") }}';
var kebiasaanIds = ['bangun_pagi', 'beribadah', 'berolahraga', 'makan_sehat', 'gemar_belajar', 'bermasyarakat', 'tidur_cepat'];

function showNotification(message, type) {
    var existingToast = document.querySelector('.toast-notification');
    if (existingToast) existingToast.remove();
    
    var iconMap = {
        'warning': 'exclamation-triangle',
        'info': 'info-circle',
        'error': 'times-circle',
        'success': 'check-circle'
    };
    
    var toast = document.createElement('div');
    toast.className = 'toast-notification ' + type;
    toast.innerHTML = '<i class="fas fa-' + iconMap[type] + ' mr-2"></i>' + message;
    document.body.appendChild(toast);
    
    setTimeout(function() {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(function() { toast.remove(); }, 300);
    }, 3000);
}

function showLockedNotification() {
    showNotification('Status kebiasaan bersifat otomatis dan tidak dapat diubah manual. Status dihitung dari data pemantauan harian siswa.', 'warning');
}

function setKebiasaanStatus(id, nilai, status) {
    var card = document.getElementById('card-' + id);
    var badge = document.getElementById('status_badge_' + id);
    var nilaiEl = document.getElementById('nilai_' + id);
    var hiddenInput = document.getElementById(id + '_status');
    
    if (hiddenInput) hiddenInput.value = status;
    
    if (nilaiEl) {
        var nilaiNum = parseFloat(nilai);
        var nilaiClass = nilaiNum >= 85 ? 'text-green-600 font-semibold' : (nilaiNum > 0 ? 'text-red-600' : 'text-gray-400');
        nilaiEl.innerHTML = '<span class="' + nilaiClass + '">Nilai: ' + nilai + '</span>';
    }
    
    if (status === 'sudah_terbiasa') {
        card.className = 'kebiasaan-card p-4 border border-gray-200 rounded-lg cursor-pointer active';
        badge.className = 'status-badge sudah';
        badge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Sudah Terbiasa';
    } else {
        card.className = 'kebiasaan-card p-4 border border-gray-200 rounded-lg cursor-pointer inactive';
        badge.className = 'status-badge belum';
        badge.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Belum Terbiasa';
    }
}

function updateProgress() {
    var count = 0;
    kebiasaanIds.forEach(function(id) {
        var hiddenInput = document.getElementById(id + '_status');
        if (hiddenInput && hiddenInput.value === 'sudah_terbiasa') count++;
    });
    
    var pct = Math.round((count / 7) * 100);
    
    document.getElementById('selectedCount').textContent = count;
    document.getElementById('progressText').textContent = pct + '%';
    
    var bar = document.getElementById('progressBar');
    bar.style.width = pct + '%';
    bar.className = 'h-4 rounded-full transition-all duration-500';
    
    if (pct >= 85) bar.classList.add('bg-green-500');
    else if (pct >= 50) bar.classList.add('bg-yellow-500');
    else bar.classList.add('bg-red-500');
}

function extractKelasLevel(namaKelas) {
    if (!namaKelas) return '1';
    var match = namaKelas.match(/\d+/);
    return match ? match[0] : '1';
}

function updateSiswaInfo(selectEl) {
    var siswaId = selectEl.value;
    var siswaInfoCard = document.getElementById('siswaInfoCard');
    var orangtuaSelect = document.getElementById('orangtua_siswa');
    
    if (!siswaId) {
        siswaInfoCard.classList.add('hidden');
        document.getElementById('siswa_id').value = '';
        document.getElementById('nama_lengkap').value = '';
        document.getElementById('kelas').value = '';
        orangtuaSelect.innerHTML = '<option value="">-- Pilih Siswa Dahulu --</option>';
        return;
    }
    
    var selectedOption = selectEl.options[selectEl.selectedIndex];
    var nama = selectedOption.getAttribute('data-nama') || '-';
    var nis = selectedOption.getAttribute('data-nis') || '-';
    var kelasNama = selectedOption.getAttribute('data-kelas') || '';
    var ayah = selectedOption.getAttribute('data-ayah') || '-';
    var ibu = selectedOption.getAttribute('data-ibu') || '-';
    
    var tingkatKelas = extractKelasLevel(kelasNama);
    
    document.getElementById('siswa_id').value = siswaId;
    document.getElementById('nama_lengkap').value = nama;
    document.getElementById('kelas').value = tingkatKelas;
    
    document.getElementById('infoNama').textContent = nama;
    document.getElementById('infoNis').textContent = nis;
    document.getElementById('infoKelas').textContent = kelasNama || '-';
    document.getElementById('infoAyah').textContent = ayah;
    document.getElementById('infoIbu').textContent = ibu;
    siswaInfoCard.classList.remove('hidden');
    
    orangtuaSelect.innerHTML = '<option value="">-- Pilih Orangtua --</option>';
    if (ayah && ayah !== '-' && ayah !== '') {
        orangtuaSelect.innerHTML += '<option value="' + ayah + '">' + ayah + ' (Ayah)</option>';
    }
    if (ibu && ibu !== '-' && ibu !== '') {
        orangtuaSelect.innerHTML += '<option value="' + ibu + '">' + ibu + ' (Ibu)</option>';
    }
    if (orangtuaSelect.options.length === 1) {
        orangtuaSelect.innerHTML = '<option value="">Data orangtua tidak tersedia</option>';
    }
}

function loadNilaiKebiasaan(siswaId) {
    var bulan = document.getElementById('bulan').value;
    var tahun = document.getElementById('tahun').value;
    
    if (!siswaId || !bulan || !tahun) {
        document.getElementById('kebiasaanPlaceholder').classList.remove('hidden');
        document.getElementById('kebiasaanContainer').classList.add('hidden');
        return;
    }
    
    var url = baseUrl + '/rekap-pemantauan/siswa-detail/' + siswaId + '?bulan=' + bulan + '&tahun=' + tahun;
    
    fetch(url, {
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) {
            console.error(data.message);
            showNotification('Gagal memuat data kebiasaan', 'error');
            return;
        }
        
        document.getElementById('kebiasaanPlaceholder').classList.add('hidden');
        document.getElementById('kebiasaanContainer').classList.remove('hidden');
        
        if (data.orangtua) {
            var ttd = data.orangtua.tanda_tangan_ayah || data.orangtua.tanda_tangan_ibu;
            if (ttd) {
                document.getElementById('ttdPreview').classList.remove('hidden');
                document.getElementById('ttdImg').src = ttd;
            } else {
                document.getElementById('ttdPreview').classList.add('hidden');
            }
        }
        
        if (data.nilai_kebiasaan) {
            for (var key in data.nilai_kebiasaan) {
                var info = data.nilai_kebiasaan[key];
                setKebiasaanStatus(key, info.nilai, info.status);
            }
            updateProgress();
            showNotification('Data kebiasaan berhasil dimuat', 'success');
        }
    })
    .catch(function(err) {
        console.error(err);
        showNotification('Gagal memuat data kebiasaan', 'error');
    });
}

document.getElementById('siswa_select').addEventListener('change', function() {
    updateSiswaInfo(this);
    loadNilaiKebiasaan(this.value);
});

document.getElementById('bulan').addEventListener('change', function() {
    var siswaId = document.getElementById('siswa_select').value;
    if (siswaId) loadNilaiKebiasaan(siswaId);
});

document.getElementById('tahun').addEventListener('change', function() {
    var siswaId = document.getElementById('siswa_select').value;
    if (siswaId) loadNilaiKebiasaan(siswaId);
});

document.getElementById('rekapForm').addEventListener('submit', function(e) {
    var siswaId = document.getElementById('siswa_id').value;
    var bulan = document.getElementById('bulan').value;
    var tahun = document.getElementById('tahun').value;
    
    if (!siswaId) {
        e.preventDefault();
        showNotification('Pilih siswa terlebih dahulu!', 'warning');
        return false;
    }
    if (!bulan || !tahun) {
        e.preventDefault();
        showNotification('Pilih bulan dan tahun!', 'warning');
        return false;
    }
    
    document.getElementById('submitBtn').disabled = true;
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
});

document.addEventListener('DOMContentLoaded', function() {
    var siswaSelect = document.getElementById('siswa_select');
    if (siswaSelect.value) {
        updateSiswaInfo(siswaSelect);
        loadNilaiKebiasaan(siswaSelect.value);
    }
});
</script>
@endsection