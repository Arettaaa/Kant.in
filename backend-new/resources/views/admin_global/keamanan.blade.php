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

    {{-- ======================== SIDEBAR GLOBAL ADMIN ======================== --}}
    <aside
        class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-12 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col text-start">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mt-1">Global Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/global/dasbor"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Transaksi
            </a>
            <a href="/admin/global/notifikasi"
                class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg> Notifikasi</div>
                <span
                    class="w-5 h-5 bg-[#FF6900] text-white text-[10px] flex items-center justify-center rounded-full shadow-sm font-black">2</span>
            </a>

            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] text-start">
                Sistem</div>
            <a href="/admin/global/pengaturan"
                class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all text-start"
                style="background-color: #FFF3E8; color: #FF6900 !important;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Pengaturan
            </a>
        </nav>

        <a href="/logout"
            class="flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 mt-auto text-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg> Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">

        <header
            class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm text-start">
            <div class="text-start">
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1 text-start">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold text-start">Memuat Tanggal...</p>
            </div>

            <div class="flex items-center gap-6 text-start">
                <div class="relative text-start" id="bellWrapper">
                    <button onclick="toggleDropdown()"
                        class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all focus:outline-none">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <span id="bellBadge" class="absolute top-2.5 right-3 w-3 h-3 border-2 border-white rounded-full"
                            style="background-color:#FF6900;"></span>
                    </button>
                    <div id="notifDropdown" class="notif-dropdown hidden text-start" style="right:-20px;">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50 text-start">
                            <span class="text-sm font-extrabold text-gray-900 text-start">Notifikasi Terbaru</span>
                            <span class="text-xs font-black px-2.5 py-1 rounded-xl text-start"
                                style="background-color:#FFF3E8; color:#FF6900;">2 Baru</span>
                        </div>
                        <div class="p-5 flex items-start gap-3 text-start">
                            <div
                                class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 flex-shrink-0 text-start">
                                <i class="fa-solid fa-store text-sm text-start"></i>
                            </div>
                            <div class="flex-1 text-start">
                                <p class="text-sm font-extrabold text-gray-900 leading-tight text-start">Pendaftaran
                                    Kantin Baru</p>
                                <p class="text-[11px] text-gray-400 mt-1 text-start">10 mnt lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-10 w-[1px] bg-gray-100 text-start"></div>

                <div class="flex items-center gap-4 group text-start">
                    <div class="text-right text-start">
                        <p
                            class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900] text-start">
                            Admin Utama</p>
                        <p class="text-[10px] font-bold text-[#FF6900] uppercase tracking-widest text-start">Pusat
                            Kendali</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-[#FFF3E8] flex items-center justify-center text-[#FF6900] font-black text-lg border border-orange-100 shadow-sm transition-all group-hover:bg-[#FF6900] group-hover:text-white">
                        A</div>
                </div>
            </div>
        </header>

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
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    }
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