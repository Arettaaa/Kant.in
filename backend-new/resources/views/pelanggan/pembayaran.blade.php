@extends('layouts.app')

@section('title', 'Pembayaran - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .upload-area {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #FF6900;
        background-color: #FFFAF7;
    }

    .upload-area.has-file {
        border-color: #22c55e;
        background-color: #F0FDF4;
    }

    .konfirmasi-btn {
        transition: all 0.2s ease;
    }

    .konfirmasi-btn:hover:not(:disabled) {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.35);
    }

    .konfirmasi-btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .konfirmasi-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background: linear-gradient(135deg, #d1d5db, #9ca3af) !important;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.88) translateY(12px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes checkPop {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        60% {
            transform: scale(1.2);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse-icon {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }
    }

    .modal-card {
        animation: modalIn 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .check-anim {
        animation: checkPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
    }

    .fade-up {
        animation: fadeUp 0.3s ease 0.35s both;
    }

    .fade-up-2 {
        animation: fadeUp 0.3s ease 0.45s both;
    }

    .fade-up-3 {
        animation: fadeUp 0.3s ease 0.55s both;
    }

    .pulse-anim {
        animation: pulse-icon 1.8s ease-in-out infinite;
    }

    .qr-wrap {
        position: relative;
        display: inline-block;
    }

    @keyframes cancelCountdown {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }

    .cancel-bar {
        animation: cancelCountdown 30s linear forwards;
    }

    .item-note-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #FFF3E8;
        color: #FF6900;
        border-radius: 8px;
        padding: 2px 7px;
        font-size: 10px;
        font-weight: 700;
        margin-top: 3px;
    }

    .loading-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
    }

    .loading-overlay.show {
        display: flex;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .spin {
        animation: spin 0.8s linear infinite;
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">


    @include('pelanggan.partials.sidebar', ['currentPath' => 'pembayaran']  )
    {{-- MAIN --}}
    <main class="flex-1 flex h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="flex flex-1 gap-6 px-8 py-8 max-w-5xl w-full mx-auto">

            {{-- ===== LEFT COLUMN ===== --}}
            <div class="flex-1 flex flex-col gap-5">

                {{-- Header --}}
                <div class="flex items-center gap-4">
                    <a href="/keranjang"
                        class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-gray-50 transition-all flex-shrink-0 shadow-sm">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 leading-tight">Selesaikan Pembayaran</h1>
                        <p class="text-sm text-gray-400 font-medium mt-0.5">Satu langkah lagi untuk menikmati
                            hidanganmu.</p>
                    </div>
                </div>

                {{-- ===== QR CARD ===== --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col items-center gap-5">

                    <div class="flex items-center gap-3 self-start w-full">
                        <span
                            class="text-xs font-black px-3 py-1.5 rounded-full border-2 border-blue-200 text-blue-500 tracking-widest">QRIS</span>
                        <span class="text-base font-extrabold text-gray-800">Pindai untuk Membayar</span>
                    </div>

                    <p class="text-sm text-gray-400 font-medium text-center leading-relaxed -mt-2">
                        Buka aplikasi m-banking atau e-wallet (GoPay, OVO, Dana)<br>
                        dan pindai kode QR di bawah ini.
                    </p>

                    {{-- QR Image --}}
                    <div class="qr-wrap">
                        <div
                            class="w-52 h-52 rounded-2xl overflow-hidden border-4 border-white shadow-lg bg-white flex items-center justify-center p-2">
                            @if(!empty($canteen['qris_image']))
                            <img src="{{ $canteen['qris_image'] }}" alt="QRIS {{ $canteen['name'] ?? 'Kantin' }}"
                                class="w-full h-full object-contain rounded-xl">
                            @else
                            {{-- Placeholder QR kalau belum ada --}}
                            <div
                                class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gray-50 rounded-xl">
                                <i class="fa-solid fa-qrcode text-4xl text-gray-300"></i>
                                <p class="text-[10px] font-bold text-gray-300 text-center leading-tight">QRIS
                                    belum<br>diatur</p>
                            </div>
                            @endif
                        </div>
                    </div>                   

                    {{-- Nama Kantin --}}
                    <div
                        class="flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 text-sm text-gray-500 font-semibold">
                        <i class="fa-solid fa-store text-gray-400 text-xs"></i>
                        {{ $canteen['name'] ?? 'Kantin' }}
                    </div>

                </div>

                {{-- ===== UPLOAD BUKTI ===== --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-extrabold text-gray-900 mb-1">Unggah Bukti Pembayaran</h2>
                    <p class="text-xs text-gray-400 font-medium mb-4">Foto struk atau screenshot konfirmasi QRIS kamu
                    </p>

                    <label for="fileInput"
                        class="upload-area block border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center">
                        <input type="file" id="fileInput" accept=".jpg,.jpeg,.png" class="hidden"
                            onchange="handleFile(this)">

                        <div id="uploadDefault" class="flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center"
                                style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-cloud-arrow-up text-xl" style="color:#FF6900;"></i>
                            </div>
                            <div>
                                <p class="text-sm font-extrabold text-gray-700">Ketuk untuk mengunggah bukti transfer
                                </p>
                                <p class="text-xs text-gray-400 mt-1">Format: JPG atau PNG (Maks. 5MB)</p>
                            </div>
                        </div>

                        <div id="uploadDone" class="hidden flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
                            </div>
                            <p id="fileName" class="text-sm font-extrabold text-green-600"></p>
                            <p class="text-xs text-gray-400">Klik untuk ganti file</p>
                        </div>
                    </label>
                </div>

            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="w-[285px] flex-shrink-0 flex flex-col gap-4">

                {{-- Ringkasan Pembayaran --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col gap-4">

                    <div class="flex flex-col items-center gap-3">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center"
                            style="background-color:#FFF3E8;">
                            <i class="fa-solid fa-receipt text-xl" style="color:#FF6900;"></i>
                        </div>
                        <div class="text-center">
                            <h2 class="text-base font-extrabold text-gray-900">Ringkasan Pesanan</h2>
                            <p class="text-xs text-gray-400 font-semibold mt-0.5">
                                {{ $canteen['name'] ?? 'Kantin' }}
                            </p>
                        </div>
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    {{-- Daftar Item --}}
                    <div class="flex flex-col gap-2.5">
                        @foreach($selectedItems as $item)
                        @php
                        $imgUrl = $item['image'] ?? null;
                        if ($imgUrl && !str_starts_with($imgUrl, 'http')) {
                        $imgUrl = asset('storage/' . $imgUrl);
                        }
                        @endphp
                        <div class="flex items-start gap-2.5">
                            <div class="w-9 h-9 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
                                @if($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center bg-orange-50">
                                    <i class="fa-solid fa-utensils text-orange-200 text-xs"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-1">
                                    <p class="text-xs font-extrabold text-gray-800 leading-snug truncate max-w-[120px]">
                                        {{ $item['name'] }}
                                    </p>
                                    <span class="text-xs font-bold text-gray-600 flex-shrink-0">
                                        Rp {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',',
                                        '.') }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-400 font-medium">x{{ $item['quantity'] }}</p>
                                @if(!empty($item['notes']))
                                <span class="item-note-badge">
                                    <i class="fa-regular fa-note-sticky text-[9px]"></i>
                                    {{ $item['notes'] }}
                                </span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="h-px bg-gray-100"></div>

                    {{-- Kalkulasi --}}
                    <div class="flex flex-col gap-2.5 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-semibold">Subtotal ({{ count($selectedItems) }} item)</span>
                            <span class="font-bold text-gray-700">
                                Rp {{ number_format($checkout['subtotal'], 0, ',', '.') }}
                            </span>
                        </div>

                        @if($checkout['metode'] === 'kurir')
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-semibold">Ongkos Kirim</span>
                            <span class="font-bold text-gray-700">
                                @if($checkout['ongkir'] > 0)
                                Rp {{ number_format($checkout['ongkir'], 0, ',', '.') }}
                                @else
                                Gratis
                                @endif
                            </span>
                        </div>
                        @endif

                        <div class="h-px bg-gray-100"></div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-extrabold text-gray-900">Total Akhir</span>
                            <span class="text-xl font-extrabold" style="color:#FF6900;">
                                Rp {{ number_format($checkout['total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>

                {{-- Metode & Alamat --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 flex flex-col gap-3">

                    {{-- Metode --}}
                    <div class="flex items-center gap-2.5">
                        @if($checkout['metode'] === 'kurir')
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                            style="background-color:#FFF3E8;">
                            <i class="fa-solid fa-person-biking text-xs" style="color:#FF6900;"></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold text-gray-800">Kurir Antar</p>
                            <p class="text-[11px] text-gray-400 font-medium">Diantar ke lokasimu</p>
                        </div>
                        @else
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                            style="background-color:#FFF3E8;">
                            <i class="fa-solid fa-bag-shopping text-xs" style="color:#FF6900;"></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold text-gray-800">Ambil Sendiri</p>
                            <p class="text-[11px] text-gray-400 font-medium">Siap dalam 10–15 menit</p>
                        </div>
                        @endif
                    </div>

                    @if($checkout['metode'] === 'kurir' && !empty($checkout['alamat']))
                    <div class="h-px bg-gray-100"></div>
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fa-solid fa-location-dot text-xs" style="color:#FF6900;"></i>
                        <p class="text-xs font-extrabold text-gray-800">Alamat Pengiriman</p>
                    </div>
                    <div class="flex items-start gap-2.5 px-3 py-2.5 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                            style="background-color:#FFF3E8;">
                            <i class="fa-solid fa-building text-[10px]" style="color:#FF6900;"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-700 leading-snug">{{ $checkout['alamat'] }}</p>
                        </div>
                        <i class="fa-solid fa-circle-check text-green-400 text-xs mt-0.5 flex-shrink-0"></i>
                    </div>
                    @endif

                </div>

                {{-- Tombol Konfirmasi --}}
                <button id="konfirmasiBtn" onclick="submitPembayaran()"
                    class="konfirmasi-btn w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #d1d5db, #9ca3af);" disabled>
                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    Unggah Bukti Dulu
                </button>

                {{-- Info --}}
                <div class="flex items-start gap-2.5 px-4 py-3 rounded-2xl bg-amber-50 border border-amber-100">
                    <i class="fa-solid fa-circle-info text-amber-400 text-sm mt-0.5 flex-shrink-0"></i>
                    <p class="text-xs text-amber-700 font-medium leading-relaxed">
                        Pembayaran akan diverifikasi oleh admin kantin. Pesanan diproses setelah konfirmasi berhasil.
                    </p>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- ===== LOADING OVERLAY ===== --}}
<div id="loadingOverlay" class="loading-overlay">
    <div class="bg-white rounded-3xl px-10 py-8 flex flex-col items-center gap-4 shadow-2xl">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color:#FFF3E8;">
            <i class="fa-solid fa-spinner spin text-2xl" style="color:#FF6900;"></i>
        </div>
        <p class="text-sm font-extrabold text-gray-700">Mengirim pesanan...</p>
        <p class="text-xs text-gray-400 font-medium">Mohon tunggu sebentar</p>
    </div>
</div>

{{-- ===== MODAL 1: MENUNGGU VALIDASI ADMIN ===== --}}
<div id="menungguModal" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background:rgba(0,0,0,0.4); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-5">

            <div class="pulse-anim w-20 h-20 rounded-[22px] flex items-center justify-center"
                style="background-color:#FFF3E8;">
                <div class="relative w-full h-full flex items-center justify-center">
                    <i class="fa-solid fa-shield-halved text-4xl" style="color:#FF6900; opacity:0.25;"></i>
                    <i class="fa-regular fa-clock text-xl absolute" style="color:#FF6900;"></i>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Menunggu Validasi Admin</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Bukti pembayaran Anda berhasil diunggah.<br>
                    Kami sedang memverifikasi pembayaran Anda,<br>
                    mohon tunggu sebentar.
                </p>
            </div>

            {{-- ID Pesanan --}}
            <div
                class="w-full px-4 py-3 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400">ID Pesanan</span>
                <span id="modalOrderId" class="text-xs font-black text-gray-700 tracking-wide">#—</span>
            </div>

            {{-- Countdown batalkan --}}
            <div class="w-full rounded-2xl p-4 text-center" style="background-color:#FEF2F2;">
                <p class="text-xs font-black text-red-500 tracking-widest uppercase mb-2">Batas Waktu Pembatalan</p>
                <p id="cancelCountdown" class="text-4xl font-black text-red-500 leading-none">00:30</p>
                <div class="w-full h-1.5 bg-red-100 rounded-full overflow-hidden mt-3">
                    <div id="cancelBar" class="cancel-bar h-full rounded-full" style="background-color:#ef4444;"></div>
                </div>
            </div>

            <button onclick="showBatalkanConfirm()"
                class="w-full py-3.5 rounded-2xl border-2 flex items-center justify-center gap-2 text-sm font-extrabold transition-all hover:bg-red-50"
                style="border-color:#ef4444; color:#ef4444;">
                <i class="fa-regular fa-circle-xmark text-base"></i>
                Batalkan Pesanan
            </button>

        </div>
    </div>
</div>

{{-- ===== MODAL 2: KONFIRMASI BATALKAN ===== --}}
<div id="batalkanConfirmModal" class="fixed inset-0 z-[60] hidden items-center justify-center"
    style="background:rgba(0,0,0,0.5); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">

            <div class="w-16 h-16 rounded-2xl flex items-center justify-center" style="background-color:#FEF2F2;">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i>
            </div>

            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Batalkan Pesanan?</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Apakah Anda yakin ingin membatalkan pesanan ini?<br>
                    Aksi ini tidak dapat dibatalkan kembali.
                </p>
            </div>

            <div class="flex gap-3 w-full mt-1">
                <button onclick="closeBatalkanConfirm()"
                    class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                    Kembali
                </button>
                <button onclick="doBatalkan()" id="batalkanBtn"
                    class="flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold transition-all hover:brightness-110"
                    style="background-color:#ef4444;">
                    Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL 3: PESANAN DIBATALKAN ===== --}}
<div id="batalkanDoneModal" class="fixed inset-0 z-[70] hidden items-center justify-center"
    style="background:rgba(0,0,0,0.5); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#ef4444,#dc2626);"></div>
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">
            <div class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg"
                style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                <i class="fa-solid fa-xmark text-white text-2xl"></i>
            </div>
            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Pesanan Dibatalkan</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Pesanan Anda telah berhasil dibatalkan.<br>
                    Anda dapat melihatnya di riwayat pesanan.
                </p>
            </div>
            <div class="flex flex-col gap-2.5 w-full mt-1">
                <a href="/pesanan"
                    class="w-full py-3.5 rounded-2xl text-white font-extrabold text-sm text-center shadow-md"
                    style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                    Lihat Riwayat Pesanan
                </a>
                <a href="/beranda"
                    class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm text-center hover:bg-gray-50 transition-all">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL 4: WAKTU HABIS / DIPROSES ===== --}}
<div id="diproseModal" class="fixed inset-0 z-[70] hidden items-center justify-center"
    style="background:rgba(0,0,0,0.5); backdrop-filter:blur(6px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[380px] mx-4 overflow-hidden">
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#FF6900,#ea580c);"></div>
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">
            <div class="pulse-anim w-16 h-16 rounded-2xl flex items-center justify-center"
                style="background-color:#FFF3E8;">
                <i class="fa-solid fa-clock-rotate-left text-2xl" style="color:#FF6900;"></i>
            </div>
            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Sedang Diverifikasi</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Pembayaran Anda sedang diverifikasi oleh admin kantin.<br>
                    Batas pembatalan telah habis.
                </p>
            </div>
            <div class="flex flex-col gap-2.5 w-full mt-1">
                <button
                    class="w-full py-3.5 rounded-2xl text-white font-extrabold text-sm text-center shadow-md cursor-default"
                    style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                    Sedang Diproses Admin
                </button>
                <a href="/pesanan"
                    class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm text-center hover:bg-gray-50 transition-all">
                    Lihat Pesanan Saya
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── State ─────────────────────────────────────────────
    let uploadedFile   = null;
    let currentOrderId = null;
    let cancelSeconds  = 30;
    let cancelInterval = null;

    // ── File Upload ───────────────────────────────────────
    function handleFile(input) {
        if (!input.files.length) return;
        const file = input.files[0];

        if (file.size > 5 * 1024 * 1024) {
            alert('File terlalu besar. Maksimal 5MB.');
            input.value = '';
            return;
        }

        uploadedFile = file;

        input.closest('label').classList.add('has-file');
        document.getElementById('uploadDefault').classList.add('hidden');

        const done = document.getElementById('uploadDone');
        done.classList.remove('hidden');
        done.classList.add('flex');
        document.getElementById('fileName').textContent = file.name;

        // Aktifkan tombol konfirmasi
        const btn = document.getElementById('konfirmasiBtn');
        btn.disabled = false;
        btn.style.background = 'linear-gradient(135deg, #FF6900, #ea580c)';
        btn.innerHTML = `<i class="fa-solid fa-paper-plane text-sm"></i> Konfirmasi Pembayaran`;
    }

    // ── Submit Pembayaran ─────────────────────────────────
    function submitPembayaran() {
        if (!uploadedFile) return;

        // Tampilkan loading
        document.getElementById('loadingOverlay').classList.add('show');

        const formData = new FormData();
        formData.append('payment_proof', uploadedFile);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch('/pembayaran', {
            method: 'POST',
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loadingOverlay').classList.remove('show');

            if (data.success) {
                currentOrderId = data.order_id;
                showMenungguModal();
            } else {
                alert(data.message || 'Gagal membuat pesanan. Silakan coba lagi.');
            }
        })
        .catch(() => {
            document.getElementById('loadingOverlay').classList.remove('show');
            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
        });
    }

    // ── Cancel Countdown ──────────────────────────────────
    function startCancelCountdown() {
        cancelSeconds = 30;
        updateCancelDisplay();

        cancelInterval = setInterval(() => {
            cancelSeconds--;
            updateCancelDisplay();

            if (cancelSeconds <= 0) {
                clearInterval(cancelInterval);
                closeModal('menungguModal');
                showModal('diproseModal');
            }
        }, 1000);
    }

    function updateCancelDisplay() {
        const m = String(Math.floor(cancelSeconds / 60)).padStart(2, '0');
        const s = String(cancelSeconds % 60).padStart(2, '0');
        document.getElementById('cancelCountdown').textContent = `${m}:${s}`;
    }

    // ── Modal Helpers ─────────────────────────────────────
    function showModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function showMenungguModal() {
        // Set order ID di modal
        if (currentOrderId) {
            document.getElementById('modalOrderId').textContent = '#' + currentOrderId.slice(-6).toUpperCase();
        }

        // Reset cancel bar animation
        const bar = document.getElementById('cancelBar');
        bar.style.animation = 'none';
        bar.offsetHeight; // reflow
        bar.style.animation = '';

        showModal('menungguModal');
        startCancelCountdown();
    }

    function showBatalkanConfirm() {
        clearInterval(cancelInterval);
        showModal('batalkanConfirmModal');
    }

    function closeBatalkanConfirm() {
        closeModal('batalkanConfirmModal');

        // Lanjut countdown
        cancelInterval = setInterval(() => {
            cancelSeconds--;
            updateCancelDisplay();
            if (cancelSeconds <= 0) {
                clearInterval(cancelInterval);
                closeModal('menungguModal');
                showModal('diproseModal');
            }
        }, 1000);
    }

    function doBatalkan() {
        if (!currentOrderId) {
            // Kalau order ID belum ada, langsung tutup saja
            closeModal('batalkanConfirmModal');
            closeModal('menungguModal');
            showModal('batalkanDoneModal');
            return;
        }

        const btn = document.getElementById('batalkanBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Membatalkan...';

        fetch('/pembayaran/batalkan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ order_id: currentOrderId }),
        })
        .then(r => r.json())
        .then(() => {
            closeModal('batalkanConfirmModal');
            closeModal('menungguModal');
            showModal('batalkanDoneModal');
        })
        .catch(() => {
            // Tetap tampilkan dibatalkan meski request gagal
            closeModal('batalkanConfirmModal');
            closeModal('menungguModal');
            showModal('batalkanDoneModal');
        });
    }
</script>
@endpush