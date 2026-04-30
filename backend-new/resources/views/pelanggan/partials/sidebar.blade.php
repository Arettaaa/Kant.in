@php
    $currentPath = request()->path();
@endphp

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
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
            {{ $currentPath === 'beranda' ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ $currentPath === 'beranda' ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Beranda
        </a>

        <a href="/jelajah"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
            {{ $currentPath === 'jelajah' ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ $currentPath === 'jelajah' ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Jelajah
        </a>

        <a href="/pesanan"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
            {{ $currentPath === 'pesanan' ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ $currentPath === 'pesanan' ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Pesanan
        </a>

        <a href="/profil"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
            {{ str_starts_with($currentPath, 'profil') ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ str_starts_with($currentPath, 'profil') ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profil
        </a>

    </nav>

   {{-- Logout / Login Dinamis --}}
@if(Session::has('user'))
<form action="{{ route('logout') }}" method="POST" class="mt-auto">
    @csrf
    <button type="submit"
        class="flex items-center w-full gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Keluar
    </button>
</form>
@else
<a href="/login"
    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-orange-500 hover:bg-orange-50 transition-all mt-auto">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
    </svg>
    Masuk
</a>
@endif
</aside>