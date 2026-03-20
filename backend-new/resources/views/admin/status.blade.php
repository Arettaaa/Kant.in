@extends('layouts.app')

@section('title', 'Perbarui Status - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR (STAY) ======================== --}}
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
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative items-center text-start hide-scrollbar">
        
        {{-- Header (FIXED: Tambah z-30 dan bg-white solid) --}}
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-30 shadow-sm text-start">
            <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div class="text-start">
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1 text-start text-start">Perbarui Status</h2>
                <p id="displayOrderId" class="text-sm text-[#FF6900] font-bold tracking-wide text-start text-start">#ORD-000</p>
            </div>
        </div>

        <div class="w-full max-w-2xl px-6 py-10 space-y-10 pb-40 flex flex-col items-center">
            
            {{-- Status Selection --}}
            <div class="w-full">
                <p class="text-[11px] font-black text-gray-900 mb-6 uppercase tracking-[0.2em] text-start">Status Saat Ini</p>
                <div class="grid grid-cols-2 gap-6 w-full text-start">
                    <button id="btnDimasak" onclick="setStatus('dimasak')" class="flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 bg-white border-gray-100 text-gray-300 hover:border-orange-200">
                        <i id="iconDimasak" class="fa-solid fa-fire-flame-curved text-4xl text-start"></i>
                        <span class="text-lg font-black uppercase tracking-wider text-start">Dimasak</span>
                    </button>
                    <button id="btnSiap" onclick="setStatus('siap')" class="flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 bg-white border-gray-100 text-gray-300 hover:border-green-200">
                        <i id="iconSiap" class="fa-solid fa-circle-check text-4xl text-start"></i>
                        <span class="text-lg font-black uppercase tracking-wider text-start">Siap</span>
                    </button>
                </div>
                <p id="helperText" class="text-[12px] text-gray-400 mt-8 text-center font-medium italic">Pilih status untuk memberikan update ke pelanggan.</p>
            </div>

            {{-- Detail Pesanan Card (Sinkron Menu & Catatan) --}}
            <div class="bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 w-full text-start">
                <div class="flex items-center justify-between mb-10 text-start">
                    <div class="flex items-center gap-4 text-start">
                        <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-200 text-start">
                            <i class="fa-solid fa-user text-2xl text-start"></i>
                        </div>
                        <div class="text-start">
                            <p id="displayCustomerName" class="text-xl font-black text-gray-800 leading-tight text-start">Memuat...</p>
                            <span id="displayMethodBadge" class="text-[10px] font-black uppercase bg-blue-50 text-blue-500 px-2.5 py-1 rounded-lg tracking-wider mt-1 inline-block text-start">
                                <i class="fa-solid fa-truck-fast mr-1 text-start"></i> ANTAR KURIR
                            </span>
                        </div>
                    </div>
                    <p id="displayTime" class="text-sm text-gray-400 font-black tracking-tighter text-start text-start">🕒 --:-- WIB</p>
                </div>

                <div class="space-y-6 text-start">
                    <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] mb-4 text-start">Daftar Menu</p>
                    <div id="itemListStatus" class="space-y-6 text-start">
                        </div>
                </div>
            </div>
        </div>

        {{-- Action Button Fixed --}}
        <div class="fixed bottom-0 right-0 left-[240px] p-8 bg-white/90 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center text-start">
            <button id="btnComplete" disabled onclick="window.location.href='/admin/pesanan'" 
                class="w-full max-w-md py-5 bg-[#1A1A1A] text-white rounded-3xl font-black text-[15px] shadow-2xl flex items-center justify-center gap-3 transition-all duration-500 opacity-20 cursor-not-allowed text-start">
                Tandai Selesai & Diserahkan
            </button>
        </div>

    </main>
</div>

@push('scripts')
<script>
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        const name = params.get('name');
        const orderId = params.get('order');
        const time = params.get('time');

        if(name) document.getElementById('displayCustomerName').innerText = name;
        if(orderId) document.getElementById('displayOrderId').innerText = orderId;
        if(time) document.getElementById('displayTime').innerText = `🕒 ${time}`;

        // Menentukan Menu & Catatan Berdasarkan Nama (Sinkron Kelola Menu & Pesanan)
        const itemListContainer = document.getElementById('itemListStatus');
        let menusData = [];

        if(name === 'Alex Johnson') {
            menusData = [
                { qty: 2, name: 'Nasi Goreng Spesial', note: 'Pedas sedang' },
                { qty: 1, name: 'Brown Sugar Boba', note: 'Less sugar' }
            ];
        } else if(name === 'Sarah Smith') {
            menusData = [
                { qty: 1, name: 'Mie Goreng Ayam', note: 'Tidak pakai sayur' },
                { qty: 1, name: 'Es Teh Manis', note: 'Es batu banyak' }
            ];
        } else if(name === 'Budi Santoso') {
            menusData = [
                { qty: 2, name: 'Mie Goreng Ayam', note: 'Level 5' },
                { qty: 2, name: 'Brown Sugar Boba', note: 'Normal sugar' }
            ];
        } else {
            menusData = [{ qty: 1, name: 'Nasi Goreng Spesial', note: 'Porsi kuli' }]; // Default
        }

        // Tampilkan Menu & Catatan
        menusData.forEach((m) => {
            const html = `
                <div class="flex items-start gap-4 bg-gray-50/50 p-5 rounded-2xl border border-gray-100 text-start">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-sm font-black text-[#FF6900] border border-gray-100 flex-shrink-0 text-start">${m.qty}</div>
                    <div class="flex-1 text-start">
                        <p class="text-[15px] font-black text-gray-800 text-start">${m.name}</p>
                        <p class="text-xs text-[#EE4D2D] font-medium mt-1.5 bg-red-50 inline-block px-2.5 py-1 rounded-md text-start">
                            <i class="fa-solid fa-comment-dots mr-1.5 text-start"></i>Catatan: ${m.note}
                        </p>
                    </div>
                </div>`;
            itemListContainer.insertAdjacentHTML('beforeend', html);
        });

        // Logika Otomatis STAY SIAP (Contoh untuk David Lee jika ada)
        if(name === 'David Lee') {
            setStatus('siap');
        } else {
            setStatus('dimasak');
        }
    }

    function setStatus(status) {
        const btnDimasak = document.getElementById('btnDimasak');
        const btnSiap = document.getElementById('btnSiap');
        const btnComplete = document.getElementById('btnComplete');
        const helperText = document.getElementById('helperText');

        if (status === 'dimasak') {
            // Aktifkan Dimasak
            btnDimasak.className = "flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 shadow-2xl shadow-orange-200/50 bg-[#FF6900] border-[#FF6900] text-white scale-105 z-10 text-start";
            btnSiap.className = "flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 bg-white border-gray-100 text-gray-300 opacity-60 text-start";
            
            btnComplete.disabled = true;
            btnComplete.classList.add('opacity-20', 'cursor-not-allowed');
            helperText.textContent = "Pelanggan melihat: Sedang menyiapkan makananmu...";
            helperText.className = "text-[12px] text-orange-500 mt-8 text-center font-bold italic";
        } else {
            // Aktifkan Siap
            btnDimasak.className = "flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 bg-white border-gray-100 text-gray-300 opacity-60 text-start";
            btnSiap.className = "flex flex-col items-center justify-center gap-4 py-12 rounded-[40px] border-2 transition-all duration-500 shadow-2xl shadow-green-200/50 bg-[#22C55E] border-[#22C55E] text-white scale-105 z-10 text-start";
            
            btnComplete.disabled = false;
            btnComplete.classList.remove('opacity-20', 'cursor-not-allowed');
            helperText.textContent = "Pelanggan melihat: Makananmu sudah siap!";
            helperText.className = "text-[12px] text-green-600 mt-8 text-center font-bold italic";
        }
    }
</script>
@endpush
@endsection