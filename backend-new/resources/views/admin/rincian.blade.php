@extends('layouts.app')

@section('title', 'Rincian Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR (SERAGAM) ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto text-start border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">
        
        <div class="sticky top-0 z-10 w-full flex items-center justify-between px-10 py-6 bg-white border-b border-gray-100 text-start">
            <div class="flex items-center gap-4 text-start">
                <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                    <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
                </a>
                <div class="text-start">
                    <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1 text-start">Rincian Pesanan</h2>
                    <p id="detOrderId" class="text-sm text-[#FF6900] font-bold tracking-wide text-start">#ORD-000</p>
                </div>
            </div>
        </div>

        <div class="px-10 py-8 grid grid-cols-12 gap-8 pb-20 text-start">
            {{-- KOLOM KIRI --}}
            <div class="col-span-12 lg:col-span-7 space-y-6 text-start">
                
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 text-start">
                    <div class="flex items-center gap-4 text-start">
                        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100 overflow-hidden text-gray-200 text-2xl text-start">
                            <i class="fa-solid fa-user text-start"></i>
                        </div>
                        <div class="text-start">
                            <p id="detName" class="text-xl font-black text-gray-800 leading-tight text-start">Memuat...</p>
                            <p class="text-sm text-gray-400 font-bold mt-1 uppercase tracking-wide text-start">Metode: <span class="text-blue-500 text-start">Antar Kurir</span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 text-start">
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6 text-start">Menu Dipesan</p>
                    
                    <div id="itemList" class="space-y-6 text-start"></div>

                    <div class="mt-10 pt-8 border-t-2 border-dashed border-gray-100 space-y-3 text-start">
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase text-start">
                            <span class="text-start">Subtotal</span>
                            <span id="detSub" class="text-start text-gray-800 font-black">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-400 uppercase text-start">
                            <span class="text-start">Biaya Ongkir</span>
                            <span id="detOngkir" class="text-start text-gray-800 font-black">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 text-start">
                            <span class="text-base font-black text-gray-900 uppercase text-start">Total Pembayaran</span>
                            <span id="detTotal" class="text-2xl font-black text-[#FF6900] text-start">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-span-12 lg:col-span-5 space-y-6 text-start">
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 text-start">
                    <div class="flex items-center gap-3 mb-6 text-start">
                        <i class="fa-solid fa-receipt text-gray-900 text-start"></i>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest text-start">Bukti Pembayaran</p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-1696413565d3?w=800" class="w-full h-80 object-cover rounded-2xl border border-gray-100 mb-4 shadow-inner text-start" alt="Struk">
                    
                    <div class="flex flex-col gap-3 text-start mt-8">
                        <button onclick="window.location.href='/admin/pesanan'" class="w-full py-4 rounded-2xl bg-[#22C55E] text-white font-black text-sm shadow-xl shadow-green-100 hover:brightness-110 transition-all text-center">
                            ✓ Verifikasi & Terima
                        </button>
                        <button class="w-full py-4 rounded-2xl border-2 border-red-50 text-red-500 font-black text-sm hover:bg-red-50 transition-all text-center">
                            ✕ Tolak Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    // DATA MASTER HARGA (Sama kayak di Kelola Menu)
    const priceMaster = {
        'Nasi Goreng Spesial': 25000,
        'Mie Goreng Ayam': 22000,
        'Es Teh Manis': 5000,
        'Brown Sugar Boba': 18000
    };

    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        
        if(params.get('name')) document.getElementById('detName').innerText = params.get('name');
        if(params.get('order')) document.getElementById('detOrderId').innerText = params.get('order');

        const items = params.get('items');
        if(items) {
            const listContainer = document.getElementById('itemList');
            let subtotalCount = 0;

            items.split(',').forEach(item => {
                const parts = item.trim().split(' ');
                const qty = parseInt(parts[0]);
                const name = parts.slice(1).join(' ');
                
                // --- LOGIKA MATEMATIKA ---
                const unitPrice = priceMaster[name] || 0;
                const lineTotal = unitPrice * qty; // Kelipatan harga
                subtotalCount += lineTotal; // Total semua menu

                const html = `
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50/50 border border-transparent hover:border-gray-100 transition-all text-start">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sm font-black text-[#FF6900] border border-gray-100 flex-shrink-0 text-start">
                            ${qty}x
                        </div>
                        <div class="flex-1 text-start">
                            <div class="flex justify-between items-start text-start">
                                <div>
                                    <p class="text-[16px] font-black text-gray-800 text-start">${name}</p>
                                    <p class="text-[11px] text-gray-400 font-bold mt-1 text-start italic">
                                        Harga Satuan: Rp ${unitPrice.toLocaleString('id-ID')}
                                    </p>
                                </div>
                                <div class="text-right text-start">
                                    <p class="text-[15px] font-black text-gray-900 text-start">
                                        Rp ${lineTotal.toLocaleString('id-ID')}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-3 bg-white p-3 rounded-xl border border-gray-100 text-start">
                                <p class="text-[11px] text-orange-700 font-bold italic text-start leading-none">
                                    <i class="fa-solid fa-comment-dots mr-1"></i> Catatan: Sesuai pesanan standar
                                </p>
                            </div>
                        </div>
                    </div>`;
                listContainer.insertAdjacentHTML('beforeend', html);
            });

            // Perhitungan Akhir
            const ongkir = 5000; 
            const totalBayar = subtotalCount + ongkir;

            // Masukkan hasil matematika ke UI
            document.getElementById('detSub').innerText = `Rp ${subtotalCount.toLocaleString('id-ID')}`;
            document.getElementById('detOngkir').innerText = `Rp ${ongkir.toLocaleString('id-ID')}`;
            document.getElementById('detTotal').innerText = `Rp ${totalBayar.toLocaleString('id-ID')}`;
        }
    }
</script>
@endpush
@endsection