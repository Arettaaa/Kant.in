@extends('layouts.app')

@section('title', 'Masuk - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

<div class="w-full min-h-screen bg-white flex">

    {{-- LEFT: Image Panel --}}
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800&q=80"
            alt="Makanan Lezat" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,rgba(249,115,22,0.78) 0%,rgba(194,65,12,0.88) 100%);"></div>
        <div class="relative z-10 flex flex-col justify-end p-12 pb-20 text-white">
            <h2 class="text-5xl font-extrabold leading-tight mb-6">Sedang ingin<br>makan enak?</h2>
            <p class="text-lg leading-relaxed max-w-md" style="color:rgba(255,237,213,0.95);">
                Lewati antrean dan pesan makanan kampus<br>
                favoritmu lebih awal. Segar, cepat, dan siap<br>
                kapan pun kamu mau.
            </p>
        </div>
    </div>

    {{-- RIGHT: Form --}}
    <div class="w-full md:w-1/2 flex flex-col min-h-screen overflow-y-auto">

        {{-- Logo Section --}}
        <div class="flex flex-col items-center pt-10 pb-8 px-10 rounded-b-3xl" style="background-color:#FFF7ED;">
            {{-- Logo: Box Orange + Font Awesome Utensils FREE --}}
            <div class="w-16 h-16 rounded-[18px] flex items-center justify-center mb-3 shadow-sm" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-3xl text-white"></i>
            </div>
            <h1 class="text-[22px] font-extrabold text-gray-900 tracking-tight">Kant<span style="color:#FF6900;">.in</span></h1>
            <p class="text-gray-500 text-sm text-center mt-1">
                Makanan kampus segar,<br>siap saat kamu sedia.
            </p>
        </div>

        {{-- Form Section --}}
        <div class="flex-1 flex flex-col px-10 pt-7 pb-10" style="background-color:#FFFFFF;">
            <div class="space-y-4">

                {{-- Alamat Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" placeholder="mahasiswa@kampus.ac.id"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" 
                            style="background-color:#FAFAFA;">
                    </div>
                </div>

                {{-- Kata Sandi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="passwordLogin" type="password" placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-transparent transition-all duration-200" 
                            style="background-color:#FAFAFA;">
                        <button type="button" onclick="togglePassword('passwordLogin','eyeLogin')"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eyeLogin" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-end mt-1.5">
                        <a href="/admin/forgot-password" class="text-xs font-semibold hover:underline" style="color:#FF6900;">Lupa kata sandi?</a>
                    </div>
                </div>

            </div>

            <button onclick="window.location.href='/admin/pesanan'"
                class="mt-5 w-full py-3 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2 transition-all duration-200 active:scale-95"
                style="background-color:#FF6900;" onmouseover="this.style.backgroundColor='#e55f00'" onmouseout="this.style.backgroundColor='#FF6900'">
                Masuk ke Kant.in
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Belum punya akun?
                <a href="/admin/register" class="font-semibold hover:underline" style="color:#FF6900;">Daftar di sini</a>
            </p>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush