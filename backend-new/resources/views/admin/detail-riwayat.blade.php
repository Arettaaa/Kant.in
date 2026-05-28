@extends('layouts.app')

@section('title', 'Detail Riwayat Pesanan - Kant.in')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
    <div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar text-start">

            {{-- Header --}}
            <div
                class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white border-b border-gray-100 shadow-sm text-start">
                <div class="flex items-center gap-4 text-start">
                    <a href="javascript:history.back()"
                        class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                        <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
                    </a>
                    <div class="text-start">
                        <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-0.5 text-start">Detail Pesanan</h2>
                        <p class="text-sm text-[#FF6900] font-bold uppercase tracking-widest text-start">
                            {{ $order->order_code }}</p>
                    </div>
                </div>
            </div>

            <div class="p-10 text-start">
                <div class="grid grid-cols-12 gap-8 text-start">

                    {{-- KOLOM KIRI — status & info transaksi --}}
                    <div class="col-span-12 lg:col-span-5 space-y-6 text-start">

                        {{-- Status & total --}}
                        <div
                            class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm flex flex-col items-center text-center space-y-6 text-start">
                            @php
                                $statusMap = [
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-green-50 text-green-600 border-green-100'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-red-50 text-red-500 border-red-100'],
                                ];
                                $s = $statusMap[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-50 text-gray-400 border-gray-100'];
                            @endphp
                            <span
                                class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border {{ $s['class'] }} text-start">
                                {{ $s['label'] }}
                            </span>

                            <div class="text-start">
                                <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest mb-1 text-center">
                                    Total Pembayaran</p>
                                <h1 class="text-4xl font-black text-gray-900 tracking-tight text-center">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </h1>
                            </div>

                            <div class="w-full grid grid-cols-2 gap-4 pt-6 border-t border-gray-50 text-left text-start">
                                <div class="bg-gray-50 p-4 rounded-[24px] border border-gray-100 text-start">
                                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2 text-start">
                                        METODE</p>
                                    <div class="flex items-center gap-2 font-black text-gray-800 text-sm text-start">
                                        <i class="fa-solid fa-qrcode text-blue-500 text-start"></i>
                                        {{ strtoupper($order->payment['method'] ?? 'QRIS') }}
                                    </div>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-[24px] border border-gray-100 text-start">
                                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2 text-start">
                                        {{ $order->status === 'completed' ? 'WAKTU SELESAI' : 'WAKTU PESAN' }}
                                    </p>
                                    <p class="font-black text-gray-800 text-[11px] leading-snug text-start">
                                        {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}<br>
                                        <span class="text-gray-400 uppercase text-[9px] text-start">
                                            {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                            WIB
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Info transaksi --}}
                        <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm space-y-4 text-start">
                            <div class="flex items-center gap-3 mb-2 text-start">
                                <div
                                    class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-sm text-start">
                                    <i class="fa-solid fa-id-card text-start"></i>
                                </div>
                                <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest text-start">Info
                                    Transaksi</h4>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4 text-start">
                                <span class="font-bold text-gray-400 text-start">Nama Pelanggan</span>
                                <span
                                    class="font-black text-gray-900 text-start">{{ $order->customer_snapshot['name'] ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4 text-start">
                                <span class="font-bold text-gray-400 text-start">ID Pesanan</span>
                                <span class="font-black text-gray-800 uppercase text-start">{{ $order->order_code }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4 text-start">
                                <span class="font-bold text-gray-400 text-start">Metode Pengiriman</span>
                                <span class="font-black text-gray-800 text-start">
                                    {{ ($order->delivery_details['method'] ?? '') === 'delivery' ? 'Antar Kurir' : 'Ambil Sendiri' }}
                                </span>
                            </div>
                            @if(!empty($order->payment['paid_at']))
                                <div class="flex justify-between items-center text-sm text-start">
                                    <span class="font-bold text-gray-400 text-start">Waktu Bayar</span>
                                    <span class="font-black text-gray-800 text-[12px] text-start">
                                        {{ \Carbon\Carbon::parse($order->payment['paid_at'])->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- KOLOM KANAN — item list --}}
                    <div class="col-span-12 lg:col-span-7 text-start">
                        <div class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm space-y-8 text-start">
                            <div class="flex items-center gap-4 text-start">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-xl border border-orange-100 text-start">
                                    <i class="fa-solid fa-utensils text-start"></i>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 text-start">Menu yang Dibeli</h3>
                            </div>

                            <div class="space-y-5 text-start">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-5 pb-5 border-b border-gray-50 text-start">
                                        <span
                                            class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF6900] flex items-center justify-center text-base font-black border border-orange-100 flex-shrink-0 text-start">
                                            {{ $item['quantity'] }}x
                                        </span>
                                        <div class="flex-1 text-start">
                                            <p class="text-[16px] font-black text-gray-900 text-start">{{ $item['name'] }}</p>
                                            <p
                                                class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mt-1 text-start">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }} / item
                                            </p>
                                            @if(!empty($item['notes']))
                                                <p class="text-[11px] text-orange-500 italic mt-1 text-start">📝
                                                    {{ $item['notes'] }}</p>
                                            @endif
                                        </div>
                                        <span class="text-[16px] font-black text-gray-900 text-start">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Ringkasan harga --}}
                            <div class="pt-6 border-t-2 border-dashed border-gray-100 space-y-4 text-start">
                                <div
                                    class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em] text-start">
                                    <span class="text-start">Subtotal</span>
                                    <span class="text-gray-900 font-black text-start">Rp
                                        {{ number_format($order->subtotal_amount, 0, ',', '.') }}</span>
                                </div>
                                <div
                                    class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em] text-start">
                                    <span class="text-start">Ongkir</span>
                                    <span class="text-gray-900 font-black text-start">
                                        {{ ($order->delivery_details['fee'] ?? 0) > 0
        ? 'Rp ' . number_format($order->delivery_details['fee'], 0, ',', '.')
        : 'Gratis' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-5 border-t border-gray-100 text-start">
                                    <span
                                        class="text-lg font-black text-gray-900 uppercase tracking-tighter text-start">Total
                                        Akhir</span>
                                    <span class="text-3xl font-black text-[#FF6900] text-start">
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