@extends('layouts.app')

@section('title', 'Detail Kantin - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── SCROLLBAR ── */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* ── LEFT PANEL ── */
    .left-panel {
        width: 320px; 
        flex-shrink: 0;
        position: relative;
        display: flex;
        flex-direction: column;
        border-right: 1px solid #F0EDE8;
    }

    .hero-img-wrap {
        height: 220px; 
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }

    .hero-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, transparent 40%);
        pointer-events: none;
    }

    /* ── INFO BADGES ── */
    .info-card {
        background: #F9FAFB;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #F3F4F6;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #4B5563;
        line-height: 1.4;
    }

    .info-icon {
        width: 18px;
        display: flex;
        justify-content: center;
        margin-top: 2px;
    }

    /* ── MENU CARD ── */
    .menu-item-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #F0EDE8;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .menu-item-card:hover {
        box-shadow: 0 12px 24px rgba(255, 105, 0, 0.08);
        border-color: #FFD0A8;
        transform: translateY(-4px);
    }

    .menu-img-container {
        width: 100%;
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #F9FAFB;
        position: relative;
    }

    .menu-item-card .menu-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .menu-item-card:hover .menu-img {
        transform: scale(1.08);
    }

    .add-btn {
        width: 32px; height: 32px;
        border-radius: 50%;
        border: 2px solid #F3F4F6;
        display: flex; align-items: center; justify-content: center;
        color: #9CA3AF;
        background: white;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .menu-item-card:hover .add-btn {
        border-color: #FF6900;
        color: #FF6900;
    }

    .add-btn:active {
        background-color: #FF6900 !important;
        color: white !important;
        transform: scale(0.95);
    }

    /* ── SEARCH INPUT (Main Area) ── */
    .search-input {
        width: 100%;
        padding: 14px 16px 14px 44px;
        border-radius: 18px;
        background: white;
        border: 1.5px solid #F0EDE8;
        font-size: 14px;
        color: #111;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .search-input:focus {
        outline: none;
        border-color: #FF6900;
        box-shadow: 0 0 0 4px rgba(255,105,0,0.1);
    }

    /* ── FLOATING CART ── */
    .cart-float {
        position: fixed;
        bottom: 32px;
        right: 40px;
        z-index: 50;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cart-float:hover {
        transform: translateY(-4px) scale(1.02);
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== LEFT PANEL ======================== --}}
    <div class="left-panel h-screen bg-white flex flex-col overflow-y-auto hide-scrollbar">

        {{-- Hero Image --}}
        <div class="hero-img-wrap">
            @if(!empty($kantin['image']))
            <img src="{{ $kantin['image'] }}" alt="{{ $kantin['name'] }}">
            @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-[#2d1300]">
                <i class="fa-solid fa-store text-orange-500/30 text-6xl"></i>
            </div>
            @endif
            <div class="hero-overlay"></div>
            
            <a href="javascript:history.back()"
                class="absolute top-5 left-5 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-white hover:bg-white/40 hover:scale-105 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        </div>

        {{-- Info Kantin --}}
        <div class="flex-1 px-7 py-6 flex flex-col gap-6">
            
            {{-- Title & Rating --}}
            <div class="flex items-start justify-between gap-3">
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $kantin['name'] }}</h1>
                
                @if($kantin['computed_rating'] !== null)
                <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 px-2.5 py-1.5 rounded-xl flex-shrink-0">
                    <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                    <span class="text-sm font-extrabold text-amber-600">{{ $kantin['computed_rating'] }}</span>
                </div>
                @else
                <div class="flex items-center gap-1.5 bg-gray-100 px-2.5 py-1.5 rounded-xl flex-shrink-0">
                    <i class="fa-solid fa-star text-gray-400 text-xs"></i>
                    <span class="text-[11px] font-extrabold text-gray-500 uppercase tracking-wide">Baru</span>
                </div>
                @endif
            </div>

            {{-- Info Badges Card --}}
            <div class="info-card">
                @if(!empty($kantin['location']))
                <div class="info-row">
                    <div class="info-icon"><i class="fa-solid fa-location-dot" style="color:#FF6900;"></i></div>
                    <span>{{ $kantin['location'] }}</span>
                </div>
                @endif

                @if(!empty($kantin['operating_hours']))
                <div class="info-row">
                    <div class="info-icon"><i class="fa-regular fa-clock text-gray-400"></i></div>
                    <span>{{ $kantin['operating_hours']['open'] }} - {{ $kantin['operating_hours']['close'] }} WIB</span>
                </div>
                @endif

                <div class="info-row items-center">
                    <div class="info-icon">
                        <span class="relative flex h-3 w-3 mt-0.5">
                          @if($kantin['is_open'] ?? false)
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                          @else
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-gray-400"></span>
                          @endif
                        </span>
                    </div>
                    <span style="color: {{ ($kantin['is_open'] ?? false) ? '#22c55e' : '#9ca3af' }}; font-weight: 700;">
                        {{ ($kantin['is_open'] ?? false) ? 'Sedang Buka' : 'Sedang Tutup' }}
                    </span>
                </div>
            </div>

            {{-- Deskripsi Kantin --}}
            @if(!empty($kantin['description']))
            <div>
                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tentang Kantin</h3>
                <p class="text-[13.5px] text-gray-500 leading-relaxed font-medium text-justify">
                    {{ $kantin['description'] }}
                </p>
            </div>
            @endif

        </div>

    </div>{{-- END LEFT PANEL --}}

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar relative">
        <div class="px-10 py-10 flex flex-col gap-10 pb-32">

            {{-- SEARCH BAR (Berada di atas daftar menu makanan) --}}
            <div class="relative w-full mb-2">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input id="menuSearch" type="text" placeholder="Cari menu di kantin ini..." oninput="searchMenu()" class="search-input">
            </div>

            @forelse($menuByKategori as $kategori => $items)
            <section class="menu-section" data-section="{{ strtolower($kategori) }}">
                <div class="flex items-center gap-3 mb-5">
                    <h2 class="text-xl font-extrabold text-gray-900 capitalize">{{ $kategori }}</h2>
                    <div class="h-px bg-gray-200 flex-1 opacity-50"></div>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @php
                        $available = collect($items)->filter(fn($m) => $m['is_available'] ?? true)->values();
                        $unavailable = collect($items)->filter(fn($m) => !($m['is_available'] ?? true))->values();
                        $sortedItems = $available->merge($unavailable);
                    @endphp

                    @foreach($sortedItems as $menu)
                        <a href="/menu/{{ $menu['_id'] }}"
                            class="menu-item-card group {{ !($menu['is_available'] ?? true) ? 'opacity-60 grayscale pointer-events-none' : '' }}"
                            data-name="{{ strtolower($menu['name']) }}">

                        <div class="menu-img-container">
                            @if(!empty($menu['image']))
                            <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}" class="menu-img">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-orange-50/50">
                                <i class="fa-solid fa-utensils text-orange-200 text-4xl"></i>
                            </div>
                            @endif
                            
                            {{-- Badge Rating di dalam foto jika ada --}}
                            @if(!empty($menu['average_rating']))
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                                <span class="text-[11px] font-bold text-gray-800">{{ number_format($menu['average_rating'], 1) }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="p-4 flex flex-col flex-1 justify-between gap-3">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight line-clamp-2">{{ $menu['name'] }}</p>
                                @if(!empty($menu['description']))
                                <p class="text-[11.5px] text-gray-400 font-medium leading-snug line-clamp-1 mt-1">{{ $menu['description'] }}</p>
                                @endif
                            </div>
                            
                            <div class="flex items-end justify-between mt-auto">
                                <span class="text-[15px] font-black tracking-tight" style="color:#FF6900;">
                                    Rp {{ number_format($menu['price'], 0, ',', '.') }}
                                </span>

                                @if($kantin['is_open'] ?? false)
                                    @if($menu['is_available'] ?? true)
                                    <button type="button"
                                        onclick="event.preventDefault(); addToCart(this, '{{ $menu['_id'] }}', {{ $menu['price'] }})"
                                        class="add-btn">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                    @else
                                    <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-1 rounded-md border border-red-100">Habis</span>
                                    @endif
                                @else
                                    <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Tutup</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @empty
            <div class="flex flex-col items-center justify-center py-32 gap-3 opacity-60">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                    <i class="fa-solid fa-bowl-food text-3xl text-gray-400"></i>
                </div>
                <p class="text-lg font-extrabold text-gray-700">Kantin Belum Memiliki Menu</p>
                <p class="text-sm text-gray-500 font-medium">Silakan kembali lagi nanti.</p>
            </div>
            @endforelse

        </div>
    </main>{{-- END MAIN CONTENT --}}

</div>{{-- END FLEX WRAPPER --}}

{{-- FLOATING CART --}}
<div id="cartFloat" class="cart-float hidden">
    <button onclick="window.location.href='/keranjang'"
        class="flex items-center gap-3 px-6 py-4 rounded-[20px] text-white font-extrabold text-sm shadow-2xl"
        style="background: linear-gradient(135deg, #FF6900, #ea580c); box-shadow: 0 10px 25px rgba(255,105,0,0.3);">
        <div class="relative flex items-center justify-center">
            <i class="fa-solid fa-cart-shopping text-lg"></i>
            <span id="cartCount"
                class="absolute -top-2 -right-2.5 w-5 h-5 rounded-full bg-white text-[10px] font-black flex items-center justify-center"
                style="color:#FF6900; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">0</span>
        </div>
        <div class="w-px h-5 bg-white/20 mx-1"></div>
        <div class="flex flex-col items-start text-left leading-tight">
            <span class="text-[11px] font-semibold text-orange-100">Total Belanja</span>
            <span id="cartTotal" class="text-[15px] font-black tracking-tight">Rp 0</span>
        </div>
        <i class="fa-solid fa-chevron-right ml-2 text-orange-200 text-xs"></i>
    </button>
</div>

@endsection

@push('scripts')
<script>
    let cartCount = 0;
    let cartTotal = 0;

    function showToast(message, type = 'success') {
        const existing = document.getElementById('toastNotif');
        if (existing) existing.remove();

        const colors = type === 'success'
            ? 'background: linear-gradient(135deg, #22c55e, #16a34a);'
            : 'background: linear-gradient(135deg, #ef4444, #dc2626);';

        const icon = type === 'success' ? 'fa-check' : 'fa-xmark';

        const toast = document.createElement('div');
        toast.id = 'toastNotif';
        toast.style.cssText = `
            position: fixed; top: 24px; right: 24px; z-index: 9999;
            display: flex; align-items: center; gap: 12px;
            padding: 14px 20px; border-radius: 16px; color: white;
            font-size: 14px; font-weight: 700; box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1); ${colors}
        `;
        toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function addToCart(btn, menuId, price) {
        btn.style.backgroundColor = '#FF6900';
        btn.style.color = 'white';
        btn.style.borderColor = '#FF6900';
        btn.style.transform = 'scale(1.15)';
        btn.disabled = true;
        setTimeout(() => { btn.style.transform = 'scale(1)'; }, 150);

        fetch('/keranjang/items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ menu_id: menuId, quantity: 1 }),
        })
        .then(async r => {
            if (r.status === 401 || (r.redirected && r.url.includes('/login'))) {
                window.location.href = '/login';
                return Promise.reject('unauthorized');
            }

            const contentType = r.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                window.location.href = '/login';
                return Promise.reject('unauthorized');
            }

            const data = await r.json();
            
            if (!r.ok) {
                return Promise.reject(data.message || 'Gagal menambahkan ke keranjang.');
            }

            return data;
        })
        .then(data => {
            btn.disabled = false;
            setTimeout(() => {
                btn.style.backgroundColor = 'white';
                btn.style.color = '#FF6900'; 
            }, 300);

            cartCount++;
            cartTotal += price;
            updateCartFloat();
            showToast('Berhasil ditambahkan ke keranjang!', 'success');
        })
        .catch(error => {
            if (error === 'unauthorized') return; 

            btn.disabled = false;
            btn.style.backgroundColor = 'white';
            btn.style.color = '#9CA3AF';
            btn.style.borderColor = '#F3F4F6';
            
            const errorMsg = typeof error === 'string' ? error : 'Terjadi kesalahan. Coba lagi.';
            showToast(errorMsg, 'error');
        });
    }

    function updateCartFloat() {
        document.getElementById('cartCount').textContent = cartCount;
        document.getElementById('cartTotal').textContent = 'Rp ' + cartTotal.toLocaleString('id-ID');
        
        const cartFloat = document.getElementById('cartFloat');
        if (cartCount > 0) {
            cartFloat.classList.remove('hidden');
        } else {
            cartFloat.classList.add('hidden');
        }
    }

    function searchMenu() {
        const q = document.getElementById('menuSearch').value.toLowerCase().trim();
        document.querySelectorAll('.menu-item-card').forEach(card => {
            if(card.dataset.name.includes(q)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
        
        document.querySelectorAll('.menu-section').forEach(section => {
            const visible = [...section.querySelectorAll('.menu-item-card')].some(c => c.style.display !== 'none');
            section.style.display = visible ? 'block' : 'none';
        });
    }
</script>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(40px) scale(0.95); }
        to { opacity: 1; transform: translateX(0) scale(1); }
    }
    @keyframes slideOut {
        from { opacity: 1; transform: translateX(0) scale(1); }
        to { opacity: 0; transform: translateX(40px) scale(0.95); }
    }
</style>
@endpush