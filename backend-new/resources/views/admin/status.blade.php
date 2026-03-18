@extends('layouts.app')

@section('title', 'Perbarui Status - Kant.in')

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

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto relative items-center">
        
        {{-- Header --}}
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-10">
            <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div>
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1 text-start">Perbarui Status</h2>
                <p id="displayOrderId" class="text-sm text-orange-500 font-bold tracking-wide text-start">#ORD-000</p>
            </div>
        </div>

        <div class="w-full max-w-2xl px-6 py-8 space-y-8 pb-32 flex flex-col items-center">
            
            {{-- Status Selection --}}
            <div class="w-full">
                <p class="text-sm font-black text-gray-900 mb-5 text-start uppercase tracking-widest">Status Saat Ini</p>
                <div class="grid grid-cols-2 gap-8 w-full">
                    <button id="btnDimasak" onclick="setStatus('dimasak')" class="flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 shadow-lg shadow-orange-200/50 bg-[#FF6900] border-[#FF6900] text-white">
                        <i id="iconDimasak" class="fa-solid fa-fire-flame-curved text-4xl"></i>
                        <span class="text-lg font-bold">Dimasak</span>
                    </button>
                    <button id="btnSiap" onclick="setStatus('siap')" class="flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 bg-white border-gray-100 text-gray-300 hover:border-green-200 hover:text-green-500">
                        <i id="iconSiap" class="fa-solid fa-circle-check text-4xl"></i>
                        <span class="text-lg font-bold">Siap</span>
                    </button>
                </div>
                <p id="helperText" class="text-xs text-gray-400 mt-6 text-center italic">Pelanggan melihat: Sedang menyiapkan makananmu...</p>
            </div>

            {{-- Detail Pesanan Card --}}
            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 w-full text-start">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100 text-gray-200">
                            <i class="fa-solid fa-user text-2xl"></i>
                        </div>
                        <div class="text-start">
                            <p id="displayCustomerName" class="text-lg font-black text-gray-800 leading-tight">Memuat...</p>
                            <span class="text-xs font-black uppercase text-purple-500 bg-purple-50 px-2 py-0.5 rounded-lg tracking-tighter">
                                <i class="fa-solid fa-bag-shopping mr-1"></i> AMBIL SENDIRI
                            </span>
                        </div>
                    </div>
                    <p id="displayTime" class="text-sm text-gray-400 font-bold tracking-tighter">🕒 --:-- WIB</p>
                </div>

                <div class="space-y-6">
                    <p class="text-xs font-black text-gray-300 uppercase tracking-widest text-start">Daftar Pesanan</p>
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-sm font-black text-gray-400 border border-gray-100 flex-shrink-0">1</div>
                        <div class="flex-1 text-start">
                            <p class="text-[15px] font-black text-gray-800">Ayam Geprek Sambal Matah</p>
                            <p class="text-xs text-red-500 font-bold mt-1 bg-red-50 inline-block px-2 py-0.5 rounded">Catatan: Pedas mampus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Button Fixed --}}
        <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center">
            <button id="btnComplete" disabled onclick="window.location.href='/admin/pesanan'" 
                class="w-full max-w-lg py-4 bg-[#1A1A1A] text-white rounded-2xl font-black text-sm shadow-xl flex items-center justify-center gap-3 transition-all duration-300 opacity-30 cursor-not-allowed">
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
    }

    function setStatus(status) {
        const btnDimasak = document.getElementById('btnDimasak');
        const btnSiap = document.getElementById('btnSiap');
        const btnComplete = document.getElementById('btnComplete');
        const helperText = document.getElementById('helperText');

        if (status === 'dimasak') {
            btnDimasak.className = "flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 shadow-lg shadow-orange-200/50 bg-[#FF6900] border-[#FF6900] text-white";
            btnSiap.className = "flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 bg-white border-gray-100 text-gray-300";
            btnComplete.disabled = true;
            btnComplete.classList.add('opacity-30', 'cursor-not-allowed');
            helperText.textContent = "Pelanggan melihat: Sedang menyiapkan makananmu...";
        } else {
            btnDimasak.className = "flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 bg-white border-gray-100 text-gray-300";
            btnSiap.className = "flex flex-col items-center justify-center gap-3 py-10 rounded-[32px] border-2 transition-all duration-300 shadow-lg shadow-green-200/50 bg-[#22C55E] border-[#22C55E] text-white";
            btnComplete.disabled = false;
            btnComplete.classList.remove('opacity-30', 'cursor-not-allowed');
            helperText.textContent = "Pelanggan melihat: Makananmu sudah siap!";
        }
    }
</script>
@endpush
@endsection