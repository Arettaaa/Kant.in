@php
    $user = Session::get('user');
    $userName = $user['name'] ?? 'Admin Global';
    
    // Ambil jumlah pending dari session atau hitung
    $pendingCount = Session::get('pending_count', 0);
    
    $currentRoute = request()->route()->getName();
@endphp

<aside class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
    <div class="flex items-center gap-3 mb-12 px-2">
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
            <i class="fa-solid fa-utensils text-lg text-white"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
            <span class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mt-1">Global Admin</span>
        </div>
    </div>

    <nav class="flex flex-col gap-2 flex-1">
        <a href="{{ route('admin.global.dasbor') }}"
            class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all {{ $currentRoute === 'admin.global.dasbor' ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Dasbor
        </a>

        <a href="{{ route('admin.global.kantin') }}"
            class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all {{ $currentRoute === 'admin.global.kantin' ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Kantin Mitra
        </a>

        <a href="{{ route('admin.global.transaksi') }}"
            class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all {{ $currentRoute === 'admin.global.transaksi' ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Transaksi
        </a>

        <a href="{{ route('admin.global.notifikasi') }}"
            class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all {{ str_starts_with($currentRoute, 'admin.global.notifikasi') || $currentRoute === 'admin.global.rev-pendaftaran' ? 'active' : '' }}">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifikasi
            </div>
            @if($pendingCount > 0)
            <span class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black text-white shadow-sm" style="background-color:#FF6900;">
                {{ $pendingCount }}
            </span>
            @endif
        </a>

        <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Sistem</div>

        <a href="{{ route('admin.global.pengaturan') }}"
            class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all {{ $currentRoute === 'admin.global.pengaturan' || $currentRoute === 'admin.global.keamanan' ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Pengaturan
        </a>
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="mt-auto">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 text-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </button>
    </form>
</aside>