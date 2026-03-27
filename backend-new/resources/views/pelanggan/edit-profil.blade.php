@extends('layouts.app')

@section('title', 'Edit Profil - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 16px 14px 44px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.12);
        background-color: #fff;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap .icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        width: 16px;
        height: 16px;
    }

    .save-btn {
        transition: all 0.2s ease;
    }

    .save-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3);
    }

    .avatar-wrap {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .avatar-wrap:hover .avatar-overlay {
        opacity: 1;
    }

    .avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes checkPop {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        60% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-card {
        animation: modalIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .check-anim {
        animation: checkPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) 0.1s both;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('content')

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    {{-- SIDEBAR --}}
    <aside
        class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>
        <nav class="flex flex-col gap-1.5 flex-1">
            <a href="/beranda"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-house w-5"></i> Beranda
            </a>
            <a href="/jelajah"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-magnifying-glass w-5"></i> Jelajah
            </a>
            <a href="/pesanan"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-clipboard-list w-5"></i> Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
                style="background-color:#FFF3E8; color:#FF6900;">
                <i class="fa-solid fa-user w-5"></i> Profil
            </a>
        </nav>
        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit"
                class="flex items-center w-full gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar
            </button>
        </form>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">

        {{-- Header Asli (Transparent/Blur) --}}
        <div
            class="sticky top-0 z-30 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/profil"
                class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div>
                <h1 class="text-lg font-extrabold text-gray-900 leading-none">Edit Profil</h1>
                <p class="text-xs text-gray-400 font-medium mt-1">Perbarui detail profil Anda</p>
            </div>
        </div>

        <div class="px-10 py-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto mt-4">

                {{-- Form dengan Atribut Lengkap --}}
                <form id="editProfileForm" action="{{ route('pelanggan.profil.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Avatar --}}
                    <div class="flex justify-center mb-10">
                        <div class="avatar-wrap" onclick="document.getElementById('avatarInput').click()">
                            <div id="previewCircle"
                                class="w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-md overflow-hidden bg-[#FEF3E2]"
                                style="{{ $user->photo_profile ? 'background-image:url('.asset('storage/'.$user->photo_profile).'); background-size:cover; background-position:center;' : '' }}">

                                @if(!$user->photo_profile)
                                <i class="fa-solid fa-user text-orange-200 text-4xl"></i>
                                @endif

                            </div>
                            <div class="avatar-overlay">
                                <i class="fa-solid fa-camera text-white text-sm"></i>
                            </div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 rounded-full flex items-center justify-center shadow-md"
                                style="background-color:#FF6900;">
                                <i class="fa-solid fa-camera text-white text-[10px]"></i>
                            </div>
                        </div>
                        <input type="file" name="photo_profile" id="avatarInput" accept="image/*" class="hidden"
                            onchange="previewAvatar(this)">
                    </div>

                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Nama Lengkap</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-user icon"></i>
                                <input type="text" name="name" class="input-field"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Nomor Telepon</label>
                            <div class="input-wrap">
                                <i class="fa-solid fa-phone icon"></i>
                                <input type="tel" name="phone" class="input-field"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Email</label>
                            <div class="input-wrap">
                                <i class="fa-regular fa-envelope icon"></i>
                                <input type="email" name="email" class="input-field"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <button type="submit"
                            class="save-btn mt-6 w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                            style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                            <i class="fa-solid fa-check"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

{{-- MODAL BERHASIL (Muncul Otomatis via Session) --}}
@if(session('success_update'))
<div id="saveModal" class="fixed inset-0 z-50 flex items-center justify-center"
    style="background:rgba(0,0,0,0.4); backdrop-filter:blur(5px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[360px] mx-4 overflow-hidden">
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#22c55e,#16a34a);"></div>
        <div class="px-8 pt-8 pb-8 flex flex-col items-center gap-4">
            <div class="check-anim w-16 h-16 rounded-full flex items-center justify-center shadow-lg"
                style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <i class="fa-solid fa-check text-white text-2xl"></i>
            </div>
            <div class="text-center">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Profil Berhasil Diperbarui!</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">Data diri Anda telah berhasil disimpan.</p>
            </div>
            <button onclick="closeSaveModal()"
                class="w-full py-3.5 rounded-2xl border-2 border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const circle = document.getElementById('previewCircle');
                circle.style.backgroundImage = `url(${e.target.result})`;
                circle.style.backgroundSize = 'cover';
                circle.innerHTML = '';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function closeSaveModal() {
        document.getElementById('saveModal').classList.add('hidden');
    }
</script>
@endpush