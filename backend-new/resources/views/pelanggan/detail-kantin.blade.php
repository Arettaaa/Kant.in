@extends('layouts.app')

@section('title', 'Detail Kantin - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .menu-item-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .menu-item-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        transform: translateY(-1px);
    }
    .menu-item-card .menu-img {
        transition: transform 0.3s ease;
    }
    .menu-item-card:hover .menu-img {
        transform: scale(1.05);
    }

    .add-btn {
        transition: all 0.15s ease;
    }
    .add-btn:hover {
        background-color: #FF6900;
        color: white;
        transform: scale(1.1);
    }

    /* Left panel hero image with overlay info */
    .left-panel {
        width: 300px;
        flex-shrink: 0;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .hero-img-wrap {
        height: 300px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .hero-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    /* Cart badge */
    .cart-float {
        position: fixed;
        bottom: 32px;
        right: 40px;
        z-index: 50;
        transition: all 0.2s ease;
    }
    .cart-float:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== LEFT PANEL ======================== --}}
    <div class="left-panel h-screen bg-white border-r border-gray-100 flex flex-col overflow-y-auto">

        {{-- Back button over hero image --}}
        <div class="relative">
            <div class="hero-img-wrap">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80"
                     alt="Warung Bu Ani">
            </div>
            {{-- Back button --}}
            <a href="/beranda"
               class="absolute top-4 left-4 w-9 h-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all">
                <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

        {{-- Info card --}}
        <div class="flex-1 px-6 py-5 flex flex-col gap-4">

            {{-- Name + Rating --}}
            <div class="flex items-start justify-between gap-2">
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">Warung Bu Ani</h1>
                <div class="flex items-center gap-1.5 bg-amber-50 px-2.5 py-1.5 rounded-xl flex-shrink-0">
                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                    <span class="text-sm font-extrabold text-gray-800">4.8</span>
                </div>
            </div>

            {{-- Description --}}
            <p class="text-sm text-gray-500 leading-relaxed">
                Menyediakan nasi goreng khas dengan bumbu rempah pilihan, lauk lengkap, dan aneka minuman segar.
            </p>

            {{-- Meta --}}
            <div class="flex items-center gap-3 text-xs text-gray-400 font-semibold">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" style="color:#FF6900;" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Kantin Utama
                </span>
                <span class="text-gray-200">•</span>
                <span class="flex items-center gap-1">
                    <i class="fa-regular fa-clock text-gray-400"></i>
                    10–15 min
                </span>
                <span class="text-gray-200">•</span>
                <span class="flex items-center gap-1 font-bold" style="color:#22c55e;">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                    Buka
                </span>
            </div>

            {{-- Search --}}
            <div class="relative mt-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="menuSearch"
                       type="text"
                       placeholder="Cari menu di warung ini..."
                       oninput="searchMenu()"
                       onfocus="this.style.borderColor='#FF6900'; this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.12)';"
                       onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='';"
                       class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 text-sm text-gray-700 placeholder-gray-400 focus:outline-none transition-all">
            </div>
        </div>
    </div>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="px-8 py-8 flex flex-col gap-8 pb-28">

            {{-- ---- MAKANAN UTAMA ---- --}}
            <section class="menu-section" data-section="makanan utama">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Makanan Utama</h2>
                <div class="grid grid-cols-2 gap-4">

                    {{-- Nasi Goreng Spesial --}}
                    <a href="/menu/nasi-goreng-spesial" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="nasi goreng spesial">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=300&q=80"
                                 alt="Nasi Goreng Spesial" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Nasi Goreng Spesial</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Nasi goreng dengan telur & ayam</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 25.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Nasi Kuning Komplit --}}
                    <a href="/menu/nasi-kuning-komplit" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="nasi kuning komplit">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=300&q=80"
                                 alt="Nasi Kuning Komplit" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Nasi Kuning Komplit</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Nasi kuning + lauk lengkap</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 20.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Nasi Uduk --}}
                    <a href="/menu/nasi-uduk" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="nasi uduk">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=300&q=80"
                                 alt="Nasi Uduk" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Nasi Uduk</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Nasi uduk Betawi + serundeng</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 18.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Nasi Pecel --}}
                    <a href="/menu/nasi-pecel" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="nasi pecel">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=300&q=80"
                                 alt="Nasi Pecel" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Nasi Pecel</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Sayur segar + sambal kacang</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 15.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </section>

            {{-- ---- MINUMAN ---- --}}
            <section class="menu-section" data-section="minuman">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Minuman</h2>
                <div class="grid grid-cols-2 gap-4">

                    {{-- Es Teh Manis --}}
                    <a href="/menu/es-teh-manis" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="es teh manis">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=300&q=80"
                                 alt="Es Teh Manis" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Es Teh Manis</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Es teh manis segar</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 5.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Es Jeruk --}}
                    <a href="/menu/es-jeruk" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="es jeruk">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?w=300&q=80"
                                 alt="Es Jeruk" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Es Jeruk</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Es jeruk peras asli</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 6.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Es Kopi Susu --}}
                    <a href="/menu/es-kopi-susu" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="es kopi susu">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=300&q=80"
                                 alt="Es Kopi Susu" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Es Kopi Susu</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Kopi susu dingin creamy</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 10.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Air Mineral --}}
                    <div class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="air mineral">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1548839140-29a749e1cf4d?w=300&q=80"
                                 alt="Air Mineral" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Air Mineral</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Botol 600ml</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 3.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </section>

            {{-- ---- CAMILAN ---- --}}
            <section class="menu-section" data-section="camilan">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Camilan</h2>
                <div class="grid grid-cols-2 gap-4">

                    {{-- Tahu Krispi --}}
                    <a href="/menu/tahu-krispi" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="tahu krispi">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=300&q=80"
                                 alt="Tahu Krispi" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Tahu Krispi</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Tahu goreng renyah + cabai</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 8.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Tempe Mendoan --}}
                    <a href="/menu/tempe-mendoan" class="menu-item-card bg-white rounded-2xl p-3 shadow-sm border border-gray-100 flex gap-3"
                         data-name="tempe mendoan">
                        <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1604329760661-e71dc83f8f26?w=300&q=80"
                                 alt="Tempe Mendoan" class="menu-img w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                            <div>
                                <p class="text-sm font-extrabold text-gray-900 leading-tight">Tempe Mendoan</p>
                                <p class="text-xs text-gray-400 mt-1 leading-snug">Tempe tepung goreng setengah matang</p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 5.000</span>
                                <div class="add-btn w-7 h-7 rounded-full border-2 flex items-center justify-center text-gray-300 border-gray-200">
                                    <i class="fa-solid fa-plus text-[10px]"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </section>

        </div>
    </main>
</div>

{{-- ======================== FLOATING CART BUTTON ======================== --}}
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
        // Animate button
        btn.style.backgroundColor = '#FF6900';
        btn.style.color = 'white';
        btn.style.borderColor = '#FF6900';
        btn.style.transform = 'scale(1.15)';
        setTimeout(() => { btn.style.transform = 'scale(1)'; }, 150);

        // Add or increment
        const existing = cart.find(i => i.name === name);
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ name, price, qty: 1 });
        }

        updateCart();
    }

    function updateCart() {
        const totalQty   = cart.reduce((s, i) => s + i.qty, 0);
        const totalPrice = cart.reduce((s, i) => s + i.price * i.qty, 0);

        document.getElementById('cartCount').textContent = totalQty;
        document.getElementById('cartTotal').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');

        const floatEl = document.getElementById('cartFloat');
        if (totalQty > 0) {
            floatEl.classList.remove('hidden');
        } else {
            floatEl.classList.add('hidden');
        }
    }

    function searchMenu() {
        const q = document.getElementById('menuSearch').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.menu-item-card');

        cards.forEach(card => {
            const match = card.dataset.name.includes(q);
            card.style.display = match ? 'flex' : 'none';
        });

        // Hide section headers if all items in section hidden
        document.querySelectorAll('.menu-section').forEach(section => {
            const visible = [...section.querySelectorAll('.menu-item-card')]
                .some(c => c.style.display !== 'none');
            section.style.display = visible ? 'block' : 'none';
        });
    }
</script>
@endpush