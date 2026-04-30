@extends('layouts.app')

@section('title', 'Pengaturan Kata Sandi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    .input-custom {
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
    }

    .input-custom:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 4px rgba(255, 105, 0, 0.1);
    }

    .notif-dropdown {
        position: absolute;
        top: 60px;
        width: 340px;
        background: white;
        border-radius: 28px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        z-index: 100;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start font-sans">

    {{-- ======================== SIDEBAR ======================== --}}
    @include('admin_global.partials.sidebar')

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        @include('admin_global.partials.topbar')

        {{-- FORM AREA (COMPACT & CENTERED) --}}
        <div class="p-10 flex flex-col items-center justify-start flex-1 bg-[#F9FAFB] text-start">
            {{-- Header Judul --}}
            <div class="w-full max-w-xl mb-8 flex items-center gap-4 text-start">
                <a href="/admin/global/pengaturan"
                    class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left text-xs text-start"></i>
                </a>
                <h3 class="text-2xl font-black text-gray-900 tracking-tight text-start">Pengaturan Kata Sandi</h3>
            </div>

            {{-- CARD FORM --}}
            <div class="w-full max-w-xl bg-white p-12 rounded-[44px] border border-gray-100 shadow-sm text-start">

                {{-- Success Message --}}
                @if(session('success_password'))
                <div
                    class="mb-8 bg-green-50 text-green-700 px-6 py-4 rounded-2xl font-bold text-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success_password') }}
                </div>
                @endif

                <form action="{{ route('admin.global.keamanan.update') }}" method="POST" class="space-y-8 text-start">
                    @csrf

                    {{-- Kata Sandi Saat Ini --}}
                    <div class="text-start">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 block ml-2">Kata
                            Sandi Saat Ini</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="current_password" id="current_password"
                                placeholder="Masukkan kata sandi saat ini"
                                class="input-custom w-full pl-14 pr-14 py-5 rounded-[22px] bg-[#F9FAFB] text-[14px] font-bold text-gray-700 outline-none">
                            <button type="button" onclick="toggleEye('current_password', 'eye-current')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#FF6900] transition-all focus:outline-none">
                                <i id="eye-current" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                        @error('current_password')
                        <p class="text-red-500 text-xs font-bold mt-2 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kata Sandi Baru --}}
                    <div class="text-start">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 block ml-2">Kata
                            Sandi Baru</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="new_password" id="new_password"
                                placeholder="Masukkan kata sandi baru" oninput="checkStrength(this.value); checkMatch()"
                                class="input-custom w-full pl-14 pr-14 py-5 rounded-[22px] bg-[#F9FAFB] text-[14px] font-bold text-gray-700 outline-none">
                            <button type="button" onclick="toggleEye('new_password', 'eye-new')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#FF6900] transition-all focus:outline-none">
                                <i id="eye-new" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>

                        {{-- Strength Bar --}}
                        <div class="mt-3 ml-2 space-y-2" id="strengthBox" style="display:none;">
                            <div class="flex gap-1.5">
                                <div id="bar1" class="h-1.5 flex-1 rounded-full bg-gray-100 transition-all"></div>
                                <div id="bar2" class="h-1.5 flex-1 rounded-full bg-gray-100 transition-all"></div>
                                <div id="bar3" class="h-1.5 flex-1 rounded-full bg-gray-100 transition-all"></div>
                                <div id="bar4" class="h-1.5 flex-1 rounded-full bg-gray-100 transition-all"></div>
                            </div>
                            <div class="flex gap-4 text-[11px] font-bold text-gray-400">
                                <span id="req-len" class="flex items-center gap-1"><i
                                        class="fa-solid fa-circle text-[6px]"></i> Min. 8 karakter</span>
                                <span id="req-upper" class="flex items-center gap-1"><i
                                        class="fa-solid fa-circle text-[6px]"></i> Huruf besar</span>
                                <span id="req-num" class="flex items-center gap-1"><i
                                        class="fa-solid fa-circle text-[6px]"></i> Angka</span>
                            </div>
                        </div>

                        @error('new_password')
                        <p class="text-red-500 text-xs font-bold mt-2 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Kata Sandi Baru --}}
                    <div class="text-start">
                        <label
                            class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 block ml-2">Konfirmasi
                            Kata Sandi Baru</label>
                        <div class="relative">
                            <i class="fa-solid fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                placeholder="Ketik ulang kata sandi baru" oninput="checkMatch()"
                                class="input-custom w-full pl-14 pr-14 py-5 rounded-[22px] bg-[#F9FAFB] text-[14px] font-bold text-gray-700 outline-none">
                            <button type="button" onclick="toggleEye('new_password_confirmation', 'eye-confirm')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-[#FF6900] transition-all focus:outline-none">
                                <i id="eye-confirm" class="fa-solid fa-eye-slash"></i>
                            </button>
                        </div>
                        {{-- Match Feedback --}}
                        <p id="matchMsg" class="text-xs font-bold mt-2 ml-2 hidden"></p>
                    </div>

                    {{-- Button Group --}}
                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit"
                            class="px-8 py-4 bg-[#FF6900] text-white rounded-[22px] font-black text-[13px] flex items-center gap-3 shadow-lg shadow-orange-100 hover:scale-[1.03] transition-all uppercase tracking-widest">
                            <i class="fa-solid fa-rotate"></i> Perbarui Kata Sandi
                        </button>
                        <a href="{{ route('admin.global.pengaturan') }}"
                            class="px-8 py-4 bg-white border border-gray-100 text-gray-400 rounded-[22px] font-black text-[13px] flex items-center justify-center hover:bg-gray-50 transition-all uppercase tracking-widest">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    // Toggle show/hide password
function toggleEye(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.className = isHidden ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
}

// Password strength checker
function checkStrength(val) {
    const box = document.getElementById('strengthBox');
    box.style.display = val.length > 0 ? 'block' : 'none';

    const hasLen   = val.length >= 8;
    const hasUpper = /[A-Z]/.test(val);
    const hasLower = /[a-z]/.test(val);
    const hasNum   = /[0-9]/.test(val);

    // Update requirement labels
    setReq('req-len',   hasLen);
    setReq('req-upper', hasUpper);
    setReq('req-num',   hasNum);

    // Strength score 0-4
    const score = [hasLen, hasUpper, hasLower, hasNum].filter(Boolean).length;
    const colors = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
    const color  = colors[score - 1] ?? 'bg-gray-100';

    ['bar1','bar2','bar3','bar4'].forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'h-1.5 flex-1 rounded-full transition-all ' + (i < score ? color : 'bg-gray-100');
    });
}

function setReq(id, passed) {
    const el = document.getElementById(id);
    el.classList.toggle('text-green-500', passed);
    el.classList.toggle('text-gray-400',  !passed);
}

// Confirm password match checker
function checkMatch() {
    const pw      = document.getElementById('new_password').value;
    const confirm = document.getElementById('new_password_confirmation').value;
    const msg     = document.getElementById('matchMsg');

    if (confirm.length === 0) { msg.classList.add('hidden'); return; }

    msg.classList.remove('hidden');
    if (pw === confirm) {
        msg.textContent = '✓ Kata sandi cocok';
        msg.className = 'text-xs font-bold mt-2 ml-2 text-green-500';
    } else {
        msg.textContent = '✗ Kata sandi tidak cocok';
        msg.className = 'text-xs font-bold mt-2 ml-2 text-red-500';
    }
}

    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('realtimeDate');
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);
    });

    function toggleDropdown() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    window.onclick = function(e) {
        if (!document.getElementById('bellWrapper').contains(e.target)) {
            document.getElementById('notifDropdown').classList.add('hidden');
        }
    }
</script>
@endpush
@endsection