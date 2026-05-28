@extends('layouts.app')

@section('title', 'Profil Kantin - Kant.in')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
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

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start relative">

            {{-- Header Oren --}}
            <div
                class="absolute top-0 left-0 w-full h-[220px] bg-[#FF6900] rounded-b-[48px] z-0 flex flex-col items-center pt-12">
                <h1 class="text-2xl font-black text-white">Profil & Pengaturan</h1>
            </div>

            {{-- Scrollable Container --}}
            <div
                class="relative z-10 w-full h-full overflow-y-auto hide-scrollbar px-10 pt-36 pb-20 flex flex-col items-center">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div id="flashMessage"
                        class="w-full max-w-4xl mb-6 px-5 py-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl font-semibold text-sm text-center transition-all">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Card Profil Utama --}}
                <div
                    class="w-full max-w-4xl bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 mb-10 text-center">
                    <div class="relative w-28 h-28 mx-auto mb-4">
                        {{-- Foto Kantin (Dari Database atau Inisial) --}}
                        @php
                            $canteenImage = $canteen->image
                                ? asset('storage/' . $canteen->image)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($canteen->name ?? 'Kantin') . '&color=FF6900&background=FFF3E8&size=200';
                        @endphp
                        <img src="{{ $canteenImage }}"
                            class="w-full h-full rounded-full object-cover border-4 border-white shadow-md"
                            alt="Logo Kantin">

                        <div
                            class="absolute bottom-1 right-1 w-7 h-7 bg-green-500 border-4 border-white rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px] text-white"></i>
                        </div>
                    </div>

                    {{-- Nama Kantin --}}
                    <h2 class="text-2xl font-black text-gray-900 mb-1">{{ $canteen->name ?? 'Kantin Belum Dinamai' }}</h2>
                    <div class="flex items-center justify-center gap-2 mb-8">
                        <span
                            class="px-4 py-1.5 bg-orange-50 rounded-full text-[11px] font-black text-[#FF6900] uppercase tracking-widest border border-orange-100 flex items-center gap-1.5">
                            <i class="fa-solid fa-star text-yellow-500"></i>
                            {{ $averageRating }} 
                        </span>
                    </div>

                    {{-- Info Pemilik (User Admin) --}}
                    <div class="flex items-center gap-4 p-4 rounded-3xl bg-gray-50/50 border border-gray-50 w-fit mx-auto">
                        @php
                            $userImage = $user->photo_profile
                                ? asset('storage/' . $user->photo_profile)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'Admin') . '&background=random&size=100';
                        @endphp
                        <img src="{{ $userImage }}"
                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm" alt="Foto Pemilik">
                        <div class="text-left">
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest leading-none mb-1">
                                Pengelola</p>
                            <p class="text-[15px] font-bold text-gray-800">{{ $user->name ?? 'Nama Pengelola' }}</p>
                        </div>
                    </div>
                </div>

                {{-- List Menu Pengaturan --}}
                <div class="w-full max-w-4xl space-y-8">

                    {{-- Manajemen Toko --}}
                    <div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 ml-2">Manajemen Toko</h3>
                        <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">

                            {{-- MENGARAH KE RUTE EDIT --}}
                            <a href="{{ route('admin.profil.edit') }}"
                                class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all border-b border-gray-100">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                                        <i class="fa-solid fa-store text-lg"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[15px] font-black text-gray-800">Edit Info Kantin & Pengelola</p>
                                        <p class="text-[12px] text-gray-400 font-medium">Perbarui identitas, alamat, dan
                                            kata sandi</p>
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
                            <a href="/admin/pusat-bantuan"
                                class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all">
                                <div class="flex items-center gap-5">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-500">
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

    @push('scripts')
        <script>
            setTimeout(() => {
                const msg = document.getElementById('flashMessage');
                if (msg) msg.style.display = 'none';
            }, 4000);
        </script>
    @endpush
@endsection