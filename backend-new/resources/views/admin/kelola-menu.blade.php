@extends('layouts.app')

@section('title', 'Manajemen Menu - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR ======================== --}}
    @include('admin.partials.sidebar')

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">

        {{-- HEADER DENGAN SEARCH BAR --}}
        <div class="sticky top-0 z-10 w-full flex items-center justify-between px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 text-start">
            <div class="flex items-center gap-4 text-start">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-orange-50 shadow-sm text-start">
                    <i class="fa-solid fa-store text-xl" style="color: #FF6900;"></i>
                </div>
                <div class="text-start">
                    <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Warung Bu Ani</h2>
                    <p class="text-sm text-gray-400 font-medium tracking-wide text-start">
                        <span id="menuCount">{{ count($menus) }}</span> Menu ditampilkan
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                {{-- Search Bar --}}
                <div class="relative group">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF6900] transition-colors"></i>
                    <input type="text" id="searchInput" placeholder="Cari menu atau kategori..." class="w-64 pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-[#FF6900] transition-all placeholder-gray-400 shadow-sm">
                </div>

                <a href="{{ route('admin.menu.tambah') }}" class="flex items-center gap-2 px-6 py-3 rounded-2xl bg-[#FF6900] text-white font-bold text-sm hover:brightness-110 transition-all shadow-sm">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Menu
                </a>
            </div>
        </div>

        @if(session('success'))
        <div id="flashMessage" class="mx-10 mt-6 px-5 py-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl font-semibold text-sm transition-opacity duration-500 ease-in-out opacity-100">
            {{ session('success') }}
        </div>
        @endif

        <div id="menuContainer" class="px-10 pb-10 mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6 text-start relative">

            @forelse($menus as $menu)
            <div id="menu-{{ $menu['_id'] }}" class="menu-card bg-white rounded-[32px] p-5 shadow-sm border border-gray-100 flex gap-4 transition-all duration-300 {{ !$menu['is_available'] ? 'opacity-60 grayscale order-last' : '' }}">

                @if(!empty($menu['image']))
                    <img src="{{ $menu['image'] }}" class="w-24 h-24 rounded-2xl object-cover flex-shrink-0" alt="{{ $menu['name'] }}">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-utensils text-gray-300 text-2xl"></i>
                    </div>
                @endif

                <div class="flex-1 flex flex-col justify-between text-start">
                    <div class="flex justify-between items-start text-start">
                        <div>
                            <h3 class="menu-title text-base font-black {{ !$menu['is_available'] ? 'text-gray-400' : 'text-gray-800' }} text-start">{{ $menu['name'] }}</h3>
                            @if(!empty($menu['category']))
                                <span class="text-xs text-gray-400 font-medium">{{ $menu['category'] }}</span>
                            @endif
                        </div>

                        <div class="flex gap-2 text-start">
                            <a href="{{ route('admin.menu.edit', $menu['_id']) }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-all">
                                <i class="fa-solid fa-pencil text-[12px]"></i>
                            </a>
                            <button
                                onclick="openDeleteModal('{{ $menu['_id'] }}', '{{ addslashes($menu['name']) }}')"
                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 transition-all">
                                <i class="fa-solid fa-trash-can text-[12px]"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-auto text-start">
                        <span class="menu-price text-start font-black {{ !$menu['is_available'] ? 'text-gray-400' : 'text-[#FF6900]' }}">
                            Rp {{ number_format($menu['price'], 0, ',', '.') }}
                        </span>

                        <div class="flex items-center gap-3 text-start">
                            <span class="status-text text-[10px] font-black tracking-widest uppercase {{ !$menu['is_available'] ? 'text-gray-400' : 'text-[#22c55e]' }}">
                                {{ $menu['is_available'] ? 'TERSEDIA' : 'HABIS' }}
                            </span>
                            <button
                                onclick="toggleMenuStatus('{{ $menu['_id'] }}')"
                                data-available="{{ $menu['is_available'] ? '1' : '0' }}"
                                class="toggle-btn relative inline-flex items-center w-11 h-6 rounded-full transition-all duration-300 {{ !$menu['is_available'] ? 'bg-gray-200' : 'bg-[#22c55e]' }}">
                                <span class="toggle-circle absolute w-4 h-4 bg-white rounded-full transition-all duration-300 {{ !$menu['is_available'] ? 'left-[4px]' : 'left-[24px]' }}"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-1 lg:col-span-2 flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-utensils text-2xl text-orange-300"></i>
                </div>
                <p class="text-gray-400 font-semibold">Belum ada menu. Tambahkan menu pertamamu!</p>
            </div>
            @endforelse

            {{-- ELEMENT EMPTY STATE KHUSUS PENCARIAN -}}
            <div id="searchEmptyState" class="hidden col-span-1 lg:col-span-2 flex-col items-center justify-center py-24 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-magnifying-glass text-2xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-black text-gray-900 mb-1">Pencarian Tidak Ditemukan</h3>
                <p class="text-gray-400 font-medium text-sm">Tidak ada menu atau kategori yang cocok dengan "<span id="searchKeyword" class="font-bold text-gray-800"></span>"</p>
            </div>

        </div>
    </main>
</div>

{{-- ======================== MODAL DELETE ======================== --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px] transition-all">
    <div class="bg-white w-[400px] rounded-[32px] p-10 shadow-2xl scale-95 transition-transform duration-300">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center text-red-500 mb-6">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Menu?</h3>
            <p class="text-[15px] text-gray-500 font-medium leading-relaxed mb-1">Apakah Anda yakin ingin menghapus menu</p>
            <p id="deleteMenuName" class="text-[16px] text-gray-900 font-black mb-6"></p>
            <p class="text-[13px] text-gray-400 mb-10">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="grid grid-cols-2 w-full gap-4">
                <button onclick="closeDeleteModal()" class="py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-[15px] hover:bg-gray-200 transition-all">Batal</button>
                <button onclick="confirmDelete()" id="confirmDeleteBtn" class="py-4 bg-[#FF3B30] text-white rounded-2xl font-black text-[15px] shadow-lg shadow-red-100 hover:brightness-110 transition-all text-center">Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashMsg = document.getElementById('flashMessage');
        if (flashMsg) {
            setTimeout(() => {
                flashMsg.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => { flashMsg.remove(); }, 500);
            }, 3000); 
        }
    });

    const searchInput = document.getElementById('searchInput');
    const searchEmptyState = document.getElementById('searchEmptyState');
    const searchKeyword = document.getElementById('searchKeyword');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const cards = document.querySelectorAll('.menu-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.querySelector('.menu-title').textContent.toLowerCase();
                const categoryEl = card.querySelector('span.text-xs.text-gray-400');
                const category = categoryEl ? categoryEl.textContent.toLowerCase() : '';

                if (title.includes(filter) || category.includes(filter)) {
                    card.style.display = 'flex'; 
                    visibleCount++;
                } else {
                    card.style.display = 'none'; 
                }
            });

            document.getElementById('menuCount').innerText = visibleCount;

            if (visibleCount === 0 && cards.length > 0) {
                searchEmptyState.classList.remove('hidden');
                searchEmptyState.classList.add('flex');
                searchKeyword.innerText = this.value; 
            } else {
                searchEmptyState.classList.add('hidden');
                searchEmptyState.classList.remove('flex');
            }
        });
    }

    function showNotification(message) {
        const notif = document.createElement('div');
        notif.className = 'mx-10 mt-6 px-5 py-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl font-semibold text-sm transition-opacity duration-500 ease-in-out opacity-0';
        notif.innerText = message;
        
        const container = document.getElementById('menuContainer');
        container.parentNode.insertBefore(notif, container);
        
        setTimeout(() => notif.classList.replace('opacity-0', 'opacity-100'), 10);
        setTimeout(() => {
            notif.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => notif.remove(), 500);
        }, 3000);
    }

    function updateMenuCount() {
        const visibleCards = Array.from(document.querySelectorAll('.menu-card')).filter(card => card.style.display !== 'none');
        document.getElementById('menuCount').innerText = visibleCards.length;
    }

    function toggleMenuStatus(id) {
        const card     = document.getElementById('menu-' + id);
        const btn      = card.querySelector('.toggle-btn');
        const circle   = card.querySelector('.toggle-circle');
        const statusEl = card.querySelector('.status-text');
        const titleEl  = card.querySelector('.menu-title');
        const priceEl  = card.querySelector('.menu-price');

        const isCurrentlyAvailable = btn.dataset.available === '1';
        const newAvailable = isCurrentlyAvailable ? 0 : 1;

        fetch(`/admin/menu/${id}/availability`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ is_available: newAvailable }),
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) { alert('Gagal mengubah status menu.'); return; }

            btn.dataset.available = newAvailable;

            if (newAvailable === 0) {
                card.classList.add('opacity-60', 'grayscale', 'order-last'); 
                btn.classList.replace('bg-[#22c55e]', 'bg-gray-200');
                circle.style.left = '4px';
                statusEl.textContent = 'HABIS';
                statusEl.classList.replace('text-[#22c55e]', 'text-gray-400');
                titleEl.classList.replace('text-gray-800', 'text-gray-400');
                priceEl.classList.replace('text-[#FF6900]', 'text-gray-400');
            } else {
                card.classList.remove('opacity-60', 'grayscale', 'order-last');
                btn.classList.replace('bg-gray-200', 'bg-[#22c55e]');
                circle.style.left = '24px';
                statusEl.textContent = 'TERSEDIA';
                statusEl.classList.replace('text-gray-400', 'text-[#22c55e]');
                titleEl.classList.replace('text-gray-400', 'text-gray-800');
                priceEl.classList.replace('text-gray-400', 'text-[#FF6900]');
            }
        })
        .catch(() => alert('Terjadi kesalahan. Coba lagi.'));
    }

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
        menuIdToDelete = null;
    }

    function confirmDelete() {
        if (!menuIdToDelete) return;

        const btn = document.getElementById('confirmDeleteBtn');
        btn.disabled = true;
        btn.textContent = 'Menghapus...';

        fetch(`/admin/menu/${menuIdToDelete}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ _method: 'DELETE' }),
        })
        .then(res => {
            if (res.ok) {
                const card = document.getElementById('menu-' + menuIdToDelete);
                card.classList.add('scale-0', 'opacity-0');
                
                setTimeout(() => {
                    card.remove();
                    updateMenuCount();
                    closeDeleteModal();
                    
                    showNotification('Menu berhasil dihapus.');
                    
                    btn.disabled = false;
                    btn.textContent = 'Hapus';
                }, 300);
            } else {
                return res.json().then(d => { throw new Error(d.message || 'Gagal menghapus.'); });
            }
        })
        .catch(err => {
            alert(err.message);
            btn.disabled = false;
            btn.textContent = 'Hapus';
        });
    }
</script>
@endpush
@endsection