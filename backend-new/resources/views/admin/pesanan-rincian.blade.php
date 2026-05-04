@extends('layouts.app')

@section('title', 'Rincian Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('admin.pesanan') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="{{ route('admin.menu') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="{{ route('admin.riwayat') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="{{ route('admin.profil') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto border-t border-gray-100 pt-6">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.pesanan') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-0.5">Rincian Pesanan</h2>
                    <p class="text-sm text-[#FF6900] font-bold tracking-wide">{{ $order->order_code }}</p>
                </div>
            </div>

            {{-- Badge status --}}
            @php
                $statusMap = [
                    'pending_verification' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-yellow-50 text-yellow-600 border-yellow-100'],
                    'processing'           => ['label' => 'Dimasak', 'class' => 'bg-orange-50 text-[#FF6900] border-orange-100'],
                    'ready'                => ['label' => 'Siap Diambil', 'class' => 'bg-green-50 text-green-600 border-green-100'],
                    'completed'            => ['label' => 'Selesai', 'class' => 'bg-blue-50 text-blue-600 border-blue-100'],
                    'cancelled'            => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-500 border-red-100'],
                ];
                $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-50 text-gray-500 border-gray-100'];
            @endphp
            <span class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest border {{ $s['class'] }}">
                {{ $s['label'] }}
            </span>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mx-10 mt-5 px-5 py-3 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mx-10 mt-5 px-5 py-3 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-400"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Content --}}
        <div class="px-10 py-8 grid grid-cols-12 gap-8 pb-20">

            {{-- KOLOM KIRI — info customer + item list --}}
            <div class="col-span-12 lg:col-span-7 space-y-6">

                {{-- Info customer --}}
                <div class="bg-white rounded-[28px] p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-4">
                        @php $photo = $order->customer_snapshot['photo_profile'] ?? null; @endphp
                        @if($photo)
                            <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}"
                                class="w-14 h-14 rounded-full object-cover border border-gray-100" alt="foto">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-300 text-2xl">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-xl font-black text-gray-800">{{ $order->customer_snapshot['name'] ?? '-' }}</p>
                            @if(!empty($order->customer_snapshot['phone']))
                                <p class="text-sm text-gray-400 font-semibold mt-0.5">{{ $order->customer_snapshot['phone'] }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-1">
                                @if(($order->delivery_details['method'] ?? '') === 'delivery')
                                    <span class="text-[10px] font-black bg-blue-50 text-blue-500 px-2.5 py-1 rounded-lg uppercase">Antar Kurir</span>
                                @else
                                    <span class="text-[10px] font-black bg-purple-50 text-purple-500 px-2.5 py-1 rounded-lg uppercase">Ambil Sendiri</span>
                                @endif
                                @if(!empty($order->delivery_details['location_note']))
                                    <span class="text-[11px] text-gray-400 font-semibold">📍 {{ $order->delivery_details['location_note'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Item list --}}
                <div class="bg-white rounded-[28px] p-8 shadow-sm border border-gray-100">
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6">Menu Dipesan</p>

                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sm font-black text-[#FF6900] border border-gray-100 flex-shrink-0">
                                {{ $item['quantity'] }}x
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[15px] font-black text-gray-800">{{ $item['name'] }}</p>
                                        <p class="text-[11px] text-gray-400 font-semibold mt-0.5">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }} / item
                                        </p>
                                    </div>
                                    <p class="text-[15px] font-black text-gray-900">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </p>
                                </div>
                                @if(!empty($item['notes']))
                                <div class="mt-2 bg-white px-3 py-2 rounded-xl border border-gray-100">
                                    <p class="text-[11px] text-orange-600 font-semibold italic">
                                        <i class="fa-solid fa-comment-dots mr-1"></i> {{ $item['notes'] }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Catatan pesanan --}}
                    @if($order->order_notes)
                    <div class="mt-5 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                        <p class="text-[11px] font-black text-orange-400 uppercase tracking-widest mb-1">Catatan</p>
                        <p class="text-sm text-orange-700 font-semibold">{{ $order->order_notes }}</p>
                    </div>
                    @endif

                    {{-- Ringkasan harga --}}
                    <div class="mt-8 pt-6 border-t-2 border-dashed border-gray-100 space-y-3">
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase">
                            <span>Subtotal</span>
                            <span class="text-gray-800 font-black">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase">
                            <span>Ongkir</span>
                            <span class="text-gray-800 font-black">
                                {{ ($order->delivery_details['fee'] ?? 0) > 0
                                    ? 'Rp ' . number_format($order->delivery_details['fee'], 0, ',', '.')
                                    : 'Gratis' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <span class="text-base font-black text-gray-900 uppercase">Total Pembayaran</span>
                            <span class="text-2xl font-black text-[#FF6900]">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN — bukti bayar + aksi --}}
            <div class="col-span-12 lg:col-span-5 space-y-6">
                <div class="bg-white rounded-[28px] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-2 mb-5">
                        <i class="fa-solid fa-receipt text-gray-400"></i>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Bukti Pembayaran</p>
                    </div>

                    @if(!empty($order->payment['proof']))
                        <img src="{{ asset('storage/' . $order->payment['proof']) }}"
                            class="w-full rounded-2xl border border-gray-100 object-cover mb-6"
                            style="max-height: 320px; object-fit: contain;"
                            alt="Bukti Bayar">
                    @else
                        <div class="w-full h-40 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-6">
                            <p class="text-gray-300 text-sm font-semibold">Tidak ada bukti bayar</p>
                        </div>
                    @endif

                    {{-- Info waktu --}}
                    <div class="text-sm text-gray-400 font-semibold mb-6 space-y-1">
                        <p>📅 Dipesan: <span class="text-gray-700 font-bold">{{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></p>
                        @if(!empty($order->payment['paid_at']))
                        <p>✅ Dibayar: <span class="text-gray-700 font-bold">{{ \Carbon\Carbon::parse($order->payment['paid_at'])->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span></p>
                        @endif
                    </div>

                    {{-- Tombol aksi: hanya tampil kalau masih pending_verification --}}
                    @if($order->payment['status'] === 'pending_verification')
                    <div class="flex flex-col gap-3">
                        <form method="POST" action="{{ route('admin.pesanan.verify', $order->_id) }}">
                            @csrf
                            <button type="submit"
                                class="w-full py-4 rounded-2xl bg-[#22C55E] text-white font-black text-sm shadow-lg shadow-green-100 hover:brightness-105 transition-all">
                                ✓ Verifikasi & Terima
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.pesanan.reject', $order->_id) }}">
                            @csrf
                            <input type="hidden" name="reason" value="">
                            <button type="submit"
                                class="w-full py-4 rounded-2xl border-2 border-red-50 text-red-500 font-black text-sm hover:bg-red-50 transition-all">
                                ✕ Tolak Pembayaran
                            </button>
                        </form>
                    </div>
                    @elseif($order->payment['status'] === 'paid' && $order->status === 'processing')
                    {{-- Sudah diverifikasi, masih dimasak — bisa langsung tandai siap --}}
                    <form method="POST" action="{{ route('admin.pesanan.status', $order->_id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit"
                            class="w-full py-4 rounded-2xl bg-[#1a1a1a] text-white font-black text-sm hover:bg-black transition-all">
                            Tandai Siap Diambil
                        </button>
                    </form>
                    @else
                    <div class="px-4 py-3 bg-gray-50 rounded-2xl text-center text-sm text-gray-400 font-semibold">
                        Tidak ada aksi tersedia untuk status ini
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
</div>
@endsection