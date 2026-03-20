@extends('layouts.app')

@section('title', 'Edit Info Kantin - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] text-start">
        
        {{-- Header Sticky --}}
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/admin/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div class="text-start">
                <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Edit Info Kantin</h2>
                <p class="text-[12px] text-gray-400 font-medium">Perbarui detail usaha Anda</p>
            </div>
        </div>

        <form class="px-10 py-10 space-y-8 max-w-4xl mx-auto w-full pb-32">
            
            {{-- Logo Kantin --}}
            <div class="flex flex-col items-center mb-10">
                <div class="relative group cursor-pointer">
                    <img id="logoPreview" src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=400" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md transition-all group-hover:brightness-75" alt="Logo">
                    <label for="logoInput" class="absolute bottom-0 right-0 w-8 h-8 bg-[#FF6900] rounded-full border-4 border-white flex items-center justify-center cursor-pointer hover:scale-110 transition-all">
                        <i class="fa-solid fa-camera text-[12px] text-white"></i>
                    </label>
                    <input type="file" id="logoInput" class="hidden" accept="image/*">
                </div>
                <p class="text-[11px] text-gray-400 font-bold mt-4 uppercase tracking-widest">Ketuk untuk ubah logo kantin</p>
            </div>

            {{-- Input Nama Kantin --}}
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Kantin</p>
                <div class="relative">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300">
                        <i class="fa-solid fa-store"></i>
                    </span>
                    <input type="text" value="Warung Bu Ani" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                </div>
            </div>

            {{-- Input Baris 2 (Pemilik & Telepon) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Nama Pemilik</p>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" value="Ani Suryani" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                    </div>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Nomor Telepon</p>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-300">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" value="081234567890" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all">
                    </div>
                </div>
            </div>

            {{-- Input Alamat --}}
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Alamat Lokasi</p>
                <div class="relative">
                    <span class="absolute left-6 top-6 text-gray-300">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <textarea placeholder="Masukkan alamat lengkap kantin" rows="3" class="w-full pl-14 pr-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all leading-relaxed"></textarea>
                </div>
            </div>

            {{-- Input Deskripsi --}}
            <div>
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Singkat</p>
                <textarea placeholder="Apa yang Anda jual? Jelaskan kantin Anda..." rows="4" class="w-full px-6 py-4 rounded-2xl bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all"></textarea>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-10 flex justify-center">
                <button type="submit" class="w-full max-w-lg py-4 bg-[#FF6900] text-white rounded-2xl font-black text-[15px] shadow-xl hover:brightness-110 transition-all flex items-center justify-center gap-3">
                    <i class="fa-solid fa-check"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </main>
</div>

@push('scripts')
<script>
    // Logic Preview Logo
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');

    logoInput.onchange = (e) => {
        const [file] = logoInput.files;
        if (file) {
            logoPreview.src = URL.createObjectURL(file);
        }
    }
</script>
@endpush
@endsection