<aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
            <i class="fa-solid fa-store text-lg text-white"></i>
        </div>
        <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
    </div>

    <nav class="flex flex-col gap-2 flex-1">
        <a href="{{ route('admin.pesanan') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
                {{ request()->routeIs('admin.pesanan*') ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ request()->routeIs('admin.pesanan*') ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Pesanan
        </a>
        <a href="{{ route('admin.menu') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
                {{ request()->routeIs('admin.kelola-menu*') ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ request()->routeIs('admin.kelola-menu*') ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            Kelola Menu
        </a>
        <a href="{{ route('admin.riwayat') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
                {{ request()->routeIs('admin.riwayat*') ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ request()->routeIs('admin.riwayat*') ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            Riwayat Transaksi
        </a>
        <a href="{{ route('admin.profil') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all
                {{ request()->routeIs('admin.profil*') || request()->routeIs('admin.support') ? 'text-[#FF6900]' : 'text-gray-400 hover:bg-gray-50' }}"
            style="{{ request()->routeIs('admin.profil*') || request()->routeIs('admin.support') ? 'background-color:#FFF3E8;' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Profil Kantin
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-auto border-t border-gray-100 pt-6">
        @csrf
        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </button>
    </form>
</aside>