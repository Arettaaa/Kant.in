@extends('layouts.app')

@section('title', 'Perbarui Status - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    @include('admin.partials.sidebar')

    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative hide-scrollbar">

        {{-- Header --}}
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-30 shadow-sm">
            <a href="{{ route('admin.pesanan') }}?tab=diproses"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div>
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-0.5">Perbarui Status</h2>
                <p class="text-sm text-[#FF6900] font-bold tracking-wide">{{ $order->order_code }}</p>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mx-10 mt-5 px-5 py-3 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
        </div>
        @endif

        <div class="w-full max-w-2xl mx-auto px-6 py-10 space-y-8 pb-40">

            {{-- Status Selection --}}
            <div class="w-full">
                <p class="text-[11px] font-black text-gray-400 mb-5 uppercase tracking-[0.2em]">Status Saat Ini</p>
                <div class="grid grid-cols-2 gap-5 w-full">

                    {{-- Tombol Dimasak --}}
                    <button id="btnDimasak" type="button" onclick="setStatus('dimasak')"
                        class="flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-300
                            {{ $order->status === 'processing' ? 'bg-[#FF6900] border-[#FF6900] text-white shadow-2xl shadow-orange-200/50 scale-105' : 'bg-white border-gray-100 text-gray-300 opacity-60' }}">
                        <i class="fa-solid fa-fire-flame-curved text-4xl"></i>
                        <span class="text-lg font-black uppercase tracking-wider">Dimasak</span>
                    </button>

                    {{-- Tombol Siap --}}
                    <button id="btnSiap" type="button" onclick="setStatus('siap')"
                        class="flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-300
                            {{ $order->status === 'ready' ? 'bg-[#22C55E] border-[#22C55E] text-white shadow-2xl shadow-green-200/50 scale-105' : 'bg-white border-gray-100 text-gray-300' }} hover:border-green-200">
                        <i class="fa-solid fa-circle-check text-4xl"></i>
                        <span class="text-lg font-black uppercase tracking-wider">Siap</span>
                    </button>

                </div>
                <p id="helperText"
                    class="text-[12px] mt-6 text-center font-bold italic
                        {{ $order->status === 'ready' ? 'text-green-600' : 'text-orange-500' }}">
                    {{ $order->status === 'ready' ? 'Pelanggan melihat: Makananmu sudah siap!' : 'Pelanggan melihat: Sedang menyiapkan makananmu...' }}
                </p>
            </div>

            {{-- Detail Pesanan --}}
            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 w-full">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        @php $photo = $order->customer_snapshot['photo_profile'] ?? null; @endphp
                        @if($photo)
                            <img src="{{ Str::startsWith($photo, 'http') ? $photo : asset('storage/' . $photo) }}"
                                class="w-14 h-14 rounded-full object-cover border border-gray-100" alt="foto">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-300">
                                <i class="fa-solid fa-user text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-xl font-black text-gray-800">{{ $order->customer_snapshot['name'] ?? '-' }}</p>
                            @if(($order->delivery_details['method'] ?? '') === 'delivery')
                                <span class="text-[10px] font-black uppercase bg-blue-50 text-blue-500 px-2.5 py-1 rounded-lg tracking-wider mt-1 inline-block">
                                    <i class="fa-solid fa-truck-fast mr-1"></i> Antar Kurir
                                </span>
                            @else
                                <span class="text-[10px] font-black uppercase bg-purple-50 text-purple-500 px-2.5 py-1 rounded-lg tracking-wider mt-1 inline-block">
                                    <i class="fa-solid fa-store mr-1"></i> Ambil Sendiri
                                </span>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 font-black">
                        🕒 {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB
                    </p>
                </div>

                <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] mb-5">Daftar Menu</p>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-start gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sm font-black text-[#FF6900] border border-gray-100 flex-shrink-0">
                            {{ $item['quantity'] }}x
                        </div>
                        <div class="flex-1">
                            <p class="text-[15px] font-black text-gray-800">{{ $item['name'] }}</p>
                            @if(!empty($item['notes']))
                            <p class="text-xs text-red-500 font-medium mt-1.5 bg-red-50 inline-block px-2.5 py-1 rounded-md italic">
                                <i class="fa-solid fa-comment-dots mr-1"></i> {{ $item['notes'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($order->order_notes)
                <div class="mt-5 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                    <p class="text-[11px] font-black text-orange-400 uppercase tracking-widest mb-1">Catatan Pesanan</p>
                    <p class="text-sm text-orange-700 font-semibold">{{ $order->order_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Tombol Simpan Fixed di bawah --}}
        <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/90 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center">
            <form method="POST" action="{{ route('admin.pesanan.updateStatus', $order->_id) }}" class="w-full max-w-md">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" id="inputStatus" value="{{ $order->status }}">
                <button id="btnSimpan" type="submit"
                    class="w-full py-5 rounded-3xl font-black text-[15px] shadow-2xl flex items-center justify-center gap-3 transition-all duration-300
                        {{ $order->status === 'ready' ? 'bg-[#1A1A1A] text-white cursor-pointer' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
                    {{ $order->status === 'processing' ? 'disabled' : '' }}>
                    {{ $order->status === 'ready' ? 'Simpan — Pesanan Siap Diambil' : 'Pilih "Siap" untuk menyimpan' }}
                </button>
            </form>
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
    const currentStatus = '{{ $order->status }}';

    function setStatus(status) {
        const btnDimasak  = document.getElementById('btnDimasak');
        const btnSiap     = document.getElementById('btnSiap');
        const helperText  = document.getElementById('helperText');
        const inputStatus = document.getElementById('inputStatus');
        const btnSimpan   = document.getElementById('btnSimpan');

        const activeOrange = 'flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-300 bg-[#FF6900] border-[#FF6900] text-white shadow-2xl shadow-orange-200/50 scale-105';
        const activeGreen  = 'flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-300 bg-[#22C55E] border-[#22C55E] text-white shadow-2xl shadow-green-200/50 scale-105';
        const inactive     = 'flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-300 bg-white border-gray-100 text-gray-300 opacity-60';

        if (status === 'dimasak') {
            // Hanya bisa pilih dimasak kalau status masih processing
            // Kalau sudah ready, tidak bisa balik ke dimasak
            if (currentStatus === 'ready') return;

            btnDimasak.className = activeOrange;
            btnSiap.className    = inactive;
            inputStatus.value    = 'processing';

            helperText.textContent  = 'Pelanggan melihat: Sedang menyiapkan makananmu...';
            helperText.className    = 'text-[12px] mt-6 text-center font-bold italic text-orange-500';

            btnSimpan.disabled      = true;
            btnSimpan.className     = btnSimpan.className.replace('bg-[#1A1A1A] text-white cursor-pointer', 'bg-gray-200 text-gray-400 cursor-not-allowed');
            btnSimpan.textContent   = 'Pilih "Siap" untuk menyimpan';

        } else {
            btnDimasak.className = inactive;
            btnSiap.className    = activeGreen;
            inputStatus.value    = 'ready';

            helperText.textContent  = 'Pelanggan melihat: Makananmu sudah siap!';
            helperText.className    = 'text-[12px] mt-6 text-center font-bold italic text-green-600';

            btnSimpan.disabled      = false;
            btnSimpan.className     = btnSimpan.className.replace('bg-gray-200 text-gray-400 cursor-not-allowed', 'bg-[#1A1A1A] text-white cursor-pointer');
            btnSimpan.textContent   = 'Simpan — Pesanan Siap Diambil';
        }
    }
</script>
@endpush