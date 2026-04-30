@extends('layouts.app')

@section('title', 'Beranda - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-card {
        transition: all 0.2s ease;
    }

    .category-card:hover {
        transform: translateY(-2px);
    }

    .category-card.active {
        background-color: #FF6900;
        color: white;
    }

    .category-card.active .cat-icon-wrap {
        background-color: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .category-card.active .cat-label {
        color: white;
    }

    .food-card:hover .food-img {
        transform: scale(1.04);
    }

    .food-img {
        transition: transform 0.3s ease;
    }

    .kantin-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .kantin-card {
        transition: all 0.2s ease;
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    @include('pelanggan.partials.sidebar', ['currentPath' => 'beranda'])
    
    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        {{-- ---- TOPBAR ---- --}}
        <div
            class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white/90 backdrop-blur-md border-b border-gray-100">

            {{-- Location + Greeting --}}
            <div>
                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-semibold mb-0.5">
                    <svg class="w-3.5 h-3.5" style="color:#FF6900;" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                    <span style="color:#FF6900;">Sekolah Vokasi IPB</span>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 leading-tight">Halo, {{ $namaDepan }}! 👋</h1>
            </div>

            {{-- Icons --}}
            <div class="flex items-center gap-3">
                {{-- Riwayat --}}
                <a href="/pesanan"
                    class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </a>
                {{-- Keranjang --}}
                <a href="/keranjang"
                    class="relative w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    @if($cartCount > 0)
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-[10px] font-black text-white flex items-center justify-center"
                        style="background-color:#FF6900;">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>

                {{-- Avatar --}}
                <a href="/profil"
                    class="w-10 h-10 rounded-full overflow-hidden border-2 border-orange-100 bg-orange-50 flex-shrink-0 cursor-pointer"
                    style="display:block; width:40px; height:40px;">
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-300" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>

        {{-- ---- SCROLL BODY ---- --}}
        <div class="px-10 py-8 flex flex-col gap-8">

            {{-- SEARCH --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input id="searchBeranda" type="text" placeholder="Mau makan apa hari ini?"
                    onkeydown="if(event.key==='Enter') handleSearchBeranda()"
                    onfocus="this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.15)'; this.style.borderColor='#FF6900';"
                    onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';"
                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all duration-200">
                <button onclick="handleSearchBeranda()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-xl flex items-center justify-center transition-all"
                    style="background-color:#FF6900;">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            {{-- KATEGORI --}}
            <section>
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Kategori</h2>
                <div class="grid grid-cols-4 gap-3">

                    {{-- Semua --}}
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan', 'category' => 'Semua']) }}"
                        class="category-card active flex flex-col items-center gap-2 py-5 px-3 rounded-2xl border border-transparent">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center"
                            style="background-color:rgba(255,255,255,0.25);">
                            <i class="fa-solid fa-border-all text-lg text-white"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-white">Semua</span>
                    </a>

                    {{-- Makanan --}}
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan', 'category' => 'makanan']) }}"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-utensils text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Makanan</span>
                    </a>

                    {{-- Minuman --}}
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan', 'category' => 'minuman']) }}"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-mug-hot text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Minuman</span>
                    </a>

                    {{-- Camilan --}}
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan', 'category' => 'camilan']) }}"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-cookie-bite text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Camilan</span>
                    </a>

                </div>
            </section>

            {{-- MAKANAN SEDANG TREN --}}
            <section>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Menu Populer</h2>
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'makanan']) }}"
                        class="text-sm font-bold hover:underline" style="color:#FF6900;">
                        Lihat Semua &rsaquo;
                    </a>
                </div>

                {{-- Scroll horizontal --}}
                <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2">
                    @forelse($menuPopuler as $menu)
                    <a href="/menu/{{ $menu['_id'] }}"
                        class="food-card flex-shrink-0 bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all"
                        style="width: calc(50% - 8px);">
                        <div class="relative h-40 overflow-hidden bg-gray-100">
                            @if(!empty($menu['image']))
                            <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}"
                                class="food-img w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-orange-50">
                                <i class="fa-solid fa-utensils text-4xl text-orange-200"></i>
                            </div>
                            @endif
                            <div
                                class="absolute top-2 left-2 flex items-center gap-1 bg-amber-400 text-white text-[10px] font-black px-2 py-0.5 rounded-xl shadow">
                                <i class="fa-solid fa-star text-[9px]"></i>
                                {{ ($menu['total_reviews'] ?? 0) > 0 ? number_format($menu['average_rating'], 1) :
                                'Baru' }}
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="text-sm font-bold text-gray-800 mb-0.5 truncate">{{ $menu['name'] }}</p>
                            <p class="text-[11px] text-gray-400 mb-1 truncate">{{ $menu['canteen_name'] ?? '-' }}</p>
                            <p class="text-sm font-extrabold" style="color:#FF6900;">
                                Rp {{ number_format($menu['price'], 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-400 py-4">Belum ada menu populer</p>
                    @endforelse
                </div>
            </section>
            {{-- KANTIN POPULER --}}
            <section class="pb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Kantin Rekomendasi</h2>
                    <a href="{{ route('pelanggan.jelajah', ['tab' => 'kantin']) }}"
                        class="text-sm font-bold hover:underline" style="color:#FF6900;">
                        Lihat Semua &rsaquo;
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @forelse($kantinRekomendasi as $kantin)
                    <a href="/kantin/{{ $kantin['_id'] }}"
                        class="kantin-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            @if(!empty($kantin['image']))
                            <img src="{{ $kantin['image'] }}" alt="{{ $kantin['name'] }}"
                                class="w-16 h-16 rounded-2xl object-cover">
                            @else
                            <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center">
                                <i class="fa-solid fa-store text-2xl text-orange-200"></i>
                            </div>
                            @endif
                            @if($kantin['computed_rating'] !== null)
                            <div
                                class="absolute -bottom-1 -right-1 flex items-center gap-0.5 bg-amber-400 text-white text-[10px] font-black px-1.5 py-0.5 rounded-lg shadow">
                                <i class="fa-solid fa-star text-[9px]"></i> {{ $kantin['computed_rating'] }}
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900 truncate">{{ $kantin['name'] }}</p>
                            <p class="text-xs text-gray-400 font-medium mb-2 truncate">{{ $kantin['location'] ?? '-' }}
                            </p>
                            @if($kantin['is_open'] ?? false)
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl"
                                style="background-color:#FFF3E8; color:#FF6900;">
                                <i class="fa-regular fa-clock text-[10px]"></i> Buka
                            </span>
                            @else
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl bg-gray-100 text-gray-400">
                                <i class="fa-regular fa-clock text-[10px]"></i> Tutup
                            </span>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="col-span-2 py-8 text-center text-gray-400 text-sm">Belum ada kantin</div>
                    @endforelse
                </div>
            </section>

        </div>
    </main>
</div>

@endsection

@push('scripts')
<script>
    // Data untuk smart search (dari controller)
    const allMenuNames    = @json($allMenuNames);
    const allCanteenNames = @json($allCanteenNames);

    function handleSearchBeranda() {
        const query = document.getElementById('searchBeranda').value.trim();
        if (!query) return;

        const q = query.toLowerCase();
        const adaMenu    = allMenuNames.some(n    => n && n.toLowerCase().includes(q));
        const adaKantin  = allCanteenNames.some(n => n && n.toLowerCase().includes(q));

        let url;
        if (adaMenu && adaKantin) {
            url = `{{ route('pelanggan.jelajah') }}?tab=makanan&search=${encodeURIComponent(query)}`;
        } else if (adaMenu) {
            url = `{{ route('pelanggan.jelajah') }}?tab=makanan&category=Semua&search=${encodeURIComponent(query)}`;
        } else if (adaKantin) {
            url = `{{ route('pelanggan.jelajah') }}?tab=kantin&search=${encodeURIComponent(query)}`;
        } else {
            url = `{{ route('pelanggan.jelajah') }}?tab=makanan&search=${encodeURIComponent(query)}`;
        }

        window.location.href = url;
    }

    const carousel = document.getElementById('menuCarousel');
function scrollCarousel(dir) {
    carousel.scrollBy({ left: dir * (176 + 16) * 2, behavior: 'smooth' });
}
carousel.addEventListener('scroll', () => {
    document.getElementById('btnCarouselPrev').disabled = carousel.scrollLeft < 10;
    document.getElementById('btnCarouselNext').disabled =
        carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 10;
});
document.getElementById('btnCarouselPrev').disabled = true;
</script>
@endpush