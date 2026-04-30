@extends('layouts.app')

@section('title', 'Keamanan Akun - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 44px 14px 16px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
        letter-spacing: 0.05em;
    }

    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.12);
        background-color: #fff;
    }

    .input-field::placeholder {
        color: #d1d5db;
        letter-spacing: normal;
    }

    .input-wrap {
        position: relative;
    }

    .toggle-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .toggle-eye:hover {
        color: #FF6900;
    }

    .label-tag {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #111827;
        margin-bottom: 8px;
        display: block;
    }

    .save-btn {
        transition: all 0.2s ease;
    }

    .save-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3);
    }

    .save-btn:active {
        transform: translateY(0);
    }

    .strength-bar div {
        height: 4px;
        border-radius: 99px;
        transition: background 0.3s ease, width 0.3s ease;
    }
</style>
@endpush

@section('content')

{{-- ======================== MODAL SUKSES (Muncul via Session Controller) ======================== --}}
@if(session('success_password'))
<div id="autoSuccessModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center transform scale-100 transition-transform duration-300">
        
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #FFF3E8;">
            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembaruan Berhasil!</h3>
        <p class="text-gray-500 text-sm mb-6">{{ session('success_password') }}</p>
        
        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden mb-2">
            <div id="autoProgressBar" class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-[2500ms] ease-linear" style="width:0%"></div>
        </div>
        <p class="text-[10px] text-gray-400 font-medium">Menutup otomatis...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Jalankan animasi progress bar
        setTimeout(() => { 
            document.getElementById('autoProgressBar').style.width = '100%'; 
        }, 50);

        // Tutup modal otomatis setelah animasi selesai (2.8 detik)
        setTimeout(() => { 
            const modal = document.getElementById('autoSuccessModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none'; 
            }, 300); // Waktu untuk efek fade out
        }, 2800);
    });
</script>
@endif

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR MANUAL ======================== --}}
    @include('pelanggan.partials.sidebar', ['currentPath' => 'profil'])

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        {{-- Header --}}
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>

            <div class="px-1 pt-8 pb-6 bg-white border-b border-gray-100">
                <h1 class="text-lg font-extrabold text-gray-900">Keamanan Akun</h1>
                <p class="text-sm text-gray-400 font-medium mt-0.5">Detail informasi keamanan Anda</p>
            </div>
        </div>

        <div class="px-10 py-8 flex justify-center">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl w-full">

                {{-- Section title --}}
                <div class="flex items-center gap-3 mb-7">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-shield-halved text-base" style="color:#FF6900;"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Perbarui Kata Sandi</h2>
                </div>

                {{-- Alert kalau ada Error Sistem (Try-Catch) --}}
                @if(session('error_password'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                    <p class="text-sm font-medium text-red-600">{{ session('error_password') }}</p>
                </div>
                @endif

                {{-- Form Update Password --}}
                <form method="POST" action="{{ route('pelanggan.password.update') }}">
                    @csrf
                    <div class="flex flex-col gap-5">

                        {{-- Kata sandi saat ini --}}
                        <div>
                            <label class="label-tag">Kata Sandi Saat Ini</label>
                            <div class="input-wrap">
                                <input type="password" name="current_password" id="currentPass" class="input-field @error('current_password') !border-red-500 @enderror" placeholder="Masukkan kata sandi saat ini" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('currentPass', this)"></i>
                            </div>
                            {{-- Pesan Error Password Lama Salah --}}
                            @error('current_password')
                                <p class="text-xs mt-1.5 font-bold text-red-500"><i class="fa-solid fa-xmark mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kata sandi baru --}}
                        <div>
                            <label class="label-tag">Kata Sandi Baru</label>
                            <div class="input-wrap">
                                <input type="password" name="new_password" id="newPass" class="input-field @error('new_password') !border-red-500 @enderror" placeholder="Masukkan kata sandi baru" oninput="checkStrength(this.value)" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('newPass', this)"></i>
                            </div>
                            
                            {{-- Indikator Kekuatan --}}
                            <div class="mt-2 flex gap-1.5 strength-bar" id="strengthBar">
                                <div id="s1" class="flex-1 bg-gray-200"></div>
                                <div id="s2" class="flex-1 bg-gray-200"></div>
                                <div id="s3" class="flex-1 bg-gray-200"></div>
                                <div id="s4" class="flex-1 bg-gray-200"></div>
                            </div>
                            <p id="strengthLabel" class="text-xs text-gray-400 mt-1 font-medium"></p>
                            
                            {{-- Pesan Error Validasi Password Baru --}}
                            @error('new_password')
                                <p class="text-xs mt-1.5 font-bold text-red-500"><i class="fa-solid fa-xmark mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi kata sandi baru --}}
                        <div>
                            <label class="label-tag">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-wrap">
                                <input type="password" name="new_password_confirmation" id="confirmPass" class="input-field" placeholder="Konfirmasi kata sandi baru" oninput="checkMatch()" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('confirmPass', this)"></i>
                            </div>
                            <p id="matchLabel" class="text-xs mt-1 font-medium hidden"></p>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 mt-8">
                        <a href="/profil" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all text-center flex items-center justify-center">
                            Batal
                        </a>
                        <button type="submit" class="save-btn flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md flex items-center justify-center gap-2" style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    function togglePass(id, icon) {
        const input = document.getElementById(id);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        icon.className = isPass
            ? 'fa-regular fa-eye-slash toggle-eye'
            : 'fa-regular fa-eye toggle-eye';
    }

    // Password strength checker
    function checkStrength(val) {
        const bars   = ['s1','s2','s3','s4'];
        const label  = document.getElementById('strengthLabel');
        let score    = 0;
        
        if (val.length >= 6)                        score++;
        if (val.length >= 10)                       score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val))              score++;

        const colors  = ['#ef4444','#f97316','#eab308','#22c55e'];
        const labels  = ['Lemah','Cukup','Kuat','Sangat Kuat'];
        const lblClrs = ['text-red-500','text-orange-500','text-yellow-500','text-green-500'];

        bars.forEach((b, i) => {
            const el = document.getElementById(b);
            el.style.backgroundColor = i < score ? colors[score - 1] : '#e5e7eb';
        });

        if (val.length === 0) {
            label.textContent = '';
        } else {
            label.textContent = labels[score - 1] || 'Lemah';
            label.className   = `text-xs mt-1 font-medium ${lblClrs[score - 1] || 'text-red-500'}`;
        }
    }

    // Match checker
    function checkMatch() {
        const newP  = document.getElementById('newPass').value;
        const confP = document.getElementById('confirmPass').value;
        const label = document.getElementById('matchLabel');
        
        if (!confP) { label.classList.add('hidden'); return; }
        label.classList.remove('hidden');
        
        if (newP === confP) {
            label.innerHTML  = '<i class="fa-solid fa-check mr-1"></i> Kata sandi cocok';
            label.className    = 'text-xs mt-1.5 font-bold text-green-500';
        } else {
            label.innerHTML  = '<i class="fa-solid fa-xmark mr-1"></i> Kata sandi tidak cocok';
            label.className    = 'text-xs mt-1.5 font-bold text-red-500';
        }
    }
</script>
@endpush