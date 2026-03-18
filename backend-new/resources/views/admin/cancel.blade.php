@extends('layouts.app')

@section('title', 'Pesanan Dibatalkan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR (Sama dengan Pesanan) --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 mt-auto text-start border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] items-center text-start">
        {{-- Header --}}
        <div class="w-full flex items-center gap-4 px-10 py-6 bg-white border-b border-gray-100 sticky top-0 z-10 text-start">
            <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
            </a>
            <div class="text-start">
                <h2 class="text-lg font-extrabold text-gray-900 leading-none mb-1 text-start">Status Pesanan</h2>
                <p id="detOrderId" class="text-sm text-red-500 font-bold tracking-wide text-start">#ORD-081</p>
            </div>
        </div>

        <div class="w-full max-w-xl px-6 py-12 flex flex-col items-center text-start">
            {{-- Status Card --}}
            <div class="w-full bg-red-50 border border-red-100 rounded-[40px] p-10 flex flex-col items-center justify-center mb-8 shadow-sm text-start">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mb-4 shadow-lg shadow-red-200 text-start">
                    <i class="fa-solid fa-xmark text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-black text-red-600 mb-2 text-start tracking-tight text-center">Pesanan Dibatalkan</h3>
                <p class="text-sm text-red-400 font-medium text-center leading-relaxed text-start italic px-4 text-center">
                    Pesanan ini telah dibatalkan dan tidak dapat diproses lebih lanjut.
                </p>
            </div>

            {{-- Info Card --}}
            <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 w-full text-start">
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-50 text-start text-start">
                    <div class="flex items-center gap-4 text-start">
                        <div class="w-14 h-14 rounded-full overflow-hidden border border-gray-100 text-start">
                            <img src="https://ui-avatars.com/api/?name=Siti&background=FF6900&color=fff" alt="User" class="text-start">
                        </div>
                        <div class="text-start">
                            <p class="text-lg font-black text-gray-800 text-start leading-tight">Siti Aminah</p>
                            <span class="text-[10px] font-black uppercase text-purple-500 bg-purple-50 px-2 py-0.5 rounded-md text-start">
                                <i class="fa-solid fa-bag-shopping mr-1 text-start"></i> Pick Up
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 font-bold tracking-tighter text-start">🕒 09:15 AM</p>
                </div>

                <div class="space-y-6 text-start">
                    <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] text-start">Detail Menu & Catatan</p>
                    <div class="flex items-start gap-4 text-start text-start">
                        <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-sm font-black text-gray-400 border border-gray-100 flex-shrink-0 text-start text-start">1</div>
                        <div class="flex-1 text-start">
                            <p class="text-[16px] font-black text-gray-800 text-start">Mie Goreng Seafood</p>
                            <p class="text-xs text-red-500 font-bold mt-2 bg-red-50 inline-block px-3 py-1.5 rounded-xl italic text-start">
                                Catatan: Tidak pedas
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="/admin/pesanan" class="mt-8 text-sm font-bold text-gray-400 hover:text-gray-600 transition-all flex items-center gap-2 text-start">
                <i class="fa-solid fa-arrow-left text-start"></i> Kembali ke Daftar Pesanan
            </a>
        </div>
    </main>
</div>
@endsection