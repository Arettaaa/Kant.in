@extends('layouts.app')

@section('title', 'Pesanan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR (STAY) ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT (SCROLL) ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        {{-- Header --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-orange-50 shadow-sm">
                    <i class="fa-solid fa-store text-xl" style="color: #FF6900;"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Warung Bu Ani</h2>
                    <p class="text-sm text-gray-400 font-medium tracking-wide">Dasbor Kantin</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <span id="statusLabel" class="text-xs font-black tracking-widest" style="color:#22c55e;">MENERIMA PESANAN</span>
                <button id="toggleBtn" onclick="toggleStatus()" class="relative inline-flex items-center w-14 h-7 rounded-full transition-all duration-300 shadow-inner" style="background-color:#22c55e;">
                    <span id="toggleCircle" class="absolute w-6 h-6 bg-white rounded-full shadow-md transition-all duration-300" style="left:30px;"></span>
                </button>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-3 px-10 mt-8 mb-2">
            <button id="tabMasukBtn" onclick="switchTab('masuk')" class="flex items-center gap-2 px-6 py-2.5 rounded-2xl text-[15px] font-bold border-2 transition-all" style="background-color:white; border-color:#FF6900; color:#FF6900;">
                Pesanan Masuk
                <span id="badgeMasuk" class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white shadow-sm" style="background-color:#FF6900;">3</span>
            </button>
            <button id="tabDiprosesBtn" onclick="switchTab('diproses')" class="flex items-center gap-2 px-6 py-2.5 rounded-2xl text-[15px] font-bold border-2 border-transparent text-gray-400 hover:text-gray-600 bg-gray-100 transition-all">
                Diproses
                <span id="badgeDiproses" class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold text-white bg-gray-300">2</span>
            </button>
        </div>

        {{-- ===== TAB: PESANAN MASUK ===== --}}
        <div id="tabMasuk" class="flex-1 px-10 py-6">
            <div id="orderGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Card Alex Johnson --}}
                <div onclick="window.location.href='/admin/pesanan/rincian?name=Alex Johnson&order=%23ORD-089&total=Rp 73.000&sub=Rp 68.000&ongkir=Rp 5.000&items=2x Nasi Goreng Spesial,1x Brown Sugar Boba'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div>
                                <p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">Alex Johnson</p>
                                <p class="text-[11px] text-gray-400 font-bold uppercase mt-0.5 tracking-tighter">🕒 2 menit yang lalu</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-gray-300 mb-1">#ORD-089</p>
                            <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-blue-50 text-blue-500">Antar Kurir</span>
                        </div>
                    </div>
                    <div class="flex-1 text-sm text-gray-700 space-y-2 border-t border-b border-gray-50 py-4 mb-4">
                        <p><span><b class="text-gray-900">2x</b> Nasi Goreng Spesial</span></p>
                        <p><span><b class="text-gray-900">1x</b> Brown Sugar Boba</span></p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-sm text-gray-400 font-bold">Total</span>
                        <span class="text-lg font-black" style="color:#FF6900;">Rp 73.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto">
                        <button onclick="event.stopPropagation();" class="flex-1 py-3 rounded-2xl border-2 border-red-50 text-red-500 text-sm font-bold hover:bg-red-50">✕ Tolak</button>
                        <button onclick="event.stopPropagation(); terimaOrder('card-alex', 'Alex Johnson', '#ORD-089', 'Antar Kurir', '2 menit yang lalu', 2)" class="flex-1 py-3 rounded-2xl text-white text-sm font-bold shadow-md hover:brightness-110" style="background-color:#22c55e;">✓ Terima</button>
                    </div>
                </div>

                {{-- Card Sarah Smith --}}
                <div onclick="window.location.href='/admin/pesanan/rincian?name=Sarah Smith&order=%23ORD-090&total=Rp 35.000&sub=Rp 30.000&ongkir=Rp 5.000&items=1x Mie Goreng Ayam,1x Es Teh Manis'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div>
                                <p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">Sarah Smith</p>
                                <p class="text-[11px] text-gray-400 font-bold uppercase mt-0.5 tracking-tighter">🕒 5 menit yang lalu</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-gray-300 mb-1">#ORD-090</p>
                            <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-blue-50 text-blue-500">Antar Kurir</span>
                        </div>
                    </div>
                    <div class="flex-1 text-sm text-gray-700 space-y-2 border-t border-b border-gray-50 py-4 mb-4">
                        <p><span><b class="text-gray-900">1x</b> Mie Goreng Ayam</span></p>
                        <p><span><b class="text-gray-900">1x</b> Es Teh Manis</span></p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-sm text-gray-400 font-bold">Total</span>
                        <span class="text-lg font-black" style="color:#FF6900;">Rp 35.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto">
                        <button onclick="event.stopPropagation();" class="flex-1 py-3 rounded-2xl border-2 border-red-50 text-red-500 text-sm font-bold hover:bg-red-50">✕ Tolak</button>
                        <button onclick="event.stopPropagation();" class="flex-1 py-3 rounded-2xl text-white text-sm font-bold shadow-md hover:brightness-110" style="background-color:#22c55e;">✓ Terima</button>
                    </div>
                </div>

                {{-- Card Budi Santoso --}}
                <div onclick="window.location.href='/admin/pesanan/rincian?name=Budi Santoso&order=%23ORD-091&total=Rp 75.000&sub=Rp 75.000&ongkir=Rp 0&items=3x Nasi Kuning Komplit'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[320px] hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div>
                                <p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">Budi Santoso</p>
                                <p class="text-[11px] text-gray-400 font-bold uppercase mt-0.5 tracking-tighter">🕒 12 menit yang lalu</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-gray-300 mb-1">#ORD-091</p>
                            <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-purple-50 text-purple-500">Ambil Sendiri</span>
                        </div>
                    </div>
                    <div class="flex-1 text-sm text-gray-700 space-y-2 border-t border-b border-gray-50 py-4 mb-4">
                        <p><span><b class="text-gray-900">3x</b> Nasi Kuning Komplit</span></p>
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-sm text-gray-400 font-bold">Total</span>
                        <span class="text-lg font-black" style="color:#FF6900;">Rp 75.000</span>
                    </div>
                    <div class="flex gap-3 mt-auto">
                        <button onclick="event.stopPropagation();" class="flex-1 py-3 rounded-2xl border-2 border-red-50 text-red-500 text-sm font-bold hover:bg-red-50">✕ Tolak</button>
                        <button onclick="event.stopPropagation();" class="flex-1 py-3 rounded-2xl text-white text-sm font-bold shadow-md hover:brightness-110" style="background-color:#22c55e;">✓ Terima</button>
                    </div>
                </div>

            </div>
        </div>

        {{-- ===== TAB: DIPROSES ===== --}}
        <div id="tabDiproses" class="hidden flex-1 px-10 py-6">
            <div id="diprosesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Rina Putri --}}
                <div onclick="window.location.href='/admin/pesanan/status?name=Rina Putri&order=%23ORD-085&time=10:42 WIB'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[220px] hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div>
                                <p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">Rina Putri</p>
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">🕒 10:42 WIB</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-gray-300 mb-1">#ORD-085</p>
                            <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-purple-50 text-purple-500">Ambil Sendiri</span>
                        </div>
                    </div>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                        <p class="text-sm text-gray-400 font-bold">2 Item</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black shadow-sm" style="background-color:#FFF7ED; color:#F54900;">
                            <i class="fa-solid fa-fire-flame-curved"></i> DIMASAK
                        </span>
                    </div>
                </div>

                {{-- David Lee --}}
                <div onclick="window.location.href='/admin/pesanan/status?name=David Lee&order=%23ORD-083&time=10:30 WIB'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[220px] hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                            <div>
                                <p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">David Lee</p>
                                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">🕒 10:30 WIB</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-gray-300 mb-1">#ORD-083</p>
                            <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-blue-50 text-blue-500">Antar Kurir</span>
                        </div>
                    </div>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                        <p class="text-sm text-gray-400 font-bold">1 Item</p>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black shadow-sm" style="background-color:#F0FDF4; color:#00A63E;">
                            <i class="fa-solid fa-check"></i> SIAP
                        </span>
                    </div>
                </div>

                <div id="acceptedOrders" class="contents"></div>
            </div>
        </div>

    </main>
</div>

@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        const tMasuk = document.getElementById('tabMasuk');
        const tDiproses = document.getElementById('tabDiproses');
        const bMasuk = document.getElementById('tabMasukBtn');
        const bDiproses = document.getElementById('tabDiprosesBtn');

        if (tab === 'masuk') {
            tMasuk.classList.remove('hidden'); tDiproses.classList.add('hidden');
            bMasuk.style.cssText = 'background-color:white; border-color:#FF6900; color:#FF6900;';
            bDiproses.style.cssText = '';
            bDiproses.className = 'flex items-center gap-2 px-6 py-2.5 rounded-2xl text-[15px] font-bold border-2 border-transparent text-gray-400 hover:text-gray-600 bg-gray-100 transition-all';
        } else {
            tDiproses.classList.remove('hidden'); tMasuk.classList.add('hidden');
            bDiproses.style.cssText = 'background-color:white; border-color:#FF6900; color:#FF6900;';
            bMasuk.style.cssText = '';
            bMasuk.className = 'flex items-center gap-2 px-6 py-2.5 rounded-2xl text-[15px] font-bold border-2 border-transparent text-gray-400 hover:text-gray-600 bg-gray-100 transition-all';
        }
    }

    function terimaOrder(cardId, nama, orderId, tipe, waktu, itemCount) {
        // Logika hapus card & update badge (simulasi)
        const badgeM = document.getElementById('badgeMasuk');
        const badgeD = document.getElementById('badgeDiproses');
        badgeM.textContent = Math.max(0, parseInt(badgeM.textContent) - 1);
        badgeD.textContent = parseInt(badgeD.textContent) + 1;

        const target = document.getElementById('acceptedOrders');
        const linkUrl = `/admin/pesanan/status?name=${encodeURIComponent(nama)}&order=${encodeURIComponent(orderId)}&time=${encodeURIComponent('Baru Saja')}`;

        const html = `
            <div onclick="window.location.href='${linkUrl}'" class="cursor-pointer group bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col min-h-[220px] hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-300"><i class="fa-solid fa-user text-xl"></i></div>
                        <div><p class="text-base font-bold text-gray-800 group-hover:text-[#FF6900]">${nama}</p><p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">🕒 Baru Saja</p></div>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-black text-gray-300 mb-1">${orderId}</p>
                        <span class="text-[11px] font-black uppercase px-2 py-1 rounded-lg bg-purple-50 text-purple-500">${tipe}</span>
                    </div>
                </div>
                <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-50">
                    <p class="text-sm text-gray-400 font-bold">${itemCount} Item</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black shadow-sm" style="background-color:#FFF7ED; color:#F54900;"><i class="fa-solid fa-fire-flame-curved"></i> DIMASAK</span>
                </div>
            </div>`;
        target.insertAdjacentHTML('beforeend', html);
        switchTab('diproses');
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