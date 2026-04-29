@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 16px 14px 44px;
        background-color: #FAFAFA;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.12);
        background-color: #fff;
    }

    .input-wrap {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 16px;
        height: 16px;
    }

    .submit-btn {
        transition: all 0.2s ease;
    }

    .submit-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .tab-method {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .tab-method.active {
        background-color: #FF6900;
        color: white;
    }
</style>
@endpush

@section('content')

<div class="w-full min-h-screen flex" style="background-color:#F9FAFB;">

    {{-- LEFT: Illustration --}}
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80" alt="Food"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0"
            style="background:linear-gradient(135deg,rgba(249,115,22,0.82) 0%,rgba(194,65,12,0.90) 100%);"></div>
        <div class="relative z-10 flex flex-col justify-end p-12 pb-20 text-white">
            <h2 class="text-4xl font-extrabold leading-tight mb-4">Lupa kata sandi?<br>Tenang aja!</h2>
            <p class="text-base leading-relaxed max-w-sm" style="color:rgba(255,237,213,0.92);">
                Kami akan bantu kamu reset kata sandi lewat email atau nomor HP yang terdaftar.
            </p>
        </div>
    </div>

    {{-- RIGHT: Form --}}
    <div class="w-full md:w-1/2 flex flex-col min-h-screen overflow-y-auto">

        {{-- Logo --}}
        <div class="flex flex-col items-center pt-10 pb-8 px-10 rounded-b-3xl" style="background-color:#FFF7ED;">
            <div class="w-14 h-14 rounded-[16px] flex items-center justify-center mb-3 shadow-sm"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-2xl text-white"></i>
            </div>
            <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Kant<span style="color:#FF6900;">.in</span>
            </h1>
        </div>

        {{-- Content --}}
        <div class="flex-1 flex flex-col px-10 pt-8 pb-10">

            {{-- Back link --}}
            <a href="/login"
                class="flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-all mb-6 w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Login
            </a>

            {{-- Header --}}
            <div class="mb-7">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-1">Lupa Kata Sandi</h2>
                <p class="text-sm text-gray-400 font-medium">Masukkan email yang terdaftar untuk melanjutkan.</p>
            </div>

            {{-- Method toggle
            <div class="flex rounded-xl p-1 mb-6" style="background-color:#F3F4F6;">
                <button id="tabEmail" onclick="switchMethod('email')"
                    class="tab-method active flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200">
                    Email
                </button>
                <button id="tabHP" onclick="switchMethod('hp')"
                    class="tab-method flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200">
                    No. HP
                </button>
            </div> --}}

            {{-- Email input --}}
            <div id="inputEmail">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                <div class="input-wrap">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input type="email" id="emailInput" class="input-field" placeholder="mahasiswa@kampus.ac.id">
                </div>
            </div>

            {{-- HP input (hidden by default)
            <div id="inputHP" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP</label>
                <div class="input-wrap">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <input type="tel" id="hpInput" class="input-field" placeholder="+62 812-3456-7890">
                </div>
            </div> --}}

            {{-- Submit --}}
            <button onclick="kirimOTP()"
                class="submit-btn mt-6 w-full py-3.5 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2"
                style="background-color:#FF6900;" onmouseover="this.style.backgroundColor='#e55f00'"
                onmouseout="this.style.backgroundColor='#FF6900'">
                <i class="fa-solid fa-paper-plane text-sm"></i>
                Lanjutkan
            </button>

            <p class="text-center text-sm text-gray-400 mt-5">
                Ingat kata sandi?
                <a href="/login" class="font-semibold hover:underline" style="color:#FF6900;">Masuk sekarang</a>
            </p>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    let activeMethod = 'email';

    function switchMethod(method) {
        activeMethod = method;
        const tabE   = document.getElementById('tabEmail');
        const tabH   = document.getElementById('tabHP');
        const inpE   = document.getElementById('inputEmail');
        const inpH   = document.getElementById('inputHP');

        if (method === 'email') {
            tabE.classList.add('active');
            tabH.classList.remove('active');
            tabH.className = 'tab-method flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200';
            inpE.classList.remove('hidden');
            inpH.classList.add('hidden');
        } else {
            tabH.classList.add('active');
            tabE.classList.remove('active');
            tabE.className = 'tab-method flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200';
            inpH.classList.remove('hidden');
            inpE.classList.add('hidden');
        }
    }

    function kirimOTP() {
    const email = document.getElementById('emailInput').value.trim();

    if (!email) {
        alert('Masukkan email terlebih dahulu!');
        return;
    }

    const btn = document.querySelector('button[onclick="kirimOTP()"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memeriksa...';

    fetch('/lupa-sandi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ email }),
    })
    .then(r => r.json().then(data => ({ ok: r.ok, data })))
    .then(({ ok, data }) => {
        if (ok) {
            window.location.href = '/lupa-sandi/reset';
        } else {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-arrow-right text-sm"></i> Lanjutkan';
            alert(data.message ?? 'Email tidak ditemukan.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-arrow-right text-sm"></i> Lanjutkan';
        alert('Terjadi kesalahan.');
    });
}
</script>
@endpush