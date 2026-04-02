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
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR (RIWAYAT AKTIF) --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg> Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg> Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil Kantin
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto border-t border-gray-50 pt-6 text-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Keluar
        </a>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar text-start relative">
        <div class="w-full flex items-center justify-between px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="/admin/riwayat" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                    <i class="fa-solid fa-arrow-left text-gray-400"></i>
                </a>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Detail Pesanan</h2>
                    <p id="detOrderId" class="text-sm text-[#FF6900] font-bold uppercase tracking-widest">ORD-000</p>
                </div>
            </div>
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-orange-50 text-[#FF6900] hover:bg-orange-100 transition-all shadow-sm">
                <i class="fa-solid fa-download text-sm"></i>
            </button>
        </div>

        <div class="p-10">
            <div class="grid grid-cols-12 gap-8">
                <div class="col-span-12 lg:col-span-5 space-y-8">
                    <div class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm flex flex-col items-center text-center space-y-8">
                        <div id="detBadge" class="px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border">Loading...</div>
                        <div>
                            <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest mb-1">Total Pembayaran</p>
                            <h1 id="detTotalPrice" class="text-4xl font-black text-gray-900 tracking-tight">Rp 0</h1>
                        </div>
                        <div class="w-full grid grid-cols-2 gap-4 pt-8 border-t border-gray-50 text-start">
                            <div class="bg-gray-50/50 p-4 rounded-[28px] border border-gray-100/50">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2">METODE</p>
                                <div class="flex items-center gap-2 font-black text-gray-800 text-sm"><i class="fa-solid fa-qrcode text-blue-500"></i> QRIS</div>
                            </div>
                            <div class="bg-gray-50/50 p-4 rounded-[28px] border border-gray-100/50">
                                <p class="text-[9px] font-black text-gray-300 uppercase tracking-wider mb-2">WAKTU SELESAI</p>
                                <p id="detWib" class="font-black text-gray-800 text-[11px] leading-tight">-- -- ----<br><span class="text-gray-400 uppercase text-[9px]">00:00 WIB</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm space-y-5 text-start">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-sm"><i class="fa-solid fa-id-card"></i></div>
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest">Info Transaksi</h4>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4">
                            <span class="font-bold text-gray-400">Nama Pelanggan</span>
                            <span id="detCustName" class="font-black text-gray-900">...</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-4">
                            <span class="font-bold text-gray-400">ID Pesanan</span>
                            <span id="detOrderIdLabel" class="font-black text-gray-800 uppercase">...</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-gray-400">ID Transaksi</span>
                            <span class="font-bold text-gray-500 font-mono text-[11px] tracking-tight">QRS-8890-1234-ABCD</span>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-7">
                    <div class="bg-white rounded-[44px] p-10 border border-gray-100 shadow-sm space-y-10 text-start">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF6900] text-xl border border-orange-100"><i class="fa-solid fa-utensils"></i></div>
                            <h3 class="text-xl font-black text-gray-900">Menu yang dibeli</h3>
                        </div>
                        <div id="detItemsList" class="space-y-8"></div>
                        <div class="pt-10 border-t-2 border-dashed border-gray-100 space-y-5">
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em]"><span>Subtotal</span><span id="detSub" class="text-gray-900 font-black">Rp 0</span></div>
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em]"><span>PPN (11%)</span><span class="text-gray-900 font-black">Rp 0</span></div>
                            <div class="flex justify-between text-xs font-bold text-gray-400 uppercase tracking-[0.1em]"><span>Biaya Aplikasi</span><span class="text-gray-900 font-black">Rp 2.000</span></div>
                            <div class="flex justify-between items-center pt-6 border-t border-gray-100"><span class="text-lg font-black text-gray-900 uppercase tracking-tighter">Total Akhir</span><span id="detTotalFinal" class="text-3xl font-black text-[#FF6900]">Rp 0</span></div>
                        </div>
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
        const name = params.get('name') || "Pelanggan";
        const orderId = params.get('order') || "ORD-000";
        const price = params.get('price') || "0";
        const status = params.get('status') || "SELESAI";
        const timeFromUrl = params.get('time'); // AMBIL JAM DARI URL

        document.getElementById('detCustName').innerText = name;
        document.getElementById('detOrderId').innerText = orderId;
        document.getElementById('detOrderIdLabel').innerText = orderId;
        document.getElementById('detTotalPrice').innerText = 'Rp ' + price;
        document.getElementById('detTotalFinal').innerText = 'Rp ' + price;
        document.getElementById('detSub').innerText = 'Rp ' + price;

        const badge = document.getElementById('detBadge');
        badge.innerText = status;
        badge.className = (status === 'BATAL' || status === 'DIBATALKAN') ? "px-6 py-2 rounded-full bg-red-50 text-red-500 text-[10px] font-black uppercase border border-red-100" : "px-6 py-2 rounded-full bg-green-50 text-[#22c55e] text-[10px] font-black uppercase border border-green-100";

        // REVISI LOGIKA WAKTU: Kunci jam dari URL
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', timeZone: 'Asia/Jakarta' });
        const displayTime = timeFromUrl ? timeFromUrl : now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' }) + " WIB";
        
        document.getElementById('detWib').innerHTML = `${dateStr}<br><span class="text-gray-400 font-bold uppercase text-[9px]">${displayTime}</span>`;

        const list = document.getElementById('detItemsList');
        let items = name.includes('Budi') ? [{q: 2, n: 'Mie Goreng Ayam', p: '22.000'}] : [{q: 1, n: 'Es Teh Manis', p: '5.000'}];
        items.forEach(m => {
            list.innerHTML += `<div class="flex items-center gap-6 pb-8 border-b border-gray-50 text-start"><span class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF6900] flex items-center justify-center text-base font-black border border-orange-100">${m.q}x</span><div class="flex-1"><p class="text-[17px] font-black text-gray-900">${m.n}</p><p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mt-1.5 bg-gray-50 inline-block px-3 py-1 rounded-md text-start">Rp ${m.p} / item</p></div><span class="text-lg font-black text-gray-900">Rp ${m.p}</span></div>`;
        });
    }
</script>
@endpush
@endsection