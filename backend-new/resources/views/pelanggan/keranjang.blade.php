@extends('layouts.app')

@section('title', 'Keranjang Saya - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .cart-item {
        transition: all 0.2s ease;
    }
    .cart-item.removing {
        opacity: 0;
        transform: translateX(20px);
    }

    .qty-btn {
        transition: all 0.15s ease;
    }
    .qty-btn:hover {
        background-color: #FF6900;
        color: white;
        border-color: #FF6900;
    }

    .metode-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .metode-card.active {
        border-color: #FF6900;
        background-color: #FFFAF7;
    }
    .metode-card:not(.active):hover {
        border-color: #fdba74;
    }

    .radio-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #d1d5db;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }
    .metode-card.active .radio-dot {
        border-color: #FF6900;
        background-color: #FF6900;
    }
    .radio-inner {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: white;
        display: none;
    }
    .metode-card.active .radio-inner {
        display: block;
    }

    .checkout-btn {
        transition: all 0.2s ease;
    }
    .checkout-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.35);
    }
    .checkout-btn:active {
        transform: translateY(0);
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== LEFT: KERANJANG ======================== --}}
    <div class="flex-1 flex flex-col h-screen overflow-y-auto border-r border-gray-100 bg-white">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-5 bg-white border-b border-gray-100">
            <a href="javascript:history.back()"
               class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-base font-extrabold text-gray-900">Keranjang Saya</h1>
            <button onclick="openClearModal()"
                    class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center hover:bg-red-50 hover:text-red-400 transition-all text-gray-400">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>

        {{-- Cart Items --}}
        <div id="cartItems" class="flex flex-col gap-3 px-6 py-6">

            {{-- Item 1: Nasi Goreng Spesial --}}
            <div class="cart-item bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                 data-id="1" data-price="25000">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=200&q=80"
                         alt="Nasi Goreng Spesial"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-extrabold text-gray-900">Nasi Goreng Spesial</p>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Warung Bu Ani</p>
                    <p class="text-sm font-extrabold mt-1" style="color:#FF6900;">Rp 25.000</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" onclick="changeQty('1', -1)"
                            class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold">
                        <i class="fa-solid fa-minus text-[10px]"></i>
                    </button>
                    <span id="qty-1" class="text-sm font-extrabold text-gray-800 w-5 text-center">2</span>
                    <button type="button" onclick="changeQty('1', 1)"
                            class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </button>
                </div>
            </div>

            {{-- Item 2: Brown Sugar Boba --}}
            <div class="cart-item bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-4"
                 data-id="2" data-price="18000">
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1558857563-b371033873b8?w=200&q=80"
                         alt="Brown Sugar Boba"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-extrabold text-gray-900">Brown Sugar Boba</p>
                    <p class="text-xs text-gray-400 font-medium mt-0.5">Fresh Sip</p>
                    <p class="text-sm font-extrabold mt-1" style="color:#FF6900;">Rp 18.000</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" onclick="changeQty('2', -1)"
                            class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold">
                        <i class="fa-solid fa-minus text-[10px]"></i>
                    </button>
                    <span id="qty-2" class="text-sm font-extrabold text-gray-800 w-5 text-center">1</span>
                    <button type="button" onclick="changeQty('2', 1)"
                            class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400 text-xs font-bold">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                    </button>
                </div>
            </div>

        </div>

        {{-- Empty state --}}
        <div id="emptyCart" class="hidden flex-col items-center justify-center py-20 gap-3 px-6">
            <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center mb-2">
                <i class="fa-solid fa-cart-shopping text-2xl" style="color:#FF6900;"></i>
            </div>
            <p class="text-base font-extrabold text-gray-700">Keranjang kosong</p>
            <p class="text-sm text-gray-400 font-medium text-center">Tambahkan makanan dari halaman kantin</p>
            <a href="/beranda"
               class="mt-2 px-6 py-2.5 rounded-2xl text-sm font-bold text-white"
               style="background-color:#FF6900;">
                Cari Makanan
            </a>
        </div>

        {{-- Metode Pemesanan --}}
        <div id="metodeSection" class="px-6 pb-8">
            <h2 class="text-base font-extrabold text-gray-900 mb-4">Metode Pemesanan</h2>
            <div class="grid grid-cols-2 gap-3">

                {{-- Ambil Sendiri --}}
                <div id="metode-ambil"
                     onclick="setMetode('ambil')"
                     class="metode-card active rounded-2xl border-2 p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-bag-shopping text-base" style="color:#FF6900;"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-800">Ambil Sendiri</p>
                        <p class="text-xs text-gray-400 mt-0.5">Siap dalam 10–15 menit</p>
                    </div>
                    <div class="radio-dot">
                        <div class="radio-inner"></div>
                    </div>
                </div>

                {{-- Kurir Antar --}}
                <div id="metode-kurir"
                     onclick="setMetode('kurir')"
                     class="metode-card rounded-2xl border-2 border-gray-200 p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-gray-100">
                        <i class="fa-solid fa-person-biking text-base text-gray-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-extrabold text-gray-800">Kurir Antar</p>
                            <span class="text-[11px] font-black px-1.5 py-0.5 rounded-lg"
                                  style="background-color:#FFF3E8; color:#FF6900;">+Rp 5rb</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">Diantar ke kelasmu</p>
                    </div>
                    <div class="radio-dot">
                        <div class="radio-inner"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ======================== RIGHT: RINGKASAN ======================== --}}
    <div class="w-[320px] flex-shrink-0 flex flex-col h-screen bg-[#F9FAFB]">

        <div class="flex-1 px-7 py-8 flex flex-col">
            <h2 class="text-lg font-extrabold text-gray-900 mb-6">Ringkasan Pesanan</h2>

            {{-- Summary rows --}}
            <div class="flex flex-col gap-3">
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm text-gray-400 font-semibold">Subtotal</span>
                    <span id="summarySubtotal" class="text-sm font-bold text-gray-800">Rp 68.000</span>
                </div>
                <div id="ongkirRow" class="hidden items-center justify-between py-1">
                    <span class="text-sm text-gray-400 font-semibold">Ongkos Kirim</span>
                    <span class="text-sm font-bold text-gray-800">Rp 5.000</span>
                </div>
                <div class="flex items-center justify-between pt-2">
                    <span class="text-base font-extrabold text-gray-900">Total</span>
                    <span id="summaryTotal" class="text-xl font-extrabold" style="color:#FF6900;">Rp 68.000</span>
                </div>
            </div>
        </div>

        {{-- Checkout button --}}
        <div class="px-7 pb-8">
            <button onclick="window.location.href='/checkout'"
                    class="checkout-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                Lanjut Pembayaran
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

    </div>

</div>

{{-- ======================== MODAL HAPUS KERANJANG ======================== --}}
<div id="clearModal"
     class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,0.4); backdrop-filter:blur(4px);">
    <div class="bg-white rounded-3xl shadow-2xl w-[340px] mx-4 overflow-hidden"
         style="animation: modalIn 0.2s ease;">
        {{-- Icon --}}
        <div class="flex flex-col items-center pt-8 pb-5 px-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4"
                 style="background-color:#FEF2F2;">
                <i class="fa-solid fa-trash-can text-xl" style="color:#ef4444;"></i>
            </div>
            <h3 class="text-base font-extrabold text-gray-900 mb-1">Hapus Keranjang</h3>
            <p class="text-sm text-gray-400 font-medium text-center leading-relaxed">
                Semua item yang ada di keranjang akan dihapus Anda yakin?
            </p>
        </div>

        {{-- Divider --}}
        <div class="h-px bg-gray-100 mx-6"></div>

        {{-- Buttons --}}
        <div class="flex gap-3 p-5">
            <button onclick="closeClearModal()"
                    class="flex-1 py-3 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                Batal
            </button>
            <button onclick="clearCart()"
                    class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:brightness-110"
                    style="background-color:#ef4444;">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.92) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
<script>
    // Initial quantities keyed as strings to match data-id
    const qtys   = { '1': 2, '2': 1 };
    const prices = { '1': 25000, '2': 18000 };
    let metode   = 'ambil';

    function changeQty(id, delta) {
        if (qtys[id] === undefined) return;
        qtys[id] = Math.max(0, qtys[id] + delta);

        if (qtys[id] === 0) {
            const card = document.querySelector(`.cart-item[data-id="${id}"]`);
            if (card) {
                card.style.transition = 'all 0.2s ease';
                card.style.opacity    = '0';
                card.style.transform  = 'translateX(20px)';
                setTimeout(() => {
                    card.remove();
                    checkEmpty();
                    updateSummary();
                }, 200);
            }
        } else {
            const qtyEl = document.getElementById(`qty-${id}`);
            if (qtyEl) qtyEl.textContent = qtys[id];
            updateSummary();
        }
    }

    function openClearModal() {
        const modal = document.getElementById('clearModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeClearModal() {
        const modal = document.getElementById('clearModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function clearCart() {
        closeClearModal();
        document.querySelectorAll('.cart-item').forEach(card => {
            card.style.transition = 'all 0.2s ease';
            card.style.opacity    = '0';
            card.style.transform  = 'translateX(20px)';
        });
        setTimeout(() => {
            document.querySelectorAll('.cart-item').forEach(c => c.remove());
            Object.keys(qtys).forEach(k => qtys[k] = 0);
            checkEmpty();
            updateSummary();
        }, 200);
    }

    // Close modal on backdrop click
    document.getElementById('clearModal').addEventListener('click', function(e) {
        if (e.target === this) closeClearModal();
    });

    function checkEmpty() {
        const remaining = document.querySelectorAll('.cart-item').length;
        const emptyEl   = document.getElementById('emptyCart');
        const metodeEl  = document.getElementById('metodeSection');
        if (remaining === 0) {
            emptyEl.classList.remove('hidden');
            emptyEl.classList.add('flex');
            metodeEl.classList.add('hidden');
        }
    }

    function setMetode(m) {
        metode = m;
        ['ambil', 'kurir'].forEach(k => {
            const el = document.getElementById(`metode-${k}`);
            el.classList.remove('active');
            el.classList.add('border-gray-200');
        });
        const active = document.getElementById(`metode-${m}`);
        active.classList.add('active');
        active.classList.remove('border-gray-200');

        const ongkirRow = document.getElementById('ongkirRow');
        if (m === 'kurir') {
            ongkirRow.classList.remove('hidden');
            ongkirRow.classList.add('flex');
        } else {
            ongkirRow.classList.add('hidden');
            ongkirRow.classList.remove('flex');
        }
        updateSummary();
    }

    function updateSummary() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(card => {
            const id    = card.dataset.id;
            const price = parseInt(card.dataset.price);
            subtotal   += price * (qtys[id] || 0);
        });
        const ongkir = metode === 'kurir' ? 5000 : 0;
        const total  = subtotal + ongkir;
        document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('summaryTotal').textContent    = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endpush