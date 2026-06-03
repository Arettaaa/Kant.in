@extends('layouts.app')

@section('title', 'Detail Menu - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Scrollbar ── */
    .detail-scroll::-webkit-scrollbar { width: 4px; }
    .detail-scroll::-webkit-scrollbar-track { background: transparent; }
    .detail-scroll::-webkit-scrollbar-thumb { background: #f0e8e0; border-radius: 99px; }

    /* ── Image Panel ── */
    .img-panel {
        position: relative;
        width: 420px;
        flex-shrink: 0;
        height: 100vh;
        overflow: hidden;
        background: #1a0a00;
    }
    .img-panel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Overlay gradients */
    .img-overlay-top {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0.1) 30%, transparent 60%);
        pointer-events: none;
    }
    .img-overlay-bottom {
        position: absolute; inset: 0;
        background: linear-gradient(0deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.2) 35%, transparent 60%);
        pointer-events: none;
    }

    /* Back button */
    .back-btn {
        position: absolute;
        top: 20px; left: 20px;
        width: 36px; height: 36px;
        border-radius: 50%;
        background-color: white; 
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); 
        color: #4b5563; 
        transition: all 0.2s ease;
        z-index: 10;
        text-decoration: none;
    }
    .back-btn:hover {
        background-color: #f9fafb;
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
    .back-btn svg {
        width: 16px;
        height: 16px;
        margin-right: 2px;
    }

    /* ── Right Panel ── */
    .right-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100vh;
        background: #FAFAF8;
        overflow: hidden;
        position: relative; 
    }

    /* Header strip */
    .right-header {
        background: white;
        padding: 28px 32px 20px;
        border-bottom: 1px solid #F0EDE8;
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .menu-title {
        font-size: 26px;
        font-weight: 900;
        color: #111;
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    /* Price chip in header */
    .price-chip {
        display: inline-flex; align-items: baseline; gap: 4px;
        background: linear-gradient(135deg, #FF6900, #ea580c);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 24px; font-weight: 900;
        letter-spacing: -0.5px;
        margin-bottom: 14px;
    }

    /* Stats row */
    .stats-row {
        display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
    }
    .stat-chip {
        display: flex; align-items: center; gap: 6px;
        background: #F7F4F0;
        border-radius: 99px;
        padding: 5px 12px;
        font-size: 12.5px; font-weight: 700; color: #555;
    }
    .stat-chip .icon { font-size: 12px; }
    .stat-chip .val { color: #111; }

    /* Scrollable body */
    .right-body {
        flex: 1; overflow-y: auto; padding: 24px 32px 0;
        display: flex; flex-direction: column; gap: 20px;
    }

    /* Section card */
    .section-card {
        background: white; border-radius: 18px; padding: 20px 22px;
        border: 1px solid #EDE9E3;
    }
    .section-label {
        font-size: 11px; font-weight: 800; letter-spacing: 0.9px;
        text-transform: uppercase; color: #B8A898; margin-bottom: 10px;
    }

    /* Canteen card */
    .canteen-card {
        background: white; border-radius: 18px; border: 1px solid #EDE9E3;
        padding: 16px 18px; display: flex; align-items: center; gap: 14px;
        text-decoration: none; transition: all 0.2s ease;
    }
    .canteen-card:hover {
        background: #FFF8F2; border-color: #FFD0A8;
        transform: translateY(-1px); box-shadow: 0 6px 20px rgba(255,105,0,0.1);
    }
    .canteen-icon {
        width: 44px; height: 44px; border-radius: 14px;
        background: linear-gradient(135deg, #FFF0E0, #FFD8AA);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .canteen-icon i { color: #FF6900; font-size: 17px; }
    .canteen-name { font-size: 14px; font-weight: 800; color: #1a1a1a; line-height: 1.3; }
    .canteen-sub { font-size: 11.5px; font-weight: 500; color: #999; margin-top: 1px; }
    .canteen-arrow { margin-left: auto; flex-shrink: 0; color: #ccc; }

    /* Description */
    .desc-text {
        font-size: 13.5px; color: #666; line-height: 1.75; font-weight: 500;
    }

    /* ── Bottom Bar ── */
    .bottom-bar {
        background: white; border-top: 1px solid #EDE9E3;
        padding: 16px 32px 20px; display: flex; align-items: center; gap: 12px;
        flex-shrink: 0; position: relative; z-index: 20;
    }

    /* Qty control */
    .qty-control {
        display: flex; align-items: center; gap: 0;
        background: #F5F1EC; border-radius: 99px; padding: 5px;
        flex-shrink: 0; border: 1.5px solid #EAE4DC;
    }
    .qty-btn {
        width: 34px; height: 34px; border-radius: 50%; border: none; background: transparent;
        display: flex; align-items: center; justify-content: center;
        color: #888; cursor: pointer; transition: all 0.15s; font-size: 11px;
    }
    .qty-btn:hover { background: #FF6900; color: white; }
    .qty-display { font-size: 15px; font-weight: 900; color: #111; width: 32px; text-align: center; }

    /* Button Tambah Keranjang (Outline) */
    .btn-cart-outline {
        width: 48px; height: 48px;
        border-radius: 99px;
        border: 2px solid #FF6900;
        color: #FF6900; background: white;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.2s ease; flex-shrink: 0;
    }
    .btn-cart-outline:hover { background: #FFF3E8; transform: translateY(-2px); }
    .btn-cart-outline:active { transform: translateY(0); }
    .btn-cart-outline:disabled { opacity: 0.8; cursor: not-allowed; transform: none; }

    /* Button Beli Sekarang (Solid) */
    .add-cart-btn {
        flex: 1; height: 48px; padding: 0 20px;
        border-radius: 99px; border: none;
        background: linear-gradient(135deg, #FF6900 0%, #ea580c 100%);
        color: white; font-size: 14px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        cursor: pointer; transition: all 0.2s ease;
        box-shadow: 0 4px 16px rgba(255,105,0,0.3); letter-spacing: 0.1px;
    }
    .add-cart-btn:hover {
        filter: brightness(1.07); transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(255,105,0,0.38);
    }
    .add-cart-btn:active { transform: translateY(0); box-shadow: 0 4px 16px rgba(255,105,0,0.3); }
    .add-cart-btn:disabled { opacity: 0.8; cursor: not-allowed; transform: none; }

    /* Unavailable states */
    .state-closed {
        flex: 1; height: 48px; padding: 0 20px; border-radius: 99px;
        background: #F3F3F3; color: #AAA; font-size: 13px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; gap: 8px; cursor: not-allowed;
    }
    .state-soldout {
        flex: 1; height: 48px; padding: 0 20px; border-radius: 99px;
        background: #FFF1F1; border: 1.5px solid #FFCDD0; color: #E05252;
        font-size: 13px; font-weight: 800;
        display: flex; align-items: center; justify-content: center; gap: 8px; cursor: not-allowed;
    }

    .btn-price-sep { opacity: 0.55; margin: 0 2px; font-weight: 400; }
    .scroll-spacer { height: 8px; flex-shrink: 0; }

    /* Toast */
    @keyframes slideInToast { from { opacity: 0; transform: translateX(48px) scale(0.95); } to { opacity: 1; transform: translateX(0) scale(1); } }
    @keyframes slideOutToast { from { opacity: 1; transform: translateX(0) scale(1); } to { opacity: 0; transform: translateX(48px) scale(0.95); } }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen overflow-hidden" style="background:#FAFAF8;">

    {{-- ======================== LEFT: FOTO ======================== --}}
    <div class="img-panel">
        @if(!empty($menu['image']))
            <img src="{{ $menu['image'] }}" alt="{{ $menu['name'] }}">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center" style="background: linear-gradient(160deg,#2d1300,#1a0a00);">
                <i class="fa-solid fa-utensils" style="font-size:72px;color:rgba(255,105,0,0.25);"></i>
            </div>
        @endif

        <div class="img-overlay-top"></div>
        <div class="img-overlay-bottom"></div>

        <a href="javascript:history.back()" class="back-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>

    {{-- ======================== RIGHT: DETAIL ======================== --}}
    <div class="right-panel">

        {{-- Header dengan Tombol Keranjang Global --}}
        <div class="right-header">
            <div class="flex-1 min-w-0">
                <h1 class="menu-title">{{ $menu['name'] }}</h1>
                <div class="price-chip">Rp {{ number_format($menu['price'], 0, ',', '.') }}</div>

                <div class="stats-row">
                    @if(!empty($menu['average_rating']))
                    <div class="stat-chip">
                        <i class="fa-solid fa-star icon" style="color:#FBBF24;"></i>
                        <span class="val">{{ number_format($menu['average_rating'], 1) }}</span>
                        @if(!empty($menu['total_reviews']))
                        <span style="color:#AAA;font-weight:600;">({{ $menu['total_reviews'] }})</span>
                        @endif
                    </div>
                    @else
                    <div class="stat-chip">
                        <i class="fa-regular fa-star icon" style="color:#CCC;"></i>
                        <span>Belum ada rating</span>
                    </div>
                    @endif

                    @if(!empty($menu['estimated_cooking_time']))
                    <div class="stat-chip">
                        <i class="fa-regular fa-clock icon" style="color:#FF6900;"></i>
                        <span class="val">{{ $menu['estimated_cooking_time'] }} menit</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- TOMBOL KERANJANG PERMANENT (Persis sama dengan Beranda) --}}
            <a href="/keranjang" class="relative w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all ml-4 flex-shrink-0">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span id="stayCartBadge" class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-[10px] font-black text-white flex items-center justify-center {{ ($cartCount ?? 0) > 0 ? '' : 'hidden' }}" style="background-color:#FF6900;">
                    {{ ($cartCount ?? 0) > 9 ? '9+' : ($cartCount ?? 0) }}
                </span>
            </a>
        </div>

        {{-- Scrollable body --}}
        <div class="right-body detail-scroll">
            <a href="/kantin/{{ $menu['canteen_id'] }}" class="canteen-card">
                <div class="canteen-icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <div class="canteen-name">{{ $canteenName }}</div>
                    <div class="canteen-sub">Lihat semua menu kantin</div>
                </div>
                <div class="canteen-arrow">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            @if(!empty($menu['description']))
            <div class="section-card">
                <div class="section-label">Deskripsi</div>
                <p class="desc-text">{{ $menu['description'] }}</p>
            </div>
            @endif

            <div class="scroll-spacer"></div>
        </div>

        {{-- ======================== BOTTOM BAR ======================== --}}
        @php
            $isAvailable = $menu['is_available'] ?? true;
            $canOrderNow = $bisaPesan && $isAvailable;
        @endphp

        <div class="bottom-bar">

            {{-- Qty --}}
            <div class="qty-control {{ !$canOrderNow ? 'opacity-40 pointer-events-none' : '' }}">
                <button type="button" class="qty-btn" onclick="changeQty(-1)">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <span id="qtyDisplay" class="qty-display">1</span>
                <button type="button" class="qty-btn" onclick="changeQty(1)">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            {{-- Action buttons --}}
            @if(!$bisaPesan)
                <div class="state-closed">
                    <i class="fa-solid fa-store-slash"></i>
                    <span>
                        @if(!$isOpen) Kantin Sedang Tutup @else Tutup ({{ $open }} – {{ $close }}) @endif
                    </span>
                </div>
            @elseif(!$isAvailable)
                <div class="state-soldout">
                    <i class="fa-solid fa-ban"></i>
                    <span>Menu Sedang Habis</span>
                </div>
            @else
                {{-- Tombol Keranjang (Ikon Saja) --}}
                <button id="btnAddToCart" type="button" class="btn-cart-outline" onclick="processOrder(false)" title="Tambah ke Keranjang">
                    <i class="fa-solid fa-cart-plus text-lg"></i>
                </button>
                
                {{-- Tombol Beli Sekarang --}}
                <button id="btnBuyNow" type="button" class="add-cart-btn" onclick="processOrder(true)">
                    <span>Beli Sekarang</span>
                    <span class="btn-price-sep">·</span>
                    <span id="totalPrice">Rp {{ number_format($menu['price'], 0, ',', '.') }}</span>
                </button>
            @endif

        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    const basePrice = {{ $menu['price'] }};
    const menuId    = '{{ $menu['_id'] }}';
    let qty = 1;

    // Ambil data total jenis menu awal dari server
    let globalCartCount = {{ $cartCount ?? 0 }};
    // Ketahui kondisi awal apakah menu ini sudah ada di keranjang sebelumnya atau tidak
    let itemAlreadyInCart = {{ $itemInCart ? 'true' : 'false' }};

    window.addEventListener('DOMContentLoaded', () => {
        updateCartBadgeUI();
    });

    function changeQty(delta) {
        qty = Math.max(1, qty + delta);
        document.getElementById('qtyDisplay').textContent = qty;
        document.getElementById('totalPrice').textContent = 'Rp ' + (basePrice * qty).toLocaleString('id-ID');
    }

    function showToast(message, type = 'success') {
        const existing = document.getElementById('toastNotif');
        if (existing) existing.remove();

        const colors = type === 'success' ? 'background: linear-gradient(135deg, #22c55e, #16a34a);' : 'background: linear-gradient(135deg, #ef4444, #dc2626);';
        const icon = type === 'success' ? 'fa-check' : 'fa-xmark';

        const toast = document.createElement('div');
        toast.id = 'toastNotif';
        toast.style.cssText = `
            position: fixed; top: 24px; right: 24px; z-index: 9999;
            display: flex; align-items: center; gap: 12px;
            padding: 14px 20px; border-radius: 16px; color: white;
            font-size: 14px; font-weight: 700; box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            animation: slideInToast 0.3s ease; ${colors}
        `;
        toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOutToast 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function updateCartBadgeUI() {
        const stayBadge = document.getElementById('stayCartBadge');
        if (stayBadge) {
            if (globalCartCount > 0) {
                stayBadge.textContent = globalCartCount > 9 ? '9+' : globalCartCount;
                stayBadge.classList.remove('hidden');
            } else {
                stayBadge.classList.add('hidden');
            }
        }
    }

    function processOrder(isBuyNow = false) {
        const btnBuy  = document.getElementById('btnBuyNow');
        const btnCart = document.getElementById('btnAddToCart');
        
        btnBuy.disabled = true;
        btnCart.disabled = true;

        if (isBuyNow) {
            btnBuy.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Memproses...</span>';
        } else {
            btnCart.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        }

        fetch('/keranjang/items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ menu_id: menuId, quantity: qty }),
        })
        .then(async r => {
            if (r.status === 401 || (r.redirected && r.url.includes('/login'))) {
                window.location.href = '/login';
                return Promise.reject('unauthorized');
            }
            const data = await r.json();
            if (!r.ok) return Promise.reject(data.message || 'Gagal memproses pesanan.');
            return data;
        })
        .then(() => {
            if (isBuyNow) {
                window.location.href = '/keranjang?buy_now=' + menuId;
            } else {
                // LOGIKA REAL-TIME: Hanya tambah +1 jenis jika menu ini benar-benar belum ada di keranjang sebelumnya
                if (!itemAlreadyInCart) {
                    globalCartCount += 1;
                    itemAlreadyInCart = true; // Kunci biar klik klik berikutnya gak nambah angka badge lagi
                }
                
                updateCartBadgeUI();

                btnCart.style.background = '#22c55e';
                btnCart.style.borderColor = '#22c55e';
                btnCart.style.color = 'white';
                btnCart.innerHTML = '<i class="fa-solid fa-check"></i>';
                
                showToast('Berhasil ditambahkan ke keranjang!', 'success');
                setTimeout(() => resetButtons(), 1600);
            }
        })
        .catch(error => {
            if (error === 'unauthorized') return;
            resetButtons();
            showToast(typeof error === 'string' ? error : 'Terjadi kesalahan. Coba lagi.', 'error');
        });
    }

    function resetButtons() {
        const btnBuy  = document.getElementById('btnBuyNow');
        const btnCart = document.getElementById('btnAddToCart');
        
        if (btnBuy) {
            btnBuy.disabled = false;
            btnBuy.innerHTML = `
                <span>Beli Sekarang</span>
                <span class="btn-price-sep">·</span>
                <span>Rp ${(basePrice * qty).toLocaleString('id-ID')}</span>
            `;
        }

        if (btnCart) {
            btnCart.disabled = false;
            btnCart.style.background = 'white';
            btnCart.style.borderColor = '#FF6900';
            btnCart.style.color = '#FF6900';
            btnCart.innerHTML = '<i class="fa-solid fa-cart-plus text-lg"></i>';
        }
    }

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            resetButtons();
        }
    });
</script>
@endpush