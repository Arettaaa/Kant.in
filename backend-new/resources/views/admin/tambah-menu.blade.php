@extends('layouts.app')

@section('title', 'Tambah Menu Baru - Kant.in')

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
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>
        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 mt-auto border-t border-gray-50 pt-6">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        
        {{-- Header (Sticky & Seragam) --}}
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/admin/menu" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <h2 class="text-xl font-extrabold text-gray-900 leading-none">Tambah Menu Baru</h2>
        </div>

        <form id="menuForm" class="px-10 py-8 space-y-8 pb-32">
            
            {{-- Upload Gambar --}}
            <div class="w-full">
                <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">Gambar Menu</p>
                <label for="imageUpload" class="group cursor-pointer w-full h-48 border-2 border-dashed border-gray-200 rounded-[32px] bg-white flex flex-col items-center justify-center gap-3 hover:border-[#FF6900] transition-all overflow-hidden relative">
                    <div id="previewContainer" class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-[#FF6900] group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                        </div>
                        <div class="text-center mt-2">
                            <p class="text-sm font-bold text-gray-800">Ketuk untuk mengunggah gambar</p>
                            <p class="text-[11px] text-gray-400 font-medium">Ukuran yang disarankan: 1:1 (Persegi)</p>
                        </div>
                    </div>
                    <img id="imagePreview" class="hidden absolute inset-0 w-full h-full object-cover rounded-[30px]" />
                    <input type="file" id="imageUpload" class="hidden" accept="image/*" required>
                </label>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-6 text-start">
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Menu</p>
                        <input type="text" id="namaMenu" placeholder="cth. Nasi Goreng Spesial" class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Harga (IDR)</p>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                            <input type="number" id="hargaMenu" placeholder="25000" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                        </div>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Kategori</p>
                        <select id="kategoriMenu" class="custom-select w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Nasi">Nasi</option>
                            <option value="Mie">Mie</option>
                            <option value="Minuman">Minuman</option>
                            <option value="Camilan">Camilan</option>
                        </select>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6 text-start">
                    <div>
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi</p>
                        <textarea id="deskripsiMenu" rows="4" placeholder="Jelaskan menu Anda..." class="w-full px-6 py-4 rounded-3xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all"></textarea>
                    </div>
                    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-sm font-black text-gray-800">Ketersediaan</p>
                            <p class="text-[11px] text-gray-400 font-medium">Matikan jika item habis</p>
                        </div>
                        <button type="button" onclick="toggleSwitch()" id="switchBtn" class="relative inline-flex items-center w-12 h-6 rounded-full transition-all bg-[#22c55e]">
                            <span id="switchCircle" class="absolute w-4 h-4 bg-white rounded-full left-[24px] transition-all"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Floating Button di Bawah --}}
            <div class="fixed bottom-0 right-0 left-[240px] p-6 bg-white/80 backdrop-blur-md border-t border-gray-100 z-20 flex justify-center">
                <button type="button" id="submitBtn" onclick="submitMenu()" disabled 
                    class="w-full max-w-lg py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-xl flex items-center justify-center gap-3 transition-all duration-300 opacity-30 cursor-not-allowed">
                    <i class="fa-solid fa-check"></i>
                    Tambah Menu
                </button>
            </div>

        </form>
    </main>
</div>

@push('scripts')
<script>
    // Logic untuk Switch Ketersediaan
    let isAvailable = true;
    function toggleSwitch() {
        isAvailable = !isAvailable;
        const btn = document.getElementById('switchBtn');
        const circle = document.getElementById('switchCircle');
        if (isAvailable) {
            btn.style.backgroundColor = '#22c55e'; circle.style.left = '24px';
        } else {
            btn.style.backgroundColor = '#d1d5db'; circle.style.left = '4px';
        }
    }

    // Logic Preview Gambar
    const imgInput = document.getElementById('imageUpload');
    const imgPreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('previewContainer');

    imgInput.onchange = (e) => {
        const [file] = imgInput.files;
        if (file) {
            imgPreview.src = URL.createObjectURL(file);
            imgPreview.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            validateForm();
        }
    }

    // Logic Validasi Form
    const formInputs = ['namaMenu', 'hargaMenu', 'kategoriMenu', 'deskripsiMenu'];
    formInputs.forEach(id => {
        document.getElementById(id).addEventListener('input', validateForm);
    });

    function validateForm() {
        const btn = document.getElementById('submitBtn');
        const isImgFilled = imgInput.files.length > 0;
        const isFormFilled = formInputs.every(id => document.getElementById(id).value.trim() !== "");

        if (isImgFilled && isFormFilled) {
            btn.disabled = false;
            btn.classList.remove('opacity-30', 'cursor-not-allowed');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-30', 'cursor-not-allowed');
        }
    }

    // Submit Action
    function submitMenu() {
        window.location.href = '/admin/menu';
    }
</script>
@endpush
@endsection