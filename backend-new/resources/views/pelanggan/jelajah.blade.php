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
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
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
    <aside
        class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
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
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>

            <a href="/jelajah"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
                style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Jelajah
            </a>

            <a href="/pesanan"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Pesanan
            </a>

            <a href="/profil"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil
            </a>
        </nav>

        {{-- Logout --}}
        <a href="/login"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="px-10 py-8 flex flex-col gap-6">

            {{-- TITLE --}}
            <h1 class="text-2xl font-extrabold text-gray-900">Jelajah</h1>

            {{-- TABS --}}
            <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-2xl w-fit">
                <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all
              {{ $tab === 'makanan' ? 'text-white shadow-sm' : 'text-gray-400 hover:text-gray-600' }}"
                    style="{{ $tab === 'makanan' ? 'background-color:#FF6900;' : '' }}">
                    <i class="fa-solid fa-utensils mr-2"></i>Makanan
                </a>
                <a href="{{ route('pelanggan.jelajah', ['tab' => 'kantin']) }}" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all
              {{ $tab === 'kantin' ? 'text-white shadow-sm' : 'text-gray-400 hover:text-gray-600' }}"
                    style="{{ $tab === 'kantin' ? 'background-color:#FF6900;' : '' }}">
                    <i class="fa-solid fa-store mr-2"></i>Kantin
                </a>
            </div>

            {{-- TAB MAKANAN --}}
            @if($tab === 'makanan')

            {{-- SEARCH --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input id="searchInput" type="text" placeholder="Cari makanan..." value="{{ $search }}"
                    onkeydown="if(event.key==='Enter') submitSearch()"
                    onfocus="this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.15)'; this.style.borderColor='#FF6900';"
                    onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';"
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200">
            </div>

            {{-- FILTER CHIPS --}}
            <div class="flex items-center gap-2 flex-wrap">
                @php
                $kategoriList = [
                'Semua' => 'Semua',
                'makanan' => 'Makanan',
                'minuman' => 'Minuman',
                'camilan' => 'Camilan',
                ];
                @endphp
                @foreach($kategoriList as $value => $label)
                <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan', 'category' => $value, 'search' => $search]) }}"
                    class="filter-chip px-5 py-2 rounded-full border-2 text-sm font-bold {{ $category === $value ? 'active border-orange-500' : 'border-gray-200 text-gray-500' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- GRID MENU --}}
            <div class="grid grid-cols-2 gap-4 pb-8">
                @forelse($menus as $menu)
                <a href="/menu/{{ $menu['_id'] }}"
                    class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="relative flex-shrink-0 w-20 h-20 rounded-2xl overflow-hidden bg-gray-100">
                        @if(!empty($menu['image']))
                        <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}"
                            class="menu-img w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-utensils text-orange-200 text-2xl"></i>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5 truncate">{{ $menu['name'] }}</p>
                        <p class="text-xs text-gray-400 font-medium mb-2">{{ $menu['canteen_name'] ?? '-' }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-base font-extrabold" style="color:#FF6900;">
                                Rp {{ number_format($menu['price'], 0, ',', '.') }}
                            </span>
                            <div class="flex items-center gap-2 text-xs text-gray-400 font-semibold">
                                <span class="flex items-center gap-0.5 text-amber-400 font-bold">
                                    <i class="fa-solid fa-star text-[10px]"></i> 5.0
                                </span>
                                @if(!empty($menu['estimated_cooking_time']))
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <i class="fa-regular fa-clock text-[10px]"></i> {{ $menu['estimated_cooking_time']
                                    }} mnt
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-2 flex flex-col items-center justify-center py-20 gap-3">
                    <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center">
                        <i class="fa-solid fa-bowl-food text-2xl" style="color:#FF6900;"></i>
                    </div>
                    <p class="text-base font-extrabold text-gray-700">Makanan tidak ditemukan</p>
                    <p class="text-sm text-gray-400 font-medium">Coba kata kunci atau kategori lain</p>
                </div>
                @endforelse
            </div>

            {{-- TAB KANTIN --}}
            @else

            {{-- GRID KANTIN --}}
            <div class="grid grid-cols-2 gap-4 pb-8">
                @forelse($canteens as $kantin)
                <a href="/kantin/{{ $kantin['_id'] }}"
                    class="menu-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="relative flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden bg-gray-100">
                        @if(!empty($kantin['image']))
                        <img src="{{ $kantin['image'] }}" alt="{{ $kantin['name'] }}"
                            class="menu-img w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-store text-orange-200 text-2xl"></i>
                        </div>
                        @endif

                        {{-- Badge buka/tutup --}}
                        <div class="absolute bottom-1 left-1/2 -translate-x-1/2">
                            @if($kantin['is_open'] ?? false)
                            <span
                                class="text-[9px] font-black px-1.5 py-0.5 rounded-lg bg-green-500 text-white">Buka</span>
                            @else
                            <span
                                class="text-[9px] font-black px-1.5 py-0.5 rounded-lg bg-gray-400 text-white">Tutup</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-[15px] font-extrabold text-gray-900 mb-0.5 truncate">{{ $kantin['name'] }}</p>
                        <p class="text-xs text-gray-400 font-medium mb-2 truncate">{{ $kantin['location'] ?? '-' }}</p>
                        @if(!empty($kantin['operating_hours']))
                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-xl"
                            style="background-color:#FFF3E8; color:#FF6900;">
                            <i class="fa-regular fa-clock text-[10px]"></i>
                            {{ $kantin['operating_hours']['open'] ?? '' }} - {{ $kantin['operating_hours']['close'] ??
                            '' }}
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="col-span-2 flex flex-col items-center justify-center py-20 gap-3">
                    <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center">
                        <i class="fa-solid fa-store text-2xl" style="color:#FF6900;"></i>
                    </div>
                    <p class="text-base font-extrabold text-gray-700">Belum ada kantin</p>
                    <p class="text-sm text-gray-400 font-medium">Kantin akan segera hadir</p>
                </div>
                @endforelse
            </div>

            @endif

        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    function submitSearch() {
        const q = document.getElementById('searchInput')?.value ?? '';
        const url = new URL(window.location.href);
        url.searchParams.set('search', q);
        url.searchParams.set('tab', 'makanan');
        window.location.href = url.toString();
    }
</script>
@endpush