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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h3 id="modalTitle" class="text-xl font-bold text-gray-800 mb-2">Akun Berhasil Dibuat!</h3>
        <p id="modalDesc" class="text-gray-500 text-sm mb-6">Selamat datang di Kant.in. Anda akan diarahkan ke login.
        </p>
        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
            <div id="progressBar"
                class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-[2500ms] ease-linear"
                style="width:0%"></div>
        </div>
    </div>
</div>

<div class="w-full h-screen bg-white flex overflow-hidden">
    <div class="w-full md:w-1/2 flex flex-col h-screen overflow-y-auto">
        <div class="flex flex-col items-center pt-10 pb-8 px-10 rounded-b-3xl" style="background-color:#FFF7ED;">
            <div class="w-16 h-16 rounded-[18px] flex items-center justify-center mb-3 shadow-sm"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-3xl text-white"></i>
            </div>
            <h1 class="text-[22px] font-extrabold text-gray-900 tracking-tight">Gabung Kant<span
                    style="color:#FF6900;">.in</span></h1>
            <p class="text-gray-500 text-sm mt-1">Buat akun untuk mulai memesan.</p>
        </div>

        <div class="flex-1 flex flex-col px-10 pt-7 pb-10" style="background-color:#FFFFFF;">

            {{-- PENTING: Tampilan Error Validasi dari Controller --}}
            {{-- Bagian Error Container --}}
            <div id="errorContainer" class="mb-4 p-3 rounded-xl bg-red-50 text-red-600 text-sm font-medium {{ $errors->any() ? '' : 'hidden' }}">
                <ul id="errorList" class="list-disc pl-5">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="flex rounded-xl p-1 mb-5" style="background-color:#F3F4F6;">
                <button type="button" id="tabPelanggan" onclick="switchTab('pembeli')"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200">Pelanggan</button>
                <button type="button" id="tabKantin" onclick="switchTab('admin_kantin')"
                    class="flex-1 py-2 text-sm font-semibold rounded-lg transition-all duration-200">Pemilik
                    Kantin</button>
            </div>

            {{-- PENTING: Gunakan <form> dan @csrf agar Controller bisa terima data --}}
                <form id="registerForm" action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="pembeli">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-regular fa-user"></i>
                            </span>
                            <input type="text" name="name" id="regName" placeholder="Budi Santoso" required
                                value="{{ old('name') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="regEmail" placeholder="mahasiswa@kampus.ac.id" required
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nomor WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="tel" name="phone" id="regPhone" placeholder="08123456789" required
                                value="{{ old('phone') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                        </div>
                    </div>

                   {{-- Input Kata Sandi --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input id="passwordRegister" type="password" name="password" placeholder="Min. 8 karakter (Huruf & Angka)" required
                                oninput="checkStrength(this.value)"
                                class="w-full pl-10 pr-10 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                            <button type="button" onclick="togglePassword('passwordRegister','eyeIcon')"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="eyeIcon" class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                        
                        {{-- Bar Indikator Kekuatan --}}
                        <div class="mt-2 flex gap-1.5" id="strengthBar">
                            <div id="s1" class="flex-1 h-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                            <div id="s2" class="flex-1 h-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                            <div id="s3" class="flex-1 h-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                            <div id="s4" class="flex-1 h-1 bg-gray-200 rounded-full transition-all duration-300"></div>
                        </div>
                        <p id="strengthLabel" class="text-xs text-gray-400 mt-1 font-medium"></p>
                    </div>

                    <div id="namaKantinField" style="display: none;">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kantin</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <i class="fa-solid fa-store"></i>
                            </span>
                            <input type="text" name="canteen_name" id="regCanteenName" placeholder="Warung Bu Ani"
                                value="{{ old('canteen_name') }}"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-300 transition-all"
                                style="background-color:#FAFAFA;">
                        </div>
                    </div>

                    <button type="submit"
                        class="mt-5 w-full py-3 text-white font-bold text-sm rounded-xl shadow-md flex items-center justify-center gap-2 transition-all duration-200 active:scale-95 hover:opacity-90"
                        style="background-color:#FF6900;">
                        Buat Akun
                        <i class="fa-solid fa-arrow-right ml-1"></i>
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Sudah punya akun?
                    <a id="linkLogin" href="/login" class="font-semibold hover:underline"
                        style="color:#FF6900;">Masuk</a>
                </p>
        </div>
    </div>

    <div class="hidden md:flex md:w-1/2 relative h-screen">
        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80" alt="Makanan"
            class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0"
            style="background:linear-gradient(135deg,rgba(249,115,22,0.78) 0%,rgba(194,65,12,0.88) 100%);"></div>
        <div class="relative z-10 flex flex-col items-end justify-end p-12 pb-20 text-right text-white w-full">
            <h2 class="text-5xl font-extrabold leading-tight mb-6">Gabung Kant.in<br>Hari Ini</h2>
            <p class="text-lg leading-relaxed max-w-md" style="color:rgba(255,237,213,0.95);">
                Temukan makanan kampus yang lezat,<br>
                kumpulkan poin, dan jangan pernah antre lagi.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeRole = 'pembeli';

    // 1. DOMContentLoaded cukup membungkus proses inisialisasi dan form submit
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const roleFromUrl = urlParams.get('role');
        if (roleFromUrl === 'kantin') switchTab('admin_kantin');
        else switchTab('pembeli');

        const form = document.getElementById('registerForm');
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault(); 

            const errorContainer = document.getElementById('errorContainer');
            const errorList = document.getElementById('errorList');
            
            errorContainer?.classList.add('hidden');
            if (errorList) errorList.innerHTML = '';

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Memproses...';

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.status === 422) {
                    let errorHtml = '';
                    for (let field in data.errors) {
                        data.errors[field].forEach(errMsg => {
                            errorHtml += `<li>${errMsg}</li>`;
                        });
                    }
                    if (errorList) errorList.innerHTML = errorHtml;
                    errorContainer?.classList.remove('hidden'); 
                    window.scrollTo({ top: 0, behavior: 'smooth' }); 
                } else if (response.ok && data.success) {
                    showSuccessModal(data.redirect);
                }
            } catch (error) {
                alert('Terjadi kesalahan sistem, silakan coba lagi.');
            } finally {
                const modal = document.getElementById('successModal');
                if (!modal?.classList.contains('flex')) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    }); // <--- PENTING: Tadi tanda kurung tutup ini hilang!

    // 2. Fungsi-fungsi di bawah ini harus di LUAR DOMContentLoaded biar bisa dipanggil dari HTML

    function switchTab(role) {
        const tabP = document.getElementById('tabPelanggan');
        const tabK = document.getElementById('tabKantin');
        const nkf  = document.getElementById('namaKantinField');
        const roleInput = document.getElementById('roleInput');
        const linkLogin = document.getElementById('linkLogin');

        activeRole = role;
        roleInput.value = role;

        if (role === 'pembeli') {
            tabP.style.cssText = 'background-color:#FF6900; color:#fff;';
            tabK.style.cssText = 'background-color:#F3F4F6; color:#6B7280;';
            nkf.style.display = 'none';
            linkLogin.href = '/login'; 
        } else {
            tabK.style.cssText = 'background-color:#FF6900; color:#fff;';
            tabP.style.cssText = 'background-color:#F3F4F6; color:#6B7280;';
            nkf.style.display = 'block';
            linkLogin.href = '/login'; 
        }
    }

    function showSuccessModal(redirectUrl) {
        const modal = document.getElementById('successModal');
        const bar   = document.getElementById('progressBar');
        const title = document.getElementById('modalTitle');
        const desc  = document.getElementById('modalDesc');

        if (activeRole === 'pembeli') {
            title.textContent = 'Akun Berhasil Dibuat!';
            desc.textContent  = 'Selamat datang! Mengarahkan ke halaman login...';
        } else {
            title.textContent = 'Pendaftaran Berhasil!';
            desc.textContent  = 'Akun sedang menunggu verifikasi admin. Mohon tunggu sejenak.';
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        setTimeout(() => { bar.style.width = '100%'; }, 50);

        setTimeout(() => {
            window.location.href = redirectUrl || '/login';
        }, 2800);
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Aku hapus fungsi duplikatnya ya, cukup pakai satu saja
    function checkStrength(val) {
        const bars   = ['s1','s2','s3','s4'];
        const label  = document.getElementById('strengthLabel');
        let score    = 0;
        
        if (val.length >= 8) score++; 
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++; 
        if (/[0-9]/.test(val)) score++; 
        if (/[^A-Za-z0-9]/.test(val)) score++; 

        const colors  = ['#ef4444','#f97316','#eab308','#22c55e'];
        const labels  = ['Terlalu Lemah','Lumayan','Kuat','Sangat Kuat'];
        const lblClrs = ['text-red-500','text-orange-500','text-yellow-500','text-green-500'];

        bars.forEach((b, i) => {
            const el = document.getElementById(b);
            el.style.backgroundColor = i < score ? colors[score - 1] : '#e5e7eb';
        });

        if (val.length === 0) {
            label.textContent = '';
        } else {
            label.textContent = labels[score - 1] || 'Terlalu Lemah';
            label.className   = `text-xs mt-1 font-medium ${lblClrs[score - 1] || 'text-red-500'}`;
        }
    }
</script>
@endpush