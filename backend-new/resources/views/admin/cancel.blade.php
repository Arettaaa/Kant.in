@extends('layouts.app')

@section('title', 'Pesanan Dibatalkan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR (SERAGAM DENGAN PESANAN) --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg> Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg> Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil Kantin
            </a>
        </nav>
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 mt-auto text-start border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg> Keluar
        </a>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] items-center text-start hide-scrollbar">
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-30 text-start">
            <a href="/admin/riwayat" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
            </a>
            <div class="text-start">
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1 text-start">Status Pesanan</h2>
                <p id="detOrderId" class="text-sm text-red-500 font-bold tracking-wide text-start">#ORD-000</p>
            </div>
        </div>

        <div class="w-full max-w-xl px-6 py-12 flex flex-col items-center text-start">
            <div class="w-full bg-red-50 border border-red-100 rounded-[40px] p-10 flex flex-col items-center justify-center mb-8 shadow-sm text-start">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-4 shadow-lg shadow-red-200">
                    <i class="fa-solid fa-xmark text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-red-600 mb-2 tracking-tight text-center">Pesanan Dibatalkan</h3>
                <p class="text-sm text-red-400 font-medium text-center leading-relaxed italic px-4">
                    Pesanan ini telah dibatalkan oleh sistem/admin dan tidak dapat diproses lebih lanjut.
                </p>
            </div>

            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 w-full text-start">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-50">
                    <div class="flex items-center gap-4">
                        <div id="custAvatar" class="w-14 h-14 rounded-full overflow-hidden border border-gray-100 flex items-center justify-center bg-orange-100 text-orange-500 font-black text-xl"></div>
                        <div class="text-start">
                            <p id="detCustName" class="text-lg font-black text-gray-800 leading-tight">Memuat...</p>
                            <span class="text-[10px] font-black uppercase text-purple-500 bg-purple-50 px-2 py-0.5 rounded-md">
                                <i class="fa-solid fa-clock mr-1"></i> DIBATALKAN
                            </span>
                        </div>
                    </div>
                    <p id="detWaktu" class="text-sm text-gray-400 font-bold tracking-tighter">🕒 --:-- WIB</p>
                </div>

                <div class="space-y-6 text-start">
                    <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em]">Daftar Menu Saat Pembatalan</p>
                    <div id="cancelItemList" class="space-y-4"></div>
                </div>
            </div>

            <a href="/admin/riwayat" class="mt-8 text-sm font-bold text-gray-400 hover:text-[#FF6900] transition-all flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Transaksi
            </a>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    window.onload = function() {
        const params = new URLSearchParams(window.location.search);
        const name = params.get('name') || "Pelanggan";
        const orderId = params.get('order') || "#ORD-000";
        const timeFromUrl = params.get('time'); // AMBIL JAM DARI URL

        document.getElementById('detCustName').innerText = name;
        document.getElementById('detOrderId').innerText = orderId;
        document.getElementById('custAvatar').innerText = name.charAt(0).toUpperCase();

        // REVISI LOGIKA WAKTU: Pakai jam dari URL agar tidak ganti-ganti
        const now = new Date();
        const displayTime = timeFromUrl ? timeFromUrl : now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' }) + " WIB";
        document.getElementById('detWaktu').innerText = `🕒 ${displayTime}`;

        const container = document.getElementById('cancelItemList');
        let menuName = name.includes('Megawati') ? "Mie Goreng Ayam" : "Mie Goreng Seafood";
        let catatan = name.includes('Megawati') ? "Catatan: Tidak pedas" : "Dibatalkan oleh pelanggan";

        container.innerHTML = `
            <div class="flex items-start gap-4 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-sm font-black text-gray-400 border border-gray-100 flex-shrink-0">1</div>
                <div class="flex-1">
                    <p class="text-[16px] font-black text-gray-800">${menuName}</p>
                    <p class="text-xs text-red-500 font-bold mt-2 bg-red-50 inline-block px-3 py-1.5 rounded-xl italic">${catatan}</p>
                </div>
            </div>`;
    }
</script>
@endpush