@extends('layouts.app')

@section('title', 'Review Pendaftaran - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Menghilangkan scrollbar tapi tetap bisa scroll */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    body {
        background-color: #F9FAFB;
    }

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
    <header
        class="w-full bg-white border-b border-gray-100 px-10 py-6 flex justify-between items-center sticky top-0 z-50">

        {{-- Kiri: Tombol Kembali & Badge --}}
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.global.notifikasi') }}"
                class="flex items-center gap-2 text-gray-400 hover:text-[#FF6900] font-bold text-sm transition-colors duration-200">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali</span>
            </a>

            <div class="h-6 w-px bg-gray-200"></div> {{-- Garis pemisah --}}

            <div class="badge-pending">Menunggu Persetujuan</div>
        </div>

        {{-- Kanan: Aksi (Tolak / Setujui) --}}
        <div class="flex items-center gap-4">
            <form action="{{ route('admin.global.notifikasi.reject', $canteen['_id']) }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-8 py-3.5 bg-white border border-gray-200 text-red-500 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-red-50 transition-all">
                    Tolak
                </button>
            </form>

            <form action="{{ route('admin.global.notifikasi.approve', $canteen['_id']) }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-8 py-3.5 bg-[#FF6900] text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:brightness-110 shadow-lg shadow-orange-100 transition-all">
                    Setujui & Aktifkan
                </button>
            </form>
        </div>
    </header>

    {{-- Cek apakah ada dokumen --}}
    @php
    $hasDocument = !empty($canteen['document_path']);
    @endphp

    {{-- Layout Pintar: Grid 2 Kolom kalau ada dokumen, Centered kalau tidak ada dokumen --}}
    <div class="{{ $hasDocument ? 'grid grid-cols-12 gap-8' : 'max-w-4xl mx-auto w-full' }} p-10">

        {{-- KOLOM KIRI: DATA PENDAFTAR --}}
        <div class="{{ $hasDocument ? 'col-span-12 lg:col-span-5' : 'w-full' }} space-y-6">

            {{-- Banner atas --}}
            <div class="bg-[#FFF8F4] border border-[#FFE0CC] p-6 rounded-[28px] flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-[#FF6900]">
                    <i class="fa-solid fa-file-signature text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-base font-black text-gray-900">Permohonan Registrasi Baru</h4>
                    <p class="text-sm text-gray-400 font-bold">
                        Diajukan
                        {{
                        \Carbon\Carbon::parse($canteen['created_at'])->setTimezone('Asia/Jakarta')->translatedFormat('d
                        F Y, H:i') }} WIB
                    </p>
                </div>
            </div>

            {{-- Detail Kantin --}}
            <div class="review-card">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fa-solid fa-store text-[#FF6900]"></i>
                    <h3 class="text-base font-black text-gray-900">Detail Kantin</h3>
                </div>
                <div class="space-y-5">
                    <div>
                        <p class="label-title">Nama Kantin</p>
                        <p class="data-value">{{ $canteen['name'] }}</p>
                    </div>
                    @if(!empty($canteen['location']))
                    <div>
                        <p class="label-title">Lokasi</p>
                        <p class="data-value">{{ $canteen['location'] }}</p>
                    </div>
                    @endif
                    @if(!empty($canteen['description']))
                    <div>
                        <p class="label-title">Deskripsi</p>
                        <div class="bg-[#F9FAFB] p-4 rounded-2xl text-gray-500 font-bold text-sm leading-relaxed">
                            {{ $canteen['description'] }}
                        </div>
                    </div>
                    @endif
                    @if(!empty($canteen['phone']))
                    <div>
                        <p class="label-title">No. Telepon Kantin</p>
                        <p class="data-value">{{ $canteen['phone'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Informasi Pemilik --}}
            <div class="review-card">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fa-solid fa-user-tie text-[#FF6900]"></i>
                    <h3 class="text-base font-black text-gray-900">Informasi Pemilik</h3>
                </div>
                <div class="space-y-5">
                    <div>
                        <p class="label-title">Nama Lengkap</p>
                        <p class="data-value">{{ $canteen['admin_name'] ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="label-title">Email</p>
                        <p class="data-value">{{ $canteen['admin_email'] ?? '-' }}</p>
                    </div>
                    @if(!empty($canteen['admin_phone']))
                    <div>
                        <p class="label-title">No. Telepon</p>
                        <p class="data-value">{{ $canteen['admin_phone'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DOKUMEN LAMPIRAN DINAMIS --}}
        @if($hasDocument)
        <div class="col-span-12 lg:col-span-7 space-y-6">
            <div class="review-card text-start h-full">
                <div class="flex items-center gap-3 mb-8">
                    <i class="fa-solid fa-file-shield text-[#FF6900] text-sm"></i>
                    <h3 class="text-base font-black text-gray-900">Dokumen Lampiran</h3>
                </div>

                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-3">
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Foto Dokumen Pendaftar</p>
                        <div class="img-doc-preview relative">
                            {{-- Memanggil gambar secara dinamis dari storage --}}
                            <img src="{{ asset('storage/' . $canteen['document_path']) }}"
                                class="w-full h-full object-cover opacity-90" alt="Dokumen Kantin">
                            <div
                                class="absolute bottom-6 right-6 px-6 py-3 bg-white rounded-2xl shadow-xl flex items-center gap-3 border border-gray-100 cursor-pointer hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-magnifying-glass-plus text-[#FF6900]"></i>
                                <span class="text-xs font-black text-gray-700">Perbesar Gambar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection