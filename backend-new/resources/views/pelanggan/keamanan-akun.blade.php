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
        box-shadow: 0 0 0 3px rgba(255,105,0,0.12);
        background-color: #fff;
    }
    .input-field::placeholder {
        color: #d1d5db;
        letter-spacing: normal;
    }
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

    .label-tag {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #111827;;
        margin-bottom: 8px;
        display: block;
    }

    .save-btn {
        transition: all 0.2s ease;
    }
    .save-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.3);
    }
    .save-btn:active { transform: translateY(0); }

    .strength-bar div {
        height: 4px;
        border-radius: 99px;
        transition: background 0.3s ease, width 0.3s ease;
    }

    /* Modal */
    @keyframes modalIn {
        from { opacity:0; transform:scale(0.9) translateY(10px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }
    @keyframes checkPop {
        0%  { transform:scale(0); opacity:0; }
        60% { transform:scale(1.2); }
        100%{ transform:scale(1); opacity:1; }
    }
    .modal-card { animation: modalIn 0.25s cubic-bezier(0.34,1.56,0.64,1); }
    .check-anim { animation: checkPop 0.4s cubic-bezier(0.34,1.56,0.64,1) 0.1s both; }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        <p class="text-[10px] font-black text-gray-300 tracking-widest uppercase px-2 mb-3">Menu Pelanggan</p>

        <nav class="flex flex-col gap-1.5 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil
            </a>
        </nav>

        <a href="/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

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

                {{-- Form --}}
                <div class="flex flex-col gap-5">

                    {{-- Kata sandi saat ini --}}
                    <div>
                        <label class="label-tag">Kata Sandi Saat Ini</label>
                        <div class="input-wrap">
                            <input type="password" id="currentPass" class="input-field" placeholder="Masukkan kata sandi saat ini">
                            <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('currentPass', this)"></i>
                        </div>
                    </div>

                    {{-- Kata sandi baru --}}
                    <div>
                        <label class="label-tag">Kata Sandi Baru</label>
                        <div class="input-wrap">
                            <input type="password" id="newPass" class="input-field" placeholder="Masukkan kata sandi baru" oninput="checkStrength(this.value)">
                            <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('newPass', this)"></i>
                        </div>
                        {{-- Strength indicator --}}
                        <div class="mt-2 flex gap-1.5 strength-bar" id="strengthBar">
                        <p id="strengthLabel" class="text-xs text-gray-400 mt-1 font-medium"></p>
                    </div>

                    {{-- Konfirmasi kata sandi baru --}}
                    <div>
                        <label class="label-tag">Konfirmasi Kata Sandi Baru</label>
                        <div class="input-wrap">
                            <input type="password" id="confirmPass" class="input-field" placeholder="Konfirmasi kata sandi baru" oninput="checkMatch()">
                            <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('confirmPass', this)"></i>
                        </div>
                        <p id="matchLabel" class="text-xs mt-1 font-medium hidden"></p>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 mt-8">
                    <a href="/profil"
                       class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all text-center">
                        Batal
                    </a>
                    <button onclick="handleSave()"
                            class="save-btn flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md flex items-center justify-center gap-2"
                            style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- ======================== MODAL BERHASIL ======================== --}}
<div id="successModal"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,0.4); backdrop-filter:blur(5px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[360px] mx-4 overflow-hidden">
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#22c55e,#16a34a);"></div>
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">

            <div class="check-anim w-16 h-16 rounded-full flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Kata Sandi Berhasil Diperbarui!</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">Gunakan kata sandi baru Anda untuk masuk berikutnya.</p>
            </div>

            <div class="flex flex-col gap-2.5 w-full mt-1">
                <a href="/profil"
                   class="w-full py-3.5 rounded-2xl text-white font-extrabold text-sm text-center shadow-md"
                   style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                    Kembali ke Profil
                </a>
                <button onclick="closeSuccessModal()"
                        class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
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
            label.textContent  = '✓ Kata sandi cocok';
            label.className    = 'text-xs mt-1 font-medium text-green-500';
        } else {
            label.textContent  = '✕ Kata sandi tidak cocok';
            label.className    = 'text-xs mt-1 font-medium text-red-500';
        }
    }

    // Save handler
    function handleSave() {
        const cur  = document.getElementById('currentPass').value;
        const nw   = document.getElementById('newPass').value;
        const conf = document.getElementById('confirmPass').value;

        if (!cur || !nw || !conf) {
            alert('Semua field wajib diisi!');
            return;
        }
        if (nw !== conf) {
            alert('Konfirmasi kata sandi tidak cocok!');
            return;
        }
        if (nw.length < 6) {
            alert('Kata sandi minimal 6 karakter!');
            return;
        }
        showSuccessModal();
    }

    function showSuccessModal() {
        const m = document.getElementById('successModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeSuccessModal() {
        const m = document.getElementById('successModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) closeSuccessModal();
    });
</script>
@endpush