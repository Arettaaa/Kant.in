@extends('layouts.app')

@section('title', 'Reset Kata Sandi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 44px 14px 16px;
        background-color: #FAFAFA;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
        letter-spacing: 0.04em;
    }
    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255,105,0,0.12);
        background-color: #fff;
    }
    .input-field::placeholder { letter-spacing: normal; color: #d1d5db; }

    .input-wrap { position: relative; }
    .toggle-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .toggle-eye:hover { color: #FF6900; }

    .strength-seg {
        height: 4px;
        border-radius: 99px;
        transition: background 0.3s ease;
        background-color: #e5e7eb;
    }

    .submit-btn { transition: all 0.2s ease; }
    .submit-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.3);
    }
    .submit-btn:active { transform: translateY(0); }

    /* Modal */
    @keyframes modalIn {
        from { opacity:0; transform:scale(0.88) translateY(12px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    @keyframes checkPop {
        0%   { transform:scale(0); opacity:0; }
        60%  { transform:scale(1.2); }
        100% { transform:scale(1); opacity:1; }
    }
    @keyframes fadeUp {
        from { opacity:0; transform:translateY(8px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .modal-card  { animation: modalIn  0.28s cubic-bezier(0.34,1.56,0.64,1); }
    .check-anim  { animation: checkPop 0.4s  cubic-bezier(0.34,1.56,0.64,1) 0.1s both; }
    .fade-up-1   { animation: fadeUp   0.3s  ease 0.3s both; }
    .fade-up-2   { animation: fadeUp   0.3s  ease 0.45s both; }
</style>
@endpush

@section('content')

{{-- ======================== MODAL BERHASIL ======================== --}}
<div id="successModal"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,0.45); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#22c55e,#16a34a);"></div>
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">

            {{-- Check icon --}}
            <div class="check-anim w-18 h-18 w-[72px] h-[72px] rounded-full flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <div class="fade-up-1 text-center">
                <h2 class="text-xl font-extrabold text-gray-900 mb-1">Kata Sandi Diperbarui!</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Kata sandi kamu berhasil direset.<br>Silakan login dengan kata sandi baru.
                </p>
            </div>

            {{-- Info box --}}
            <div class="fade-up-1 w-full flex items-center gap-3 px-4 py-3 rounded-2xl" style="background-color:#F0FDF4;">
                <i class="fa-solid fa-shield-halved text-green-500 text-sm flex-shrink-0"></i>
                <p class="text-xs text-green-700 font-semibold">Akun kamu sudah aman dengan kata sandi baru</p>
            </div>

            {{-- CTA --}}
            <div class="fade-up-2 w-full flex flex-col gap-2.5 mt-1">
                <a href="/login"
                   class="w-full py-3.5 rounded-2xl text-white font-extrabold text-sm text-center shadow-md"
                   style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                    Login Sekarang
                </a>
            </div>

        </div>
    </div>
</div>

<div class="w-full min-h-screen flex" style="background-color:#F9FAFB;">

    {{-- LEFT --}}
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80"
             alt="Food" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(249,115,22,0.82) 0%,rgba(194,65,12,0.90) 100%);"></div>
        <div class="relative z-10 flex flex-col justify-end p-12 pb-20 text-white">
            <h2 class="text-4xl font-extrabold leading-tight mb-4">Buat kata sandi<br>yang kuat ya!</h2>
            <p class="text-base leading-relaxed max-w-sm" style="color:rgba(255,237,213,0.92);">
                Gunakan kombinasi huruf besar, angka, dan simbol agar akun kamu lebih aman.
            </p>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="w-full md:w-1/2 flex flex-col min-h-screen overflow-y-auto">

        {{-- Logo --}}
        <div class="flex flex-col items-center pt-10 pb-8 px-10 rounded-b-3xl" style="background-color:#FFF7ED;">
            <div class="w-14 h-14 rounded-[16px] flex items-center justify-center mb-3 shadow-sm" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-2xl text-white"></i>
            </div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Kant<span style="color:#FF6900;">.in</span></h1>
        </div>

        {{-- Content --}}
        <div class="flex-1 flex flex-col px-10 pt-8 pb-10">

            {{-- Back --}}
            <a href="/lupa-sandi/verifikasi" class="flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-all mb-6 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            {{-- Header --}}
            <div class="mb-7">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Reset Kata Sandi</h2>
                <p class="text-sm text-gray-400 font-medium">Buat kata sandi baru yang kuat untuk akun kamu.</p>
            </div>

            {{-- Fields --}}
            <div class="flex flex-col gap-5">

                {{-- Kata sandi baru --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                    <div class="input-wrap">
                        <input type="password" id="newPass" class="input-field" placeholder="••••••••"
                               oninput="checkStrength(this.value)">
                        <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('newPass', this)"></i>
                    </div>
                    {{-- Strength bar --}}
                    <div class="flex gap-1.5 mt-2" id="strengthBar">
                    </div>
                    <p id="strengthLabel" class="text-xs text-gray-400 mt-1 font-medium"></p>
                </div>

                {{-- Konfirmasi kata sandi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                    <div class="input-wrap">
                        <input type="password" id="confirmPass" class="input-field" placeholder="••••••••"
                               oninput="checkMatch()">
                        <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('confirmPass', this)"></i>
                    </div>
                    <p id="matchLabel" class="text-xs mt-1 font-medium hidden"></p>
                </div>

            </div>

            {{-- Syarat password --}}
            <div class="mt-5 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                <p class="text-xs font-bold text-gray-500 mb-2">Kata sandi harus mengandung:</p>
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center gap-2" id="req1">
                        <i class="fa-regular fa-circle text-gray-300 text-xs flex-shrink-0"></i>
                        <span class="text-xs text-gray-400">Minimal 8 karakter</span>
                    </div>
                    <div class="flex items-center gap-2" id="req2">
                        <i class="fa-regular fa-circle text-gray-300 text-xs flex-shrink-0"></i>
                        <span class="text-xs text-gray-400">Minimal 1 huruf kapital (A-Z)</span>
                    </div>
                    <div class="flex items-center gap-2" id="req3">
                        <i class="fa-regular fa-circle text-gray-300 text-xs flex-shrink-0"></i>
                        <span class="text-xs text-gray-400">Minimal 1 angka (0-9)</span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button onclick="handleReset()"
                    class="submit-btn mt-6 w-full py-3.5 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2"
                    style="background-color:#FF6900;"
                    onmouseover="this.style.backgroundColor='#e55f00'"
                    onmouseout="this.style.backgroundColor='#FF6900'">
                <i class="fa-solid fa-key text-sm"></i>
                Simpan Kata Sandi Baru
            </button>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Toggle show/hide password
    function togglePass(id, icon) {
        const input = document.getElementById(id);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        icon.className = isPass
            ? 'fa-regular fa-eye-slash toggle-eye'
            : 'fa-regular fa-eye toggle-eye';
    }

    // Strength checker
    function checkStrength(val) {
        const bars   = ['s1','s2','s3','s4'];
        const label  = document.getElementById('strengthLabel');

        // Check requirements
        setReq('req1', val.length >= 8);
        setReq('req2', /[A-Z]/.test(val));
        setReq('req3', /[0-9]/.test(val));

        let score = 0;
        if (val.length >= 6)                         score++;
        if (val.length >= 10)                        score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val))               score++;

        const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
        const labels = ['Lemah','Cukup','Kuat','Sangat Kuat'];
        const lClrs  = ['text-red-500','text-orange-500','text-yellow-500','text-green-500'];

        bars.forEach((b, i) => {
            document.getElementById(b).style.backgroundColor = i < score ? colors[score-1] : '#e5e7eb';
        });
        label.textContent = val.length ? (labels[score-1] || 'Lemah') : '';
        label.className   = `text-xs mt-1 font-medium ${lClrs[score-1] || 'text-red-500'}`;
    }

    function setReq(id, met) {
        const el   = document.getElementById(id);
        const icon = el.querySelector('i');
        const text = el.querySelector('span');
        if (met) {
            icon.className = 'fa-solid fa-circle-check text-green-500 text-xs flex-shrink-0';
            text.className = 'text-xs text-green-600 font-semibold';
        } else {
            icon.className = 'fa-regular fa-circle text-gray-300 text-xs flex-shrink-0';
            text.className = 'text-xs text-gray-400';
        }
    }

    // Match checker
    function checkMatch() {
        const nw   = document.getElementById('newPass').value;
        const conf = document.getElementById('confirmPass').value;
        const lbl  = document.getElementById('matchLabel');
        if (!conf) { lbl.classList.add('hidden'); return; }
        lbl.classList.remove('hidden');
        if (nw === conf) {
            lbl.textContent = '✓ Kata sandi cocok';
            lbl.className   = 'text-xs mt-1 font-medium text-green-500';
        } else {
            lbl.textContent = '✕ Kata sandi tidak cocok';
            lbl.className   = 'text-xs mt-1 font-medium text-red-500';
        }
    }

    // Submit
    function handleReset() {
        const nw   = document.getElementById('newPass').value;
        const conf = document.getElementById('confirmPass').value;

        if (!nw || !conf) { alert('Semua field wajib diisi!'); return; }
        if (nw.length < 8) { alert('Kata sandi minimal 8 karakter!'); return; }
        if (nw !== conf)   { alert('Konfirmasi kata sandi tidak cocok!'); return; }

        // Tampilkan modal berhasil
        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Backdrop klik tutup modal
    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
</script>
@endpush