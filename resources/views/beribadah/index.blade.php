@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Ibadah Sholat - {{ $siswaList->first()->nama_lengkap }}</title>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sholat-gradient {
            background: linear-gradient(135deg, #0033A0 0%, #00A86B 100%);
            color: white;
            font-weight: bold;
        }

        .sholat-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .sholat-card:hover:not(.locked) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .sholat-card.locked {
            opacity: 0.6;
            cursor: not-allowed;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        .sholat-card.done {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .sholat-card.late {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .paraf-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .locked-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.3;
        }

        .time-info {
            font-size: 0.65rem;
            margin-top: 0.25rem;
            opacity: 0.9;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .waktu-info-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-left: 4px solid #0033A0;
        }

        .waktu-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .waktu-badge.tepat {
            background: #dcfce7;
            color: #166534;
        }

        .waktu-badge.terlambat {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        @php
            $siswa = $siswaList->first();
            $beribadah = $beribadahs->get($siswa->id);
            $isToday = \Carbon\Carbon::parse($selectedTanggal)->isToday();
        @endphp

        <!-- Date Selector -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in">
            <form action="{{ route('beribadah.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4">
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $selectedTanggal }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all text-gray-900">
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full lg:w-auto bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Lihat
                    </button>
                </div>
            </form>
            @if(!$isToday)
            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-xl">
                <div class="flex items-center gap-2 text-yellow-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-medium">Catatan: Checklist sholat hanya bisa diisi untuk hari ini. Data tanggal ini hanya bisa dilihat.</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Sholat Checklist -->
        <div class="glass-card rounded-2xl shadow-lg p-6 mb-8 fade-in" style="animation-delay: 0.2s;">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-2 h-8 bg-gradient-to-b from-primary-500 to-accent-green rounded-full"></div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Checklist Sholat</h2>
                    <p class="text-gray-500 text-sm">Tanggal {{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <!-- Sholat Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jenisSholat as $sholat)
                    @php
                        $waktu = $beribadah ? $beribadah->{$sholat . '_waktu'} : null;
                        $kategori = $beribadah ? $beribadah->{$sholat . '_kategori'} : null;
                        $paraf = $beribadah ? $beribadah->{$sholat . '_paraf'} : false;
                        $statusSholat = getStatusSholat($sholat, $selectedTanggal);
                        $isLocked = $statusSholat['is_locked'];
                        $pengaturanSholat = $pengaturan->get($sholat);
                        
                        // Cek apakah sudah lewat waktu
                        $isExpired = false;
                        if ($pengaturanSholat && $isToday) {
                            $currentTime = now()->format('H:i:s');
                            $waktuTerlambatEnd = $pengaturanSholat->waktu_terlambat_end;
                            $isExpired = $currentTime > $waktuTerlambatEnd;
                        }
                    @endphp
                    <div class="glass-card rounded-xl p-4 hover:shadow-lg transition-shadow">
                        <!-- Header Card -->
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $sholatLabels[$sholat] }}</h3>
                            @if($pengaturanSholat && $pengaturanSholat->is_active)
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">Aktif</span>
                            @endif
                        </div>

                      
                        <!-- Status Checklist -->
                        <!-- Status Checklist -->
                        <div class="flex flex-col items-center">
                        @if($isLocked && !$waktu)
                            <!-- Tombol terkunci (bukan hari ini atau belum waktunya) -->
                            <div class="relative sholat-card locked rounded-xl w-full h-32 flex flex-col items-center justify-center border-2 border-gray-200">
                                <div class="locked-icon">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">{{ $statusSholat['message'] }}</p>
                        @elseif(!$waktu)
                            @if($isExpired)
                                <!-- Sudah lewat waktu - Tidak bisa checklist -->
                                <div class="relative sholat-card locked rounded-xl w-full h-32 flex flex-col items-center justify-center border-2 border-red-200 bg-red-50">
                                    <svg class="w-12 h-12 text-red-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-red-600">Waktu Habis</span>
                                </div>
                                <p class="text-xs text-red-500 mt-2 font-medium">Sudah lewat batas waktu</p>
                            @else
                                <!-- Tombol Checklist (belum diisi & masih dalam waktu) -->
                                <button onclick="checklistSholat({{ $siswa->id }}, '{{ $sholat }}')" 
                                        class="sholat-card rounded-xl w-full h-32 flex flex-col items-center justify-center border-2 border-dashed border-primary-300 hover:border-primary-500 hover:bg-primary-50 transition-all group"
                                        title="Checklist {{ $sholatLabels[$sholat] }}">
                                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-2 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-primary-600">Checklist Sekarang</span>
                                </button>
                                @if($statusSholat['waktu_buka'])
                                <p class="time-info text-green-600 mt-2 font-medium">✓ Sudah bisa checklist</p>
                                @endif
                            @endif
                        @else
                            <!-- Sudah Checklist -->
                            <div class="relative w-full">
                                <div class="sholat-card {{ $kategori == 'terlambat' ? 'late' : 'done' }} rounded-xl w-full h-32 flex flex-col items-center justify-center transition-all">
                                    <svg class="w-12 h-12 text-white mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <div class="text-base font-bold text-white">Sudah Sholat</div>
                                    @if($paraf)
                                        <div class="paraf-badge">
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center mt-2">
                                    <span class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full {{ $kategori == 'terlambat' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $kategori == 'terlambat' ? '⚠️ Terlambat' : '✓ Tepat Waktu' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paraf Status -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-1">Status Paraf Orang Tua</h4>
                        <p class="text-sm text-gray-600">
                            @if($beribadah)
                                @php
                                    $parafCount = 0;
                                    $sholatCount = 0;
                                    foreach ($jenisSholat as $sholat) {
                                        if ($beribadah->{$sholat . '_waktu'}) {
                                            $sholatCount++;
                                            if ($beribadah->{$sholat . '_paraf'}) {
                                                $parafCount++;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $parafCount }}/{{ $sholatCount }} sholat sudah diparaf
                            @else
                                Belum ada checklist sholat
                            @endif
                        </p>
                    </div>
                    <div>
                        @if($beribadah && $sholatCount > 0 && $parafCount < $sholatCount)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Menunggu Paraf
                            </span>
                        @elseif($beribadah && $sholatCount > 0 && $parafCount == $sholatCount)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Sudah Diparaf
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Checklist Sholat dengan Info Waktu -->
    <div id="modalChecklist" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 fade-in">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl sholat-gradient flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalChecklistTitle">Checklist Sholat</h3>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <!-- Info Waktu Sholat di Modal -->
            <div id="modalWaktuInfo" class="mb-6 space-y-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold text-gray-900 text-sm">Jadwal Waktu Sholat</h4>
                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-medium">Aktif</span>
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="flex-shrink-0 w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="text-sm text-green-800 font-medium" id="modalWaktuTepat">
                            Tepat Waktu: -
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-shrink-0 w-3 h-3 rounded-full bg-yellow-500"></div>
                        <span class="text-sm text-yellow-800 font-medium" id="modalWaktuTerlambat">
                            Terlambat: -
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                <p class="text-sm text-blue-800">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Apakah Anda yakin sudah melaksanakan sholat <strong id="sholatName"></strong>?
                </p>
            </div>

            <form id="formChecklist" onsubmit="submitChecklist(event)">
                <input type="hidden" id="checklistSiswaId" value="{{ $siswa->id }}">
                <input type="hidden" id="checklistSholat">
                
                <div class="flex gap-3">
                    <button type="button" onclick="tutupModalChecklist()" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-primary-500 to-accent-green text-white font-semibold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all">
                        Ya, Sudah Sholat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
    // Checklist Sholat
    function checklistSholat(siswaId, sholat) {
        // Cek apakah sudah lewat waktu
        const pengaturan = @json($pengaturan);
        const sholatPengaturan = pengaturan[sholat];
        
        if (sholatPengaturan) {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' + 
                              now.getMinutes().toString().padStart(2, '0') + ':' + 
                              now.getSeconds().toString().padStart(2, '0');
            
            const waktuTerlambatEnd = sholatPengaturan.waktu_terlambat_end;
            
            if (currentTime > waktuTerlambatEnd) {
                showNotification('error', 'Maaf, Anda sudah melewati waktu yang ditentukan untuk sholat ' + sholat.charAt(0).toUpperCase() + sholat.slice(1) + '. Checklist tidak dapat dilakukan.');
                return;
            }

            // Tampilkan info waktu di modal
            const waktuTepatStart = sholatPengaturan.waktu_tepat_start.substring(0, 5);
            const waktuTepatEnd = sholatPengaturan.waktu_tepat_end.substring(0, 5);
            const waktuTerlambatStart = sholatPengaturan.waktu_terlambat_start.substring(0, 5);
            const waktuTerlambatEndFormatted = waktuTerlambatEnd.substring(0, 5);

            document.getElementById('modalWaktuTepat').textContent = `Tepat Waktu: ${waktuTepatStart} - ${waktuTepatEnd}`;
            document.getElementById('modalWaktuTerlambat').textContent = `Terlambat: ${waktuTerlambatStart} - ${waktuTerlambatEndFormatted}`;
        }
        
        document.getElementById('checklistSiswaId').value = siswaId;
        document.getElementById('checklistSholat').value = sholat;
        
        const sholatLabels = {
            'subuh': 'Subuh',
            'dzuhur': 'Dzuhur', 
            'ashar': 'Ashar',
            'maghrib': 'Maghrib',
            'isya': 'Isya'
        };
        
        const sholatLabel = sholatLabels[sholat] || sholat;
        document.getElementById('modalChecklistTitle').textContent = `Checklist ${sholatLabel}`;
        document.getElementById('sholatName').textContent = sholatLabel;
        
        document.getElementById('modalChecklist').classList.remove('hidden');
        document.getElementById('modalChecklist').classList.add('flex');
    }

    function tutupModalChecklist() {
        document.getElementById('modalChecklist').classList.add('hidden');
        document.getElementById('modalChecklist').classList.remove('flex');
    }

    function submitChecklist(event) {
        event.preventDefault();
        
        const siswaId = document.getElementById('checklistSiswaId').value;
        const sholat = document.getElementById('checklistSholat').value;
        const tanggal = '{{ $selectedTanggal }}';
        
        // Gunakan waktu sekarang
        const now = new Date();
        const waktu = now.getHours().toString().padStart(2, '0') + ':' + 
                     now.getMinutes().toString().padStart(2, '0');
        
        // Tampilkan loading state
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        submitBtn.disabled = true;
        
        fetch('{{ route("beribadah.store-checklist") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                siswa_id: siswaId,
                tanggal: tanggal,
                sholat: sholat,
                waktu: waktu
            })
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                showNotification('success', data.message);
                tutupModalChecklist();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            showNotification('error', 'Terjadi kesalahan: ' + error.message);
        });
    }

    // Show Notification
    function showNotification(type, message) {
        const bgColor = type === 'success' 
            ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-green-200' 
            : 'bg-gradient-to-r from-red-50 to-pink-50 border-red-200';
        const textColor = type === 'success' ? 'text-green-800' : 'text-red-800';
        const iconColor = type === 'success' ? 'text-green-500' : 'text-red-500';
        const icon = type === 'success' 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
        
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `notification ${bgColor} border rounded-xl p-4 shadow-lg`;
        notification.innerHTML = `
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 ${iconColor} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
                <p class="${textColor} font-medium">${message}</p>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto">
                    <svg class="w-4 h-4 ${textColor}" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        const modalChecklist = document.getElementById('modalChecklist');
        if (modalChecklist && e.target === modalChecklist) {
            tutupModalChecklist();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalChecklist();
        }
    });

    // Animasi untuk elements
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${0.1 * index}s`;
        });
    });
    </script>
</body>
</html>
@endsection