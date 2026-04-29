@extends('layouts.app')

@section('title', 'Kantin Mitra - Kant.in Global')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    /* Modal Backdrop */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        position: fixed;
        inset: 0;
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    /* Box Modal dengan Internal Scroll */
    .modal-container {
        background: white;
        border-radius: 44px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    body.modal-open { overflow: hidden; }

    .select-custom {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23D1D5DB'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1.5rem center;
        background-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR (VERSI AWAL BOS) ======================== --}}
    <aside class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-12 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col text-start">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mt-1 text-start">Global Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/global/dasbor" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra" class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color: #FFF3E8; color: #FF6900 !important;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Transaksi
            </a>
            <a href="/admin/global/notifikasi" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg> Notifikasi</div>
                <span class="w-5 h-5 bg-[#FF6900] text-white text-[10px] flex items-center justify-center rounded-full shadow-sm font-black">2</span>
            </a>
            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] text-start">Sistem</div>
            <a href="/admin/global/pengaturan" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
        </nav>

        <form method="POST" action="/logout" class="mt-auto">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 w-full text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Keluar
            </button>
        </form>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">
        <header class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Kantin Mitra</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold tracking-wide">Memuat Tanggal...</p>
            </div>
            <button onclick="openModal('modalTambah')" class="flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-[#FF6900] text-white font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-orange-100">
                <i class="fa-solid fa-plus text-xs"></i> Tambah Kantin
            </button>
        </header>

        <div class="p-10 space-y-10">
            {{-- KARTU STATISTIK --}}
            <div class="flex gap-6 w-full">
                <div class="w-[180px] bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start transition-all hover:-translate-y-1">
                    <div class="w-10 h-10 bg-orange-50 text-[#FF6900] rounded-xl flex items-center justify-center mb-4"><i class="fa-solid fa-store"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">TOTAL KANTIN</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $totalKantin }}</h3>
                </div>
                <div class="w-[180px] bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start transition-all hover:-translate-y-1">
                    <div class="w-10 h-10 bg-green-50 text-[#22C55E] rounded-xl flex items-center justify-center mb-4"><i class="fa-solid fa-bolt-lightning text-start"></i></div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">KANTIN AKTIF</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $kantinAktif }}</h3>
                </div>
            </div>

            {{-- SEARCH BAR --}}
            <div class="relative group">
                <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF6900] transition-colors"></i>
                <input type="text" id="searchInput" placeholder="Cari kantin atau pemilik..." class="w-full h-[68px] pl-14 pr-8 bg-white rounded-[28px] text-sm font-bold text-gray-700 outline-none border border-gray-100 focus:ring-2 focus:ring-orange-100 transition-all shadow-sm">
            </div>

            {{-- GRID LIST KANTIN --}}
            <div id="kantinGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12 w-full">
                @foreach($canteens as $kantin)
                <div class="kantin-card bg-white p-6 rounded-[36px] border border-gray-100 shadow-sm flex items-center justify-between group transition-all duration-300 hover:shadow-md" data-status="{{ $kantin['status'] }}">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-[28px] overflow-hidden bg-gray-50 border border-orange-50 flex items-center justify-center">
                            @if(!empty($kantin['image']))
                                <img src="{{ $kantin['image'] }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-black text-[#FF6900] text-xl">{{ strtoupper(substr($kantin['name'], 0, 2)) }}</span>
                            @endif
                        </div>
                        <div>
                            <h4 class="name-target text-[17px] font-black text-gray-900 leading-none mb-1">{{ $kantin['name'] }}</h4>
                            {{-- Nama Pemilik (Bu Vivi dkk) --}}
                            <p class="owner-target text-xs text-gray-400 font-bold italic mb-3">
                                <i class="fa-solid fa-user-tie mr-1"></i> Pemilik: {{ $kantin['admin_kantin_name'] }}
                            </p>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $kantin['status'] == 'active' ? 'bg-green-50 text-[#22C55E]' : 'bg-red-50 text-red-500' }} text-[10px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full {{ $kantin['status'] == 'active' ? 'bg-[#22C55E]' : 'bg-red-500' }}"></span>
                                {{ $kantin['status'] == 'active' ? 'Aktif' : 'Nonaktif' }}
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2.5">
                        {{-- Tombol Edit --}}
                        <button onclick='openEditModal(@json($kantin))' class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-[#6B7280] border border-gray-100 transition-all hover:brightness-95">
                            <i class="fa-solid fa-pencil text-[13px]"></i>
                        </button>
                        {{-- Tombol Hapus --}}
                        <button onclick="confirmDelete('{{ $kantin['_id'] ?? $kantin['id'] }}', '{{ $kantin['name'] }}')" class="w-10 h-10 rounded-full bg-[#FEF2F2] flex items-center justify-center text-[#EF4444] border border-[#FEE2E2] transition-all hover:brightness-95">
                            <i class="fa-solid fa-trash-can text-[13px]"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

{{-- ======================== MODAL TAMBAH ======================== --}}
<div id="modalTambah" class="modal-backdrop">
    <div class="modal-container p-10 hide-scrollbar text-start">
        <div class="flex items-center gap-4 mb-8">
            <button onclick="closeModal('modalTambah')" class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50"><i class="fa-solid fa-arrow-left"></i></button>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Daftarkan Mitra Baru</h2>
        </div>

        <form action="{{ route('admin.global.kantin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            {{-- Bagian Bisnis --}}
            <div class="bg-gray-50 p-5 rounded-[32px] space-y-4 shadow-inner">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Informasi Kantin</p>
                <input type="text" name="name" placeholder="Nama Bisnis (e.g. Warung Bu Vivi)" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                <input type="text" name="location" placeholder="Lokasi Gedung / Lantai" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                <input type="number" name="delivery_fee_flat" placeholder="Biaya Ongkir Flat" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                <div class="flex gap-2">
                    <input type="time" name="operating_hours[open]" class="flex-1 px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                    <input type="time" name="operating_hours[close]" class="flex-1 px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                </div>
            </div>

            {{-- Bagian Akun Pemilik --}}
            <div class="bg-orange-50/50 p-5 rounded-[32px] space-y-4 border border-orange-100">
                <p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-2 ml-1">Akun Pemilik (Login)</p>
                <input type="text" name="admin_name" placeholder="Nama Lengkap Pemilik" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                <input type="email" name="admin_email" placeholder="Alamat Email" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                <input type="password" name="admin_password" placeholder="Password Login" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
                {{-- INPUT NO HP SESUAI BE --}}
                <input type="text" name="phone" placeholder="No. HP / WhatsApp" class="w-full px-6 py-4 rounded-2xl border-none font-bold text-sm shadow-sm" required>
            </div>

            <button type="submit" class="w-full bg-[#FF6900] py-5 text-white font-black rounded-3xl mt-4 shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-xs transition-all">Simpan & Aktifkan Mitra</button>
        </form>
    </div>
</div>

{{-- ======================== MODAL EDIT ======================== --}}
<div id="modalEdit" class="modal-backdrop">
    <div class="modal-container p-10 hide-scrollbar text-start">
        <div class="flex items-center gap-4 mb-8">
            <button onclick="closeModal('modalEdit')" class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50"><i class="fa-solid fa-arrow-left"></i></button>
            <h2 class="text-xl font-black text-gray-900 tracking-tight">Edit Info Kantin</h2>
        </div>

        <form id="formEdit" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Nama Kantin</label>
                    <input type="text" name="name" id="edit_name" class="w-full px-6 py-4 rounded-2xl bg-[#F9FAFB] border-none font-bold text-sm shadow-sm">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">No. HP Kantin</label>
                    <input type="text" name="phone" id="edit_phone" class="w-full px-6 py-4 rounded-2xl bg-[#F9FAFB] border-none font-bold text-sm shadow-sm">
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1 mb-2 block">Status Kantin</label>
                    <select name="status" id="edit_status" class="w-full px-6 py-4 rounded-2xl bg-[#F9FAFB] border-none font-bold text-sm shadow-sm select-custom">
                        <option value="active">AKTIF</option>
                        <option value="inactive">NONAKTIF</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full bg-[#FF6900] py-5 text-white font-black rounded-3xl mt-6 shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-xs transition-all">Simpan Perubahan</button>
        </form>
    </div>
</div>

{{-- ======================== MODAL HAPUS ======================== --}}
<div id="modalHapus" class="modal-backdrop">
    <div class="bg-white w-full max-w-sm rounded-[40px] p-10 shadow-2xl text-center">
        <form id="formDelete" method="POST">
            @csrf
            @method('DELETE')
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6 border border-red-100">
                <i class="fa-solid fa-trash-can text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Kantin?</h3>
            <p class="text-sm text-gray-500 font-medium leading-relaxed mb-10">Kantin <span id="del_name" class="font-black text-gray-900"></span> akan dihapus permanen beserta akun pemiliknya.</p>
            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeModal('modalHapus')" class="py-4 bg-gray-100 text-gray-600 rounded-2xl font-black text-xs uppercase tracking-widest">Batal</button>
                <button type="submit" class="py-4 bg-[#FF3B30] text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-100">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // --- REAL TIME DATE ---
    document.addEventListener('DOMContentLoaded', function () {
        const dateElement = document.getElementById('realtimeDate');
        if (dateElement) {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.innerText = now.toLocaleDateString('id-ID', options);
        }

        // --- LIVE SEARCH ---
        document.getElementById('searchInput').addEventListener('input', function() {
            let search = this.value.toLowerCase();
            document.querySelectorAll('.kantin-card').forEach(card => {
                let name = card.querySelector('.name-target').innerText.toLowerCase();
                let owner = card.querySelector('.owner-target').innerText.toLowerCase();
                card.style.display = (name.includes(search) || owner.includes(search)) ? "flex" : "none";
            });
        });
    });

    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
        document.body.classList.add('modal-open');
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.classList.remove('modal-open');
    }

    // --- LOGIKA HAPUS ---
    function confirmDelete(id, name) {
        const form = document.getElementById('formDelete');
        form.action = `/admin/global/kantin-mitra/${id}`;
        document.getElementById('del_name').innerText = name;
        openModal('modalHapus');
    }

    // --- LOGIKA EDIT ---
    function openEditModal(kantin) {
        const form = document.getElementById('formEdit');
        let kantinId = kantin._id || kantin.id;
        form.action = `/admin/global/kantin-mitra/${kantinId}`;

        // Isi Field
        document.getElementById('edit_name').value = kantin.name || '';
        document.getElementById('edit_phone').value = kantin.phone || '';
        document.getElementById('edit_status').value = kantin.status || 'active';

        openModal('modalEdit');
    }

    // Klik luar modal untuk tutup
    window.onclick = function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            e.target.style.display = 'none';
            document.body.classList.remove('modal-open');
        }
    }
</script>
@endpush
@endsection