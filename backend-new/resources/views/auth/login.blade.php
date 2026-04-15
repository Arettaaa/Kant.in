@extends('layouts.app')

@section('title', 'Masuk - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

{{-- Error Alert (Hidden by default) --}}
<div id="errorAlert"
    class="fixed top-5 right-5 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg z-50 hidden transition-all duration-300">
    <div class="flex items-center">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        <p id="errorMessage" class="text-sm font-semibold"></p>
    </div>
</div>

<div class="w-full min-h-screen bg-white flex">

    {{-- LEFT: Image Panel --}}
    <div class="hidden md:flex md:w-1/2 relative min-h-screen">
        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=800&q=80" alt="Makanan Lezat"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0"
            style="background:linear-gradient(135deg,rgba(249,115,22,0.78) 0%,rgba(194,65,12,0.88) 100%);"></div>
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
            <div class="w-16 h-16 rounded-[18px] flex items-center justify-center mb-3 shadow-sm"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-3xl text-white"></i>
            </div>
            <h1 class="text-[22px] font-extrabold text-gray-900 tracking-tight">Kant<span
                    style="color:#FF6900;">.in</span></h1>
            <p class="text-gray-500 text-sm text-center mt-1">
                Makanan kampus segar,<br>siap saat kamu sedia.
            </p>
        </div>

        {{-- Form Section --}}
        {{-- Ganti bagian Form Section kamu dengan ini --}}
        <div class="flex-1 flex flex-col px-10 pt-7 pb-10" style="background-color:#FFFFFF;">

            {{-- Tampilkan Error dari Controller --}}
            @if ($errors->has('message'))
            <div class="mb-4 p-3 rounded-xl bg-red-50 border-l-4 border-red-500 text-red-600 text-sm font-medium">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ $errors->first('message') }}
            </div>
            @endif

            {{-- PENTING: Gunakan tag <form> dan @csrf --}}
                <form id="loginForm" class="space-y-4 mt-4">
                    @csrf

                    {{-- Alamat Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="loginEmail" placeholder="mahasiswa@kampus.ac.id"
                                required value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                        </div>
                    </div>

                    {{-- Kata Sandi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input id="passwordLogin" type="password" name="password" placeholder="••••••••" required
                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                            <button type="button" onclick="togglePassword('passwordLogin','eyeLogin')"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="eyeLogin" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        <div class="flex justify-end mt-1.5">
                            <a href="/lupa-sandi" class="text-xs font-semibold hover:underline"
                                style="color:#FF6900;">Lupa kata sandi?</a>
                        </div>
                    </div>

                    {{-- Tombol Masuk --}}
                    <button type="submit" id="btnLogin"
                        class="mt-8 w-full py-3 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2 transition-all duration-200 active:scale-95"
                        style="background-color:#FF6900;">
                        Masuk
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </form>

                <div class="text-center text-sm text-gray-500 mt-6 border-t border-gray-100 pt-5">
                    <p class="mb-3">Belum punya akun? Daftar sekarang:</p>
                    <div class="flex items-center justify-center gap-4">
                        <a href="/register?role=pelanggan"
                            class="font-semibold hover:text-orange-700 hover:underline transition-colors flex items-center gap-1.5"
                            style="color:#FF6900;">
                            <i class="fa-regular fa-user"></i> Sebagai Pelanggan
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="/register?role=kantin"
                            class="font-semibold hover:text-orange-700 hover:underline transition-colors flex items-center gap-1.5"
                            style="color:#FF6900;">
                            <i class="fa-solid fa-store"></i> Sebagai Pemilik Kantin
                        </a>
                    </div>
                </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('btnLogin');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');

    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Mencegah form reload bawaan HTML

        // Tampilkan animasi loading
        const originalBtnText = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        btn.style.opacity = '0.8';
        btn.style.pointerEvents = 'none';
        errorAlert.classList.add('hidden'); // Sembunyikan error sebelumnya jika ada

        // Ambil data dari form
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try {
            // Tembak endpoint API kamu
            const response = await fetch('/api/auth/sessions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // Kirim CSRF token untuk keamanan bawaan Laravel
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value 
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (!response.ok) {
                // Jika gagal login (password salah, belum diapprove, dll)
                throw new Error(result.message || 'Terjadi kesalahan saat login.');
            }

            // JIKA BERHASIL: Simpan token ke localStorage
            localStorage.setItem('auth_token', result.token);
            localStorage.setItem('user_data', JSON.stringify(result.user));

            // Redirect ke halaman beranda
            window.location.href = '/beranda';

        } catch (error) {
            // Tampilkan pesan error dari API
            errorMessage.textContent = error.message;
            errorAlert.classList.remove('hidden');
            
            // Kembalikan tombol ke semula
            btn.innerHTML = originalBtnText;
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
        }
    });
});


    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            eye.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
@endpush