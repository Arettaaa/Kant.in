@extends('layouts.app')

@section('title', 'Pesanan Saya - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .tab-underline {
        position: relative;
        transition: color 0.2s ease;
    }
    .tab-underline.active {
        color: #FF6900;
        font-weight: 800;
    }
    .tab-underline.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #FF6900;
        border-radius: 2px;
    }
    .tab-underline:not(.active) {
        color: #9ca3af;
        font-weight: 600;
    }

    /* Progress tracker */
    .step-line {
        flex: 1;
        height: 2px;
        background-color: #e5e7eb;
        margin: 0 4px;
        position: relative;
        top: -14px;
    }
    .step-line.done {
        background-color: #FF6900;
    }

    .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 14px;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .step-circle.done {
        border-color: #FF6900;
        color: #FF6900;
    }
    .step-circle.active {
        border-color: #FF6900;
        background-color: #FF6900;
        color: white;
    }

    .order-card {
        transition: all 0.2s ease;
    }
    .order-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
    }

    .riwayat-badge-selesai {
        background-color: #F0FDF4;
        color: #16a34a;
    }
    .riwayat-badge-batal {
        background-color: #FEF2F2;
        color: #dc2626;
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        {{-- Kembali ke Beranda --}}
        <a href="/beranda"
           class="flex items-center gap-2 px-2 py-2 mb-4 text-sm font-semibold text-gray-400 hover:text-gray-600 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Beranda
        </a>

        {{-- Nav --}}
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>

            <a href="/jelajah"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajah
            </a>

            <a href="/pesanan"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
               style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan Saya
            </a>

            <a href="/profil"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Profil
            </a>
        </nav>

        {{-- Logout --}}
        <a href="/login"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        <div class="px-10 py-8 flex flex-col gap-6">

            {{-- TITLE --}}
            <h1 class="text-2xl font-extrabold text-gray-900">Pesanan Saya</h1>

            {{-- TABS --}}
            <div class="border-b border-gray-200">
                <div class="flex gap-8">
                    <button id="tabAktifBtn" onclick="switchTab('aktif')"
                            class="tab-underline active pb-3 text-sm tracking-wide">
                        Aktif
                    </button>
                    <button id="tabRiwayatBtn" onclick="switchTab('riwayat')"
                            class="tab-underline pb-3 text-sm tracking-wide">
                        Riwayat
                    </button>
                </div>
            </div>

            {{-- ===== TAB AKTIF ===== --}}
            <div id="tabAktif" class="grid grid-cols-2 gap-4 pb-8">

                {{-- ORD-8492: step = Dimasak (step 2) --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8492</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 30.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:30 PM</p>

                    {{-- Kantin & Items --}}
                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Warung Bu Ani</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Nasi Goreng Spesial, 1x Es Teh Manis</p>
                        </div>
                    </div>

                    {{-- Progress: Menunggu → Dimasak → Siap Diambil --}}
                    {{-- step=2: Menunggu=done, Dimasak=active, Siap=pending --}}
                    <div class="flex items-start justify-between">
                        {{-- Step 1: Menunggu done --}}
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle done">
                                <i class="fa-regular fa-clock text-xs"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400">Menunggu</span>
                        </div>

                        {{-- Line 1: done --}}
                        <div class="step-line done"></div>

                        {{-- Step 2: Dimasak active --}}
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle active">
                                <i class="fa-solid fa-fire text-xs"></i>
                            </div>
                            <span class="text-[11px] font-extrabold" style="color:#FF6900;">Dimasak</span>
                        </div>

                        {{-- Line 2: pending --}}
                        <div class="step-line"></div>

                        {{-- Step 3: Siap Diambil pending --}}
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle">
                                <i class="fa-solid fa-box-open text-xs"></i>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-400">Siap Diambil</span>
                        </div>
                    </div>
                </div>

                {{-- ORD-8493: step = Menunggu (step 1) --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8493</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 44.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:45 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Mie Nusantara</p>
                            <p class="text-xs text-gray-400 mt-0.5">2x Mie Goreng Ayam</p>
                        </div>
                    </div>

                    {{-- step=1: Menunggu=active, Dimasak=pending, Siap=pending --}}
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle active">
                                <i class="fa-regular fa-clock text-xs"></i>
                            </div>
                            <span class="text-[11px] font-extrabold" style="color:#FF6900;">Menunggu</span>
                        </div>

                        <div class="step-line"></div>

                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle">
                                <i class="fa-solid fa-fire text-xs"></i>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-400">Dimasak</span>
                        </div>

                        <div class="step-line"></div>

                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle">
                                <i class="fa-solid fa-box-open text-xs"></i>
                            </div>
                            <span class="text-[11px] font-semibold text-gray-400">Siap Diambil</span>
                        </div>
                    </div>
                </div>

                {{-- ORD-8488: step = Siap Diambil (step 3) — full width --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 col-span-2 md:col-span-1">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8488</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 15.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:15 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Fresh Sip</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Es Kopi Susu</p>
                        </div>
                    </div>

                    {{-- step=3: semua done, Siap Diambil = active/done --}}
                    <div class="flex items-start justify-between">
                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle done">
                                <i class="fa-regular fa-clock text-xs"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400">Menunggu</span>
                        </div>

                        <div class="step-line done"></div>

                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle done">
                                <i class="fa-solid fa-fire text-xs"></i>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400">Dimasak</span>
                        </div>

                        <div class="step-line done"></div>

                        <div class="flex flex-col items-center gap-1.5">
                            <div class="step-circle active">
                                <i class="fa-solid fa-box-open text-xs"></i>
                            </div>
                            <span class="text-[11px] font-extrabold" style="color:#FF6900;">Siap Diambil</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ===== TAB RIWAYAT ===== --}}
            <div id="tabRiwayat" class="hidden flex flex-col gap-4 pb-8">

                {{-- Riwayat card 1 --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8480</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 47.000</span>
                            <span class="text-[11px] font-black px-3 py-1 rounded-xl riwayat-badge-selesai">Selesai</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Kemarin • 13:10 PM</p>
                    <div class="bg-gray-50 rounded-2xl px-4 py-3 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Warung Bu Ani</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Nasi Goreng Spesial, 1x Es Teh Manis, 1x Tahu Krispi</p>
                        </div>
                    </div>
                </div>

                {{-- Riwayat card 2 --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8471</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 22.000</span>
                            <span class="text-[11px] font-black px-3 py-1 rounded-xl riwayat-badge-selesai">Selesai</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Kemarin • 11:45 AM</p>
                    <div class="bg-gray-50 rounded-2xl px-4 py-3 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Mie Nusantara</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Mie Goreng Ayam</p>
                        </div>
                    </div>
                </div>

                {{-- Riwayat card 3 (dibatalkan) --}}
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center bg-gray-100">
                                <i class="fa-solid fa-receipt text-xs text-gray-400"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8460</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold text-gray-400">Rp 20.000</span>
                            <span class="text-[11px] font-black px-3 py-1 rounded-xl riwayat-badge-batal">Dibatalkan</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">2 hari lalu • 09:20 AM</p>
                    <div class="bg-gray-50 rounded-2xl px-4 py-3 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Geprek Bensu</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Ayam Geprek Jumbo</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const aktif   = document.getElementById('tabAktif');
        const riwayat = document.getElementById('tabRiwayat');
        const btnA    = document.getElementById('tabAktifBtn');
        const btnR    = document.getElementById('tabRiwayatBtn');

        if (tab === 'aktif') {
            aktif.classList.remove('hidden');
            riwayat.classList.add('hidden');
            btnA.classList.add('active');
            btnR.classList.remove('active');
        } else {
            riwayat.classList.remove('hidden');
            aktif.classList.add('hidden');
            btnR.classList.add('active');
            btnA.classList.remove('active');
        }
    }
</script>
@endpush 