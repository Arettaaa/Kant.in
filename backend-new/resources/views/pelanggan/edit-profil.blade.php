@extends('layouts.app')

@section('title', 'Edit Profil - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 16px 14px 44px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
    }
    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255,105,0,0.12);
        background-color: #fff;
    }
    .input-wrap {
        position: relative;
    }
    .input-wrap .icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 16px;
        height: 16px;
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

    .avatar-wrap {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    .avatar-wrap:hover .avatar-overlay { opacity: 1; }
    .avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    /* Success modal */
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.9) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes checkPop {
        0%   { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
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
            <h1 class="text-lg font-extrabold text-gray-900">Edit Profil</h1>
            <p class="text-sm text-gray-400 font-medium mt-0.5">Perbarui detail profil Anda</p>
        </div>
        </div>

        <div class="px-10 py-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto">


                {{-- Avatar --}}
                <div class="flex justify-center mb-8">
                    <div class="avatar-wrap" onclick="document.getElementById('avatarInput').click()">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center" style="background-color:#FEF3E2;">
                            <svg class="w-12 h-12 text-orange-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>
                        <div class="avatar-overlay">
                            <i class="fa-solid fa-camera text-white text-sm"></i>
                        </div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 rounded-full flex items-center justify-center shadow-md" style="background-color:#FF6900;">
                            <i class="fa-solid fa-camera text-white text-xs"></i>
                        </div>
                    </div>
                    <input type="file" id="avatarInput" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                </div>

                {{-- Form fields --}}
                <div class="flex flex-col gap-5">

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block">Nama Lengkap</label>
                        <div class="input-wrap">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input type="text" class="input-field" value="Yumna Aqila" placeholder="Nama lengkap Anda">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block">Nomor Telepon</label>
                        <div class="input-wrap">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <input type="tel" class="input-field" value="+62 812-3456-7890" placeholder="Nomor telepon Anda">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block">Email</label>
                        <div class="input-wrap">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input type="email" class="input-field" value="yumnaaqila@ipb.ac.id" placeholder="Email">
                        </div>
                    </div>

                     <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block">Alamat</label>
                        <div class="input-wrap">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <input type="text" class="input-field" value="Gedung CA SV IPB, CA B01 lt.2, Kampus IPB Cilibende" placeholder="Alamat lengkap Anda">
                        </div>

                </div>

                {{-- Save button --}}
                <button onclick="showSaveModal()"
                        class="save-btn mt-8 w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                    <i class="fa-solid fa-check"></i>
                    Simpan Perubahan
                </button>

            </div>
        </div>
    </main>
</div>

{{-- ======================== MODAL BERHASIL ======================== --}}
<div id="saveModal"
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
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Profil Berhasil Diperbarui!</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">Data diri Anda telah berhasil disimpan.</p>
            </div>

                <button onclick="closeSaveModal()"
                        class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">
                        <a href="/profil/data-diri">
                    Tutup
                </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function previewAvatar(input) {
        if (!input.files || !input.files[0]) return;
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = input.closest('.avatar-wrap') || input.previousElementSibling;
            const circle = document.querySelector('.avatar-wrap > div:first-child');
            if (circle) {
                circle.style.backgroundImage = `url(${e.target.result})`;
                circle.style.backgroundSize = 'cover';
                circle.style.backgroundPosition = 'center';
                circle.innerHTML = '';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }

    function showSaveModal() {
        const m = document.getElementById('saveModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeSaveModal() {
        const m = document.getElementById('saveModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    document.getElementById('saveModal').addEventListener('click', function(e) {
        if (e.target === this) closeSaveModal();
    });
</script>
@endpush