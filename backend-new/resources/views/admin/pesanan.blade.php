@extends('layouts.app')

@section('title', 'Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR ======================== --}}
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
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar text-start">
        
        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <div class="flex items-center gap-4 text-start">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-orange-50 shadow-sm text-start">
                    <i class="fa-solid fa-store text-xl text-[#FF6900]"></i>
                </div>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Warung Bu Ani</h2>
                    <p class="text-sm text-gray-400 font-medium tracking-wide">Dasbor Kantin</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-start text-start">
                <span id="statusLabel" class="text-xs font-black tracking-widest text-[#22c55e]">MENERIMA PESANAN</span>
                <button id="toggleBtn" onclick="toggleStatus()" class="relative inline-flex items-center w-14 h-7 rounded-full bg-[#22c55e] transition-all shadow-inner text-start">
                    <span id="toggleCircle" class="absolute w-6 h-6 bg-white rounded-full left-[30px] transition-all shadow-md"></span>
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-3 px-10 mt-8 mb-2 text-start">
            <button id="tabMasukBtn" onclick="switchTab('masuk')" class="px-6 py-2.5 rounded-2xl text-[15px] font-bold border-2 border-[#FF6900] text-[#FF6900] bg-white transition-all">
                Pesanan Masuk <span id="badgeMasuk" class="ml-2 px-2.5 py-0.5 bg-[#FF6900] text-white rounded-full text-[10px] shadow-sm">3</span>
            </button>
            <button id="tabDiprosesBtn" onclick="switchTab('diproses')" class="px-6 py-2.5 rounded-2xl text-[15px] font-bold text-gray-400 bg-gray-100 transition-all border-2 border-transparent">
                Diproses <span id="badgeDiproses" class="ml-2 px-2.5 py-0.5 bg-gray-300 text-white rounded-full text-[10px] shadow-sm">3</span>
            </button>
        </div>

        {{-- ===== TAB: PESANAN MASUK ===== --}}
        <div id="tabMasuk" class="flex-1 px-10 py-6 text-start">
            <div id="gridMasuk" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-start">

                {{-- Card Alex --}}
                <div id="order-alex" onclick="window.location.href='/admin/pesanan/rincian?name=Alex Johnson&order=%23ORD-089&items=2x Nasi Goreng Spesial,1x Brown Sugar Boba&ongkir=Rp 5.000'" class="cursor-pointer group bg-white rounded-[32px] p-7 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md text-start">
                    <div class="flex justify-between mb-6 text-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div class="text-start">
                                <p class="text-base font-black text-gray-800">Alex Johnson</p>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tight">🕒 12:08 WIB</p>
                            </div>
                        </div>
                        <div class="text-right text-start"><p class="text-[10px] font-black text-gray-300 mb-1">#ORD-089</p><span class="text-[10px] font-black bg-blue-50 text-blue-500 px-2 py-1 rounded-lg uppercase tracking-tight">Antar Kurir</span></div>
                    </div>
                    <div class="flex-1 text-[13px] text-gray-700 py-5 border-y border-gray-50 space-y-3 text-start">
                        <p><b>2x</b> Nasi Goreng Spesial</p>
                        <p><b>1x</b> Brown Sugar Boba</p>
                    </div>
                    <div class="flex justify-between items-center my-6">
                        <span class="text-[11px] text-gray-400 font-black uppercase tracking-widest">Total</span>
                        <span class="text-xl font-black text-[#FF6900]">Rp 73.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto text-start">
                        <button onclick="event.stopPropagation(); deleteCard(this);" class="flex-1 py-4 rounded-2xl border-2 border-red-50 text-red-500 text-xs font-black hover:bg-red-50 transition-all">✕ Tolak</button>
                        <button onclick="event.stopPropagation(); terimaOrder('alex', 'Alex Johnson', '#ORD-089', '2', 'DIMASAK', '12:08 WIB')" class="flex-1 py-4 rounded-2xl bg-[#22c55e] text-white text-xs font-black shadow-lg shadow-green-100 hover:brightness-105 transition-all">✓ Terima</button>
                    </div>
                </div>

                {{-- Card Sarah --}}
                <div id="order-sarah" onclick="window.location.href='/admin/pesanan/rincian?name=Sarah Smith&order=%23ORD-090&items=1x Mie Goreng Ayam,1x Es Teh Manis&ongkir=Rp 5.000'" class="cursor-pointer group bg-white rounded-[32px] p-7 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md text-start">
                    <div class="flex justify-between mb-6 text-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div class="text-start">
                                <p class="text-base font-black text-gray-800">Sarah Smith</p>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tight">🕒 12:05 WIB</p>
                            </div>
                        </div>
                        <div class="text-right text-start"><p class="text-[10px] font-black text-gray-300 mb-1">#ORD-090</p><span class="text-[10px] font-black bg-blue-50 text-blue-500 px-2 py-1 rounded-lg uppercase tracking-tight">Antar Kurir</span></div>
                    </div>
                    <div class="flex-1 text-[13px] text-gray-700 py-5 border-y border-gray-50 space-y-3 text-start">
                        <p><b>1x</b> Mie Goreng Ayam</p>
                        <p><b>1x</b> Es Teh Manis</p>
                    </div>
                    <div class="flex justify-between items-center my-6 text-start">
                        <span class="text-[11px] text-gray-400 font-black uppercase tracking-widest">Total</span>
                        <span class="text-xl font-black text-[#FF6900]">Rp 32.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto text-start">
                        <button onclick="event.stopPropagation(); deleteCard(this);" class="flex-1 py-4 rounded-2xl border-2 border-red-50 text-red-500 text-xs font-black hover:bg-red-50 transition-all">✕ Tolak</button>
                        <button onclick="event.stopPropagation(); terimaOrder('sarah', 'Sarah Smith', '#ORD-090', '2', 'DIMASAK', '12:05 WIB')" class="flex-1 py-4 rounded-2xl bg-[#22c55e] text-white text-xs font-black shadow-lg shadow-green-100 hover:brightness-105 transition-all">✓ Terima</button>
                    </div>
                </div>

                {{-- Card Budi --}}
                <div id="order-budi" onclick="window.location.href='/admin/pesanan/rincian?name=Budi Santoso&order=%23ORD-091&items=2x Mie Goreng Ayam,2x Brown Sugar Boba&ongkir=Rp 0'" class="cursor-pointer group bg-white rounded-[32px] p-7 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md transition-all text-start">
                    <div class="flex justify-between mb-6 text-start">
                        <div class="flex items-center gap-3 text-start">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl text-start"></i></div>
                            <div class="text-start">
                                <p class="text-base font-black text-gray-800 group-hover:text-[#FF6900]">Budi Santoso</p>
                                <p class="text-[10px] text-gray-400 font-black uppercase mt-0.5 tracking-tight">🕒 11:58 WIB</p>
                            </div>
                        </div>
                        <div class="text-right text-start text-start"><p class="text-[10px] font-black text-gray-300 mb-1">#ORD-091</p><span class="text-[10px] font-black bg-purple-50 text-purple-500 px-2 py-1 rounded-lg uppercase tracking-tight">Ambil Sendiri</span></div>
                    </div>
                    <div class="flex-1 text-[13px] text-gray-700 py-5 border-y border-gray-50 space-y-3 text-start">
                        <p><b>2x</b> Mie Goreng Ayam</p>
                        <p><b>2x</b> Brown Sugar Boba</p>
                    </div>
                    <div class="flex justify-between items-center my-6 text-start">
                        <span class="text-[11px] text-gray-400 font-black uppercase tracking-widest">Total</span>
                        <span class="text-xl font-black text-[#FF6900]">Rp 80.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto text-start">
                        <button onclick="event.stopPropagation(); deleteCard(this);" class="flex-1 py-4 rounded-2xl border-2 border-red-50 text-red-500 text-xs font-black hover:bg-red-50 transition-all">✕ Tolak</button>
                        <button onclick="event.stopPropagation(); terimaOrder('budi', 'Budi Santoso', '#ORD-091', '4', 'DIMASAK', '11:58 WIB')" class="flex-1 py-4 rounded-2xl bg-[#22c55e] text-white text-xs font-black shadow-lg shadow-green-100 hover:brightness-105 transition-all">✓ Terima</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TAB: DIPROSES ===== --}}
        <div id="tabDiproses" class="hidden flex-1 px-10 py-6 text-start">
            <div id="gridDiproses" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-start">
                
                {{-- David Lee --}}
                <div id="order-david" onclick="window.location.href='/admin/pesanan/status?name=David Lee&order=%23ORD-083&time=11:42 WIB'" class="cursor-pointer group bg-white rounded-[32px] p-7 shadow-sm border border-gray-100 flex flex-col min-h-[220px] transition-all hover:shadow-md text-start">
                    <div class="flex justify-between mb-6 text-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div class="text-start">
                                <p class="text-base font-black text-gray-800">David Lee</p>
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-tight">🕒 11:42 WIB</p>
                            </div>
                        </div>
                        <div class="text-right text-start"><p class="text-[10px] font-black text-gray-300 mb-1">#ORD-083</p><span class="text-[10px] font-black bg-blue-50 text-blue-500 px-2 py-1 rounded-lg uppercase tracking-tight">Antar Kurir</span></div>
                    </div>
                    <div class="mt-auto flex justify-between items-center pt-5 border-t border-gray-50 text-start">
                        <p class="text-[11px] text-gray-400 font-black uppercase tracking-widest text-start">1 ITEM</p>
                        <div class="flex gap-2 text-start text-start">
                            <button onclick="event.stopPropagation(); deleteCard(this)" class="px-4 py-2 rounded-xl bg-[#1A1A1A] text-white text-[10px] font-black shadow-lg hover:bg-black transition-all">SELESAIKAN</button>
                            <span class="px-4 py-2 rounded-xl text-[10px] font-black bg-green-50 text-[#00A63E] shadow-sm"><i class="fa-solid fa-check mr-1"></i> SIAP</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi simple untuk buang card tanpa pindah halaman
    function deleteCard(btn) {
        const card = btn.closest('.cursor-pointer');
        card.remove();
        updateBadges();
    }

    function switchTab(tab) {
        const tMasuk = document.getElementById('tabMasuk');
        const tDiproses = document.getElementById('tabDiproses');
        const bMasuk = document.getElementById('tabMasukBtn');
        const bDiproses = document.getElementById('tabDiprosesBtn');

        if (tab === 'masuk') {
            tMasuk.classList.remove('hidden'); tDiproses.classList.add('hidden');
            bMasuk.style.cssText = 'background-color:white; border-color:#FF6900; color:#FF6900;';
            bDiproses.style.cssText = '';
            bDiproses.className = 'px-6 py-2.5 rounded-2xl text-[15px] font-bold text-gray-400 bg-gray-100 transition-all border-2 border-transparent';
        } else {
            tDiproses.classList.remove('hidden'); tMasuk.classList.add('hidden');
            bDiproses.style.cssText = 'background-color:white; border-color:#FF6900; color:#FF6900;';
            bMasuk.style.cssText = '';
            bMasuk.className = 'px-6 py-2.5 rounded-2xl text-[15px] font-bold text-gray-400 bg-gray-100 transition-all border-2 border-transparent';
        }
    }

    function terimaOrder(id, nama, orderId, items, status, waktu) {
        const target = document.getElementById('order-' + id);
        if(target) target.remove();
        
        const html = `
            <div onclick="window.location.href='/admin/pesanan/status?name=${nama}&order=${orderId.replace('#','%23')}&time=${waktu}'" class="cursor-pointer group bg-white rounded-[32px] p-7 shadow-sm border border-gray-100 flex flex-col min-h-[220px] transition-all hover:shadow-md text-start">
                <div class="flex justify-between mb-6 text-start">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                        <div class="text-start">
                            <p class="text-base font-black text-gray-800">${nama}</p>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-tight">🕒 ${waktu}</p>
                        </div>
                    </div>
                    <div class="text-right text-start"><p class="text-[10px] font-black text-gray-300 mb-1">${orderId}</p></div>
                </div>
                <div class="mt-auto flex justify-between items-center pt-5 border-t border-gray-50 text-start">
                    <p class="text-[11px] text-gray-400 font-black uppercase tracking-widest text-start">${items} ITEM</p>
                    <div class="flex gap-2">
                        <button onclick="event.stopPropagation(); deleteCard(this)" class="px-4 py-2 rounded-xl bg-[#1A1A1A] text-white text-[10px] font-black">SELESAIKAN</button>
                        <span class="px-4 py-2 rounded-xl text-[10px] font-black bg-orange-50 text-[#F54900] shadow-sm"><i class="fa-solid fa-fire-flame-curved mr-1 text-start"></i> DIMASAK</span>
                    </div>
                </div>
            </div>`;
        document.getElementById('gridDiproses').insertAdjacentHTML('beforeend', html);
        updateBadges();
        switchTab('diproses');
    }

    function updateBadges() {
        document.getElementById('badgeMasuk').innerText = document.getElementById('gridMasuk').children.length;
        document.getElementById('badgeDiproses').innerText = document.getElementById('gridDiproses').children.length;
    }

    let isOpen = true;
    function toggleStatus() {
        isOpen = !isOpen;
        const btn = document.getElementById('toggleBtn');
        const circle = document.getElementById('toggleCircle');
        const label = document.getElementById('statusLabel');
        if (isOpen) {
            btn.style.backgroundColor = '#22c55e'; circle.style.left = '30px';
            label.textContent = 'MENERIMA PESANAN'; label.style.color = '#22c55e';
        } else {
            btn.style.backgroundColor = '#d1d5db'; circle.style.left = '4px';
            label.textContent = 'TUTUP SEMENTARA'; label.style.color = '#6b7280';
        }
    }
</script>
@endpush