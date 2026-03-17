@extends('layouts.app')

@section('title', 'Detail Menu - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .addon-row {
        transition: all 0.15s ease;
        cursor: pointer;
    }
    .addon-row:hover {
        background-color: #FFFAF7;
    }
    .addon-row.checked {
        background-color: #FFF7ED;
    }

    .custom-checkbox {
        width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s ease;
        background: white;
    }
    .addon-row.checked .custom-checkbox {
        background-color: #FF6900;
        border-color: #FF6900;
    }
    .check-icon {
        display: none;
        color: white;
        font-size: 10px;
    }
    .addon-row.checked .check-icon {
        display: block;
    }

    .qty-btn {
        transition: all 0.15s ease;
    }
    .qty-btn:hover {
        background-color: #FF6900;
        color: white;
        border-color: #FF6900;
    }

    .add-cart-btn {
        transition: all 0.2s ease;
    }
    .add-cart-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.35);
    }
    .add-cart-btn:active {
        transform: translateY(0);
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-white overflow-hidden">

    {{-- ======================== LEFT: FOTO ======================== --}}
    <div class="w-[420px] flex-shrink-0 relative h-screen overflow-hidden bg-gray-100">
        <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=800&q=85"
             alt="Nasi Goreng Spesial"
             class="w-full h-full object-cover">

        {{-- Gradient overlay bottom --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(to top, rgba(0,0,0,0.3) 0%, transparent 50%);"></div>

        {{-- Back button --}}
        <a href="javascript:history.back()"
           class="absolute top-5 left-5 w-9 h-9 rounded-full bg-white/80 backdrop-blur-sm flex items-center justify-center shadow-md hover:bg-white transition-all">
            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    {{-- ======================== RIGHT: DETAIL ======================== --}}
    <div class="flex-1 flex flex-col h-screen overflow-y-auto bg-white">

        <div class="px-8 py-8 flex flex-col gap-6 flex-1">

            {{-- TITLE + PRICE --}}
            <div class="flex items-start justify-between gap-4">
                <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">Nasi Goreng Spesial</h1>
                <span class="text-xl font-extrabold flex-shrink-0" style="color:#FF6900;">Rp 25.000</span>
            </div>

            {{-- RATING + WAKTU --}}
            <div class="flex items-center gap-3 text-sm text-gray-500 font-semibold -mt-3">
                <span class="flex items-center gap-1.5">
                    <i class="fa-solid fa-star text-amber-400 text-base"></i>
                    <span class="font-extrabold text-gray-800">4.8</span>
                    <span class="text-gray-400">(124 ulasan)</span>
                </span>
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-gray-400"></i>
                    15 mnt waktu siapkan
                </span>
            </div>

            {{-- KANTIN CARD --}}
            <a href="/kantin/warung-bu-ani"
               class="flex items-center gap-3 px-4 py-3.5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-orange-50 hover:border-orange-100 transition-all">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=100&q=80"
                     alt="Warung Bu Ani"
                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-extrabold text-gray-900">Warung Bu Ani</p>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Kantin Utama</p>
                </div>
                <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            {{-- DESKRIPSI --}}
            <div>
                <h2 class="text-base font-extrabold text-gray-900 mb-2">Deskripsi</h2>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Sepiring nasi goreng khas Indonesia yang menggugah selera, dimasak dengan kecap manis,
                    disajikan dengan telur mata sapi yang digoreng sempurna, taburan bawang goreng renyah,
                    irisan mentimun, dan tomat segar. Makanan lezat yang cocok dinikmati kapan saja.
                </p>
            </div>

            {{-- TAMBAHAN --}}
            <div>
                <h2 class="text-base font-extrabold text-gray-900 mb-3">Tambahan (Opsional)</h2>
                <div class="flex flex-col rounded-2xl border border-gray-100 overflow-hidden">

                    {{-- Telur Tambahan --}}
                    <div class="addon-row flex items-center gap-3 px-4 py-4 border-b border-gray-100"
                         onclick="toggleAddon(this, 5000)">
                        <div class="custom-checkbox">
                            <i class="fa-solid fa-check check-icon"></i>
                        </div>
                        <span class="flex-1 text-sm font-semibold text-gray-700">Telur Tambahan</span>
                        <span class="text-sm font-bold" style="color:#FF6900;">+Rp 5.000</span>
                    </div>

                    {{-- Sosis Ayam --}}
                    <div class="addon-row flex items-center gap-3 px-4 py-4 border-b border-gray-100"
                         onclick="toggleAddon(this, 8000)">
                        <div class="custom-checkbox">
                            <i class="fa-solid fa-check check-icon"></i>
                        </div>
                        <span class="flex-1 text-sm font-semibold text-gray-700">Sosis Ayam</span>
                        <span class="text-sm font-bold" style="color:#FF6900;">+Rp 8.000</span>
                    </div>

                    {{-- Sambal Pedas --}}
                    <div class="addon-row flex items-center gap-3 px-4 py-4"
                         onclick="toggleAddon(this, 0)">
                        <div class="custom-checkbox">
                            <i class="fa-solid fa-check check-icon"></i>
                        </div>
                        <span class="flex-1 text-sm font-semibold text-gray-700">Sambal Pedas</span>
                        <span class="text-sm font-semibold text-gray-400">Gratis</span>
                    </div>

                </div>
            </div>

        </div>

        {{-- ======================== BOTTOM BAR ======================== --}}
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-8 py-5 flex items-center gap-4">

            {{-- Qty controls --}}
            <div class="flex items-center gap-3 bg-gray-50 rounded-2xl px-4 py-2.5">
                <button type="button" onclick="changeQty(-1)"
                        class="qty-btn w-8 h-8 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-400 flex-shrink-0">
                    <i class="fa-solid fa-minus text-xs"></i>
                </button>
                <span id="qtyDisplay" class="text-base font-extrabold text-gray-900 w-5 text-center">1</span>
                <button type="button" onclick="changeQty(1)"
                        class="qty-btn w-8 h-8 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center text-gray-400 flex-shrink-0">
                    <i class="fa-solid fa-plus text-xs"></i>
                </button>
            </div>

            {{-- Add to cart button --}}
            <a href="/keranjang"
               id="addCartBtn"
               class="add-cart-btn flex-1 py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
               style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                <i class="fa-solid fa-bag-shopping text-base"></i>
                <span>Tambah ke Keranjang</span>
                <span id="totalPrice" class="font-extrabold">· Rp 25.000</span>
            </a>

        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const basePrice = 25000;
    let qty         = 1;
    let addonTotal  = 0;

    function changeQty(delta) {
        qty = Math.max(1, qty + delta);
        document.getElementById('qtyDisplay').textContent = qty;
        updateTotal();
    }

    function toggleAddon(row, price) {
        const wasChecked = row.classList.contains('checked');
        row.classList.toggle('checked');
        addonTotal += wasChecked ? -price : price;
        updateTotal();
    }

    function updateTotal() {
        const total = (basePrice + addonTotal) * qty;
        document.getElementById('totalPrice').textContent =
            '· Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush