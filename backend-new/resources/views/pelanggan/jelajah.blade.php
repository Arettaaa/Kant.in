@extends('layouts.app')

@section('title', 'Jelajah - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .filter-chip {
        transition: all 0.18s ease;
        cursor: pointer;
        white-space: nowrap;
    }
    .filter-chip.active {
        background-color: #FF6900;
        color: white;
        border-color: #FF6900;
    }
    .filter-chip:not(.active):hover {
        border-color: #FF6900;
        color: #FF6900;
    }

    .menu-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .menu-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    .menu-card .menu-img {
        transition: transform 0.3s ease;
    }
    .menu-card:hover .menu-img {
        transform: scale(1.05);
    }

    .hot-badge {
        background: linear-gradient(135deg, #FF6900, #c2410c);
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
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
               style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajah
            </a>

            <a href="/pesanan"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>

            <a href="/profil"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
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
            <h1 class="text-2xl font-extrabold text-gray-900">Jelajah Makanan</h1>

            {{-- SEARCH + FILTER ICON --}}
            <div class="flex items-center gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input id="searchInput"
                           type="text"
                           placeholder="Cari nasi goreng, Popchick..."
                           oninput="filterMenu()"
                           onfocus="this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.15)'; this.style.borderColor='#FF6900';"
                           onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';"
                           class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200">
                </div>
                <button class="w-12 h-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center hover:border-orange-400 hover:text-orange-500 transition-all text-gray-400 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </button>
            </div>

            {{-- FILTER CHIPS --}}
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="setFilter(this, 'semua')"
                        class="filter-chip active px-5 py-2 rounded-full border-2 text-sm font-bold"
                        data-filter="semua">Semua</button>
                <button onclick="setFilter(this, 'nasi')"
                        class="filter-chip px-5 py-2 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-500"
                        data-filter="nasi">Nasi</button>
                <button onclick="setFilter(this, 'mie')"
                        class="filter-chip px-5 py-2 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-500"
                        data-filter="mie">Mie</button>
                <button onclick="setFilter(this, 'ayam')"
                        class="filter-chip px-5 py-2 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-500"
                        data-filter="ayam">Ayam</button>
                <button onclick="setFilter(this, 'minuman')"
                        class="filter-chip px-5 py-2 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-500"
                        data-filter="minuman">Minuman</button>
                <button onclick="setFilter(this, 'camilan')"
                        class="filter-chip px-5 py-2 rounded-full border-2 border-gray-200 text-sm font-bold text-gray-500"
                        data-filter="camilan">Camilan</button>
            </div>

            {{-- MENU GRID --}}
            <div id="menuGrid" class="grid grid-cols-2 gap-4 pb-8">

                {{-- Nasi Goreng Spesial --}}
                <a href="/menu/nasi-goreng-spesial" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="nasi" data-name="nasi goreng spesial">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"
                             alt="Nasi Goreng Spesial" class="menu-img w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Nasi Goreng Spesial</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Warung Bu Ani</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 25.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.8
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 15 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Mie Goreng Ayam --}}
                <a href="/menu/mie-goreng-ayam" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="mie" data-name="mie goreng ayam">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=300&q=80"
                             alt="Mie Goreng Ayam" class="menu-img w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Mie Siram Spesial</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Mie Nusantara</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 22.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.6
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 10 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Ayam Geprek Jumbo --}}
                <a href="/menu/ayam-geprek-jumbo" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="ayam" data-name="ayam geprek jumbo">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=300&q=80"
                             alt="Ayam Geprek Jumbo" class="menu-img w-full h-full object-cover">
                        <div class="absolute top-1.5 left-1.5 hot-badge text-white text-[9px] font-black px-1.5 py-0.5 rounded-lg flex items-center gap-0.5">
                            <i class="fa-solid fa-fire text-[8px]"></i> Hot
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Ayam Geprek Jumbo</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Geprek Bensu</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 20.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.9
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 12 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Sate Ayam Madura --}}
                <a href="/menu/sate-ayam-madura" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="ayam" data-name="sate ayam madura">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1529563021893-cc83c992d75d?w=300&q=80"
                             alt="Sate Ayam Madura" class="menu-img w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Sate Ayam Madura</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Sate Khas Senayan</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 28.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.7
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 20 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Es Teh Manis --}}
                <a href="/menu/es-teh-manis" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="minuman" data-name="es teh manis">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300&q=80"
                             alt="Es Teh Manis" class="menu-img w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Es Teh Manis</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Fresh Sip</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 5.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.8
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 3 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

                {{-- Tahu Krispi --}}
                <a href="/menu/tahu-krispi" class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                     data-cat="camilan" data-name="tahu krispi">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"
                             alt="Tahu Krispi" class="menu-img w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5">Tahu Krispi</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">Jajanan Kampus</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">Rp 12.000</span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 4.5
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> 8 mnt
                                </span>
                            </div>
                        </div>
                    </div>
                </a>

            </div>

            {{-- EMPTY STATE --}}
            <div id="emptyState" class="hidden flex-col items-center justify-center py-20 gap-3">
                <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-bowl-food text-2xl" style="color:#FF6900;"></i>
                </div>
                <p class="text-base font-extrabold text-gray-700">Makanan tidak ditemukan</p>
                <p class="text-sm text-gray-400 font-medium">Coba kata kunci atau kategori lain</p>
            </div>

        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    let activeFilter = 'semua';

    function setFilter(el, filter) {
        activeFilter = filter;

        document.querySelectorAll('.filter-chip').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('border-gray-200', 'text-gray-500');
        });

        el.classList.add('active');
        el.classList.remove('border-gray-200', 'text-gray-500');

        filterMenu();
    }

    function filterMenu() {
        const query = document.getElementById('searchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#menuGrid .menu-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const matchFilter = activeFilter === 'semua' || card.dataset.cat === activeFilter;
            const matchSearch = card.dataset.name.includes(query);

            if (matchFilter && matchSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const empty = document.getElementById('emptyState');
        if (visibleCount === 0) {
            empty.classList.remove('hidden');
            empty.classList.add('flex');
        } else {
            empty.classList.add('hidden');
            empty.classList.remove('flex');
        }
    }
</script>
@endpush