@extends('layouts.app')

@section('title', 'Profil - Kant.in')

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

        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>
        </nav>

        <a href="/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start relative">

        {{-- Banner Orange --}}
        <div class="absolute top-0 left-0 w-full h-[220px] bg-[#FF6900] rounded-b-[48px] z-0 flex flex-col items-center pt-12">
            <h1 class="text-2xl font-black text-white">Profil & Pengaturan</h1>
        </div>

        {{-- Scrollable Content --}}
        <div class="relative z-10 w-full h-full overflow-y-auto hide-scrollbar px-10 pt-36 pb-20 flex flex-col items-center">

            {{-- Card Profil Utama --}}
            <div class="w-full max-w-4xl bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 mb-10 text-center">

                {{-- Avatar --}}
                <div class="relative w-28 h-28 mx-auto mb-4">
                    <div class="w-full h-full rounded-full bg-orange-100 flex items-center justify-center border-4 border-white shadow-md">
                        <i class="fa-solid fa-user text-[#FF6900] text-4xl"></i>
                    </div>
                </div>

                {{-- Nama & Role --}}
                <h2 class="text-2xl font-black text-gray-900 mb-1">Yumna</h2>
                <div class="flex items-center justify-center gap-2 mb-8">
                    <span class="px-4 py-1.5 bg-gray-50 rounded-full text-[11px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">
                        <i class="fa-solid fa-graduation-cap mr-1"></i> Mahasiswa
                    </span>
                </div>

                {{-- Edit Profil --}}
                <a href="/profil/edit"
                   class="inline-block bg-[#FF6900] text-white text-sm font-bold px-6 py-2.5 rounded-2xl hover:scale-105 transition shadow">
                    Edit Profil
                </a>
            </div>

            {{-- Manajemen Profil --}}
            <div class="w-full max-w-4xl space-y-8">
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 ml-2">Manajemen Profil</h3>
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">

                        {{-- Data Diri --}}
                        <a href="/profil/data-diri" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all border-b border-gray-100">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                                    <i class="fa-solid fa-address-card text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Data Diri</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Nama, alamat, kontak, Email</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>

                        {{-- Keamanan --}}
                        <a href="/profil/keamanan" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Keamanan Akun</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Password & keamanan</p>
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