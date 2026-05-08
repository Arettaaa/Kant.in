@extends('layouts.app')

@section('title', 'Pesanan Dibatalkan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">

        {{-- Header --}}
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-30">
            <a href="{{ route('admin.pesanan') }}"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div>
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-0.5">Status Pesanan</h2>
                <p class="text-sm text-red-500 font-bold tracking-wide">{{ $order->order_code }}</p>
            </div>
        </div>

        <div class="w-full max-w-xl mx-auto px-6 py-10 flex flex-col items-center gap-6">

            {{-- Banner dibatalkan --}}
            <div class="w-full bg-red-50 border border-red-100 rounded-[40px] p-10 flex flex-col items-center shadow-sm">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-4 shadow-lg shadow-red-200">
                    <i class="fa-solid fa-xmark text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-red-600 mb-2 tracking-tight text-center">Pesanan Dibatalkan</h3>
                <p class="text-sm text-red-400 font-medium text-center leading-relaxed italic px-4">
                    Pesanan ini telah dibatalkan dan tidak dapat diproses lebih lanjut.
                </p>
            </div>

            {{-- Info pesanan --}}
            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 w-full">

                {{-- Customer --}}
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-50">
                    <div class="flex items-center gap-4">
                        @php $photo = $order->customer_snapshot['photo_profile'] ?? null; @endphp
                        @if($photo)
                            <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}"
                                class="w-14 h-14 rounded-full object-cover border border-gray-100" alt="foto">
                        @else
                            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 font-black text-xl">
                                {{ strtoupper(substr($order->customer_snapshot['name'] ?? 'P', 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-lg font-black text-gray-800">{{ $order->customer_snapshot['name'] ?? '-' }}</p>
                            <span class="text-[10px] font-black uppercase text-red-500 bg-red-50 px-2 py-0.5 rounded-md">
                                <i class="fa-solid fa-clock mr-1"></i> Dibatalkan
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 font-bold">
                        🕒 {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                    </p>
                </div>

                {{-- Item list --}}
                <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] mb-4">Daftar Menu Saat Pembatalan</p>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-sm font-black text-gray-400 border border-gray-100 flex-shrink-0">
                            {{ $item['quantity'] }}x
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-black text-gray-800">{{ $item['name'] }}</p>
                            @if(!empty($item['notes']))
                            <p class="text-xs text-red-500 font-bold mt-1.5 bg-red-50 inline-block px-3 py-1 rounded-xl italic">
                                {{ $item['notes'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Info pembatalan --}}
                <div class="mt-6 p-4 bg-red-50 rounded-2xl border border-red-100">
                    <p class="text-[11px] font-black text-red-400 uppercase tracking-widest mb-1">Alasan Pembatalan</p>
                    <p class="text-sm text-red-600 font-semibold">
                        {{ $order->payment['status'] === 'rejected'
                            ? 'Pembayaran ditolak oleh admin kantin.'
                            : 'Pesanan dibatalkan oleh admin kantin.' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.pesanan') }}"
                class="text-sm font-bold text-gray-400 hover:text-[#FF6900] transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Pesanan
            </a>

        </div>
    </main>
</div>
@endsection