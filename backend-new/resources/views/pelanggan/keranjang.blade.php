@extends('layouts.app')

@section('title', 'Keranjang Saya - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .cart-item {
        transition: all 0.2s ease;
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
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.35);
    }

    .checkout-btn:active {
        transform: translateY(0);
    }

    /* Alamat section */
    .alamat-section {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.35s ease, opacity 0.3s ease, margin 0.3s ease;
    }

    .alamat-section.show {
        max-height: 400px;
        opacity: 1;
    }

    .alamat-input {
        width: 100%;
        padding: 12px 14px;
        background-color: #F9FAFB;
        border: 1.5px solid #e5e7eb;
        border-radius: 14px;
        font-size: 13px;
        color: #374151;
        outline: none;
        transition: all 0.2s;
        resize: none;
    }

    .alamat-input:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.1);
        background-color: #fff;
    }

    /* ===== CUSTOM CHECKBOX ===== */
    .custom-cb {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid #d1d5db;
        background-color: white;
        cursor: pointer;
        flex-shrink: 0;
        transition: all 0.15s ease;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Hover effect saat belum dicentang */
    .custom-cb:hover:not(:checked) {
        border-color: #FF6900;
    }

    /* State: DICENTANG (Oren + Centang) */
    .custom-cb:checked {
        background-color: #FF6900;
        border-color: #FF6900;
    }

    .custom-cb:checked::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 1px;
        width: 6px;
        height: 10px;
        border: 2px solid white;
        border-top: none;
        border-left: none;
        transform: rotate(45deg);
    }

    /* State: INDETERMINATE (Sebagian dipilih) */
    .custom-cb:indeterminate {
        background-color: white;
        border-color: #9ca3af;
    }

    .custom-cb:indeterminate::after {
        display: none !important;
    }

    /* Item yang tidak dicentang: sedikit redup */
    .cart-item.unselected {
        opacity: 0.55;
    }

    .cart-item.unselected:hover {
        opacity: 0.8;
    }

    /* Bar Pilih Semua */
    .select-all-bar {
        background: white;
        border-bottom: 1px solid #f3f4f6;
        padding: 10px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        position: sticky;
        top: 73px;
        z-index: 9;
    }

    .notes-input {
        transition: all 0.2s ease;
    }

    .notes-input:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.08);
        background-color: #fff;
    }

    .notes-wrapper {
        transition: all 0.25s ease;
    }

    .select-all-label {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        user-select: none;
    }

    .selected-count-badge {
        margin-left: auto;
        font-size: 12px;
        font-weight: 700;
        color: #FF6900;
        background-color: #FFF3E8;
        padding: 2px 10px;
        border-radius: 20px;
        transition: all 0.2s;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.92) translateY(8px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- LEFT: KERANJANG --}}
    <div class="flex-1 flex flex-col h-screen overflow-y-auto border-r border-gray-100 bg-white">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-5 bg-white border-b border-gray-100">
            <a href="javascript:history.back()"
                class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-base font-extrabold text-gray-900">Keranjang Saya</h1>
            <button onclick="openClearModal()"
                class="w-9 h-9 rounded-full bg-gray-50 flex items-center justify-center hover:bg-red-50 hover:text-red-400 transition-all text-gray-400">
                <i class="fa-solid fa-trash-can text-sm"></i>
            </button>
        </div>

        {{-- SELECT ALL BAR --}}
        <div class="select-all-bar" id="selectAllBar">
            <input type="checkbox" class="custom-cb" id="cbSelectAll" onchange="toggleSelectAll(this)">
            <label for="cbSelectAll" class="select-all-label">Pilih Semua</label>
            <span class="selected-count-badge" id="selectedCountBadge">0 dipilih</span>
        </div>

        {{-- Cart Items --}}
        <div id="cartItems" class="flex flex-col px-6 py-4">
            @if($cart && !empty($cart['canteens']))
            @foreach($cart['canteens'] as $canteen)
            {{-- HEADER KANTIN --}}
            <div class="flex items-center gap-2 px-1 py-2 mb-2 mt-3 first:mt-0"
                data-canteen-header="{{ $canteen['canteen_id'] }}">
                <input type="checkbox" class="custom-cb canteen-cb" data-canteen-id="{{ $canteen['canteen_id'] }}"
                    onchange="toggleKantin(this)">
                <i class="fa-solid fa-store text-xs" style="color:#FF6900;"></i>
                <span class="text-sm font-extrabold text-gray-700">{{ $canteen['canteen_name'] }}</span>
            </div>

            {{-- ITEMS PER KANTIN --}}
            <div class="flex flex-col gap-3 mb-4" data-canteen-group="{{ $canteen['canteen_id'] }}">
                @foreach($canteen['items'] as $item)
                @php
                $imgUrl = $item['image'] ?? null;
                if ($imgUrl && !str_starts_with($imgUrl, 'http')) {
                $imgUrl = asset('storage/' . $imgUrl);
                }
                @endphp
                <div class="cart-item unselected bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col gap-2"
                    data-id="{{ $item['menu_id'] }}" data-price="{{ $item['price'] }}"
                    data-canteen-id="{{ $canteen['canteen_id'] }}">

                    {{-- Baris utama --}}
                    <div class="flex items-center gap-3">
                        <input type="checkbox" class="custom-cb item-cb" id="cb-{{ $item['menu_id'] }}"
                            data-id="{{ $item['menu_id'] }}" data-canteen-id="{{ $canteen['canteen_id'] }}"
                            onchange="onItemCheck(this)">
                        <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                            @if($imgUrl)
                            <img src="{{ $imgUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-orange-50">
                                <i class="fa-solid fa-utensils text-orange-200 text-xl"></i>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $canteen['canteen_name'] }}</p>
                            <p class="text-sm font-extrabold mt-1" style="color:#FF6900;">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <button type="button" onclick="changeQty('{{ $item['menu_id'] }}', -1)"
                                class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-minus text-[10px]"></i>
                            </button>
                            <span id="qty-{{ $item['menu_id'] }}"
                                class="text-sm font-extrabold text-gray-800 w-5 text-center">{{ $item['quantity']
                                }}</span>
                            <button type="button" onclick="changeQty('{{ $item['menu_id'] }}', 1)"
                                class="qty-btn w-7 h-7 rounded-full border-2 border-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-plus text-[10px]"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Notes input — muncul saat item dicentang --}}
                    <div class="notes-wrapper hidden" id="notes-wrapper-{{ $item['menu_id'] }}">
                        <div class="flex items-center gap-2 pl-1">
                            <i class="fa-regular fa-note-sticky text-[11px] text-gray-300 flex-shrink-0"></i>
                            <input type="text" id="notes-{{ $item['menu_id'] }}"
                                placeholder="Catatan untuk menu ini... (opsional)"
                                class="notes-input flex-1 px-3 py-1.5 rounded-xl bg-gray-50 border border-gray-100 text-xs text-gray-600 outline-none"
                                maxlength="100">
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
            @endforeach
            @endif
        </div>

        {{-- Empty state --}}
        <div id="emptyCart" class="hidden flex-col items-center justify-center py-20 gap-3 px-6">
            <div class="w-16 h-16 rounded-full bg-orange-50 flex items-center justify-center mb-2">
                <i class="fa-solid fa-cart-shopping text-2xl" style="color:#FF6900;"></i>
            </div>
            <p class="text-base font-extrabold text-gray-700">Keranjang kosong</p>
            <p class="text-sm text-gray-400 font-medium text-center">Tambahkan makanan dari halaman kantin</p>
            <a href="/jelajah?tab=makanan" class="mt-2 px-6 py-2.5 rounded-2xl text-sm font-bold text-white"
                style="background-color:#FF6900;">
                Cari Makanan
            </a>
        </div>

        {{-- Metode Pemesanan --}}
        <div id="metodeSection" class="px-6 pb-4">
            <h2 class="text-base font-extrabold text-gray-900 mb-4">Metode Pemesanan</h2>
            <div class="grid grid-cols-2 gap-3">

                {{-- Ambil Sendiri --}}
                <div id="metode-ambil" onclick="setMetode('ambil')"
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
                <div id="metode-kurir" onclick="setMetode('kurir')"
                    class="metode-card rounded-2xl border-2 border-gray-200 p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-gray-100">
                        <i class="fa-solid fa-person-biking text-base text-gray-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-sm font-extrabold text-gray-800">Kurir Antar</p>
                            <span id="ongkirBadge" class="text-[11px] font-black px-1.5 py-0.5 rounded-lg"
                                style="background-color:#FFF3E8; color:#FF6900;">...</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">Diantar ke kelasmu</p>
                    </div>
                    <div class="radio-dot">
                        <div class="radio-inner"></div>
                    </div>
                </div>

            </div>

            {{-- Alamat Pengiriman --}}
            <div id="alamatSection" class="alamat-section mt-4">
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-sm" style="color:#FF6900;"></i>
                            <p class="text-sm font-extrabold text-gray-800">Alamat Pengiriman</p>
                        </div>
                        <button onclick="toggleEditAlamat()"
                            class="text-xs font-bold px-3 py-1.5 rounded-xl transition-all hover:bg-orange-100"
                            style="color:#FF6900;" id="editAlamatBtn">
                            <i class="fa-solid fa-pen text-[10px] mr-1"></i>Ubah
                        </button>
                    </div>
                    <div id="alamatDisplay">
                        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-white rounded-xl border border-gray-100">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                                style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-building text-xs" style="color:#FF6900;"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-400" id="displayAlamat">SV IPB, CA B01 lt.2,
                                    Kampus IPB Cilibende</p>
                            </div>
                            <i class="fa-solid fa-circle-check text-green-400 text-sm mt-0.5 flex-shrink-0"></i>
                        </div>
                    </div>
                    <div id="alamatForm" class="hidden flex flex-col gap-3 mt-2">
                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1.5 block">Alamat Lengkap <span
                                    class="text-red-400">*</span></label>
                            <textarea id="inputAlamat" class="alamat-input" rows="1"
                                placeholder="Cth: Gedung A Lt. 2, Fak. Teknologi Pertanian, Kampus IPB Dramaga, Bogor">SV IPB, CA B01 lt.2, Kampus IPB Cilibende</textarea>
                        </div>
                        <div class="flex gap-2 mt-1">
                            <button onclick="batalEditAlamat()"
                                class="flex-1 py-2.5 rounded-xl border-2 border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button onclick="simpanAlamat()"
                                class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold transition-all hover:brightness-110"
                                style="background-color:#FF6900;">
                                Simpan Alamat
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="pb-6"></div>

    </div>

    {{-- RIGHT: RINGKASAN --}}
    <div class="w-[320px] flex-shrink-0 flex flex-col h-screen bg-[#F9FAFB]">
        <div class="flex-1 px-7 py-8 flex flex-col">
            <h2 class="text-lg font-extrabold text-gray-900 mb-6">Ringkasan Pesanan</h2>

            {{-- Info kalau belum ada yang dipilih --}}
            <div id="noSelectionInfo" class="flex flex-col items-center justify-center py-8 gap-2">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center mb-1">
                    <i class="fa-regular fa-square-check text-xl" style="color:#FF6900;"></i>
                </div>
                <p class="text-sm font-bold text-gray-500 text-center">Pilih item terlebih dahulu</p>
                <p class="text-xs text-gray-400 text-center">pilih item di keranjang untuk menghitung total</p>
            </div>

            {{-- Warning multi kantin --}}
            <div id="multiKantinWarning" class="hidden mb-3 px-4 py-3 rounded-2xl text-xs font-bold text-orange-600"
                style="background-color:#FFF7ED;">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                Item dari beberapa kantin dipilih. Checkout hanya bisa dari 1 kantin.
            </div>

            <div id="summaryDetail" class="hidden flex-col gap-3">

                {{-- Daftar item yang dipilih --}}
                <div id="selectedItemsList" class="flex flex-col gap-2 pb-3 border-b border-gray-200"></div>

                <div class="flex flex-col gap-3 pt-1">
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-400 font-semibold">Subtotal</span>
                        <span id="summarySubtotal" class="text-sm font-bold text-gray-800">Rp 0</span>
                    </div>
                    <div id="ongkirRow" class="hidden items-center justify-between py-1">
                        <span class="text-sm text-gray-400 font-semibold">Ongkos Kirim</span>
                        <span id="ongkirValue" class="text-sm font-bold text-gray-800">Gratis</span>
                    </div>
                    <div class="flex items-center justify-between pt-2">
                        <span class="text-base font-extrabold text-gray-900">Total</span>
                        <span id="summaryTotal" class="text-xl font-extrabold" style="color:#FF6900;">Rp 0</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="px-7 pb-8">
            {{-- Tombol disabled kalau tidak ada yang dipilih --}}
            <button id="checkoutBtn" onclick="handleCheckout()"
                class="checkout-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm flex items-center justify-center gap-2 transition-all"
                style="background: linear-gradient(135deg, #d1d5db, #9ca3af);" disabled>
                Pilih Item Dulu
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

</div>

{{-- MODAL HAPUS KERANJANG --}}
<div id="clearModal" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background:rgba(0,0,0,0.4); backdrop-filter:blur(4px);">
    <div class="bg-white rounded-3xl shadow-2xl w-[340px] mx-4 overflow-hidden" style="animation: modalIn 0.2s ease;">
        <div class="flex flex-col items-center pt-8 pb-5 px-6">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background-color:#FEF2F2;">
                <i class="fa-solid fa-trash-can text-xl" style="color:#ef4444;"></i>
            </div>
            <h3 class="text-base font-extrabold text-gray-900 mb-1">Hapus Item</h3>
            <p class="text-sm text-gray-400 font-medium text-center leading-relaxed">
                Item yang dipilih akan dihapus dari keranjang. Yakin?
            </p>
        </div>
        <div class="h-px bg-gray-100 mx-6"></div>
        <div class="flex gap-3 p-5">
            <button onclick="closeClearModal()"
                class="flex-1 py-3 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
            <button onclick="clearCart()"
                class="flex-1 py-3 rounded-2xl text-sm font-bold text-white transition-all hover:brightness-110"
                style="background-color:#ef4444;">Ya, Hapus</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const qtys = {
    @if($cart && !empty($cart['canteens']))
        @foreach($cart['canteens'] as $canteen)
            @foreach($canteen['items'] as $item)
                '{{ $item['menu_id'] }}': {{ $item['quantity'] }},
            @endforeach
        @endforeach
    @endif
};

let metode = 'ambil';
let ongkirAmount = 0;
let isEditingAlamat = false;

// ── ONGKIR ────────────────────────────────────────────────
function fetchOngkir(canteenId) {
    return fetch(`/keranjang/ongkir/${canteenId}`)
        .then(r => r.json())
        .then(data => {
            ongkirAmount = parseInt(data.delivery_fee) || 0; // ← parseInt di sini

            const badge = document.getElementById('ongkirBadge');
            if (badge) {
                badge.textContent = ongkirAmount > 0
                    ? '+Rp ' + ongkirAmount.toLocaleString('id-ID')
                    : 'Gratis';
            }

            updateSummary();
        })
        .catch(() => {
            ongkirAmount = 0;
            updateSummary();
        });
}

// ── METODE ────────────────────────────────────────────────
function setMetode(m) {
    metode = m;

    ['ambil','kurir'].forEach(k => {
        const el = document.getElementById(`metode-${k}`);
        el.classList.remove('active');
        el.classList.add('border-gray-200');
    });

    document.getElementById(`metode-${m}`).classList.add('active');
    document.getElementById(`metode-${m}`).classList.remove('border-gray-200');

    const ongkirRow = document.getElementById('ongkirRow');
    m === 'kurir'
        ? ongkirRow.classList.replace('hidden','flex')
        : ongkirRow.classList.replace('flex','hidden');

    const alamatSection = document.getElementById('alamatSection');
    m === 'kurir'
        ? alamatSection.classList.add('show')
        : alamatSection.classList.remove('show');

    if (m !== 'kurir' && isEditingAlamat) batalEditAlamat();

    // handle ongkir
    if (m === 'kurir') {
        const selectedCanteenId = getSelectedCanteenId();
        if (selectedCanteenId) {
            fetchOngkir(selectedCanteenId);
        } else {
            ongkirAmount = 0;
            updateSummary();
        }
    } else {
        ongkirAmount = 0;
        updateSummary();
    }
}

// ── SELECT ALL ────────────────────────────────────────────
function toggleSelectAll(cbAll) {
    document.querySelectorAll('.item-cb').forEach(cb => {
        cb.checked = cbAll.checked;
        updateItemVisual(cb);
    });

    document.querySelectorAll('.canteen-cb').forEach(cb => {
        cb.checked = cbAll.checked;
    });

    updateSummary();
}

// ── PER KANTIN ────────────────────────────────────────────
function toggleKantin(cbKantin) {
    const canteenId = cbKantin.dataset.canteenId;

    document.querySelectorAll(`.item-cb[data-canteen-id="${canteenId}"]`).forEach(cb => {
        cb.checked = cbKantin.checked;
        updateItemVisual(cb);
    });

    syncSelectAllState();
    updateSummary();
}

// ── PER ITEM ──────────────────────────────────────────────
function onItemCheck(cb) {
    updateItemVisual(cb);
    syncCanteenCheckbox(cb.dataset.canteenId);
    syncSelectAllState();

    // Re-fetch ongkir kalau mode kurir dan kantin berubah
    if (metode === 'kurir') {
        const selectedCanteenId = getSelectedCanteenId();
        if (selectedCanteenId) {
            fetchOngkir(selectedCanteenId);
            return; // updateSummary dipanggil di dalam fetchOngkir
        } else {
            ongkirAmount = 0;
        }
    }

    updateSummary();
}

function updateItemVisual(cb) {
    const card = document.querySelector(`.cart-item[data-id="${cb.dataset.id}"]`);
    if (!card) return;

    const notesWrapper = document.getElementById(`notes-wrapper-${cb.dataset.id}`);

    if (cb.checked) {
        card.classList.remove('unselected');
        notesWrapper?.classList.remove('hidden');
    } else {
        card.classList.add('unselected');
        notesWrapper?.classList.add('hidden');
    }
}
// ── SYNC CHECKBOX ─────────────────────────────────────────
function syncCanteenCheckbox(canteenId) {
    const itemCbs = document.querySelectorAll(`.item-cb[data-canteen-id="${canteenId}"]`);
    const checked = document.querySelectorAll(`.item-cb[data-canteen-id="${canteenId}"]:checked`);
    const canteenCb = document.querySelector(`.canteen-cb[data-canteen-id="${canteenId}"]`);

    if (!canteenCb) return;

    if (checked.length === 0) {
        canteenCb.checked = false;
        canteenCb.indeterminate = false;
    } else if (checked.length === itemCbs.length) {
        canteenCb.checked = true;
        canteenCb.indeterminate = false;
    } else {
        canteenCb.checked = false;
        canteenCb.indeterminate = true;
    }
}

function syncSelectAllState() {
    const all = document.querySelectorAll('.item-cb');
    const checked = document.querySelectorAll('.item-cb:checked');
    const cbAll = document.getElementById('cbSelectAll');

    if (!cbAll) return;

    if (checked.length === 0) {
        cbAll.checked = false;
        cbAll.indeterminate = false;
    } else if (checked.length === all.length) {
        cbAll.checked = true;
        cbAll.indeterminate = false;
    } else {
        cbAll.checked = false;
        cbAll.indeterminate = true;
    }
}

// ── KANTIN VALIDASI ───────────────────────────────────────
function getSelectedCanteenId() {
    const checked = document.querySelectorAll('.item-cb:checked');
    if (checked.length === 0) return null;

    const ids = new Set([...checked].map(cb => cb.dataset.canteenId));
    return ids.size === 1 ? [...ids][0] : null;
}

// ── QTY ───────────────────────────────────────────────────
function changeQty(id, delta) {
    if (qtys[id] === undefined) return;

    const newQty = qtys[id] + delta;

    if (newQty <= 0) {
        removeItem(id);
        return;
    }

    fetch(`/keranjang/items/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ quantity: newQty }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success !== false) {
            qtys[id] = newQty;
            document.getElementById(`qty-${id}`).textContent = newQty;
            updateSummary();
        }
    });
}

function removeItem(id) {
    fetch(`/keranjang/items/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(() => {
        const card = document.querySelector(`.cart-item[data-id="${id}"]`);
        if (!card) return;

        const canteenId = card.dataset.canteenId;

        card.remove();
        delete qtys[id];

        const remaining = document.querySelectorAll(`.cart-item[data-canteen-id="${canteenId}"]`);
        if (remaining.length === 0) {
            document.querySelector(`[data-canteen-header="${canteenId}"]`)?.remove();
            document.querySelector(`[data-canteen-group="${canteenId}"]`)?.remove();
        }

        checkEmpty();
        syncSelectAllState();
        updateSummary();
    });
}

// ── SUMMARY ───────────────────────────────────────────────
function updateSummary() {
    const checked = document.querySelectorAll('.item-cb:checked');
    const badge = document.getElementById('selectedCountBadge');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const summaryDetail = document.getElementById('summaryDetail');
    const noSelInfo = document.getElementById('noSelectionInfo');
    const selectedList = document.getElementById('selectedItemsList');
    const multiWarning = document.getElementById('multiKantinWarning');

    // ← Paksa integer
    const ongkir = metode === 'kurir' ? parseInt(ongkirAmount) || 0 : 0;

    badge.textContent = checked.length + ' dipilih';

    if (checked.length === 0) {
        noSelInfo.classList.remove('hidden');
        summaryDetail.classList.add('hidden');
        summaryDetail.classList.remove('flex');
        checkoutBtn.disabled = true;
        checkoutBtn.style.background = 'linear-gradient(135deg, #d1d5db, #9ca3af)';
        checkoutBtn.innerHTML = 'Pilih Item Dulu <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
        if (multiWarning) multiWarning.classList.add('hidden');
        return;
    }

    const selectedCanteenId = getSelectedCanteenId();
    const isMulti = selectedCanteenId === null;

    if (multiWarning) multiWarning.classList.toggle('hidden', !isMulti);

    noSelInfo.classList.add('hidden');
    summaryDetail.classList.remove('hidden');
    summaryDetail.classList.add('flex');

    let subtotal = 0;
    selectedList.innerHTML = '';

    checked.forEach(cb => {
        const id = cb.dataset.id;
        const card = document.querySelector(`.cart-item[data-id="${id}"]`);
        if (!card) return;

        // ← Paksa integer semua
        const price = parseInt(card.dataset.price) || 0;
        const qty   = parseInt(qtys[id]) || 0;
        const name  = card.querySelector('p.font-extrabold.text-gray-900')?.textContent || 'Item';

        subtotal += price * qty;

        selectedList.innerHTML += `
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500 font-medium truncate max-w-[140px]">${name} <span class="text-gray-400">x${qty}</span></span>
                <span class="font-bold text-gray-700">Rp ${(price * qty).toLocaleString('id-ID')}</span>
            </div>`;
    });

    // ← Pastikan keduanya integer sebelum dijumlah
    const total = parseInt(subtotal) + parseInt(ongkir);

    document.getElementById('summarySubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('summaryTotal').textContent    = 'Rp ' + total.toLocaleString('id-ID');

    // ← Update nilai ongkir di ringkasan
    const ongkirEl = document.getElementById('ongkirValue');
    if (ongkirEl) {
        ongkirEl.textContent = ongkir === 0 ? 'Gratis' : 'Rp ' + ongkir.toLocaleString('id-ID');
    }

    if (isMulti) {
        checkoutBtn.disabled = true;
        checkoutBtn.style.background = 'linear-gradient(135deg, #d1d5db, #9ca3af)';
        checkoutBtn.innerHTML = 'Pilih 1 Kantin Saja <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.style.background = 'linear-gradient(135deg, #FF6900, #ea580c)';
        checkoutBtn.innerHTML = 'Lanjut Pembayaran <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>';
    }
}

function handleCheckout() {
    const selectedCanteenId = getSelectedCanteenId();
    if (!selectedCanteenId) return;

    const checked = document.querySelectorAll('.item-cb:checked');
    const menuIds = [];
    const notes   = [];

    checked.forEach(cb => {
        const id = cb.dataset.id;
        menuIds.push(id);
        const notesInput = document.getElementById(`notes-${id}`);
        notes.push(notesInput?.value?.trim() || '');
    });

    const alamat = document.getElementById('displayAlamat')?.textContent?.trim() || '';

    // Kirim ke Laravel session dulu
    fetch('/pembayaran/session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            canteen_id: selectedCanteenId,
            menu_ids:   menuIds,
            notes:      notes,
            metode:     metode,
            alamat:     metode === 'kurir' ? alamat : null,
            subtotal:   parseInt(document.getElementById('summarySubtotal').textContent.replace(/\D/g, '')),
            ongkir:     metode === 'kurir' ? parseInt(ongkirAmount) || 0 : 0,
            total:      parseInt(document.getElementById('summaryTotal').textContent.replace(/\D/g, '')),
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) window.location.href = '/pembayaran';
    });
}

// ── EMPTY ─────────────────────────────────────────────────
function checkEmpty() {
    if (document.querySelectorAll('.cart-item').length === 0) {
        document.getElementById('emptyCart').classList.remove('hidden');
        document.getElementById('emptyCart').classList.add('flex');
        document.getElementById('selectAllBar').classList.add('hidden');
        document.getElementById('metodeSection').classList.add('hidden');
    }
}

// INIT
@if($cart && !empty($cart['canteens']))
    // Pre-fetch ongkir kantin pertama supaya badge langsung tampil
    fetchOngkir('{{ $cart["canteens"][0]["canteen_id"] }}');
@endif

@if(!$cart || empty($cart['canteens']))
    checkEmpty();
@endif

updateSummary();
</script>
@endpush