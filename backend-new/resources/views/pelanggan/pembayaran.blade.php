@extends('layouts.app')

@section('title', 'Pembayaran - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .upload-area {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .upload-area:hover {
        border-color: #FF6900;
        background-color: #FFFAF7;
    }
    .upload-area.has-file {
        border-color: #22c55e;
        background-color: #F0FDF4;
    }

    .konfirmasi-btn {
        transition: all 0.2s ease;
    }
    .konfirmasi-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.35);
    }
    .konfirmasi-btn:active {
        transform: translateY(0);
    }

    /* Success modal */
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.88) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes checkPop {
        0%   { transform: scale(0); opacity: 0; }
        60%  { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .modal-card {
        animation: modalIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .check-anim {
        animation: checkPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
    }
    .fade-up   { animation: fadeUp 0.3s ease 0.35s both; }
    .fade-up-2 { animation: fadeUp 0.3s ease 0.45s both; }
    .fade-up-3 { animation: fadeUp 0.3s ease 0.55s both; }

    /* QR pulse ring */
    .qr-wrap {
        position: relative;
        display: inline-block;
    }
    .qr-wrap::before {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: 18px;
        border: 2px solid rgba(255,105,0,0.2);
        animation: pulse-ring 2s ease-in-out infinite;
    }
    @keyframes pulse-ring {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50%       { opacity: 0.7; transform: scale(1.03); }
    }

    .timer-bar {
        animation: countdown 300s linear forwards;
    }
    @keyframes countdown {
        from { width: 100%; }
        to   { width: 0%; }
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        {{-- Label --}}
        <p class="text-[10px] font-black text-gray-300 tracking-widest uppercase px-2 mb-3">Menu Pelanggan</p>

        <nav class="flex flex-col gap-1.5 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Tersimpan
            </a>
        </nav>

        <a href="/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="flex flex-1 gap-6 px-8 py-8 max-w-5xl w-full mx-auto">

            {{-- ===== LEFT: PEMBAYARAN ===== --}}
            <div class="flex-1 flex flex-col gap-5">

                {{-- Header --}}
                <div class="flex items-center gap-4">
                    <a href="/keranjang"
                       class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-all flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 leading-tight">Selesaikan Pembayaran</h1>
                        <p class="text-sm text-gray-400 font-medium mt-0.5">Satu langkah lagi untuk menikmati hidanganmu.</p>
                    </div>
                </div>

                {{-- QR CARD --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col items-center gap-5">

                    <div class="flex items-center gap-3 self-start w-full">
                        <span class="text-xs font-black px-3 py-1.5 rounded-full border-2 border-blue-200 text-blue-500 tracking-widest">QRIS</span>
                        <span class="text-base font-extrabold text-gray-800">Pindai untuk Membayar</span>
                    </div>

                    <p class="text-sm text-gray-400 font-medium text-center leading-relaxed -mt-2">
                        Buka aplikasi m-banking atau e-wallet (GoPay, OVO, Dana) Anda<br>dan pindai kode QR di bawah ini.
                    </p>

                    {{-- QR Code --}}
                    <div class="qr-wrap">
                        <div class="w-52 h-52 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-white flex items-center justify-center p-3">
                            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                                <rect x="10" y="10" width="60" height="60" rx="6" fill="none" stroke="#111" stroke-width="8"/>
                                <rect x="22" y="22" width="36" height="36" rx="3" fill="#111"/>
                                <rect x="130" y="10" width="60" height="60" rx="6" fill="none" stroke="#111" stroke-width="8"/>
                                <rect x="142" y="22" width="36" height="36" rx="3" fill="#111"/>
                                <rect x="10" y="130" width="60" height="60" rx="6" fill="none" stroke="#111" stroke-width="8"/>
                                <rect x="22" y="142" width="36" height="36" rx="3" fill="#111"/>
                                <rect x="82" y="10" width="8" height="8" fill="#111"/><rect x="92" y="10" width="8" height="8" fill="#111"/>
                                <rect x="82" y="20" width="8" height="8" fill="#111"/><rect x="102" y="20" width="8" height="8" fill="#111"/>
                                <rect x="112" y="10" width="8" height="8" fill="#111"/><rect x="102" y="10" width="8" height="8" fill="#111"/>
                                <rect x="82" y="30" width="8" height="8" fill="#111"/><rect x="92" y="30" width="8" height="8" fill="#111"/>
                                <rect x="112" y="30" width="8" height="8" fill="#111"/>
                                <rect x="82" y="40" width="8" height="8" fill="#111"/><rect x="102" y="40" width="8" height="8" fill="#111"/>
                                <rect x="92" y="50" width="8" height="8" fill="#111"/><rect x="112" y="50" width="8" height="8" fill="#111"/>
                                <rect x="82" y="60" width="8" height="8" fill="#111"/><rect x="102" y="60" width="8" height="8" fill="#111"/>
                                <rect x="10" y="82" width="8" height="8" fill="#111"/><rect x="20" y="82" width="8" height="8" fill="#111"/>
                                <rect x="40" y="82" width="8" height="8" fill="#111"/><rect x="60" y="82" width="8" height="8" fill="#111"/>
                                <rect x="10" y="92" width="8" height="8" fill="#111"/><rect x="30" y="92" width="8" height="8" fill="#111"/>
                                <rect x="50" y="92" width="8" height="8" fill="#111"/>
                                <rect x="10" y="102" width="8" height="8" fill="#111"/><rect x="20" y="102" width="8" height="8" fill="#111"/>
                                <rect x="40" y="102" width="8" height="8" fill="#111"/><rect x="60" y="102" width="8" height="8" fill="#111"/>
                                <rect x="10" y="112" width="8" height="8" fill="#111"/><rect x="30" y="112" width="8" height="8" fill="#111"/>
                                <rect x="82" y="82" width="8" height="8" fill="#111"/><rect x="92" y="82" width="8" height="8" fill="#111"/>
                                <rect x="112" y="82" width="8" height="8" fill="#111"/>
                                <rect x="82" y="92" width="8" height="8" fill="#111"/><rect x="102" y="92" width="8" height="8" fill="#111"/>
                                <rect x="92" y="102" width="8" height="8" fill="#111"/><rect x="112" y="102" width="8" height="8" fill="#111"/>
                                <rect x="82" y="112" width="8" height="8" fill="#111"/><rect x="102" y="112" width="8" height="8" fill="#111"/>
                                <rect x="130" y="82" width="8" height="8" fill="#111"/><rect x="150" y="82" width="8" height="8" fill="#111"/>
                                <rect x="140" y="82" width="8" height="8" fill="#111"/><rect x="170" y="82" width="8" height="8" fill="#111"/>
                                <rect x="130" y="92" width="8" height="8" fill="#111"/><rect x="160" y="92" width="8" height="8" fill="#111"/>
                                <rect x="140" y="102" width="8" height="8" fill="#111"/><rect x="150" y="102" width="8" height="8" fill="#111"/>
                                <rect x="170" y="102" width="8" height="8" fill="#111"/>
                                <rect x="130" y="112" width="8" height="8" fill="#111"/><rect x="150" y="112" width="8" height="8" fill="#111"/>
                                <rect x="82" y="130" width="8" height="8" fill="#111"/><rect x="102" y="130" width="8" height="8" fill="#111"/>
                                <rect x="112" y="130" width="8" height="8" fill="#111"/>
                                <rect x="82" y="140" width="8" height="8" fill="#111"/><rect x="92" y="140" width="8" height="8" fill="#111"/>
                                <rect x="82" y="150" width="8" height="8" fill="#111"/><rect x="102" y="150" width="8" height="8" fill="#111"/>
                                <rect x="112" y="150" width="8" height="8" fill="#111"/>
                                <rect x="82" y="160" width="8" height="8" fill="#111"/><rect x="92" y="160" width="8" height="8" fill="#111"/>
                                <rect x="130" y="130" width="8" height="8" fill="#111"/><rect x="150" y="130" width="8" height="8" fill="#111"/>
                                <rect x="170" y="130" width="8" height="8" fill="#111"/>
                                <rect x="140" y="140" width="8" height="8" fill="#111"/><rect x="160" y="140" width="8" height="8" fill="#111"/>
                                <rect x="130" y="150" width="8" height="8" fill="#111"/><rect x="150" y="150" width="8" height="8" fill="#111"/>
                                <rect x="140" y="160" width="8" height="8" fill="#111"/><rect x="170" y="160" width="8" height="8" fill="#111"/>
                                <rect x="130" y="170" width="8" height="8" fill="#111"/><rect x="160" y="170" width="8" height="8" fill="#111"/>
                                <rect x="82" y="170" width="8" height="8" fill="#111"/><rect x="102" y="170" width="8" height="8" fill="#111"/>
                                <rect x="92" y="180" width="8" height="8" fill="#111"/><rect x="112" y="180" width="8" height="8" fill="#111"/>
                                <rect x="86" y="86" width="28" height="28" rx="4" fill="white" opacity="0.9"/>
                                <text x="100" y="105" text-anchor="middle" font-size="16" font-weight="bold" fill="#FF6900">⚡</text>
                            </svg>
                        </div>
                    </div>

                    {{-- Countdown timer --}}
                    <div class="w-full max-w-xs">
                        <div class="flex items-center justify-between text-xs text-gray-400 font-semibold mb-1.5">
                            <span>Kode berlaku selama</span>
                            <span id="timerDisplay" class="font-extrabold text-gray-600">05:00</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div id="timerBar" class="h-full rounded-full timer-bar" style="background:linear-gradient(90deg,#FF6900,#ea580c);"></div>
                        </div>
                    </div>

                    {{-- Kantin badge --}}
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 text-sm text-gray-500 font-semibold">
                        <i class="fa-solid fa-store text-gray-400 text-xs"></i>
                        Kantin Utama Kant.in
                    </div>

                </div>

                {{-- UPLOAD BUKTI --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-extrabold text-gray-900 mb-4">Unggah Bukti Pembayaran</h2>

                    <label for="fileInput" class="upload-area block border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center">
                        <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" class="hidden" onchange="handleFile(this)">
                        <div id="uploadDefault" class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-cloud-arrow-up text-xl" style="color:#FF6900;"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-700">Ketuk untuk mengunggah bukti transfer</p>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, atau PDF (Maks. 5MB)</p>
                            </div>
                        </div>
                        <div id="uploadDone" class="hidden flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
                            </div>
                            <p id="fileName" class="text-sm font-extrabold text-green-600"></p>
                            <p class="text-xs text-gray-400">Klik untuk ganti file</p>
                        </div>
                    </label>
                </div>

            </div>

            {{-- ===== RIGHT: RINGKASAN ===== --}}
            <div class="w-[280px] flex-shrink-0 flex flex-col gap-4">

                {{-- Ringkasan card --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col items-center gap-5">

                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-receipt text-2xl" style="color:#FF6900;"></i>
                    </div>

                    <div class="w-full">
                        <h2 class="text-base font-extrabold text-gray-900 text-center mb-1">Ringkasan Pembayaran</h2>
                        <p class="text-xs text-gray-400 font-semibold text-center">ID Pesanan: <span class="font-black text-gray-600">#KNTN-894201</span></p>
                    </div>

                    <div class="w-full h-px bg-gray-100"></div>

                    <div class="w-full flex flex-col gap-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-semibold">Subtotal (3 Item)</span>
                            <span class="font-extrabold text-gray-800">Rp 43.000</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-semibold">Ongkos Kirim</span>
                            <span class="font-extrabold text-gray-800">Rp 5.000</span>
                        </div>
                        <div class="h-px bg-gray-100"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-extrabold text-gray-900">Total Akhir</span>
                            <span class="text-xl font-extrabold" style="color:#FF6900;">Rp 48.000</span>
                        </div>
                    </div>

                </div>

                {{-- Alamat Pengiriman --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-sm" style="color:#FF6900;"></i>
                            <p class="text-sm font-extrabold text-gray-800">Alamat Pengiriman</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2.5 px-3 py-3 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5" style="background-color:#FFF3E8;">
                            <i class="fa-solid fa-building text-[10px]" style="color:#FF6900;"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-700 leading-snug">Gedung CA SV IPB, CA B01 lt.2, Kampus IPB Cilibende</p>
                        </div>
                        <i class="fa-solid fa-circle-check text-green-400 text-xs mt-0.5 flex-shrink-0"></i>
                    </div>
                    <div class="flex items-center gap-1.5 px-1">
                        <i class="fa-solid fa-person-biking text-xs" style="color:#FF6900;"></i>
                        <p class="text-xs text-gray-400 font-medium">Estimasi tiba <span class="font-bold text-gray-600">10–15 menit</span></p>
                    </div>
                </div>

                {{-- Konfirmasi button --}}
                <button onclick="showSuccessModal()"
                        class="konfirmasi-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                    Konfirmasi Pembayaran
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Info note --}}
                <div class="flex items-start gap-2.5 px-4 py-3 rounded-2xl bg-amber-50 border border-amber-100">
                    <i class="fa-solid fa-circle-info text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                    <p class="text-xs text-amber-700 font-medium leading-relaxed">
                        Pembayaran akan diverifikasi otomatis. Pesanan diproses setelah konfirmasi berhasil.
                    </p>
                </div>

            </div>

        </div>
    </main>
</div>

{{-- ======================== MODAL PEMBAYARAN BERHASIL ======================== --}}
<div id="successModal"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,0.45); backdrop-filter:blur(6px);">

    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">

        <div class="h-2 w-full" style="background:linear-gradient(90deg,#22c55e,#16a34a);"></div>

        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">

            <div class="check-anim w-20 h-20 rounded-full flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <div class="fade-up text-center">
                <h2 class="text-xl font-extrabold text-gray-900 mb-1">Pembayaran Berhasil!</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Pesanan Anda telah diterima dan sedang<br>diproses oleh kantin.
                </p>
            </div>

            <div class="fade-up-2 w-full flex items-center justify-between bg-gray-50 rounded-2xl px-4 py-3">
                <span class="text-xs text-gray-400 font-semibold">ID Pesanan</span>
                <span class="text-sm font-extrabold text-gray-800">#KNTN-894201</span>
            </div>

            <div class="fade-up-2 w-full flex flex-col gap-2">
                <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-green-50">
                    <div class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-green-500 text-[10px]"></i>
                    </div>
                    <p class="text-xs text-green-700 font-semibold">Pembayaran dikonfirmasi</p>
                </div>
                <div class="flex items-center gap-3 px-4 py-3 rounded-2xl" style="background-color:#FFF7ED;">
                    <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color:#FEE2CC;">
                        <i class="fa-solid fa-fire-flame-curved text-[10px]" style="color:#FF6900;"></i>
                    </div>
                    <p class="text-xs font-semibold" style="color:#c2410c;">Kantin sedang menyiapkan pesanan Anda</p>
                </div>
            </div>

            <div class="fade-up-3 w-full flex flex-col gap-2.5 mt-1">
                <a href="/pesanan"
                   class="w-full py-3.5 rounded-2xl text-white font-extrabold text-sm text-center shadow-md"
                   style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                    Pantau Pesanan
                </a>
                <a href="/beranda"
                   class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm text-center hover:bg-gray-50 transition-all">
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ---- Timer countdown ----
    let seconds = 300;
    const timerDisplay = document.getElementById('timerDisplay');

    const interval = setInterval(() => {
        seconds--;
        if (seconds <= 0) { clearInterval(interval); timerDisplay.textContent = '00:00'; return; }
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        timerDisplay.textContent = `${m}:${s}`;
        if (seconds <= 60) timerDisplay.style.color = '#ef4444';
    }, 1000);

    // ---- File upload ----
    function handleFile(input) {
        if (!input.files.length) return;
        const file = input.files[0];
        if (file.size > 5 * 1024 * 1024) { alert('File terlalu besar. Maksimal 5MB.'); input.value = ''; return; }
        const label = input.closest('label');
        label.classList.add('has-file');
        document.getElementById('uploadDefault').classList.add('hidden');
        const done = document.getElementById('uploadDone');
        done.classList.remove('hidden');
        done.classList.add('flex');
        document.getElementById('fileName').textContent = file.name;
    }

    // ---- Success modal ----
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('successModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
</script>
@endpush