@extends('layouts.app')

@section('title', 'Pengaturan - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    /* Style Card Pengaturan: Panjang ke kanan & Sedang */
    .setting-card {
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
        background: white;
        border-radius: 32px;
        width: 100%; /* Memanjang ke kanan */
        padding: 24px 32px; /* Sedeng aja ukurannya */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .setting-card:hover {
        transform: translateY(-2px);
        border-color: #FF6900;
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.04);
    }

    .notif-dropdown {
        position: absolute;
        top: 60px;
        width: 340px;
        background: white;
        border-radius: 28px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        z-index: 100;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start font-sans">

    {{-- ======================== SIDEBAR GLOBAL ADMIN ======================== --}}
    @include('admin_global.partials.sidebar')
    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">

        @include('admin_global.partials.topbar')

        {{-- AREA PENGATURAN (FULL WIDTH CARDS) --}}
        <div class="p-10 flex flex-col flex-1 text-start">
            <div class="mb-10 text-start">
                <h3 class="text-3xl font-black text-gray-900 tracking-tight mb-2 text-start">Pengaturan</h3>
                <p class="text-gray-400 font-bold tracking-wide text-start">Kelola informasi akun dan pengaturan keamanan Anda</p>
            </div>

            {{-- MENU CARDS: Panjang ke Kanan & Ukuran Sedang --}}
            <div class="flex flex-col gap-5 w-full text-start">
                
                {{-- Ke Pengaturan Akun --}}
                <a href="{{ route('admin.global.profil') }}" class="setting-card group text-start">
                    <div class="flex items-center gap-6 text-start">
                        <div class="w-14 h-14 bg-[#EEF2FF] text-[#4F46E5] rounded-2xl flex items-center justify-center text-xl group-hover:bg-[#4F46E5] group-hover:text-white transition-all">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="text-lg font-black text-gray-900 mb-0.5 text-start">Pengaturan Akun</h4>
                            <p class="text-sm text-gray-400 font-bold text-start">Ubah nama, alamat email, dan nomor telepon</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-300 text-sm group-hover:text-[#FF6900] transition-all mr-2"></i>
                </a>

                {{-- Ke Pengaturan Kata Sandi --}}
                <a href="{{ route('admin.global.keamanan') }}" class="setting-card group text-start">
                    <div class="flex items-center gap-6 text-start">
                        <div class="w-14 h-14 bg-[#FFF7ED] text-[#FF6900] rounded-2xl flex items-center justify-center text-xl group-hover:bg-[#FF6900] group-hover:text-white transition-all">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div class="text-start">
                            <h4 class="text-lg font-black text-gray-900 mb-0.5 text-start">Pengaturan Kata Sandi</h4>
                            <p class="text-sm text-gray-400 font-bold text-start">Ganti kata sandi untuk menjaga keamanan akun</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-gray-300 text-sm group-hover:text-[#FF6900] transition-all mr-2"></i>
                </a>

            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('realtimeDate');
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);
    });

    function toggleDropdown() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    window.onclick = function(e) {
        if (!document.getElementById('bellWrapper').contains(e.target)) {
            document.getElementById('notifDropdown').classList.add('hidden');
        }
    }
</script>
@endpush
@endsection