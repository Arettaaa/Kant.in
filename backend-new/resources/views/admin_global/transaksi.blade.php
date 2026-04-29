@extends('layouts.app')

@section('title', 'Transaksi - Kant.in Global Admin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .sidebar-link.active { background-color: #FFF3E8; color: #FF6900 !important; }
    .search-input { width: 100%; padding: 12px 12px 12px 44px; background-color: #F9FAFB; border: 1.5px solid #f3f4f6; border-radius: 16px; font-size: 14px; color: #374151; outline: none; transition: all 0.2s; }
    .search-input:focus { border-color: #FF6900; box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.1); background-color: #fff; }
    .kantin-row { transition: all 0.2s ease; cursor: default; }
    .kantin-row:hover { background-color: #FFFAF7; transform: translateX(2px); }
    .sort-chip { transition: all 0.15s ease; cursor: pointer; }
    .sort-chip.active { background-color: #FF6900; color: white; border-color: #FF6900; }
    .sort-chip:not(.active):hover { border-color: #FF6900; color: #FF6900; }
    .format-chip { transition: all 0.15s ease; cursor: pointer; }
    .format-chip.active { background-color: #FFF3E8; border-color: #FF6900; color: #FF6900; }
    .periode-chip { transition: all 0.15s ease; cursor: pointer; border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 10px 16px; font-size: 13px; font-weight: 700; color: #6b7280; background: white; }
    .periode-chip.active { background-color: #111827; color: white; border-color: #111827; }
    .periode-chip:not(.active):hover { border-color: #FF6900; color: #FF6900; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.93) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal-card { animation: modalIn 0.22s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .unduh-btn { transition: all 0.2s ease; }
    .unduh-btn:hover { filter: brightness(1.08); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3); }
    .unduh-btn:active { transform: translateY(0); }
    .notif-dropdown { position: absolute; top: calc(100% + 12px); right: 0; width: 360px; background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06); border: 1px solid #f3f4f6; z-index: 100; overflow: hidden; animation: dropIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1); }
    @keyframes dropIn { from { opacity: 0; transform: translateY(-8px) scale(0.97); } to { opacity: 1; transform: translateY(0) scale(1); } }
    .notif-dropdown-item { padding: 14px 16px; cursor: pointer; transition: background 0.15s; border-bottom: 1px solid #f9fafb; }
    .notif-dropdown-item:hover { background-color: #FFFAF7; }
    .notif-dropdown-item:last-child { border-bottom: none; }
    .notif-icon-wrap { width: 48px; height: 48px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black uppercase tracking-widest mt-1" style="color:#FF6900;">Global Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/global/dasbor" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg> Dasbor
            </a>
            <a href="/admin/global/kantin-mitra" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg> Kantin Mitra
            </a>
            <a href="/admin/global/transaksi" class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Transaksi
            </a>
            <a href="/admin/global/notifikasi" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg> Notifikasi</div>
                <span class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black text-white shadow-sm" style="background-color:#FF6900;">2</span>
            </a>

            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Sistem</div>
            <a href="/admin/global/pengaturan" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> Pengaturan
            </a>
        </nav>

        <form method="POST" action="/logout" class="mt-auto">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg> Keluar
            </button>
        </form>
    </aside>

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">

        {{-- Header --}}
        <header class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold">Memuat Tanggal...</p>
            </div>
            <div class="flex items-center gap-6">
                {{-- Bell dengan dropdown --}}
                <div class="relative" id="bellWrapper">
                    <button onclick="toggleDropdown()" class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <span id="bellBadge" class="absolute top-2.5 right-3 w-3 h-3 border-2 border-white rounded-full" style="background-color:#FF6900;"></span>
                    </button>
                    {{-- Dropdown Notif --}}
                    <div id="notifDropdown" class="notif-dropdown hidden" style="right:-20px;">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                            <span class="text-sm font-extrabold text-gray-900">Notifikasi Terbaru</span>
                            <span class="text-xs font-black px-2.5 py-1 rounded-xl" style="background-color:#FFF3E8; color:#FF6900;">2 Baru</span>
                        </div>
                        <div class="notif-dropdown-item flex items-start gap-3">
                            <div class="notif-icon-wrap flex-shrink-0" style="background-color:#FFF3E8; width:40px; height:40px; border-radius:12px;">
                                <i class="fa-solid fa-store text-sm" style="color:#FF6900;"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-extrabold text-gray-900 leading-tight">Pendaftaran Kantin Baru</p>
                                    <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1" style="background-color:#FF6900;"></div>
                                </div>
                                <p class="text-[11px] text-gray-300 font-semibold mt-1">10 mnt lalu</p>
                            </div>
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
                <h1 class="text-2xl font-black text-gray-900">Laporan Transaksi</h1>
                <button onclick="showUnduhModal()"
                    class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:border-orange-300 hover:text-orange-500 transition-all shadow-sm">
                    <i class="fa-solid fa-download text-sm" style="color:#FF6900;"></i>
                    Unduh Laporan
                </button>
            </div>

            {{-- Summary Cards (DINAMIS DARI CONTROLLER) --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-arrow-trend-up text-xl" style="color:#FF6900;"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Pendapatan ({{ $labelPeriode }})</p>
                        <p class="text-2xl font-black text-gray-900">Rp {{ number_format($grandTotalRevenue ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-receipt text-xl" style="color:#FF6900;"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Pesanan ({{ $labelPeriode }})</p>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($grandTotalOrders ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Search + Date + Filter --}}
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" id="searchInput" placeholder="Cari nama kantin..." class="search-input" oninput="filterKantin()">
                </div>

                {{-- Date picker --}}
                <div class="relative" id="datePickerContainer">
                    <div onclick="toggleDateDropdown()" class="flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-500 cursor-pointer hover:border-orange-300 transition-all shadow-sm min-w-[160px] select-none">
                        <i class="fa-regular fa-calendar text-gray-400"></i>
                        <span id="dateTextDisplay">{{ $labelPeriode }}</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-auto transition-transform duration-200" id="dateChevron"></i>
                    </div>

                    {{-- Dropdown Menu (Filter Server Side) --}}
                    <div id="dateDropdown" class="hidden absolute top-full left-0 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl z-[60] py-2 overflow-hidden" style="animation: modalIn 0.2s ease;">
                        <a href="?periode=semua" class="block px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Semua Periode</a>
                        <a href="?periode=hari" class="block px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Hari Ini</a>
                        <a href="?periode=minggu" class="block px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Minggu Ini</a>
                        <a href="?periode=bulan" class="block px-4 py-2.5 text-sm font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Bulan Ini</a>
                    </div>
                </div>

                {{-- Filter button --}}
                <button onclick="showFilterModal()" class="w-12 h-12 flex items-center justify-center bg-white border border-gray-200 rounded-2xl text-gray-400 hover:border-orange-300 hover:text-orange-500 transition-all shadow-sm">
                    <i class="fa-solid fa-sliders text-base"></i>
                </button>
            </div>

            {{-- Kantin List (DINAMIS DARI CONTROLLER) --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col" id="kantinListContainer">
                <div id="kantinList">
                    @forelse($canteens as $canteen)
                    <div class="kantin-row flex items-center gap-5 px-6 py-5 border-b border-gray-50" 
                         data-name="{{ strtolower($canteen['canteen_name']) }}" 
                         data-revenue="{{ $canteen['total_revenue'] }}"
                         data-orders="{{ $canteen['total_orders'] }}">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="{{ $canteen['canteen_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($canteen['canteen_name']).'&background=FFF3E8&color=FF6900' }}" class="w-full h-full object-cover" alt="{{ $canteen['canteen_name'] }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900">{{ $canteen['canteen_name'] }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ number_format($canteen['total_orders'], 0, ',', '.') }} Pesanan Selesai</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-base font-black text-gray-900">Rp {{ number_format($canteen['total_revenue'], 0, ',', '.') }}</p>
                            <span class="inline-block mt-1 text-[10px] font-black px-2.5 py-1 rounded-lg uppercase tracking-wider" style="background-color:#FFF3E8; color:#FF6900;">{{ $labelPeriode }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center justify-center py-16 gap-2">
                        <i class="fa-solid fa-store-slash text-3xl text-gray-200 mb-2"></i>
                        <p class="text-sm font-bold text-gray-400">Belum ada transaksi sama sekali</p>
                    </div>
                    @endforelse
                </div>

                {{-- Empty state (saat search kosong) --}}
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
        <div class="mb-6">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Pilih Format File</p>
            <div class="grid grid-cols-2 gap-3">
                <button id="fmt-pdf" onclick="setFormat('pdf')" class="format-chip active flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border-2 text-sm font-bold transition-all">PDF</button>
                <button id="fmt-excel" onclick="setFormat('excel')" class="format-chip flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-500 transition-all">Excel / CSV</button>
            </div>
        </div>
        <div class="mb-7">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Pilih Periode Laporan</p>
            <div class="grid grid-cols-2 gap-3">
                <button id="per-hari" onclick="setPeriode('hari')" class="periode-chip">Hari ini</button>
                <button id="per-minggu" onclick="setPeriode('minggu')" class="periode-chip">Minggu Ini</button>
                <button id="per-bulan" onclick="setPeriode('bulan')" class="periode-chip active">Bulan Ini</button>
                <button id="per-semua" onclick="setPeriode('semua')" class="periode-chip">Semua</button>
            </div>
        </div>
        
        {{-- ✅ TOMBOL EKSPOR YANG MEMANGGIL JAVASCRIPT --}}
        <button onclick="jalankanEkspor()" class="unduh-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 shadow-md" style="background:linear-gradient(135deg,#FF6900,#ea580c);">
            <i class="fa-solid fa-download"></i> Mulai Unduh Laporan
        </button>
    </div>
</div>

{{-- ======================== MODAL FILTER ======================== --}}
<div id="filterModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.35); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[420px] mx-4 p-7">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-extrabold text-gray-900">Urutkan Data</h2>
            <button onclick="closeFilterModal()" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-all text-gray-400">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button id="sort-pendapatan-tertinggi" onclick="setSort('pendapatan-tertinggi')" class="sort-chip px-4 py-2 rounded-2xl border-2 text-sm font-bold transition-all border-gray-200 text-gray-600">Pendapatan Tertinggi</button>
                <button id="sort-pendapatan-terendah" onclick="setSort('pendapatan-terendah')" class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">Pendapatan Terendah</button>
                <button id="sort-pesanan-terbanyak" onclick="setSort('pesanan-terbanyak')" class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">Pesanan Terbanyak</button>
                <button id="sort-pesanan-tersedikit" onclick="setSort('pesanan-tersedikit')" class="sort-chip px-4 py-2 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 transition-all">Pesanan Tersedikit</button>
            </div>
        </div>
        <div class="flex gap-3 mt-8">
            <button onclick="resetFilter()" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Atur Ulang</button>
            <button onclick="applyFilter()" class="unduh-btn flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md" style="background:linear-gradient(135deg,#FF6900,#ea580c);">Terapkan</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let activeSort = 'pendapatan-tertinggi'; // Default sort

    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('realtimeDate');
        el.textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
        
        setSort(activeSort);
        executeSort(activeSort); 
    });

    // --- SEARCH LOGIC ---
    function filterKantin() {
        const q = document.getElementById('searchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('#kantinList .kantin-row');
        let visible = 0;
        
        rows.forEach(row => {
            const name = row.dataset.name.toLowerCase();
            if (name.includes(q)) {
                row.style.display = 'flex';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        const empty = document.getElementById('emptyState');
        if (visible === 0 && rows.length > 0) {
            empty.classList.remove('hidden'); empty.classList.add('flex');
        } else {
            empty.classList.add('hidden'); empty.classList.remove('flex');
        }
    }

    // --- SORT LOGIC (Mengurutkan list tanpa reload) ---
    function executeSort(sortType) {
        const list = document.getElementById('kantinList');
        const rows = Array.from(list.querySelectorAll('.kantin-row'));
        if (rows.length === 0) return;

        rows.sort((a, b) => {
            let revA = parseInt(a.dataset.revenue) || 0;
            let revB = parseInt(b.dataset.revenue) || 0;
            let ordA = parseInt(a.dataset.orders) || 0;
            let ordB = parseInt(b.dataset.orders) || 0;

            if (sortType === 'pendapatan-tertinggi') return revB - revA;
            if (sortType === 'pendapatan-terendah') return revA - revB;
            if (sortType === 'pesanan-terbanyak') return ordB - ordA;
            if (sortType === 'pesanan-tersedikit') return ordA - ordB;
            return 0;
        });

        rows.forEach(row => list.appendChild(row));
    }

    // --- MODAL UNDUH & EXPORT LOGIC ---
    let activeFormat = 'pdf';
    let activePeriode = 'bulan';

    function showUnduhModal() {
        document.getElementById('unduhModal').classList.remove('hidden');
        document.getElementById('unduhModal').classList.add('flex');
    }
    function closeUnduhModal() {
        document.getElementById('unduhModal').classList.add('hidden');
        document.getElementById('unduhModal').classList.remove('flex');
    }
    function setFormat(fmt) {
        activeFormat = fmt;
        ['pdf', 'excel'].forEach(f => {
            const btn = document.getElementById(`fmt-${f}`);
            btn.classList.toggle('active', f === fmt);
            if (f !== fmt) { btn.style.cssText = ''; btn.classList.add('border-gray-200', 'text-gray-500'); }
            else { btn.style.cssText = 'border-color:#FF6900; color:#FF6900; background-color:#FFF3E8;'; btn.classList.remove('text-gray-500'); }
        });
    }
    function setPeriode(p) {
        activePeriode = p;
        ['hari', 'minggu', 'bulan', 'semua'].forEach(k => {
            document.getElementById(`per-${k}`).classList.toggle('active', k === p);
        });
    }

    // ✅ FUNGSI JAVASCRIPT UNTUK MENEMBAK CONTROLLER EKSPOR
    function jalankanEkspor() {
        // Ambil URL dari route Laravel
        const url = "{{ route('admin.global.transaksi.export') }}";
        
        // Siapkan parameter format (pdf/excel) dan periode
        const params = `?format=${activeFormat}&periode=${activePeriode}`;
        
        // MINTA FILE! (Redirect browser)
        window.location.href = url + params;
        
        // Tutup modal
        closeUnduhModal();
    }


    // --- MODAL FILTER & SORT ---
    function showFilterModal() {
        document.getElementById('filterModal').classList.remove('hidden');
        document.getElementById('filterModal').classList.add('flex');
    }
    function closeFilterModal() {
        document.getElementById('filterModal').classList.add('hidden');
        document.getElementById('filterModal').classList.remove('flex');
    }
    function setSort(s) {
        activeSort = s;
        ['pendapatan-tertinggi', 'pendapatan-terendah', 'pesanan-terbanyak', 'pesanan-tersedikit'].forEach(k => {
            const btn = document.getElementById(`sort-${k}`);
            if (k === s) {
                btn.classList.add('active');
                btn.classList.remove('text-gray-600', 'border-gray-200');
                btn.style.cssText = 'border-color:#FF6900; color:white; background-color:#FF6900;';
            } else {
                btn.classList.remove('active');
                btn.classList.add('text-gray-600', 'border-gray-200');
                btn.style.cssText = 'background-color: transparent;';
            }
        });
    }
    function resetFilter() {
        setSort('pendapatan-tertinggi');
    }
    function applyFilter() {
        executeSort(activeSort);
        closeFilterModal();
    }

    // --- DATE DROPDOWN ---
    function toggleDateDropdown() {
        const dropdown = document.getElementById('dateDropdown');
        const chevron = document.getElementById('dateChevron');
        dropdown.classList.toggle('hidden');
        chevron.style.transform = dropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    // --- MENUTUP DROPDOWN/MODAL KALAU DIKLIK DI LUAR ---
    window.addEventListener('click', function(e) {
        const container = document.getElementById('datePickerContainer');
        const dropdown = document.getElementById('dateDropdown');
        const chevron = document.getElementById('dateChevron');
        if (container && !container.contains(e.target)) {
            if (dropdown) dropdown.classList.add('hidden');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }

        if (e.target === document.getElementById('unduhModal')) closeUnduhModal();
        if (e.target === document.getElementById('filterModal')) closeFilterModal();
    });

    function toggleDropdown() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }
</script>
@endpush