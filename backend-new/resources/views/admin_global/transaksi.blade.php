@extends('layouts.app')

@section('title', 'Transaksi - Kant.in Global Admin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    /* Search & filter bar */
    .search-input {
        width: 100%;
        padding: 12px 12px 12px 44px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s;
    }

    .search-input:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.1);
        background-color: #fff;
    }

    /* Kantin row */
    .kantin-row {
        transition: all 0.2s ease;
        cursor: default;
    }

    .kantin-row:hover {
        background-color: #FFFAF7;
        transform: translateX(2px);
    }

    /* Sort chip */
    .sort-chip {
        transition: all 0.15s ease;
        cursor: pointer;
    }

    .sort-chip.active {
        background-color: #FF6900;
        color: white;
        border-color: #FF6900;
    }

    .sort-chip:not(.active):hover {
        border-color: #FF6900;
        color: #FF6900;
    }

    /* Format chip */
    .format-chip {
        transition: all 0.15s ease;
        cursor: pointer;
    }

    .format-chip.active {
        background-color: #FFF3E8;
        border-color: #FF6900;
        color: #FF6900;
    }

    /* Periode chip */
    .periode-chip {
        transition: all 0.15s ease;
        cursor: pointer;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        background: white;
    }

    .periode-chip.active {
        background-color: #111827;
        color: white;
        border-color: #111827;
    }

    .periode-chip:not(.active):hover {
        border-color: #FF6900;
        color: #FF6900;
    }

    /* Modal */
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.93) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-card {
        animation: modalIn 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .unduh-btn {
        transition: all 0.2s ease;
    }

    .unduh-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3);
    }

    .unduh-btn:active {
        transform: translateY(0);
    }

    /* Style untuk Bell Dropdown */
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 360px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        z-index: 100;
        overflow: hidden;
        animation: dropIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes dropIn {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .notif-dropdown-item {
        padding: 14px 16px;
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid #f9fafb;
    }

    .notif-dropdown-item:hover {
        background-color: #FFFAF7;
    }

    .notif-dropdown-item:last-child {
        border-bottom: none;
    }

    .notif-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black uppercase tracking-widest mt-1" style="color:#FF6900;">Global Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/global/dasbor" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi" class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Transaksi
            </a>
            <a href="/admin/global/notifikasi" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifikasi
                </div>
                <span class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black text-white shadow-sm" style="background-color:#FF6900;">2</span>
            </a>

            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Sistem</div>
            <a href="/admin/global/pengaturan" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        <header class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold">Memuat Tanggal...</p>
            </div>
            <div class="flex items-center gap-6">
                {{-- Bell dengan dropdown --}}
                <div class="relative" id="bellWrapper">
                    <button onclick="toggleDropdown()"
                        class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <span id="bellBadge" class="absolute top-2.5 right-3 w-3 h-3 border-2 border-white rounded-full" style="background-color:#FF6900;"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div id="notifDropdown" class="notif-dropdown hidden" style="right:-20px;">
                        {{-- Header dropdown --}}
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                            <span class="text-sm font-extrabold text-gray-900">Notifikasi Terbaru</span>
                            <span class="text-xs font-black px-2.5 py-1 rounded-xl" style="background-color:#FFF3E8; color:#FF6900;">2 Baru</span>
                        </div>

                        {{-- Item 1 --}}
                        <div class="notif-dropdown-item flex items-start gap-3">
                            <div class="notif-icon-wrap flex-shrink-0" style="background-color:#FFF3E8; width:40px; height:40px; border-radius:12px;">
                                <i class="fa-solid fa-store text-sm" style="color:#FF6900;"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-extrabold text-gray-900 leading-tight">Pendaftaran Kantin Baru: Warung</p>
                                    <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1" style="background-color:#FF6900;"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">Permohonan pendaftaran kantin baru telah diajukan dan menunggu verifikasi.</p>
                                <p class="text-[11px] text-gray-300 font-semibold mt-1">10 mnt lalu</p>
                            </div>
                        </div>

                        {{-- Item 2 --}}
                        <div class="notif-dropdown-item flex items-start gap-3">
                            <div class="notif-icon-wrap flex-shrink-0" style="background-color:#FFF3E8; width:40px; height:40px; border-radius:12px;">
                                <i class="fa-solid fa-wave-square text-sm" style="color:#FF6900;"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-extrabold text-gray-900 leading-tight">Pembaruan Sistem Selesai</p>
                                    <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1" style="background-color:#FF6900;"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">Sinkronisasi gerbang pembayaran QRIS berhasil diselesaikan tanpa</p>
                                <p class="text-[11px] text-gray-300 font-semibold mt-1">1 jam lalu</p>
                            </div>
                        </div>

                        {{-- Item 3 --}}
                        <div class="notif-dropdown-item flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved text-sm text-gray-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-extrabold text-gray-700 leading-tight">Akses Admin Baru Diberikan</p>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">Akses admin diberikan kepada budi.admin@kant.in.</p>
                                <p class="text-[11px] text-gray-300 font-semibold mt-1">3 jam lalu</p>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-5 py-3 border-t border-gray-50 text-center">
                            <a href="/admin/global/notifikasi"
                                class="text-sm font-extrabold transition-all hover:underline" style="color:#FF6900;">
                                Lihat Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
                <div class="h-10 w-[1px] bg-gray-100"></div>
                <a href="/admin/global/profil" class="flex items-center gap-4 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900]">Admin Utama</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#FF6900;">Pusat Kendali</p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg border transition-all shadow-sm group-hover:text-white"
                        style="background-color:#FFF3E8; color:#FF6900; border-color:#FFE0CC;">A</div>
                </a>
            </div>
        </header>

        {{-- Content --}}
        <div class="p-10 space-y-8">

            {{-- Title + Unduh Laporan --}}
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-black text-gray-900">Laporan Bulanan</h1>
                <button onclick="showUnduhModal()"
                    class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:border-orange-300 hover:text-orange-500 transition-all shadow-sm">
                    <i class="fa-solid fa-download text-sm" style="color:#FF6900;"></i>
                    Unduh Laporan
                </button>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-arrow-trend-up text-xl" style="color:#FF6900;"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Pendapatan</p>
                        <p class="text-2xl font-black text-gray-900">Rp 124.500.000</p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-receipt text-xl" style="color:#FF6900;"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Pesanan</p>
                        <p class="text-2xl font-black text-gray-900">4.821</p>
                    </div>
                </div>
            </div>

            {{-- Search + Date + Filter --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama kantin..."
                        class="search-input" oninput="filterKantin()">
                </div>

                {{-- Date picker --}}
                <div class="relative" id="datePickerContainer">
                    <div onclick="toggleDateDropdown()"
                        class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-500 cursor-pointer hover:border-orange-300 transition-all shadow-sm min-w-[160px] select-none">
                        <i class="fa-regular fa-calendar text-gray-400"></i>
                        <span id="dateTextDisplay">Bulan Ini</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-auto transition-transform duration-200" id="dateChevron"></i>
                    </div>

                    {{-- Dropdown Menu --}}
                    <div id="dateDropdown" class="hidden absolute top-full left-0 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl z-[60] py-2 overflow-hidden" style="animation: modalIn 0.2s ease;">
                        <div onclick="setDateFilter('Hari Ini')" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900] cursor-pointer transition-colors">
                            Hari Ini
                        </div>
                        <div onclick="setDateFilter('Minggu Ini')" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900] cursor-pointer transition-colors">
                            Minggu Ini
                        </div>
                        <div onclick="setDateFilter('Bulan Ini')" class="px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900] cursor-pointer transition-colors">
                            Bulan Ini
                        </div>
                    </div>
                </div>

                {{-- Filter button --}}
                <button onclick="showFilterModal()"
                    class="w-12 h-12 flex items-center justify-center bg-white border border-gray-200 rounded-2xl text-gray-400 hover:border-orange-300 hover:text-orange-500 transition-all shadow-sm">
                    <i class="fa-solid fa-sliders text-base"></i>
                </button>
            </div>

            {{-- Kantin List --}}
            <div id="kantinList" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Row 1 (Hari Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" data-name="ayam geprek bensu" data-periode="Hari Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=200&q=80" class="w-full h-full object-cover" alt="Ayam Geprek Bensu">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Ayam Geprek Bensu</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">12 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 450.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#EBF5FF; color:#3B82F6;">Hari Ini</span>
                    </div>
                </div>

                {{-- Row 2 (Minggu Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" data-name="warung bu ani" data-periode="Minggu Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=200&q=80" class="w-full h-full object-cover" alt="Warung Bu Ani">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Warung Bu Ani</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">340 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 8.500.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#F0FDF4; color:#22C55E;">Minggu Ini</span>
                    </div>
                </div>

                {{-- Row 3 (Bulan Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" data-name="mie gacoan" data-periode="Bulan Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=200&q=80" class="w-full h-full object-cover" alt="Mie Gacoan">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Mie Gacoan</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">980 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 29.000.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#FFF3E8; color:#FF6900;">Bulan Ini</span>
                    </div>
                </div>

                {{-- Row 4 (Bulan Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" data-name="kopi kenangan" data-periode="Bulan Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=200&q=80" class="w-full h-full object-cover" alt="Kopi Kenangan">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Kopi Kenangan</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">451 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 12.000.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#FFF3E8; color:#FF6900;">Bulan Ini</span>
                    </div>
                </div>

                {{-- Row 5 (Bulan Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" data-name="noodle ninja" data-periode="Bulan Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=200&q=80" class="w-full h-full object-cover" alt="Noodle Ninja">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Noodle Ninja</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">320 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 8.200.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#FFF3E8; color:#FF6900;">Bulan Ini</span>
                    </div>
                </div>

                {{-- Row 6 (Bulan Ini) --}}
                <div class="kantin-row flex items-center gap-5 px-6 py-5" data-name="fresh sip" data-periode="Bulan Ini">
                    <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=200&q=80" class="w-full h-full object-cover" alt="Fresh Sip">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900">Fresh Sip</p>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">210 Pesanan Selesai</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-black text-gray-900">Rp 5.100.000</p>
                        <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#FFF3E8; color:#FF6900;">Bulan Ini</span>
                    </div>
                </div>

                {{-- Empty state --}}
                <div id="emptyState" class="hidden flex-col items-center justify-center py-16 gap-2">
                    <i class="fa-solid fa-store-slash text-3xl text-gray-200 mb-2"></i>
                    <p class="text-sm font-bold text-gray-400">Kantin tidak ditemukan</p>
                </div>
            </div>

        </div>
    </main>
</div>

{{-- ======================== MODAL UNDUH LAPORAN ======================== --}}
<div id="unduhModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.35); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[420px] mx-4 p-7">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background-color:#FFF3E8;">
                    <i class="fa-solid fa-download text-base" style="color:#FF6900;"></i>
                </div>
                <h2 class="text-lg font-extrabold text-gray-900">Unduh Laporan</h2>
            </div>
            <button onclick="closeUnduhModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-all text-gray-400">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- File Format --}}
        <div class="mb-6">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">File Format</p>
            <div class="grid grid-cols-2 gap-3">
                <button id="fmt-pdf" onclick="setFormat('pdf')"
                    class="format-chip active flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border-2 text-sm font-bold transition-all">
                    PDF
                </button>
                <button id="fmt-excel" onclick="setFormat('excel')"
                    class="format-chip flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-500 transition-all">
                    Excel / CSV
                </button>
            </div>
        </div>

        {{-- Periode --}}
        <div class="mb-7">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Pilih Periode Laporan</p>
            <div class="grid grid-cols-2 gap-3">
                <button id="per-hari" onclick="setPeriode('hari')" class="periode-chip">Hari ini</button>
                <button id="per-besok" onclick="setPeriode('besok')" class="periode-chip">Besok</button>
                <button id="per-7hari" onclick="setPeriode('7hari')" class="periode-chip">7 Hari Terakhir</button>
                <button id="per-bulan" onclick="setPeriode('bulan')" class="periode-chip active">Bulan Ini</button>
            </div>
        </div>

        {{-- CTA --}}
        <button onclick="closeUnduhModal()"
            class="unduh-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 shadow-md"
            style="background:linear-gradient(135deg,#FF6900,#ea580c);">
            <i class="fa-solid fa-download"></i>
            Unduh Laporan
        </button>

    </div>
</div>

{{-- ======================== MODAL FILTER ======================== --}}
<div id="filterModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.35); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[420px] mx-4 p-7">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-extrabold text-gray-900">Filter</h2>
            <button onclick="closeFilterModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-all text-gray-400">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Urutkan --}}
        <div class="mb-6">
            <p class="text-sm font-extrabold text-gray-800 mb-3">Urutkan</p>
            <div class="flex flex-wrap gap-2">
                <button id="sort-pendapatan-tertinggi" onclick="setSort('pendapatan-tertinggi')"
                    class="sort-chip active px-4 py-2 rounded-2xl border-2 text-sm font-bold transition-all"
                    style="border-color:#FF6900; color:#FF6900; background-color:#FFF3E8;">
                    Pendapatan Tertinggi
                </button>
                <button id="sort-pendapatan-terendah" onclick="setSort('pendapatan-terendah')"
                    class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">
                    Pendapatan Terendah
                </button>
                <button id="sort-pesanan-terbanyak" onclick="setSort('pesanan-terbanyak')"
                    class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">
                    Pesanan Terbanyak
                </button>
                <button id="sort-pesanan-tersedikit" onclick="setSort('pesanan-tersedikit')"
                    class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">
                    Pesanan Tersedikit
                </button>
            </div>
        </div>

        {{-- Cari Kantin --}}
        <div class="mb-7">
            <p class="text-sm font-extrabold text-gray-800 mb-3">Cari Kantin</p>
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="filterSearchInput" placeholder="Cari nama kantin"
                    class="search-input">
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button onclick="resetFilter()"
                class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                Atur Ulang
            </button>
            <button onclick="applyFilter()"
                class="unduh-btn flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md"
                style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                Terapkan
            </button>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentPeriode = 'Bulan Ini';

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('realtimeDate');
        el.textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        filterKantin();
    });

    function filterKantin() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#kantinList .kantin-row');
        let visible = 0;
        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            const periode = row.dataset.periode;

            const matchesSearch = name.includes(q);
            const matchesPeriode = (periode === currentPeriode);

            if (matchesSearch && matchesPeriode) {
                row.style.display = 'flex';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });
        const empty = document.getElementById('emptyState');
        if (visible === 0) {
            empty.classList.remove('hidden');
            empty.classList.add('flex');
        } else {
            empty.classList.add('hidden');
            empty.classList.remove('flex');
        }
    }

    let activeFormat = 'pdf';
    let activePeriode = 'bulan';

    function showUnduhModal() {
        const m = document.getElementById('unduhModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeUnduhModal() {
        const m = document.getElementById('unduhModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    document.getElementById('unduhModal').addEventListener('click', e => {
        if (e.target === document.getElementById('unduhModal')) closeUnduhModal();
    });

    function setFormat(fmt) {
        activeFormat = fmt;
        ['pdf', 'excel'].forEach(f => {
            const btn = document.getElementById(`fmt-${f}`);
            btn.classList.toggle('active', f === fmt);
            if (f !== fmt) {
                btn.style.cssText = '';
                btn.classList.add('border-gray-200');
            }
        });
    }

    function setPeriode(p) {
        activePeriode = p;
        ['hari', 'besok', '7hari', 'bulan'].forEach(k => {
            document.getElementById(`per-${k}`).classList.toggle('active', k === p);
        });
    }

    let activeSort = 'pendapatan-tertinggi';

    function showFilterModal() {
        const m = document.getElementById('filterModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeFilterModal() {
        const m = document.getElementById('filterModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    document.getElementById('filterModal').addEventListener('click', e => {
        if (e.target === document.getElementById('filterModal')) closeFilterModal();
    });

    function setSort(s) {
        activeSort = s;
        ['pendapatan-tertinggi', 'pendapatan-terendah', 'pesanan-terbanyak', 'pesanan-tersedikit'].forEach(k => {
            const btn = document.getElementById(`sort-${k}`);
            if (k === s) {
                btn.classList.add('active');
                btn.style.cssText = 'border-color:#FF6900; color:#FF6900; background-color:#FFF3E8;';
            } else {
                btn.classList.remove('active');
                btn.style.cssText = '';
                btn.classList.add('border-gray-200');
            }
        });
    }

    function resetFilter() {
        setSort('pendapatan-tertinggi');
        document.getElementById('filterSearchInput').value = '';
    }

    function applyFilter() {
        const q = document.getElementById('filterSearchInput').value.toLowerCase().trim();
        if (q) document.getElementById('searchInput').value = q;
        filterKantin();
        closeFilterModal();
    }

    function toggleDateDropdown() {
        const dropdown = document.getElementById('dateDropdown');
        const chevron = document.getElementById('dateChevron');
        const isHidden = dropdown.classList.contains('hidden');

        dropdown.classList.toggle('hidden');
        chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function setDateFilter(filterName) {
        document.getElementById('dateTextDisplay').textContent = filterName;
        currentPeriode = filterName;
        toggleDateDropdown();
        filterKantin();
    }

    window.addEventListener('click', function(e) {
        const container = document.getElementById('datePickerContainer');
        const dropdown = document.getElementById('dateDropdown');
        const chevron = document.getElementById('dateChevron');

        if (container && !container.contains(e.target)) {
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    //dropdown bell
    function toggleDropdown() {
        const dd = document.getElementById('notifDropdown');
        dd.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const wrapper = document.getElementById('bellWrapper');
        const dropdown = document.getElementById('notifDropdown');
        if (wrapper && !wrapper.contains(e.target)) {
            if (dropdown) dropdown.classList.add('hidden');
        }
    });
</script>
@endpush