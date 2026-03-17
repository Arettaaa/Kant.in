@extends('layouts.app')

@section('title', 'Profil - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .menu-item {
        transition: all 0.18s ease;
        cursor: pointer;
    }
    .menu-item:hover {
        background-color: #F9FAFB;
    }
    .menu-item:hover .menu-arrow {
        transform: translateX(3px);
        color: #FF6900;
    }
    .menu-arrow {
        transition: all 0.18s ease;
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        {{-- Nav --}}
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>

            <a href="/jelajah"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajah
            </a>

            <a href="/pesanan"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>

            <a href="/profil"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
               style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Profil
            </a>
        </nav>

        {{-- Logout --}}
        <a href="/login"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        {{-- HERO BANNER --}}
        <div class="w-full flex items-center justify-center py-10"
             style="background: linear-gradient(135deg, #FF6900 0%, #ea580c 100%);">
            <h1 class="text-2xl font-extrabold text-white tracking-wide">Profil & Pengaturan</h1>
        </div>

        {{-- CONTENT --}}
        <div class="px-10 py-8 flex flex-col gap-6 max-w-3xl">

            {{-- Manajemen Profil --}}
            <section>
                <p class="text-sm font-extrabold text-gray-700 mb-3">Manajemen Profil</p>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Data Diri --}}
                    <a href="/profil/data-diri" class="menu-item flex items-center gap-4 px-5 py-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background-color:#EFF6FF;">
                            <i class="fa-solid fa-address-card text-base" style="color:#3b82f6;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800">Data Diri</p>
                            <p class="text-xs text-gray-400 mt-0.5">Nama, alamat, kontak</p>
                        </div>
                        <svg class="menu-arrow w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    {{-- Keamanan Akun --}}
                    <a href="/profil/keamanan" class="menu-item flex items-center gap-4 px-5 py-4">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background-color:#F0FDF4;">
                            <i class="fa-solid fa-shield-halved text-base" style="color:#22c55e;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800">Keamanan Akun</p>
                            <p class="text-xs text-gray-400 mt-0.5">Lihat penjualan & pendapatan</p>
                        </div>
                        <svg class="menu-arrow w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                </div>
            </section>

        </div>
    </main>
</div>

@endsection