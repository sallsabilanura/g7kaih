@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Card --}}
        <div class="glass-card rounded-2xl shadow-lg mb-6 fade-in">
            <div class="bg-gradient-to-r from-primary-600 to-accent-green px-6 py-5 rounded-t-2xl">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-white">Nilai Manual - Olahraga</h1>
                        <p class="text-primary-100 text-sm mt-1">Manajemen Penilaian Kegiatan Olahraga</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="text-center">
                            <div class="text-xs text-primary-100">Total Data</div>
                            <div class="text-lg font-bold text-white">{{ $data->total() }}</div>
                        </div>
                        <div class="h-8 w-px bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-xs text-yellow-100">Belum Dinilai</div>
                            <div class="text-lg font-bold text-white">{{ $data->where('nilai', null)->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }}</span> dari {{ $data->total() }} data
                </div>
            </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-4 glass-card bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-accent-green p-4 rounded-xl fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-accent-green mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-gray-900 font-medium">{!! session('success') !!}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif

        {{-- Table Container --}}
        <div class="glass-card rounded-2xl shadow-lg overflow-hidden fade-in">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-primary-500/5 to-accent-green/5">
                <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-700">
                    <div class="col-span-1">No</div>
                    <div class="col-span-3">Siswa</div>
                    <div class="col-span-2">Tanggal & Waktu</div>
                    <div class="col-span-1 text-center">Durasi</div>
                    <div class="col-span-1 text-center">Status</div>
                    <div class="col-span-2 text-center">Nilai</div>
                    <div class="col-span-2 text-center">Aksi</div>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-gray-100">
                @forelse($data as $index => $item)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150 {{ !$item->nilai ? 'bg-yellow-50/50' : '' }}">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        {{-- No --}}
                        <div class="col-span-1 text-sm text-gray-600 font-medium">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}
                        </div>

                        {{-- Siswa --}}
                        <div class="col-span-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-green rounded-lg flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($item->siswa->nama_lengkap ?: $item->siswa->nama ?? 'S', 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-medium text-gray-900 text-sm truncate">
                                        {{ $item->siswa->nama_lengkap ?: $item->siswa->nama ?? 'Siswa tidak ditemukan' }}
                                    </div>
                                    <div class="text-xs text-gray-500">NIS: {{ $item->siswa->nis ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal & Waktu --}}
                        <div class="col-span-2">
                            <div class="text-sm text-gray-900">{{ $item->tanggal->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $item->mulai_olahraga }} - {{ $item->selesai_olahraga }}</div>
                        </div>

                        {{-- Durasi --}}
                        <div class="col-span-1 text-center">
                            <span class="inline-flex px-2 py-1 bg-green-100 text-accent-green rounded-lg text-xs font-semibold">
                                {{ $item->durasi }}
                            </span>
                        </div>

                        {{-- Status --}}
                        <div class="col-span-1 text-center">
                            @if($item->nilai)
                            <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                Sudah
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                                </svg>
                                Belum
                            </span>
                            @endif
                        </div>

                        {{-- Nilai --}}
                        <div class="col-span-2 text-center">
                            @if($item->nilai)
                            <div class="bg-gradient-to-br from-primary-500 to-accent-green text-white px-3 py-2 rounded-lg shadow-sm">
                                <div class="text-lg font-bold">{{ $item->nilai }}</div>
                                <div class="text-xs text-primary-100">/100</div>
                            </div>
                            <div class="mt-1 text-xs font-medium {{ $item->kategori_nilai == 'sangat_baik' ? 'text-emerald-600' : ($item->kategori_nilai == 'baik' ? 'text-blue-600' : ($item->kategori_nilai == 'cukup' ? 'text-yellow-600' : 'text-red-600')) }}">
                                {{ ucfirst(str_replace('_', ' ', $item->kategori_nilai)) }}
                            </div>
                            @else
                            <div class="bg-gray-100 text-gray-400 px-3 py-2 rounded-lg">
                                <div class="text-lg font-bold">--</div>
                                <div class="text-xs">/100</div>
                            </div>
                            @endif
                        </div>

                        {{-- Aksi --}}
                        <div class="col-span-2">
                            <div class="flex items-center justify-center gap-2">
                                @if(!$item->nilai)
                                <button type="button" 
                                    onclick="openModal({{ $item->id }}, '{{ $item->siswa ? addslashes($item->siswa->nama_lengkap ?: $item->siswa->nama) : 'Siswa' }}', '{{ $item->tanggal->format('d/m/Y') }}', '{{ $item->nilai ?? '' }}')"
                                    class="bg-gradient-to-r from-accent-green to-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:shadow-md transition-all duration-300 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Nilai
                                </button>
                                @else
                                <button type="button" 
                                    onclick="openModal({{ $item->id }}, '{{ $item->siswa ? addslashes($item->siswa->nama_lengkap ?: $item->siswa->nama) : 'Siswa' }}', '{{ $item->tanggal->format('d/m/Y') }}', '{{ $item->nilai ?? '' }}', '{{ addslashes($item->catatan_admin ?? '') }}')"
                                    class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:shadow-md transition-all duration-300 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('nilai-manual-olahraga.reset', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin mereset nilai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:shadow-md transition-all duration-300 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Reset
                                    </button>
                                </form>
                                @endif
                            </div>
                            @if($item->video_path)
                            <div class="mt-2 flex justify-center">
                                <a href="{{ $item->video_url }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-xs flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Lihat Video
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Catatan Admin --}}
                    @if($item->catatan_admin)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-start gap-2">
                            <svg class="w-3 h-3 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-xs text-gray-600"><span class="font-semibold">Catatan:</span> {{ $item->catatan_admin }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-50 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Data</h3>
                    <p class="text-gray-500 text-sm">Belum ada data olahraga yang perlu dinilai.</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($data->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/50 to-blue-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $data->firstItem() ?? 0 }} - {{ $data->lastItem() ?? 0 }} dari {{ $data->total() }} data
                    </div>
                    <div class="flex items-center space-x-1">
                        {{ $data->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Beri/Edit Nilai --}}
<div id="nilaiModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Backdrop --}}
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeModal()"></div>
        
        {{-- Modal Content --}}
        <div class="relative inline-block w-full max-w-md p-0 overflow-hidden text-left align-middle transition-all transform glass-card shadow-2xl rounded-2xl">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-primary-600 to-accent-green px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-white" id="modalTitle">Beri Nilai</h3>
                    </div>
                    <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form id="nilaiForm" method="POST" action="">
                @csrf
                <div class="px-6 py-5">
                    {{-- Info Siswa --}}
                    <div class="mb-4 p-3 bg-gradient-to-r from-primary-50 to-blue-50 rounded-lg">
                        <p class="text-xs text-gray-500 font-semibold mb-1">Siswa</p>
                        <h4 class="text-base font-bold text-gray-900" id="modalSiswaName">-</h4>
                    </div>

                    {{-- Info Tanggal --}}
                    <div class="mb-4 p-3 bg-gradient-to-r from-accent-green/10 to-emerald-50 rounded-lg">
                        <p class="text-xs text-gray-500 font-semibold mb-1">Tanggal Olahraga</p>
                        <p class="text-sm font-medium text-gray-800" id="modalTanggal">-</p>
                    </div>

                    {{-- Input Nilai --}}
                    <div class="mb-4">
                        <label for="nilaiInput" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nilai <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="nilaiInput" 
                               name="nilai" 
                               min="0" 
                               max="100" 
                               required
                               placeholder="0-100"
                               class="w-full px-4 py-3 text-xl font-bold text-center border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300">
                        <p class="mt-1 text-xs text-gray-500 text-center">Masukkan nilai antara 0 - 100</p>
                    </div>

                    {{-- Catatan Admin --}}
                    <div class="mb-2">
                        <label for="catatanAdmin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan Admin (Opsional)
                        </label>
                        <textarea id="catatanAdmin" 
                                  name="catatan_admin"
                                  rows="2"
                                  placeholder="Masukkan catatan untuk siswa..."
                                  class="w-full px-3 py-2 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-300"></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-gray-50 flex gap-2">
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 px-3 py-2.5 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="flex-1 px-3 py-2.5 bg-gradient-to-r from-primary-500 to-accent-green text-white rounded-lg text-sm font-semibold hover:from-primary-600 hover:to-accent-green/90 transition-all duration-300 shadow hover:shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
// Base URL untuk form action
const baseUrl = '{{ route("nilai-manual-olahraga.simpan", ":id") }}';

// Open Modal
function openModal(id, siswaName, tanggal, currentNilai, catatanAdmin = '') {
    const modal = document.getElementById('nilaiModal');
    const form = document.getElementById('nilaiForm');
    const modalTitle = document.getElementById('modalTitle');
    
    // Set form action dengan ID yang benar
    form.action = baseUrl.replace(':id', id);
    
    // Set modal content
    document.getElementById('modalSiswaName').textContent = siswaName;
    document.getElementById('modalTanggal').textContent = tanggal;
    document.getElementById('nilaiInput').value = currentNilai || '';
    document.getElementById('catatanAdmin').value = catatanAdmin || '';
    
    // Update title berdasarkan apakah edit atau beri nilai baru
    modalTitle.textContent = currentNilai ? 'Edit Nilai' : 'Beri Nilai';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Focus input
    setTimeout(() => {
        document.getElementById('nilaiInput').focus();
        document.getElementById('nilaiInput').select();
    }, 100);
}

// Close Modal
function closeModal() {
    const modal = document.getElementById('nilaiModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('nilaiForm').reset();
}

// Form validation
document.getElementById('nilaiForm').addEventListener('submit', function(e) {
    const nilaiInput = document.getElementById('nilaiInput');
    const nilai = parseInt(nilaiInput.value);
    
    if (isNaN(nilai) || nilai < 0 || nilai > 100) {
        e.preventDefault();
        nilaiInput.classList.add('border-red-500', 'ring-2', 'ring-red-500/20');
        alert('Nilai harus berupa angka antara 0 - 100!');
        nilaiInput.focus();
        nilaiInput.select();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Menyimpan...
    `;
    submitBtn.disabled = true;
    
    // Reset button state if form submission fails
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 5000);
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Auto hide alert after 5 seconds
setTimeout(() => {
    const alert = document.querySelector('[role="alert"]');
    if (alert) {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);
</script>
@endsection