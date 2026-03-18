@extends('layouts.app')

@section('title', 'Profil Kantin - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Hilangkan scrollbar tapi tetap bisa scroll */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
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
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
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
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start relative">
        
        {{-- REVISI: Header Oren (Fixed/Stay di atas dengan tinggi lebih pendek) --}}
        <div class="absolute top-0 left-0 w-full h-[220px] bg-[#FF6900] rounded-b-[48px] z-0 flex flex-col items-center pt-12">
            <h1 class="text-2xl font-black text-white">Profil & Pengaturan</h1>
        </div>

        {{-- Scrollable Container --}}
        <div class="relative z-10 w-full h-full overflow-y-auto hide-scrollbar px-10 pt-36 pb-20 flex flex-col items-center">
            
            {{-- Card Profil Utama --}}
            <div class="w-full max-w-4xl bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 mb-10 text-center">
                <div class="relative w-28 h-28 mx-auto mb-4">
                    <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=400" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md" alt="Profil Kantin">
                    <div class="absolute bottom-1 right-1 w-7 h-7 bg-green-500 border-4 border-white rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-check text-[10px] text-white"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-1">Warung Bu Ani</h2>
                <div class="flex items-center justify-center gap-2 mb-8">
                    <span class="px-4 py-1.5 bg-gray-50 rounded-full text-[11px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">
                        <i class="fa-solid fa-utensils mr-1"></i> Stand Makanan
                    </span>
                </div>

                {{-- Info Pemilik --}}
                <div class="flex items-center gap-4 p-4 rounded-3xl bg-gray-50/50 border border-gray-50 w-fit mx-auto">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400" class="w-12 h-12 rounded-full object-cover" alt="Pemilik">
                    <div class="text-left">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-none mb-1">Pemilik</p>
                        <p class="text-[15px] font-bold text-gray-800">Ani Suryani</p>
                    </div>
                </div>
            </div>

            {{-- List Menu Pengaturan --}}
            <div class="w-full max-w-4xl space-y-8">
                
                {{-- Manajemen Toko --}}
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 ml-2">Manajemen Toko</h3>
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">
                        {{-- Edit Info --}}
                        <a href="/admin/profil/edit" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all border-b border-gray-100">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                                    <i class="fa-solid fa-store text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Edit Info Kantin</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Nama, alamat, kontak</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>

                        {{-- Jam Operasional --}}
                        <a href="/admin/profil/jam-operasional" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                                    <i class="fa-solid fa-clock text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Jam Operasional</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Atur jadwal buka/tutup</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>
                    </div>
                </div>

                {{-- Tentang --}}
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 ml-2">Tentang</h3>
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">
                        <a href="/admin/pusat-bantuan" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
                                    <i class="fa-solid fa-circle-info text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Pusat Bantuan</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Hubungi admin Kant.in</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>
</div>

@endsection