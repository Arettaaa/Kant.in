@extends('layouts.app')

@section('title', 'Detail Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- SIDEBAR (Riwayat aktif) --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="{{ route('admin.pesanan') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="{{ route('admin.menu') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="{{ route('admin.riwayat') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
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

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.riwayat') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-0.5">Detail Pesanan</h2>
                    <p class="text-sm text-[#FF6900] font-bold uppercase tracking-widest">{{ $order->order_code }}</p>
                </div>
            </div>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-12 gap-8">

                {{-- KOLOM KIRI — status & info transaksi --}}
                <div class="col-span-12 lg:col-span-5 space-y-6">

                    {{-- Status & total --}}
                    <div class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm flex flex-col items-center text-center space-y-6">
                        @php
                            $statusMap = [
                                'completed' => ['label' => 'Selesai',    'class' => 'bg-green-50 text-green-600 border-green-100'],
                                'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-500 border-red-100'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-50 text-gray-400 border-gray-100'];
                        @endphp
                        <span class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border {{ $s['class'] }}">
                            {{ $s['label'] }}
                        </span>

                        <div>
                            <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <h1 class="text-4xl font-black text-gray-900 tracking-tight">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </h1>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-gray-50 text-left">
                            <div class="bg-gray-50 p-4 rounded-[24px] border border-gray-100">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2">METODE</p>
                                <div class="flex items-center gap-2 font-black text-gray-800 text-sm">
                                    <i class="fa-solid fa-qrcode text-blue-500"></i>
                                    {{ strtoupper($order->payment['method'] ?? 'QRIS') }}
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-[24px] border border-gray-100">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2">
                                    {{ $order->status === 'completed' ? 'WAKTU SELESAI' : 'WAKTU PESAN' }}
                                </p>
                                <p class="font-black text-gray-800 text-[11px] leading-snug">
                                    {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}<br>
                                    <span class="text-gray-400 uppercase text-[9px]">
                                        {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Info transaksi --}}
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm space-y-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-sm">
                                <i class="fa-solid fa-id-card"></i>
                            </div>
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Info Transaksi</h4>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4">
                            <span class="font-bold text-gray-400">Nama Pelanggan</span>
                            <span class="font-black text-gray-900">{{ $order->customer_snapshot['name'] ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4">
                            <span class="font-bold text-gray-400">ID Pesanan</span>
                            <span class="font-black text-gray-800 uppercase">{{ $order->order_code }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4">
                            <span class="font-bold text-gray-400">Metode Pengiriman</span>
                            <span class="font-black text-gray-800">
                                {{ ($order->delivery_details['method'] ?? '') === 'delivery' ? 'Antar Kurir' : 'Ambil Sendiri' }}
                            </span>
                        </div>
                        @if(!empty($order->payment['paid_at']))
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-400">Waktu Bayar</span>
                            <span class="font-black text-gray-800 text-[12px]">
                                {{ \Carbon\Carbon::parse($order->payment['paid_at'])->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN — item list --}}
                <div class="col-span-12 lg:col-span-7">
                    <div class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-xl border border-orange-100">
                                <i class="fa-solid fa-utensils"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900">Menu yang Dibeli</h3>
                        </div>

                        <div class="space-y-5">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-5 pb-5 border-b border-gray-50">
                                <span class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF6900] flex items-center justify-center text-base font-black border border-orange-100 flex-shrink-0">
                                    {{ $item['quantity'] }}x
                                </span>
                                <div class="flex-1">
                                    <p class="text-[16px] font-black text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }} / item
                                    </p>
                                    @if(!empty($item['notes']))
                                    <p class="text-[11px] text-orange-500 italic mt-1">📝 {{ $item['notes'] }}</p>
                                    @endif
                                </div>
                                <span class="text-[16px] font-black text-gray-900">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Ringkasan harga --}}
                        <div class="pt-6 border-t-2 border-dashed border-gray-100 space-y-4">
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em]">
                                <span>Subtotal</span>
                                <span class="text-gray-900 font-black">Rp {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em]">
                                <span>Ongkir</span>
                                <span class="text-gray-900 font-black">
                                    {{ ($order->delivery_details['fee'] ?? 0) > 0
                                        ? 'Rp ' . number_format($order->delivery_details['fee'], 0, ',', '.')
                                        : 'Gratis' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-5 border-t border-gray-100">
                                <span class="text-lg font-black text-gray-900 uppercase tracking-tighter">Total Akhir</span>
                                <span class="text-3xl font-black text-[#FF6900]">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection