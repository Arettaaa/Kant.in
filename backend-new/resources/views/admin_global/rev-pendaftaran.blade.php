@extends('layouts.app')

@section('title', 'Review Pendaftaran - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Menghilangkan scrollbar tapi tetap bisa scroll */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    body { background-color: #F9FAFB; }

    /* Layout Full Width */
    .full-container {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .review-card {
        background: white;
        border-radius: 32px;
        border: 1px solid #f3f4f6;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    
    .label-title {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .data-value {
        font-size: 16px;
        font-weight: 800;
        color: #111827;
    }

    /* Preview Gambar Dokumen Besar */
    .img-doc-preview {
        width: 100%;
        border-radius: 24px;
        background: #F9FAFB;
        border: 1px solid #f3f4f6;
        height: 380px;
        object-fit: cover;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .badge-pending {
        background-color: #FFF3E8;
        color: #FF6900;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')
<div class="full-container hide-scrollbar overflow-y-auto">
    
    {{-- Header: Menempel di ujung ke ujung --}}
    <header class="w-full bg-white border-b border-gray-100 px-10 py-6 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.global.notifikasi') }}" 
               class="w-11 h-11 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Review Pendaftaran Kantin</h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="badge-pending mr-4">Menunggu Persetujuan</div>
            <button class="px-8 py-3.5 bg-white border border-gray-200 text-red-500 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-red-50 transition-all">Tolak</button>
            <button class="px-8 py-3.5 bg-[#FF6900] text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:brightness-110 shadow-lg shadow-orange-100 transition-all">Setujui & Aktifkan</button>
        </div>
    </header>

    {{-- Layout Grid 2 Kolom: Biar Kanan Nggak Kosong --}}
    <div class="grid grid-cols-12 gap-8 p-10">
        
        {{-- KOLOM KIRI: DATA PENDAFTAR (5/12 bagian) --}}
        <div class="col-span-12 lg:col-span-5 space-y-6">
            
            <div class="bg-[#FFF8F4] border border-[#FFE0CC] p-6 rounded-[28px] flex items-center gap-5 text-start">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-[#FF6900]">
                    <i class="fa-solid fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-gray-900">Permohonan Registrasi Baru</h4>
                    <p class="text-sm text-gray-400 font-bold">Diajukan pada 24 Oktober 2023, 10:30 WIB</p>
                </div>
            </div>

            {{-- Detail Kantin --}}
            <div class="review-card text-start">
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-store text-[#FF6900] text-sm"></i>
                    <h3 class="text-base font-black text-gray-900">Detail Kantin</h3>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="label-title">Nama Kantin</p>
                            <p class="data-value">Warung Bu Ani</p>
                        </div>
                        <div>
                            <p class="label-title">Kategori</p>
                            <p class="data-value">Indonesian Food</p>
                        </div>
                    </div>
                    <div>
                        <p class="label-title">Deskripsi Kantin</p>
                        <div class="bg-[#F9FAFB] p-5 rounded-2xl border border-gray-50 text-gray-500 font-bold text-sm leading-relaxed">
                            Menyajikan aneka masakan rumahan khas Nusantara dengan resep turun temurun yang sehat dan bergizi.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Pemilik --}}
            <div class="review-card text-start">
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-user-tie text-[#FF6900] text-sm"></i>
                    <h3 class="text-base font-black text-gray-900">Informasi Pemilik</h3>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <p class="label-title">Nama Lengkap</p>
                        <p class="data-value">Ani Suryani</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="label-title">NIK (No. KTP)</p>
                            <p class="data-value">3171234567890001</p>
                        </div>
                        <div>
                            <p class="label-title">Nomor Telepon</p>
                            <p class="data-value">081234567890</p>
                        </div>
                    </div>
                    <div>
                        <p class="label-title">Alamat Email</p>
                        <p class="data-value">ani.suryani@email.com</p>
                    </div>
                </div>
            </div>

            {{-- Rekening --}}
            <div class="review-card text-start">
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-credit-card text-[#FF6900] text-sm"></i>
                    <h3 class="text-base font-black text-gray-900">Rekening Settlement</h3>
                </div>
                <div class="bg-[#F9FAFB] p-6 rounded-[24px] border border-gray-50 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 mb-1">BANK BCA</p>
                        <h4 class="text-lg font-black text-gray-900 tracking-wider">1234567890</h4>
                        <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase italic">A/N ANI SURYANI</p>
                    </div>
                    <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DOKUMEN LAMPIRAN (7/12 bagian) --}}
        <div class="col-span-12 lg:col-span-7 space-y-6">
            <div class="review-card text-start h-full">
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-file-shield text-[#FF6900] text-sm"></i>
                    <h3 class="text-base font-black text-gray-900">Dokumen Lampiran (Preview)</h3>
                </div>
                
                <div class="grid grid-cols-1 gap-8">
                    {{-- Preview KTP --}}
                    <div class="space-y-3">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto KTP Asli</p>
                        <div class="img-doc-preview relative">
                            <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?q=80&w=1000" class="w-full h-full object-cover opacity-90">
                            <div class="absolute bottom-6 right-6 px-6 py-3 bg-white rounded-2xl shadow-xl flex items-center gap-3 border border-gray-100">
                                <i class="fa-solid fa-magnifying-glass-plus text-[#FF6900]"></i>
                                <span class="text-xs font-black text-gray-700">Perbesar Gambar</span>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Selfie --}}
                    <div class="space-y-3 pt-4">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto Selfie & KTP</p>
                        <div class="img-doc-preview">
                            <div class="flex flex-col items-center gap-3">
                                 <i class="fa-solid fa-camera text-5xl text-gray-100"></i>
                                 <p class="text-gray-300 font-bold">Gambar tidak tersedia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection