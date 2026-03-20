@extends('layouts.app')

@section('title', 'Edit Menu: Nasi Goreng Spesial - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Custom Dropdown Styling */
    .custom-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.2em;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR (Tetap Seragam) --}}
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
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 mt-auto border-t border-gray-50 pt-6">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">
        
        {{-- Header (Sticky & Seragam) --}}
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 text-start">
            <a href="/admin/menu" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
            </a>
            <h2 class="text-xl font-extrabold text-gray-900 leading-none text-start">Edit Menu</h2>
        </div>

        <form id="editMenuForm" class="px-10 py-8 space-y-8 pb-32 text-start">
            
            {{-- Upload Gambar (Sudah Terisi) --}}
            <div class="w-full text-start">
                <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4 text-start">Gambar Menu</p>
                <label for="imageUpload" class="group cursor-pointer w-full h-48 border-2 border-dashed border-gray-200 rounded-[32px] bg-white flex flex-col items-center justify-center gap-3 hover:border-[#FF6900] transition-all overflow-hidden relative text-start">
                    <img id="imagePreview" src="https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=400" class="absolute inset-0 w-full h-full object-cover rounded-[30px] text-start" />
                    <input type="file" id="imageUpload" class="hidden text-start" accept="image/*">
                </label>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-start">
                {{-- Kolom Kiri --}}
                <div class="space-y-6 text-start">
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Nama Menu</p>
                        <input type="text" id="namaMenu" value="Nasi Goreng Spesial" placeholder="cth. Nasi Goreng Spesial" class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                    </div>
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Harga (IDR)</p>
                        <div class="relative text-start">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-start">Rp</span>
                            <input type="number" id="hargaMenu" value="25000" placeholder="25000" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                        </div>
                    </div>
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Kategori</p>
                        <select id="kategoriMenu" class="custom-select w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">
                            <option value="Nasi" selected>Nasi</option>
                            <option value="Mie">Mie</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Camilan">Camilan</option>
                        </select>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6 text-start">
                    <div class="text-start">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 text-start">Deskripsi</p>
                        <textarea id="deskripsiMenu" rows="4" placeholder="Jelaskan menu Anda..." class="w-full px-6 py-4 rounded-3xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start">Nasi goreng dengan bumbu rahasia, telur, ayam suwir, dan kerupuk.</textarea>
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between text-start">
                        <div class="text-start">
                            <p class="text-sm font-black text-gray-800 text-start">Ketersediaan</p>
                            <p class="text-[11px] text-gray-400 font-medium text-start">Matikan jika item habis</p>
                        </div>
                        <button type="button" onclick="toggleSwitch()" id="switchBtn" class="relative inline-flex items-center w-12 h-6 rounded-full transition-all bg-[#22c55e] text-start">
                            <span id="switchCircle" class="absolute w-4 h-4 bg-white rounded-full left-[24px] transition-all text-start"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Floating Button di Bawah --}}
            <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center text-start">
                <div class="w-full max-w-lg flex gap-4 text-start">
                    {{-- Tombol Hapus (Merah) --}}
                    <button type="button" onclick="openDeleteModal()" class="w-1/3 py-4 bg-red-50 text-red-500 rounded-2xl font-black text-sm flex items-center justify-center gap-3 hover:bg-red-100 transition-all text-start">
                        <i class="fa-solid fa-trash-can text-start"></i>
                        Hapus
                    </button>
                    {{-- Tombol Simpan (Orange) --}}
                    <button type="button" onclick="submitEdit()" class="flex-1 py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-xl flex items-center justify-center gap-3 hover:brightness-110 transition-all text-start">
                        <i class="fa-solid fa-floppy-disk text-start"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </main>
</div>

{{-- ======================== MODAL HAPUS (SAMA SEPERTI DI KELOLA MENU) ======================== --}}
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center hidden text-start">
    {{-- Overlay Abu-abu Transparan --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm text-start"></div>
    
    {{-- Konten Modal --}}
    <div class="relative bg-white rounded-[40px] p-10 shadow-2xl w-full max-w-md transform transition-all scale-95 opacity-0 duration-300 text-start" id="modalContent">
        <div class="flex flex-col items-center text-center text-start">
            {{-- Icon Trash Bulat Gede --}}
            <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-6 border-4 border-red-100 text-start">
                <i class="fa-solid fa-trash-can text-3xl text-start"></i>
            </div>
            
            {{-- Teks Konfirmasi --}}
            <h3 class="text-xl font-black text-gray-900 mb-2 text-start">Hapus Menu Ini?</h3>
            <p class="text-sm text-gray-400 font-medium mb-10 leading-relaxed text-start">
                Menu <span class="font-bold text-gray-800 text-start">Nasi Goreng Spesial</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
            </p>
            
            {{-- Tombol Aksi --}}
            <div class="flex gap-4 w-full text-start">
                <button onclick="closeDeleteModal()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold text-sm hover:bg-gray-200 transition-all text-start flex items-center justify-center">
                    Batal
                </button>
                <button onclick="confirmDelete()" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-black text-sm hover:bg-red-600 transition-all shadow-lg shadow-red-200 text-start flex items-center justify-center">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Logic Switch Ketersediaan
    let isAvailable = true;
    function toggleSwitch() {
        isAvailable = !isAvailable;
        const btn = document.getElementById('switchBtn');
        const circle = document.getElementById('switchCircle');
        btn.style.backgroundColor = isAvailable ? '#22c55e' : '#d1d5db';
        circle.style.left = isAvailable ? '24px' : '4px';
    }

    // Logic Preview Gambar Baru
    const imgInput = document.getElementById('imageUpload');
    const imgPreview = document.getElementById('imagePreview');
    imgInput.onchange = () => {
        const [file] = imgInput.files;
        if (file) imgPreview.src = URL.createObjectURL(file);
    }

    // Logic Modal Hapus
    function openDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function confirmDelete() {
        window.location.href = '/admin/menu'; // Balik ke kelola menu
    }

    // Submit Edit Action
    function submitEdit() {
        window.location.href = '/admin/menu';
    }
</script>
@endpush
@endsection