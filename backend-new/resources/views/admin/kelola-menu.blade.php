@extends('layouts.app')

@section('title', 'Manajemen Menu - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
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
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">
        
        {{-- HEADER --}}
        <div class="sticky top-0 z-10 w-full flex items-center justify-between px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 text-start">
            <div class="flex items-center gap-4 text-start">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-orange-50 shadow-sm text-start">
                    <i class="fa-solid fa-store text-xl" style="color: #FF6900;"></i>
                </div>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Warung Bu Ani</h2>
                    <p class="text-sm text-gray-400 font-medium tracking-wide text-start">Manajemen Menu (4 terdaftar)</p>
                </div>
            </div>
            <a href="{{ route('admin.menu.tambah') }}" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#FF6900] text-white font-bold text-sm hover:brightness-110 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                Tambah Menu
            </a>
        </div>

        {{-- Grid Menu --}}
        <div class="px-10 pb-10 mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 text-start">
            
            @php
                $menus = [
                    ['id' => 1, 'name' => 'Nasi Goreng Spesial', 'price' => '25.000', 'img' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400', 'status' => 'TERSEDIA'],
                    ['id' => 2, 'name' => 'Mie Goreng Ayam', 'price' => '22.000', 'img' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400', 'status' => 'TERSEDIA'],
                    ['id' => 3, 'name' => 'Es Teh Manis', 'price' => '5.000', 'img' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'status' => 'HABIS'],
                    ['id' => 4, 'name' => 'Brown Sugar Boba', 'price' => '18.000', 'img' => 'https://images.unsplash.com/photo-1544145945-f904253d0c7b?w=400', 'status' => 'TERSEDIA'],
                ];
            @endphp

            @foreach($menus as $menu)
            <div id="menu-{{ $menu['id'] }}" class="menu-card bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 flex gap-4 transition-all duration-300 {{ $menu['status'] == 'HABIS' ? 'opacity-60 grayscale' : '' }}">
                <img src="{{ $menu['img'] }}" class="w-24 h-24 rounded-2xl object-cover" alt="Menu">
                <div class="flex-1 flex flex-col justify-between text-start">
                    <div class="flex justify-between items-start text-start">
                        <h3 class="menu-title text-base font-black {{ $menu['status'] == 'HABIS' ? 'text-gray-400' : 'text-gray-800' }} text-start">{{ $menu['name'] }}</h3>
                        
                        <div class="flex gap-2 text-start">
                            <a href="{{ route('admin.menu.edit') }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-all text-start">
                                <i class="fa-solid fa-pencil text-[12px] text-start"></i>
                            </a>
                            <button onclick="openDeleteModal('{{ $menu['id'] }}', '{{ $menu['name'] }}')" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 transition-all text-start text-start">
                                <i class="fa-solid fa-trash-can text-[12px] text-start"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-auto text-start">
                        <span class="menu-price text-start font-black {{ $menu['status'] == 'HABIS' ? 'text-gray-400' : 'text-[#FF6900]' }}">Rp {{ $menu['price'] }}</span>
                        
                        <div class="flex items-center gap-3 text-start">
                            <span class="status-text text-[10px] font-black tracking-widest uppercase {{ $menu['status'] == 'HABIS' ? 'text-gray-400' : 'text-[#22c55e]' }} text-start">{{ $menu['status'] }}</span>
                            <button onclick="toggleMenuStatus({{ $menu['id'] }})" class="toggle-btn relative inline-flex items-center w-11 h-6 rounded-full transition-all duration-300 {{ $menu['status'] == 'HABIS' ? 'bg-gray-200' : 'bg-[#22c55e]' }}">
                                <span class="toggle-circle absolute w-4 h-4 bg-white rounded-full transition-all duration-300 {{ $menu['status'] == 'HABIS' ? 'left-[4px]' : 'left-[24px]' }}"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </main>
</div>

{{-- ======================== MODAL DELETE (Sesuai Gambar Terbaru) ======================== --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px] transition-all">
    <div class="bg-white w-[400px] rounded-[32px] p-10 shadow-2xl scale-95 transition-transform duration-300">
        <div class="flex flex-col items-center text-center">
            {{-- Icon Warning Bulat Pink --}}
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            
            <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Menu?</h3>
            <p class="text-[15px] text-gray-500 font-medium leading-relaxed mb-1">Apakah Anda yakin ingin menghapus menu</p>
            <p id="deleteMenuName" class="text-[16px] text-gray-900 font-black mb-6">"Nasi Goreng Spesial"?</p>

            <p class="text-[13px] text-gray-400 mb-10">Tindakan ini tidak dapat dibatalkan.</p>

            {{-- Tombol Sejajar --}}
            <div class="grid grid-cols-2 w-full gap-4">
                <button onclick="closeDeleteModal()" class="py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-[15px] hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <button onclick="confirmDelete()" class="py-4 bg-[#FF3B30] text-white rounded-2xl font-black text-[15px] shadow-lg shadow-red-100 hover:brightness-110 transition-all text-center">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Logic Toggle Stok
    function toggleMenuStatus(id) {
        const card = document.getElementById('menu-' + id);
        const btn = card.querySelector('.toggle-btn');
        const circle = card.querySelector('.toggle-circle');
        const statusText = card.querySelector('.status-text');
        const title = card.querySelector('.menu-title');
        const price = card.querySelector('.menu-price');

        if (statusText.textContent === 'TERSEDIA') {
            card.classList.add('opacity-60', 'grayscale');
            btn.classList.replace('bg-[#22c55e]', 'bg-gray-200');
            circle.style.left = '4px';
            statusText.textContent = 'HABIS';
            statusText.classList.replace('text-[#22c55e]', 'text-gray-400');
            title.classList.replace('text-gray-800', 'text-gray-400');
            price.classList.replace('text-[#FF6900]', 'text-gray-400');
        } else {
            card.classList.remove('opacity-60', 'grayscale');
            btn.classList.replace('bg-gray-200', 'bg-[#22c55e]');
            circle.style.left = '24px';
            statusText.textContent = 'TERSEDIA';
            statusText.classList.replace('text-gray-400', 'text-[#22c55e]');
            title.classList.replace('text-gray-400', 'text-gray-800');
            price.classList.replace('text-gray-400', 'text-[#FF6900]');
        }
    }

    // Logic Modal Delete
    let menuIdToDelete = null;
    function openDeleteModal(id, name) {
        menuIdToDelete = id;
        document.getElementById('deleteMenuName').innerText = `"${name}"?`;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.replace('scale-95', 'scale-100'), 10);
    }
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.querySelector('div').classList.replace('scale-100', 'scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
    function confirmDelete() {
        // Simulasi Hapus
        document.getElementById('menu-' + menuIdToDelete).classList.add('scale-0', 'opacity-0');
        setTimeout(() => {
            document.getElementById('menu-' + menuIdToDelete).remove();
            closeDeleteModal();
        }, 300);
    }
</script>
@endpush
@endsection