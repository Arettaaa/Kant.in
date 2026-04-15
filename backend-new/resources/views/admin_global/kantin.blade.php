@extends('layouts.app')

@section('title', 'Kantin Mitra - Kant.in')

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

    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }

    .stat-card-compact {
        transition: all 0.3s ease;
        width: 180px;
    }

    .stat-card-compact:hover {
        transform: translateY(-5px);
    }

    .search-input-full {
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
        border-radius: 28px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        flex: 1;
    }

    /* Modal Backdrop Blur */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start font-sans">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside
        class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-12 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col text-start">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mt-1 text-start">Global
                    Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/global/dasbor"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra"
                class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all text-start"
                style="background-color: #FFF3E8; color: #FF6900 !important;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                Transaksi
            </a>
            <a href="/admin/global/notifikasi"
                class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg> Notifikasi</div>
                <span
                    class="w-5 h-5 bg-[#FF6900] text-white text-[10px] flex items-center justify-center rounded-full shadow-sm font-black">2</span>
            </a>
        </nav>

        <a href="/admin/login"
            class="flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 mt-auto text-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg> Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col bg-[#F9FAFB] text-start">

        <header
            class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div class="text-start">
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1 text-start">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold text-start">Memuat Tanggal...</p>
            </div>

            <div class="flex items-center gap-6">
                <button
                    class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span
                        class="absolute top-2.5 right-3 w-3 h-3 bg-[#FF6900] border-2 border-white rounded-full"></span>
                </button>
                <div class="h-10 w-[1px] bg-gray-100"></div>
                <div class="flex items-center gap-4 group text-start">
                    <div class="text-right text-start">
                        <p
                            class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900] text-start">
                            Admin Global</p>
                        <p class="text-[10px] font-bold text-[#FF6900] uppercase tracking-widest text-start">Pusat
                            Kendali</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-[#FFF3E8] flex items-center justify-center text-[#FF6900] font-black text-lg border border-orange-100 shadow-sm">
                        A</div>
                </div>
            </div>
        </header>

        <div class="p-10 space-y-10">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Kantin Terdaftar</h3>
                <button onclick="openModal('modalTambah')"
                    class="flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-[#FF6900] text-white font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-orange-100">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Kantin
                </button>
            </div>

            {{-- SUMMARY STATS --}}
            <div class="flex gap-6 w-full">
                <div
                    class="stat-card-compact bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start text-start">
                    <div
                        class="w-10 h-10 bg-orange-50 text-[#FF6900] rounded-xl flex items-center justify-center mb-4 text-start">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 text-start">TOTAL
                        KANTIN</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $totalKantin }}</h3>
                </div>

                <div
                    class="stat-card-compact bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-start text-start">
                    <div
                        class="w-10 h-10 bg-green-50 text-[#22C55E] rounded-xl flex items-center justify-center mb-4 text-start">
                        <i class="fa-solid fa-bolt-lightning text-start"></i>
                    </div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 text-start">KANTIN
                        AKTIF</p>
                    <h3 class="text-3xl font-black text-gray-900">{{ $kantinAktif }}</h3>
                </div>
            </div>

            {{-- SEARCH & FILTER BAR --}}
            <div class="flex gap-4 items-center w-full">
                <div class="relative flex-1 group text-start">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-[#FF6900] transition-colors"></i>
                    <input type="text" id="searchInput" placeholder="Cari kantin atau pemilik..."
                        class="search-input-full w-full pl-14 pr-8 bg-white text-sm font-bold text-gray-700 outline-none border border-gray-100 focus:ring-2 focus:ring-orange-100 transition-all placeholder:text-gray-300">
                </div>
                <button onclick="openModal('modalFilter')"
                    class="w-[68px] h-[68px] bg-white rounded-[24px] border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all shadow-sm flex-shrink-0 text-start">
                    <i class="fa-solid fa-sliders text-xl text-start"></i>
                </button>
            </div>

         {{-- GRID KANTIN --}}
<div id="kantinGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-8 pb-12 w-full text-start">
    
    {{-- 1. Mulai perulangan DI SINI --}}
    @foreach($canteens as $kantin)
    
        {{-- 2. Card kantin dan data-status ada DI DALAM perulangan --}}
        <div class="kantin-card bg-white p-6 rounded-[36px] border border-gray-100 shadow-sm flex items-center justify-between group transition-all duration-300 hover:shadow-md text-start"
            data-status="{{ $kantin->status }}">
            
            <div class="flex items-center gap-5 text-start">
                {{-- Tampilkan Foto Asli jika ada, jika tidak tampilkan Inisial --}}
                @if($kantin->image)
                    <img src="{{ asset('storage/' . $kantin->image) }}"
                        class="w-20 h-20 rounded-[28px] object-cover shadow-inner flex-shrink-0">
                @else
                    <div class="w-20 h-20 rounded-[28px] bg-orange-50 border border-orange-100 flex items-center justify-center font-black text-[#FF6900] text-xl shadow-inner flex-shrink-0">
                        {{ strtoupper(substr($kantin->name, 0, 2)) }}
                    </div>
                @endif

                <div class="text-start">
                    <h4 class="name-target text-[17px] font-black text-gray-900 leading-none mb-1">{{ $kantin->name }}</h4>
                    <p class="owner-target text-xs text-gray-400 font-bold italic mb-3">
                        Pemilik: {{ $kantin->admin->name ?? 'Belum ada' }}
                    </p>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $kantin->status == 'active' ? 'bg-green-50 text-[#22C55E]' : 'bg-red-50 text-red-500' }} text-[10px] font-black uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full {{ $kantin->status == 'active' ? 'bg-[#22C55E]' : 'bg-red-500' }}"></span>
                        {{ $kantin->status == 'active' ? 'Aktif' : 'Nonaktif' }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-2.5 text-start">
                <button onclick='openEditModal(@json($kantin))' class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-[#6B7280] border border-gray-100 transition-all hover:brightness-95">
                    <i class="fa-solid fa-pencil text-[13px]"></i>
                </button>
                <button onclick="confirmDelete('{{ $kantin->_id }}', '{{ $kantin->name }}')" class="w-10 h-10 rounded-full bg-[#FEF2F2] flex items-center justify-center text-[#EF4444] border border-[#FEE2E2] transition-all hover:brightness-95">
                    <i class="fa-solid fa-trash-can text-[13px]"></i>
                </button>
            </div>
            
        </div>
    {{-- 3. Tutup perulangan DI SINI --}}
    @endforeach

</div>

            {{-- ======================== SEMUA MODAL DISINI ======================== --}}

            {{-- 1. MODAL TAMBAH --}}
            <div id="modalTambah"
                class="hidden fixed inset-0 z-[100] flex items-center justify-center modal-backdrop p-4">
                <div
                    class="bg-white rounded-[40px] w-full max-w-md p-10 shadow-2xl scale-100 transition-all text-start">
                    <div class="flex items-center gap-4 mb-8">
                        <button onclick="closeModal('modalTambah')"
                            class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50"><i
                                class="fa-solid fa-arrow-left"></i></button>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">Tambah Kantin Baru</h2>
                    </div>

                    <form action="{{ route('admin.global.kantin.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-4">
                        @csrf
                        {{-- Input Gambar --}}
                        <div
                            class="group relative w-full h-32 border-2 border-dashed border-gray-100 rounded-[24px] flex flex-col items-center justify-center gap-2 text-gray-400 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-all overflow-hidden">
                            <input type="file" name="image" class="absolute inset-0 opacity-0 cursor-pointer">
                            <i class="fa-solid fa-camera text-2xl"></i>
                            <p class="text-[10px] font-bold uppercase tracking-widest">Foto Kantin</p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-300 uppercase tracking-widest ml-1">Informasi
                                Kantin</label>
                            <input type="text" name="name" placeholder="Nama Kantin"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700 mb-2"
                                required>
                            <input type="text" name="location" placeholder="Lokasi (Lantai/Gedung)"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700"
                                required>
                        </div>

                        <div class="pt-2 border-t border-gray-50">
                            <label class="text-[10px] font-black text-gray-300 uppercase tracking-widest ml-1">Akun
                                Admin Kantin</label>
                            <input type="text" name="admin_name" placeholder="Nama Lengkap Admin"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700 mb-2"
                                required>
                            <input type="email" name="admin_email" placeholder="Email Admin"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700 mb-2"
                                required>
                            <input type="password" name="admin_password" placeholder="Password Baru"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700"
                                required>
                        </div>

                        <button type="submit"
                            class="w-full bg-[#FF6900] py-5 text-white font-black rounded-2xl mt-4 shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-sm">Simpan
                            Mitra</button>
                    </form>
                </div>
            </div>

            {{-- 2. MODAL EDIT --}}
            <div id="modalEdit"
                class="hidden fixed inset-0 z-[100] flex items-center justify-center modal-backdrop p-4">
                <div
                    class="bg-white rounded-[40px] w-full max-w-md p-10 shadow-2xl scale-100 transition-all text-start">
                    <div class="flex items-center gap-4 mb-8">
                        <button onclick="closeModal('modalEdit')"
                            class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-gray-50">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">Edit Info Kantin</h2>
                    </div>

                    {{-- Preview Gambar --}}
                    <div class="mb-6 rounded-[32px] overflow-hidden h-44 shadow-sm border border-gray-100">
                        <img id="edit_preview" src="" class="w-full h-full object-cover bg-gray-100">
                    </div>

                    {{-- Pastikan action form kosong dulu, kita isi lewat JS --}}
                    <form id="formEdit" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-[10px] font-black text-gray-300 uppercase tracking-widest ml-1">Nama
                                Kantin</label>
                            <input type="text" name="name" id="edit_name"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700">
                        </div>

                        <div>
                            <label
                                class="text-[10px] font-black text-gray-300 uppercase tracking-widest ml-1">Lokasi</label>
                            <input type="text" name="location" id="edit_location"
                                class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-orange-100 font-bold text-sm text-gray-700">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-300 uppercase tracking-widest ml-1">Ganti
                                Foto (Opsional)</label>
                            <input type="file" name="image" class="w-full text-xs text-gray-400 mt-2">
                        </div>

                        <button type="submit"
                            class="w-full bg-[#FF6900] py-5 text-white font-black rounded-2xl mt-4 shadow-lg shadow-orange-100 hover:brightness-110 uppercase tracking-widest text-sm text-center">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            {{-- 3. MODAL HAPUS --}}
            <div id="modalHapus"
                class="hidden fixed inset-0 z-[100] flex items-center justify-center modal-backdrop p-4">
                <div class="bg-white w-full max-w-sm rounded-[40px] p-10 shadow-2xl text-center">
                    <form id="formDelete" method="POST">
                        @csrf
                        @method('DELETE')
                        <div
                            class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-500 mx-auto mb-6 border border-red-100">
                            <i class="fa-solid fa-trash-can text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-3">Hapus Kantin?</h3>
                        <p class="text-[15px] text-gray-500 font-medium leading-relaxed mb-10">
                            Anda yakin ingin menghapus kantin <span id="del_name"
                                class="font-black text-gray-900"></span>?
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="closeModal('modalHapus')"
                                class="py-4.5 bg-gray-100 text-gray-600 rounded-2xl font-black text-[15px]">Batal</button>
                            <button type="submit"
                                class="py-4.5 bg-[#FF3B30] text-white rounded-2xl font-black text-[15px] shadow-lg shadow-red-100">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 4. MODAL FILTER --}}
            <div id="modalFilter"
                class="hidden fixed inset-0 z-[100] flex items-center justify-center modal-backdrop p-4 font-sans text-start">
                <div class="bg-white rounded-[44px] w-full max-w-sm overflow-hidden shadow-2xl text-start">
                    <div class="p-10">
                        <div class="flex justify-between items-center mb-10 text-start">
                            <h2 class="text-2xl font-black text-gray-900 tracking-tighter">FILTER</h2>
                            <button onclick="closeModal('modalFilter')"
                                class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-gray-100 border border-gray-100 transition-all text-start">
                                <i class="fa-solid fa-xmark text-start"></i>
                            </button>
                        </div>
                        <div class="mb-10 text-start">
                            <p class="text-[12px] font-black text-gray-300 uppercase tracking-[0.3em] mb-5">Status Mitra
                            </p>
                            <div class="flex flex-wrap gap-3">
                                {{-- Tambahkan id "btnFilterSemua" dan "btnFilterAktif" --}}
                                <button type="button" id="btnFilterSemua" onclick="setFilter('semua', this)"
                                    class="filter-btn px-7 py-3 rounded-2xl border border-orange-200 bg-[#FFF3E8] text-[#FF6900] font-black text-[12px] shadow-sm">Semua</button>
                                <button type="button" id="btnFilterAktif" onclick="setFilter('active', this)"
                                    class="filter-btn px-7 py-3 rounded-2xl border border-gray-100 text-gray-400 font-bold text-[12px]">Aktif</button>
                            </div>
                        </div>
                        <div class="flex gap-4 text-center">
                            {{-- Ubah event onclick untuk eksekusi JavaScript --}}
                            <button type="button" onclick="resetFilter()"
                                class="flex-1 py-5 border border-gray-100 rounded-[25px] font-black text-gray-400 text-[14px] hover:bg-gray-50 text-center">Atur
                                Ulang</button>
                            <button type="button" onclick="applyFilter()"
                                class="flex-1 py-5 bg-[#FF6900] text-white rounded-[25px] font-black text-[14px] shadow-xl shadow-orange-100 text-center uppercase tracking-widest">Terapkan</button>
                        </div>
                    </div>
                </div>
            </div>

          @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ======================
    // REALTIME DATE
    // ======================
    const dateElement = document.getElementById('realtimeDate');
    if (dateElement) {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);
    }

    // ======================
    // SEARCH + FILTER
    // ======================
    const searchInput = document.getElementById('searchInput');

    if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
    }

});

// ======================
// MODAL
// ======================
function openModal(id) {
    document.getElementById(id)?.classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id)?.classList.add('hidden');
}

// ======================
// DELETE
// ======================
function confirmDelete(id, name) {
    const form = document.getElementById('formDelete');
    if (form) {
        form.action = `/admin/global/kantin-mitra/${id}`;
    }

    const nameText = document.getElementById('del_name');
    if (nameText) {
        nameText.innerText = name;
    }

    openModal('modalHapus');
}

// ======================
// EDIT MODAL
// ======================
function openEditModal(kantin) {
    const form = document.getElementById('formEdit'); 

    let kantinId = kantin._id;

    if (typeof kantinId === 'object' && kantinId !== null) {
        kantinId = kantinId.$oid;
    } else if (!kantinId) {
        kantinId = kantin.id;
    }

    if (form) {
        form.action = `/admin/global/kantin-mitra/${kantinId}`;
    }

    document.getElementById('edit_name').value = kantin.name || '';
    document.getElementById('edit_location').value = kantin.location || '';

    // Preview Image (FIXED)
    const preview = document.getElementById('edit_preview');
    const previewContainer = preview?.parentElement;

    if (kantin.image) {
        preview.src = kantin.image; // 🔥 sudah full URL dari controller
        previewContainer.style.display = 'block';
    } else {
        preview.src = '';
        previewContainer.style.display = 'none';
    }

    openModal('modalEdit');
}

// ======================
// FILTER
// ======================
let currentFilterStatus = 'semua';

function setFilter(status, element) {
    currentFilterStatus = status;

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = "filter-btn px-7 py-3 rounded-2xl border border-gray-100 text-gray-400 font-bold text-[12px]";
    });

    element.className = "filter-btn px-7 py-3 rounded-2xl border border-orange-200 bg-[#FFF3E8] text-[#FF6900] font-black text-[12px] shadow-sm";
}

function applyFilter() {
    let searchVal = document.getElementById('searchInput')?.value.toLowerCase() || '';

    document.querySelectorAll('.kantin-card').forEach(card => {
        let n = card.querySelector('.name-target')?.innerText.toLowerCase() || '';
        let o = card.querySelector('.owner-target')?.innerText.toLowerCase() || '';
        let cardStatus = card.getAttribute('data-status');

        let matchSearch = n.includes(searchVal) || o.includes(searchVal);
        let matchFilter = (currentFilterStatus === 'semua') || (cardStatus === currentFilterStatus);

        card.style.display = (matchSearch && matchFilter) ? "flex" : "none";
    });

    closeModal('modalFilter');
}

function resetFilter() {
    document.getElementById('btnFilterSemua')?.click();
    applyFilter();
}

// ======================
// ALERT
// ======================
@if(session('success'))
    alert("{{ session('success') }}");
@endif

</script>
@endpush
@endsection
