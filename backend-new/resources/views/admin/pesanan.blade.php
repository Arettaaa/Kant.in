@extends('layouts.app')

@section('title', 'Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .tab-active {
        border-bottom: 3px solid #FF6900;
        color: #FF6900;
        font-weight: 800;
    }
    .tab-inactive {
        border-bottom: 3px solid transparent;
        color: #9ca3af;
        font-weight: 700;
    }

    .card-order {
        background: white;
        border-radius: 24px;
        border: 1px solid #f3f4f6;
        padding: 24px;
        display: flex;
        flex-direction: column;
        transition: box-shadow 0.2s ease;
    }
    .card-order:hover {
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    }

    .badge-delivery {
        background: #eff6ff;
        color: #3b82f6;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .badge-pickup {
        background: #f5f3ff;
        color: #7c3aed;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .badge-dimasak {
        background: #fff7ed;
        color: #FF6900;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-siap {
        background: #f0fdf4;
        color: #16a34a;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .btn-siap {
        background: #1a1a1a;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 8px 16px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
        letter-spacing: 0.05em;
        transition: background 0.15s;
    }
    .btn-siap:hover { background: #000; }

    .btn-detail {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #FF6900;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: gap 0.15s;
    }
    .btn-detail:hover { gap: 8px; }

    .toggle-track {
        width: 52px;
        height: 28px;
        border-radius: 99px;
        display: flex;
        align-items: center;
        padding: 3px;
        cursor: pointer;
        transition: background 0.25s;
        position: relative;
    }
    .toggle-thumb {
        width: 22px;
        height: 22px;
        background: white;
        border-radius: 99px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.18);
        transition: transform 0.25s;
        position: absolute;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 64px 0;
        color: #d1d5db;
        gap: 12px;
    }
    .empty-state i { font-size: 40px; }
    .empty-state p { font-size: 14px; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    @include('admin.partials.sidebar')
    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                    <i class="fa-solid fa-store text-lg text-[#FF6900]"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-0.5">{{ $canteen->name ?? 'Kantin' }}</h2>
                    <p class="text-xs text-gray-400 font-semibold">Dasbor Kantin</p>
                </div>
            </div>

            {{-- Toggle Buka/Tutup --}}
            <div class="flex items-center gap-3">
                <span id="statusLabel" class="text-xs font-black tracking-widest {{ $canteen->is_open ? 'text-green-500' : 'text-gray-400' }}">
                    {{ $canteen->is_open ? 'MENERIMA PESANAN' : 'TUTUP SEMENTARA' }}
                </span>
                <div id="toggleTrack"
                    onclick="toggleKantin()"
                    class="toggle-track"
                    style="background-color: {{ $canteen->is_open ? '#22c55e' : '#d1d5db' }}; cursor:pointer;">
                    <span id="toggleThumb"
                        class="toggle-thumb"
                        style="transform: translateX({{ $canteen->is_open ? '24px' : '0px' }})">
                    </span>
                </div>
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
        <div id="flashSuccess" class="mx-10 mt-6 px-5 py-3 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold flex items-center gap-2 transition-opacity duration-500">
            <i class="fa-solid fa-circle-check text-green-500"></i>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div id="flashError" class="mx-10 mt-6 px-5 py-3 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-semibold flex items-center gap-2 transition-opacity duration-500">
            <i class="fa-solid fa-circle-exclamation text-red-400"></i>
            {{ session('error') }}
        </div>
        @endif

        {{-- Tabs --}}
        <div class="flex items-center px-10 mt-8 border-b border-gray-100">
            <button onclick="switchTab('masuk')" id="tabMasukBtn"
                style="padding-bottom:12px; margin-right:32px; font-size:15px; font-weight:800; border:none; background:none; cursor:pointer; border-bottom: 3px solid #FF6900; color:#FF6900;">
                Pesanan Masuk
                <span id="badgeMasuk" style="margin-left:8px; padding:2px 8px; background:#FF6900; color:white; border-radius:999px; font-size:10px; font-weight:900;">{{ $menungguVerifikasi->count() }}</span>
            </button>
            <button onclick="switchTab('diproses')" id="tabDiprosesBtn"
                style="padding-bottom:12px; font-size:15px; font-weight:700; border:none; background:none; cursor:pointer; border-bottom: 3px solid transparent; color:#9ca3af;">
                Diproses
                <span id="badgeDiproses" style="margin-left:8px; padding:2px 8px; background:#e5e7eb; color:#9ca3af; border-radius:999px; font-size:10px; font-weight:900;">{{ $sedangDiproses->count() }}</span>
            </button>
        </div>

        {{-- ===== TAB: PESANAN MASUK ===== --}}
        <div id="tabMasuk" class="flex-1 px-10 py-8">
            @if($menungguVerifikasi->isEmpty())
                <div class="empty-state">
                    <i class="fa-regular fa-clipboard"></i>
                    <p>Belum ada pesanan masuk</p>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($menungguVerifikasi as $order)
                <div class="card-order">
                    {{-- Header card --}}
                    <div class="flex justify-between items-start mb-5">
                        <div class="flex items-center gap-3">
                            @php $photo = $order->customer_snapshot['photo_profile'] ?? null; @endphp
                            @if($photo)
                                <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}"
                                    class="w-11 h-11 rounded-full object-cover" alt="foto">
                            @else
                                <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-gray-300 text-lg"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-[15px] font-black text-gray-800">{{ $order->customer_snapshot['name'] ?? '-' }}</p>
                                <p class="text-[11px] text-gray-400 font-semibold mt-0.5">
                                    🕒 {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-300 mb-1">{{ $order->order_code }}</p>
                            @if($order->delivery_details['method'] === 'delivery')
                                <span class="badge-delivery">Antar Kurir</span>
                            @else
                                <span class="badge-pickup">Ambil Sendiri</span>
                            @endif
                        </div>
                    </div>

                    {{-- Item list --}}
                    <div class="flex-1 py-4 border-y border-gray-50 space-y-2 mb-4">
                        @foreach($order->items as $item)
                        <p class="text-[13px] text-gray-700">
                            <span class="font-black">{{ $item['quantity'] }}x</span> {{ $item['name'] }}
                        </p>
                        @endforeach
                        @if($order->order_notes)
                        <p class="text-[11px] text-gray-400 italic mt-1">📝 {{ $order->order_notes }}</p>
                        @endif
                    </div>

                    {{-- Total & detail --}}
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-0.5">Total</p>
                            <p class="text-lg font-black text-[#FF6900]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('admin.pesanan.rincian', $order->_id) }}" class="btn-detail">
                            Lihat Detail <i class="fa-solid fa-chevron-right text-[11px]"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ===== TAB: DIPROSES ===== --}}
        <div id="tabDiproses" class="hidden flex-1 px-10 py-8">
            @if($sedangDiproses->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-fire-flame-curved"></i>
                    <p>Tidak ada pesanan yang sedang diproses</p>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($sedangDiproses as $order)
                <a href="{{ route('admin.pesanan.status', $order->_id) }}" class="card-order block" style="text-decoration:none;">
                    {{-- Header card --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            @php
                                $photo = $order->customer_snapshot['photo_profile'] ?? null;
                            @endphp
                            @if($photo)
                                <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}"
                                    class="w-11 h-11 rounded-full object-cover" alt="foto">
                            @else
                                <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-gray-300 text-lg"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-[15px] font-black text-gray-800">{{ $order->customer_snapshot['name'] ?? '-' }}</p>
                                <p class="text-[11px] text-gray-400 font-semibold mt-0.5">
                                    🕒 {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-gray-300 mb-1">{{ $order->order_code }}</p>
                            @if(($order->delivery_details['method'] ?? '') === 'delivery')
                                <span class="badge-delivery">Antar Kurir</span>
                            @else
                                <span class="badge-pickup">Ambil Sendiri</span>
                            @endif
                        </div>
                    </div>

                    {{-- Item ringkas --}}
                    <div class="text-[13px] text-gray-500 mb-4">
                        {{ count($order->items) }} item
                        &nbsp;·&nbsp;
                        <span class="font-bold text-gray-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    {{-- Footer: badge status + lihat detail --}}
                    <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                        @if($order->status === 'processing')
                            <span class="badge-dimasak"><i class="fa-solid fa-fire-flame-curved mr-1"></i>Dimasak</span>
                        @else
                            <span class="badge-siap"><i class="fa-solid fa-check mr-1"></i>Siap Diambil</span>
                        @endif
                        <span class="btn-detail">Lihat Detail <i class="fa-solid fa-chevron-right text-[11px]"></i></span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    // ── Auto dismiss flash message ─────────────────────────────
    ['flashSuccess', 'flashError'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            setTimeout(() => {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }, 3000); // hilang setelah 3 detik
        }
    });

    // ── Auto buka tab diproses kalau redirect dari status.blade ──
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'diproses') {
        switchTab('diproses');
    }

    // ── Tab switching ──────────────────────────────────────────
    function switchTab(tab) {
        const tMasuk    = document.getElementById('tabMasuk');
        const tDiproses = document.getElementById('tabDiproses');
        const bMasuk    = document.getElementById('tabMasukBtn');
        const bDiproses = document.getElementById('tabDiprosesBtn');
        const badgeMasuk    = document.getElementById('badgeMasuk');
        const badgeDiproses = document.getElementById('badgeDiproses');

        if (tab === 'masuk') {
            tMasuk.classList.remove('hidden');
            tDiproses.classList.add('hidden');

            bMasuk.style.borderBottom   = '3px solid #FF6900';
            bMasuk.style.color          = '#FF6900';
            bMasuk.style.fontWeight     = '800';
            bDiproses.style.borderBottom = '3px solid transparent';
            bDiproses.style.color        = '#9ca3af';
            bDiproses.style.fontWeight   = '700';

            badgeMasuk.style.background  = '#FF6900';
            badgeMasuk.style.color       = 'white';
            badgeDiproses.style.background = '#e5e7eb';
            badgeDiproses.style.color      = '#9ca3af';
        } else {
            tDiproses.classList.remove('hidden');
            tMasuk.classList.add('hidden');

            bDiproses.style.borderBottom = '3px solid #FF6900';
            bDiproses.style.color        = '#FF6900';
            bDiproses.style.fontWeight   = '800';
            bMasuk.style.borderBottom   = '3px solid transparent';
            bMasuk.style.color          = '#9ca3af';
            bMasuk.style.fontWeight     = '700';

            badgeDiproses.style.background = '#FF6900';
            badgeDiproses.style.color      = 'white';
            badgeMasuk.style.background  = '#e5e7eb';
            badgeMasuk.style.color       = '#9ca3af';
        }
    }

    // ── Toggle buka/tutup kantin via AJAX ─────────────────────
    let isOpen = {{ $canteen->is_open ? 'true' : 'false' }};

    function toggleKantin() {
        const track  = document.getElementById('toggleTrack');
        const thumb  = document.getElementById('toggleThumb');
        const label  = document.getElementById('statusLabel');

        isOpen = !isOpen;

        // Update UI optimistik
        track.style.backgroundColor = isOpen ? '#22c55e' : '#d1d5db';
        thumb.style.transform       = isOpen ? 'translateX(24px)' : 'translateX(0)';
        label.textContent           = isOpen ? 'MENERIMA PESANAN' : 'TUTUP SEMENTARA';
        label.style.color           = isOpen ? '#22c55e' : '#6b7280';

        // Hit API
        fetch('{{ route('admin.kantin.toggleOpen') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ is_open: isOpen }),
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                // Rollback UI kalau gagal
                isOpen = !isOpen;
                track.style.backgroundColor = isOpen ? '#22c55e' : '#d1d5db';
                thumb.style.transform       = isOpen ? 'translateX(24px)' : 'translateX(0)';
                label.textContent           = isOpen ? 'MENERIMA PESANAN' : 'TUTUP SEMENTARA';
                label.style.color           = isOpen ? '#22c55e' : '#6b7280';
                alert('Gagal mengubah status kantin.');
            }
        })
        .catch(() => {
            alert('Terjadi kesalahan jaringan.');
        });
    }
</script>
@endpush