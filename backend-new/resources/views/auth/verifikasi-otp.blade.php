@extends('layouts.app')

@section('title', 'Verifikasi OTP - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .otp-input {
        width: 56px;
        height: 60px;
        text-align: center;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
        background-color: #F9FAFB;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        outline: none;
        transition: all 0.2s ease;
        caret-color: #FF6900;
    }
    .otp-input:focus {
        border-color: #FF6900;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(255,105,0,0.12);
        transform: scale(1.04);
    }
    .otp-input.filled {
        border-color: #FF6900;
        background-color: #FFF7ED;
        color: #FF6900;
    }
    .submit-btn {
        transition: all 0.2s ease;
    }
    .submit-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.3);
    }
    .submit-btn:active { transform: translateY(0); }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60%  { transform: translateX(-6px); }
        40%, 80%  { transform: translateX(6px); }
    }
    .shake { animation: shake 0.4s ease; }
</style>
@endpush

@section('content')

<div class="w-full min-h-screen flex" style="background-color:#F9FAFB;">

    {{-- LEFT --}}
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80"
             alt="Food" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(249,115,22,0.82) 0%,rgba(194,65,12,0.90) 100%);"></div>
        <div class="relative z-10 flex flex-col justify-end p-12 pb-20 text-white">
            <h2 class="text-4xl font-extrabold leading-tight mb-4">Cek inbox kamu,<br>kode OTP sudah dikirim!</h2>
            <p class="text-base leading-relaxed max-w-sm" style="color:rgba(255,237,213,0.92);">
                Masukkan 6 digit kode yang kami kirimkan ke email atau nomor HP kamu.
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
            <a href="/lupa-sandi" class="flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-all mb-6 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            {{-- Header --}}
            <div class="mb-2">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Verifikasi OTP</h2>
                <p class="text-sm text-gray-400 font-medium">Kode OTP telah dikirim ke <span class="font-bold text-gray-600">ma***@kampus.ac.id</span></p>
            </div>

            {{-- Timer --}}
            <div class="flex items-center gap-1.5 mt-2 mb-8">
                <i class="fa-regular fa-clock text-xs" style="color:#FF6900;"></i>
                <span class="text-sm font-bold" style="color:#FF6900;">Berlaku selama <span id="countdown">05:00</span></span>
            </div>

            {{-- OTP Inputs --}}
            <div id="otpContainer" class="flex items-center justify-between gap-2 mb-8">
                <input type="text" maxlength="1" class="otp-input" id="otp1" oninput="moveNext(this, 'otp2')" onkeydown="movePrev(event, null, 'otp1')">
                <input type="text" maxlength="1" class="otp-input" id="otp2" oninput="moveNext(this, 'otp3')" onkeydown="movePrev(event, 'otp1', 'otp2')">
                <input type="text" maxlength="1" class="otp-input" id="otp3" oninput="moveNext(this, 'otp4')" onkeydown="movePrev(event, 'otp2', 'otp3')">
                <input type="text" maxlength="1" class="otp-input" id="otp4" oninput="moveNext(this, 'otp5')" onkeydown="movePrev(event, 'otp3', 'otp4')">
                <input type="text" maxlength="1" class="otp-input" id="otp5" oninput="moveNext(this, 'otp6')" onkeydown="movePrev(event, 'otp4', 'otp5')">
                <input type="text" maxlength="1" class="otp-input" id="otp6" oninput="moveNext(this, null)"   onkeydown="movePrev(event, 'otp5', 'otp6')">
            </div>

            {{-- Submit --}}
            <button onclick="verifikasiOTP()"
                    class="submit-btn w-full py-3.5 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2"
                    style="background-color:#FF6900;"
                    onmouseover="this.style.backgroundColor='#e55f00'"
                    onmouseout="this.style.backgroundColor='#FF6900'">
                <i class="fa-solid fa-shield-halved text-sm"></i>
                Verifikasi Sekarang
            </button>

            {{-- Resend --}}
            <p class="text-center text-sm text-gray-400 mt-5">
                Tidak menerima kode?
                <button id="resendBtn" onclick="resendOTP()" class="font-semibold hover:underline disabled:opacity-40" style="color:#FF6900;">
                    Kirim ulang
                </button>
            </p>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ---- OTP input navigation ----
    function moveNext(current, nextId) {
        const val = current.value.replace(/\D/g, '');
        current.value = val;
        if (val) {
            current.classList.add('filled');
            if (nextId) document.getElementById(nextId).focus();
        } else {
            current.classList.remove('filled');
        }
    }

    function movePrev(e, prevId, currentId) {
        if (e.key === 'Backspace' && !document.getElementById(currentId).value && prevId) {
            document.getElementById(prevId).focus();
        }
    }

    // ---- Countdown timer ----
    let seconds = 300;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            countdownEl.textContent = '00:00';
            countdownEl.style.color = '#ef4444';
            document.getElementById('resendBtn').disabled = false;
            return;
        }
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        countdownEl.textContent = `${m}:${s}`;
        if (seconds <= 60) countdownEl.style.color = '#ef4444';
    }, 1000);

    // ---- Verify ----
    function verifikasiOTP() {
        const code = ['otp1','otp2','otp3','otp4','otp5','otp6']
            .map(id => document.getElementById(id).value).join('');

        if (code.length < 6) {
            // Shake animation
            document.getElementById('otpContainer').classList.add('shake');
            setTimeout(() => document.getElementById('otpContainer').classList.remove('shake'), 400);
            return;
        }
        window.location.href = '/lupa-sandi/reset';
    }

    // ---- Resend ----
    function resendOTP() {
        seconds = 300;
        countdownEl.style.color = '#FF6900';
        document.getElementById('resendBtn').disabled = true;
        // Reset inputs
        ['otp1','otp2','otp3','otp4','otp5','otp6'].forEach(id => {
            const el = document.getElementById(id);
            el.value = '';
            el.classList.remove('filled');
        });
        document.getElementById('otp1').focus();
    }
</script>
@endpush