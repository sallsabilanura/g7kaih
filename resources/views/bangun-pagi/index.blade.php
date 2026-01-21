@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header yang lebih sederhana -->
    <div class="container mx-auto px-4 py-6">
        <div class="glass-card rounded-xl p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Logo dan Info -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-600 to-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                Monitoring Harian
                            </span>
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                {{ \Carbon\Carbon::parse($selectedTanggal)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <h1 class="text-lg font-bold text-gray-900">
                            Checklist Bangun Pagi
                        </h1>
                        <p class="text-xs text-gray-600">
                            Monitoring kedisiplinan siswa dalam bangun pagi
                        </p>
                    </div>
                </div>
                
                <!-- Action Button untuk Ortu/Admin -->
                @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'orangtua')
                <div class="mt-3 md:mt-0">
                    <a href="{{ route('paraf-ortu-bangun-pagi.index') }}" 
                       class="inline-flex items-center gap-1 bg-gradient-to-r from-blue-600 to-emerald-600 hover:from-blue-700 hover:to-emerald-700 text-white text-sm font-medium px-3 py-2 rounded-lg shadow-sm hover:shadow transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs">Paraf Ortu</span>
                    </a>
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>

        <!-- Info Pengaturan Waktu yang lebih ringkas -->
        @if($pengaturan)
        <div class="glass-card rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-sm font-medium text-gray-900">Waktu Bangun Pagi</h4>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-xs">
                <div class="flex items-center gap-2 p-2 bg-blue-50 rounded">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="font-medium">Tepat Waktu:</span>
                    <span class="text-blue-600">{{ $pengaturan->waktu_100_display }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-yellow-50 rounded">
                    <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                    <span class="font-medium">Sedikit Telat:</span>
                    <span class="text-yellow-600">{{ $pengaturan->waktu_70_display }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-red-50 rounded">
                    <div class="w-2 h-2 rounded-full bg-red-500"></div>
                    <span class="font-medium">Telat:</span>
                    <span class="text-red-600">Di luar rentang</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Messages -->
        @if(session('success'))
        <div class="glass-card rounded-xl p-3 mb-6">
            <div class="flex items-start gap-2">
                <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="glass-card rounded-xl p-3 mb-6">
            <div class="flex items-start gap-2">
                <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Data Table -->
        <div class="glass-card rounded-xl overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gray-50 p-3 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h2 class="text-sm font-medium text-gray-900">Daftar Siswa</h2>
                        <p class="text-xs text-gray-500">Total {{ $siswas->count() }} siswa</p>
                    </div>
                    <div class="flex items-center gap-1 text-xs">
                        <span class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-600">
                            Sudah: {{ $totalCheckedToday }}
                        </span>
                        <span class="px-1.5 py-0.5 bg-emerald-100 rounded text-emerald-600">
                            Tepat: {{ $onTimeCount }}
                        </span>
                        <span class="px-1.5 py-0.5 bg-red-100 rounded text-red-600">
                            Telat: {{ $lateCount }}
                        </span>
                    </div>
                </div>
            </div>

            @if($siswas->isEmpty())
            <!-- Empty State -->
            <div class="p-6 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-gray-900 mb-1">Belum Ada Siswa</h3>
                <p class="text-xs text-gray-500">Data siswa belum tersedia</p>
            </div>
            @else
            <!-- Mobile View: Card List -->
            <div class="md:hidden">
                @foreach($siswas as $siswa)
                    @php
                        $bangunPagi = $bangunPagiData->get($siswa->id);
                        $sudahChecklist = !is_null($bangunPagi);
                        $sudahParaf = $sudahChecklist && $bangunPagi->sudah_ttd_ortu;
                        $isToday = \Carbon\Carbon::parse($selectedTanggal)->isToday();
                    @endphp
                    
                <div class="border-b border-gray-100 last:border-b-0">
                    <div class="p-3 space-y-3 hover:bg-gray-50/30 transition-colors">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-600 to-emerald-600 flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">
                                        {{ substr($siswa->nama, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ $siswa->nama }}
                                    </h3>
                                    <p class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</p>
                                </div>
                            </div>
                            @if($sudahChecklist)
                            <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-xs">
                                Sudah
                            </span>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <p class="text-xs text-gray-500">Waktu</p>
                                <p class="text-sm font-medium text-gray-900">
                                    @if($sudahChecklist)
                                        {{ $bangunPagi->pukul_formatted ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                           
                        </div>

                        <!-- Actions -->
                        <div class="pt-2 border-t border-gray-100">
                            @if(!$sudahChecklist)
                            <div class="flex gap-1">
                                <button onclick="checklistNow({{ $siswa->id }})" 
                                        class="flex-1 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white text-xs font-medium py-1.5 rounded transition-all">
                                    Checklist
                                </button>
                                <button onclick="showTimeInput({{ $siswa->id }})" 
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium py-1.5 rounded transition-all">
                                    Input Waktu
                                </button>
                            </div>
                            
                            <!-- Time Input Mobile -->
                            <div id="time-input-mobile-{{ $siswa->id }}" class="hidden mt-2 space-y-1">
                                <input type="time" 
                                       id="custom-time-mobile-{{ $siswa->id }}" 
                                       value="{{ now()->format('H:i') }}"
                                       class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs">
                                <div class="flex gap-1">
                                    <button onclick="submitCustomTimeMobile({{ $siswa->id }})" 
                                            class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium py-1.5 rounded">
                                        Simpan
                                    </button>
                                    <button onclick="hideTimeInputMobile({{ $siswa->id }})" 
                                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-medium py-1.5 rounded">
                                        Batal
                                    </button>
                                </div>
                            </div>
                            
                            @elseif($sudahChecklist && !$sudahParaf)
                            <div class="flex gap-1">
                                <button onclick="editChecklist({{ $bangunPagi->id }}, {{ $siswa->id }})" 
                                        class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium py-1.5 rounded">
                                    Edit
                                </button>
                                <button onclick="hapusChecklist({{ $bangunPagi->id }}, {{ $siswa->id }})" 
                                        class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-medium py-1.5 rounded">
                                    Hapus
                                </button>
                            </div>
                            @else
                            <p class="text-xs text-center py-1 text-gray-500">
                                @if($sudahParaf)
                                    Sudah diparaf
                                @else
                                    Terkunci
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Desktop View: Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="p-3 text-left font-medium text-gray-700 text-xs uppercase tracking-wider">No</th>
                            <th class="p-3 text-left font-medium text-gray-700 text-xs uppercase tracking-wider">Siswa</th>
                            <th class="p-3 text-center font-medium text-gray-700 text-xs uppercase tracking-wider">Status</th>
                            <th class="p-3 text-center font-medium text-gray-700 text-xs uppercase tracking-wider">Waktu</th>
                          
                            <th class="p-3 text-center font-medium text-gray-700 text-xs uppercase tracking-wider">Paraf</th>
                            <th class="p-3 text-center font-medium text-gray-700 text-xs uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswas as $index => $siswa)
                            @php
                                $bangunPagi = $bangunPagiData->get($siswa->id);
                                $sudahChecklist = !is_null($bangunPagi);
                                $sudahParaf = $sudahChecklist && $bangunPagi->sudah_ttd_ortu;
                            @endphp
                        <tr class="hover:bg-gray-50/30 transition-colors border-b border-gray-100 last:border-b-0">
                            <td class="p-3 text-sm text-gray-600">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-600 to-emerald-600 flex items-center justify-center mr-2">
                                        <span class="text-white text-xs font-bold">
                                            {{ substr($siswa->nama, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $siswa->nama }}
                                        </div>
                                        <div class="text-xs text-gray-500">NIS: {{ $siswa->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                @if(!$sudahChecklist)
                                <div>
                                    <button onclick="checklistNow({{ $siswa->id }})" 
                                            class="px-2.5 py-1.5 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-600 hover:to-green-600 text-white text-xs font-medium rounded transition-all">
                                        Checklist
                                    </button>
                                </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Sudah
                                    </span>
                                @endif
                            </td>
                            <td class="p-3 text-center text-sm text-gray-900">
                                @if($sudahChecklist)
                                    {{ $bangunPagi->pukul_formatted ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                           
                            <td class="p-3 text-center">
                                @if($sudahChecklist)
                                    @if($sudahParaf)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✓
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">Belum</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($sudahChecklist && !$sudahParaf)
                                <div class="flex justify-center gap-1">
                                    <button onclick="editChecklist({{ $bangunPagi->id }}, {{ $siswa->id }})" 
                                            class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>
                                    <button onclick="hapusChecklist({{ $bangunPagi->id }}, {{ $siswa->id }})" 
                                            class="p-1 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                                @else
                                <span class="text-xs text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Waktu (lebih sederhana) -->
<div id="modalEditWaktu" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-lg p-5 w-full max-w-xs">
        <div class="mb-4">
            <h3 class="text-sm font-bold text-gray-900">Edit Waktu</h3>
            <p class="text-xs text-gray-500">Perbarui waktu bangun</p>
        </div>
        
        <div class="mb-4">
            <input type="time" id="editPukul" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-center text-sm font-mono focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div class="flex gap-2">
            <button type="button" onclick="tutupModalEdit()" 
                    class="flex-1 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Batal
            </button>
            <button type="button" onclick="submitEditWaktu()" 
                    class="flex-1 py-2 bg-gradient-to-r from-blue-500 to-emerald-500 hover:from-blue-600 hover:to-emerald-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
function getCurrentTime() {
    const now = new Date();
    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    return `${jam}:${menit}`;
}

async function checklistNow(siswaId) {
    const waktu = getCurrentTime();
    await submitChecklist(siswaId, waktu);
}

function showTimeInput(siswaId) {
    const inputDiv = document.getElementById(`time-input-mobile-${siswaId}`);
    if (inputDiv) {
        inputDiv.classList.remove('hidden');
        document.getElementById(`custom-time-mobile-${siswaId}`).value = getCurrentTime();
    }
}

function hideTimeInputMobile(siswaId) {
    const inputDiv = document.getElementById(`time-input-mobile-${siswaId}`);
    if (inputDiv) inputDiv.classList.add('hidden');
}

async function submitCustomTimeMobile(siswaId) {
    const waktu = document.getElementById(`custom-time-mobile-${siswaId}`).value;
    
    if (!waktu) {
        alert('Waktu harus diisi');
        return;
    }
    
    await submitChecklist(siswaId, waktu);
}

async function submitChecklist(siswaId, waktu) {
    const selectedDate = '{{ $selectedTanggal }}';
    
    try {
        const response = await fetch('{{ route("bangun-pagi.checklist") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                siswa_id: siswaId,
                tanggal: selectedDate, // Gunakan selectedDate bukan today
                pukul: waktu
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// Modal Edit
let currentBangunPagiId = null;
let currentSiswaId = null;

function editChecklist(bangunPagiId, siswaId) {
    currentBangunPagiId = bangunPagiId;
    currentSiswaId = siswaId;
    
    fetch(`/bangun-pagi/${bangunPagiId}/get-data`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('editPukul').value = data.data.pukul;
            } else {
                document.getElementById('editPukul').value = getCurrentTime();
            }
            document.getElementById('modalEditWaktu').classList.remove('hidden');
            document.getElementById('modalEditWaktu').classList.add('flex');
        })
        .catch(() => {
            document.getElementById('editPukul').value = getCurrentTime();
            document.getElementById('modalEditWaktu').classList.remove('hidden');
            document.getElementById('modalEditWaktu').classList.add('flex');
        });
}

function tutupModalEdit() {
    document.getElementById('modalEditWaktu').classList.add('hidden');
    document.getElementById('modalEditWaktu').classList.remove('flex');
    currentBangunPagiId = null;
    currentSiswaId = null;
}

async function submitEditWaktu() {
    const waktu = document.getElementById('editPukul').value;
    
    if (!waktu) {
        alert('Waktu harus diisi');
        return;
    }
    
    try {
        const response = await fetch(`/bangun-pagi/${currentBangunPagiId}/update-waktu`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ pukul: waktu })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            tutupModalEdit();
            setTimeout(() => location.reload(), 1000);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

async function hapusChecklist(bangunPagiId, siswaId) {
    if (!confirm('Yakin ingin menghapus?')) return;
    
    try {
        const response = await fetch(`/bangun-pagi/${bangunPagiId}/hapus-checklist`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// Fungsi untuk format time input
document.addEventListener('DOMContentLoaded', function() {
    // Set default value untuk input time
    const timeInputs = document.querySelectorAll('input[type="time"]');
    timeInputs.forEach(input => {
        if (!input.value) {
            input.value = getCurrentTime();
        }
    });
});
</script>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .text-lg {
        font-size: 1rem;
    }
}
</style>
@endsection