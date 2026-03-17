@extends('layouts.app')

@section('title', 'Rincian Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- SIDEBAR (Tetap sama) --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
        </nav>
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 mt-auto hover:text-red-500"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto">
        
        {{-- Header Status --}}
        <div class="w-full flex items-center justify-between px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1">Rincian Pesanan</h2>
                    <p id="detOrderId" class="text-sm text-orange-500 font-bold tracking-wide">#ORD-000</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="px-6 py-2.5 rounded-xl border-2 border-red-50 text-red-500 font-black text-sm hover:bg-red-50 transition-all">✕ Tolak</button>
                <button onclick="window.location.href='/admin/pesanan'" class="px-6 py-2.5 rounded-xl bg-[#22C55E] text-white font-black text-sm shadow-lg shadow-green-200 hover:brightness-110">✓ Verifikasi & Terima</button>
            </div>
        </div>

        <div class="px-10 py-8 grid grid-cols-12 gap-8 pb-20">
            
            {{-- KOLOM KIRI --}}
            <div class="col-span-12 lg:col-span-7 space-y-6">
                
                {{-- Card User --}}
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100 overflow-hidden text-gray-200 text-2xl">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <p id="detName" class="text-xl font-black text-gray-800 leading-tight">Memuat...</p>
                            <p class="text-sm text-gray-400 font-bold mt-1">Metode: <span class="text-blue-500 uppercase">Antar Kurir</span></p>
                        </div>
                    </div>
                </div>

                {{-- Card Menu Dinamis --}}
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest mb-6">Menu Dipesan</p>
                    <div id="itemList" class="space-y-6"></div>

                    {{-- Ringkasan Pembayaran Dinamis --}}
                    <div class="mt-10 pt-8 border-t-2 border-dashed border-gray-100 space-y-3">
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase">
                            <span>Subtotal</span>
                            <span id="detSub">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase">
                            <span>Biaya Ongkir</span>
                            <span id="detOngkir">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-4">
                            <span class="text-base font-black text-gray-900 uppercase">Total Pembayaran</span>
                            <span id="detTotal" class="text-2xl font-black text-[#FF6900]">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-span-12 lg:col-span-5">
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fa-solid fa-receipt text-[#FF6900]"></i>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Bukti Pembayaran</p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-1696413565d3?w=800" class="w-full h-80 object-cover rounded-2xl border border-gray-100 mb-4" alt="Struk">
                    <div class="p-4 rounded-2xl bg-orange-50 border border-orange-100">
                        <p class="text-[11px] text-gray-500 font-medium">Pastikan nominal sesuai sebelum melakukan verifikasi.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

@push('scripts')
<script>
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        
        if(params.get('name')) document.getElementById('detName').innerText = params.get('name');
        if(params.get('order')) document.getElementById('detOrderId').innerText = params.get('order');
        if(params.get('total')) document.getElementById('detTotal').innerText = params.get('total');
        if(params.get('sub')) document.getElementById('detSub').innerText = params.get('sub');
        if(params.get('ongkir')) document.getElementById('detOngkir').innerText = params.get('ongkir');

        const items = params.get('items');
        if(items) {
            const listContainer = document.getElementById('itemList');
            items.split(',').forEach(item => {
                const parts = item.trim().split(' ');
                const qty = parts[0];
                const name = parts.slice(1).join(' ');
                const html = `
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-sm font-black text-[#FF6900] border border-orange-100 flex-shrink-0">${qty}</div>
                        <div class="flex-1">
                            <p class="text-[16px] font-black text-gray-800">${name}</p>
                            <p class="text-xs text-orange-700 font-bold mt-2 bg-orange-50 p-2 rounded-lg italic">Catatan: Sesuai pesanan standar</p>
                        </div>
                    </div>`;
                listContainer.insertAdjacentHTML('beforeend', html);
            });
        }
    }
</script>
@endpush
@endsection