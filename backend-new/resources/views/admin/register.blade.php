@extends('layouts.app')

@section('title', 'Daftar - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

{{-- Success Modal --}}
<div id="successModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center">
        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Akun Berhasil Dibuat!</h3>
        <p class="text-gray-500 text-sm mb-6">Selamat datang di Kant.in. Anda akan diarahkan ke dashboard.</p>
        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
            <div id="progressBar" class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-[2500ms] ease-linear" style="width:0%"></div>
        </div>
    </div>
</div>

<div class="w-full h-screen bg-white flex overflow-hidden">

    {{-- LEFT: Form (Scrollable) --}}
    <div class="w-full md:w-1/2 flex flex-col h-screen overflow-y-auto">

        {{-- Logo Section --}}
        <div class="flex flex-col items-center pt-10 pb-8 px-10 rounded-b-3xl" style="background-color:#FFF7ED;">
            {{-- Logo: Box Orange + Font Awesome Utensils FREE --}}
            <div class="w-16 h-16 rounded-[18px] flex items-center justify-center mb-3 shadow-sm" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-3xl text-white"></i>
            </div>
            <h1 class="text-[22px] font-extrabold text-gray-900 tracking-tight">Gabung Kant<span style="color:#FF6900;">.in</span></h1>
            <p class="text-gray-500 text-sm mt-1">Buat akun untuk mulai memesan.</p>
        </div>

        {{-- Form Section --}}
        <div class="flex-1 flex flex-col px-10 pt-7 pb-10" style="background-color:#FFFFFF;">

            {{-- Role Toggle --}}
            <div class="flex rounded-xl p-1 mb-5" style="background-color:#F3F4F6;">
                <button id="tabPelanggan" onclick="switchTab('pelanggan')"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200">
                    Pelanggan
                </button>
                <button id="tabKantin" onclick="switchTab('kantin')"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg text-white transition-all duration-200"
                    style="background-color:#FF6900;">
                    Pemilik Kantin
                </button>
            </div>

            {{-- Fields --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" placeholder="Budi Santoso"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" style="background-color:#FAFAFA;">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" placeholder="mahasiswa@kampus.ac.id"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" style="background-color:#FAFAFA;">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="passwordRegister" type="password" placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" style="background-color:#FAFAFA;">
                        <button type="button" onclick="togglePassword('passwordRegister','eyeRegister')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eyeRegister" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="namaKantinField">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kantin</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <input type="text" placeholder="Warung Bu Ani"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" style="background-color:#FAFAFA;">
                    </div>
                </div>
            </div>

            <button onclick="handleRegister()"
                class="mt-5 w-full py-3 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2 transition-all duration-200 active:scale-95 hover:opacity-90"
                style="background-color:#FF6900;" onmouseover="this.style.backgroundColor='#e55f00'" onmouseout="this.style.backgroundColor='#FF6900'">
                Buat Akun
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Sudah punya akun?
                <a href="/admin/login" class="font-semibold hover:underline" style="color:#FF6900;">Masuk</a>
            </p>
        </div>
    </div>

    {{-- RIGHT: Image (Fixed) --}}
    <div class="hidden md:flex md:w-1/2 relative h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80"
            alt="Makanan" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(249,115,22,0.78) 0%,rgba(194,65,12,0.88) 100%);"></div>
        <div class="relative z-10 flex flex-col items-end justify-end p-12 pb-20 text-right text-white w-full">
            <h2 class="text-5xl font-extrabold leading-tight mb-6">Gabung Kant.in<br>Hari Ini</h2>
            <p class="text-lg leading-relaxed max-w-md" style="color:rgba(255,237,213,0.95);">
                Temukan makanan kampus yang lezat,<br>
                kumpulkan poin, dan jangan pernah antre lagi.<br>
                Makanan impianmu menanti.
            </p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const tabP = document.getElementById('tabPelanggan');
        const tabK = document.getElementById('tabKantin');
        const nkf  = document.getElementById('namaKantinField');
        if (tab === 'pelanggan') {
            tabP.style.cssText = 'background-color:#FF6900;color:#fff;';
            tabP.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200';
            tabK.style.cssText = '';
            tabK.className = 'flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200';
            nkf.style.display = 'none';
            setTimeout(() => { window.location.href = '/register-pelanggan'; }, 300);
        } else {
            tabK.style.cssText = 'background-color:#FF6900;color:#fff;';
            tabK.className = 'flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200';
            tabP.style.cssText = '';
            tabP.className = 'flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all duration-200';
            nkf.style.display = 'block';
        }
    }

    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye   = document.getElementById(eyeId);
        if (input.type === 'password') {
            input.type = 'text';
            eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
        } else {
            input.type = 'password';
            eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        }
    }

    function handleRegister() {
        const modal = document.getElementById('successModal');
        const bar   = document.getElementById('progressBar');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => { bar.style.width = '100%'; }, 50);
        setTimeout(() => { window.location.href = '/admin/pesanan'; }, 2800);
    }
</script>
@endpush