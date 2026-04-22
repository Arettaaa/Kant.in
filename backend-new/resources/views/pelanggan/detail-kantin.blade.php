@extends('layouts.app')

@section('title', 'Detail Kantin - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .menu-item-card { transition: all 0.2s ease; cursor: pointer; }
    .menu-item-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .menu-item-card .menu-img { transition: transform 0.3s ease; }
    .menu-item-card:hover .menu-img { transform: scale(1.05); }
    .add-btn { transition: all 0.15s ease; }
    .add-btn:hover { background-color: #FF6900; color: white; border-color: #FF6900; transform: scale(1.1); }
    .left-panel { width: 300px; flex-shrink: 0; position: relative; display: flex; flex-direction: column; }
    .hero-img-wrap { height: 300px; overflow: hidden; flex-shrink: 0; }
    .hero-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    .cart-float { position: fixed; bottom: 32px; right: 40px; z-index: 50; transition: all 0.2s ease; }
    .cart-float:hover { transform: scale(1.05); }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== LEFT PANEL ======================== --}}
    <div class="left-panel h-screen bg-white border-r border-gray-100 flex flex-col overflow-y-auto hide-scrollbar">

        {{-- Hero Image --}}
        <div class="relative">
            <div class="hero-img-wrap">
                @if(!empty($kantin['image']))
                    <img src="{{ $kantin['image'] }}" alt="{{ $kantin['name'] }}">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-orange-50">
                        <i class="fa-solid fa-store text-orange-200" style="font-size:60px;"></i>
                    </div>
                @endif
            </div>
            <a href="javascript:history.back()"
               class="absolute top-4 left-4 w-9 h-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

        {{-- Info --}}
        <div class="flex-1 px-6 py-5 flex flex-col gap-4">
            <div class="flex items-start justify-between gap-2">
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $kantin['name'] }}</h1>
                <div class="flex items-center gap-1.5 bg-amber-50 px-2.5 py-1.5 rounded-xl flex-shrink-0">
                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                    <span class="text-sm font-extrabold text-gray-800">5.0</span>
                </div>
            </div>

            @if(!empty($kantin['description']))
            <p class="text-sm text-gray-500 leading-relaxed">{{ $kantin['description'] }}</p>
            @endif

            <div class="flex items-center gap-3 text-xs text-gray-400 font-semibold flex-wrap">
                @if(!empty($kantin['location']))
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" style="color:#FF6900;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    {{ $kantin['location'] }}
                </span>
                @endif

                @if(!empty($kantin['operating_hours']))
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-clock text-gray-400"></i>
                    {{ $kantin['operating_hours']['open'] }} - {{ $kantin['operating_hours']['close'] }}
                </span>
                @endif

                <span class="flex items-center gap-1 font-bold"
                      style="color: {{ ($kantin['is_open'] ?? false) ? '#22c55e' : '#9ca3af' }}">
                    <span class="w-1.5 h-1.5 rounded-full inline-block"
                          style="background-color: {{ ($kantin['is_open'] ?? false) ? '#22c55e' : '#9ca3af' }}"></span>
                    {{ ($kantin['is_open'] ?? false) ? 'Buka' : 'Tutup' }}
                </span>
            </div>

            {{-- Search --}}
            <div class="relative mt-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="menuSearch" type="text"
                       placeholder="Cari menu di sini..."
                       oninput="searchMenu()"
                       onfocus="this.style.borderColor='#FF6900'; this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.12)';"
                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='';"
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all">
            </div>
        </div>

    </div>{{-- END LEFT PANEL --}}

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">
        <div class="px-8 py-8 flex flex-col gap-10 pb-28">

            @forelse($menuByKategori as $kategori => $items)
            <section class="menu-section" data-section="{{ strtolower($kategori) }}">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">{{ $kategori }}</h2>
                <div class="grid grid-cols-3 gap-4">
                    @foreach($items as $menu)
                    <a href="/menu/{{ $menu['_id'] }}"
                       class="menu-item-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 flex flex-col"
                       data-name="{{ strtolower($menu['name']) }}">

                        <div class="w-full aspect-square overflow-hidden bg-gray-100">
                            @if(!empty($menu['image']))
                                <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}"
                                     class="menu-img w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-orange-50">
                                    <i class="fa-solid fa-utensils text-orange-200 text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-3 flex flex-col gap-1">
                            <p class="text-sm font-extrabold text-gray-900 leading-tight">{{ $menu['name'] }}</p>
                            @if(!empty($menu['description']))
                            <p class="text-xs text-gray-400 leading-snug line-clamp-1">{{ $menu['description'] }}</p>
                            @endif
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">
                                    Rp {{ number_format($menu['price'], 0, ',', '.') }}
                                </span>
                                @if($menu['is_available'] ?? true)
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                                @else
                                <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-lg">Habis</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @empty
            <div class="flex flex-col items-center justify-center py-20 gap-3">
                <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center">
                    <i class="fa-solid fa-bowl-food text-2xl" style="color:#FF6900;"></i>
                </div>
                <p class="text-base font-extrabold text-gray-700">Belum ada menu</p>
                <p class="text-sm text-gray-400 font-medium">Menu kantin ini belum tersedia</p>
            </div>
            @endforelse

        </div>
    </main>{{-- END MAIN CONTENT --}}

</div>{{-- END FLEX WRAPPER --}}

{{-- FLOATING CART --}}
<div id="cartFloat" class="cart-float hidden">
    <button onclick="window.location.href='/keranjang'"
            class="flex items-center gap-3 px-5 py-3.5 rounded-2xl text-white font-extrabold text-sm shadow-xl"
            style="background: linear-gradient(135deg, #FF6900, #ea580c);">
        <div class="relative">
            <i class="fa-solid fa-cart-shopping text-base"></i>
            <span id="cartCount"
                  class="absolute -top-2 -right-2 w-4 h-4 rounded-full bg-white text-[10px] font-black flex items-center justify-center"
                  style="color:#FF6900;">0</span>
        </div>
        <span id="cartLabel">Lihat Keranjang</span>
        <span id="cartTotal" class="font-extrabold">Rp 0</span>
    </button>
</div>

@endsection

@push('scripts')
<script>
    let cart = [];

    function addToCart(btn, name, price) {
        btn.style.backgroundColor = '#FF6900';
        btn.style.color = 'white';
        btn.style.borderColor = '#FF6900';
        btn.style.transform = 'scale(1.15)';
        setTimeout(() => { btn.style.transform = 'scale(1)'; }, 150);

        const existing = cart.find(i => i.name === name);
        if (existing) { existing.qty++; } 
        else { cart.push({ name, price, qty: 1 }); }
        updateCart();
    }

    function updateCart() {
        const totalQty   = cart.reduce((s, i) => s + i.qty, 0);
        const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);
        document.getElementById('cartCount').textContent = totalQty;
        document.getElementById('cartTotal').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        const floatEl = document.getElementById('cartFloat');
        floatEl.classList.toggle('hidden', totalQty === 0);
    }

    function searchMenu() {
        const q = document.getElementById('menuSearch').value.toLowerCase().trim();
        document.querySelectorAll('.menu-item-card').forEach(card => {
            card.style.display = card.dataset.name.includes(q) ? 'flex' : 'none';
        });
        document.querySelectorAll('.menu-section').forEach(section => {
            const visible = [...section.querySelectorAll('.menu-item-card')].some(c => c.style.display !== 'none');
            section.style.display = visible ? 'block' : 'none';
        });
    }
</script>
@endpush