@extends('layouts.app')

@section('title', 'Pengaturan Akun - Kant.in')

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

    .input-custom {
        transition: all 0.3s ease;
        border: 1px solid #F3F4F6;
    }

    .input-custom:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 4px rgba(255, 105, 0, 0.1);
    }

    .notif-dropdown {
        position: absolute;
        top: 60px;
        width: 340px;
        background: white;
        border-radius: 28px;
        border: 1px solid #F3F4F6;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 100;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start font-sans">

    {{-- ======================== SIDEBAR ======================== --}}
    @include('admin_global.partials.sidebar')

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        @include('admin_global.partials.topbar')

        {{-- FORM AREA (COMPACT & CENTERED) --}}
        <div class="p-10 flex flex-col items-center justify-start flex-1 bg-[#F9FAFB]">
            {{-- Header Judul Mungil --}}
            <div class="w-full max-w-md mb-6 flex items-center gap-4 text-start">
                <a href="/admin/global/pengaturan"
                    class="w-9 h-9 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all shadow-sm">
                    <i class="fa-solid fa-arrow-left text-[10px]"></i>
                </a>
                <h3 class="text-lg font-black text-gray-900 tracking-tight">Pengaturan Akun</h3>
            </div>

            {{-- CARD FORM (KECIL - max-w-md) --}}
            <div class="w-full max-w-md bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
                {{-- Taruh di atas form, di dalam card --}}
                @if(session('success_update'))
                <div class="mb-6 px-5 py-4 rounded-2xl text-sm font-bold flex items-center gap-3"
                    style="background-color: #F0FDF4; color: #166534;">
                    <i class="fa-solid fa-circle-check"></i>
                    Profil berhasil diperbarui!
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 px-5 py-4 rounded-2xl text-sm font-bold flex items-center gap-3"
                    style="background-color: #FFF1F2; color: #991B1B;">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('admin.global.profil.update') }}" method="POST" class="space-y-6 text-start">
                    @csrf
                    @method('PUT')

                    <div class="text-start">
                        <label
                            class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2.5 block ml-1">Nama
                            Lengkap</label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 text-[13px]"></i>
                            <input type="text" name="name" value="{{ $user->name }}"
                                class="input-custom w-full pl-12 pr-6 py-4 rounded-[20px] bg-[#F9FAFB] text-[13px] font-bold text-gray-700 outline-none border-none">
                        </div>
                    </div>

                    <div class="text-start">
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2.5 block ml-1">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 text-[13px]"></i>
                            <input type="email" name="email" value="{{ $user->email }}" disabled
                                class="input-custom w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-100 text-[13px] font-bold text-gray-400 outline-none border-none cursor-not-allowed">
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 ml-1">
                            Email tidak dapat diubah
                        </p>
                    </div>

                    <div class="text-start mt-4">
                        <!-- kasih jarak -->
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2.5 block ml-1">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <i
                                class="fa-solid fa-phone absolute left-5 top-1/2 -translate-y-1/2 text-gray-300 text-[13px]"></i>
                            <input type="text" name="phone" value="{{ $user->phone ?? '08123456789' }}"
                                class="input-custom w-full pl-12 pr-6 py-4 rounded-[20px] bg-[#F9FAFB] text-[13px] font-bold text-gray-700 outline-none border-none">
                        </div>
                    </div>

                    {{-- Button Simpan (CENTERED) --}}
                    <div class="pt-2 flex justify-center">
                        <button type="submit"
                            class="px-8 py-4 bg-[#FF6900] text-white rounded-[20px] font-black text-[12px] flex items-center justify-center gap-2.5 shadow-lg shadow-orange-100 hover:scale-[1.03] transition-all uppercase tracking-widest">
                            <i class="fa-solid fa-save text-[11px]"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('realtimeDate');
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);
    });

    function toggleDropdown() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    window.onclick = function(e) {
        if (!document.getElementById('bellWrapper').contains(e.target)) {
            document.getElementById('notifDropdown').classList.add('hidden');
        }
    }
</script>
@endpush
@endsection