@extends('layouts.app')

@section('title', 'Notifikasi - Kant.in Global Admin')

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

    /* Notif card */
    .notif-card {
        transition: all 0.2s ease;
    }

    .notif-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        transform: translateY(-1px);
    }

    .notif-card.unread {
        background-color: #FFFAF7;
        border-color: #FFE0CC;
    }

    .notif-card.read {
        background-color: white;
    }

    /* Icon wrap per type */
    .notif-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Action buttons */
    .action-btn-primary {
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn-primary:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
    }

    .action-btn-secondary {
        transition: all 0.15s ease;
    }

    .action-btn-secondary:hover {
        background-color: #f3f4f6;
    }

    /* Dropdown notif (dari bell icon) */
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 12px);
        right: 0;
        width: 360px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06);
        border: 1px solid #f3f4f6;
        z-index: 100;
        overflow: hidden;
        animation: dropIn 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes dropIn {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .notif-dropdown-item {
        padding: 14px 16px;
        cursor: pointer;
        transition: background 0.15s;
        border-bottom: 1px solid #f9fafb;
    }

    .notif-dropdown-item:hover {
        background-color: #FFFAF7;
    }

    .notif-dropdown-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start font-sans">

    {{-- ======================== SIDEBAR ======================== --}}
    <aside
        class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-12 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black uppercase tracking-widest mt-1" style="color:#FF6900;">Global
                    Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/global/dasbor"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Transaksi
            </a>

            {{-- Notifikasi AKTIF --}}
            <a href="/admin/global/notifikasi"
                class="sidebar-link active flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifikasi
                </div>
                <span id="sidebarBadge"
                    class="w-5 h-5 flex items-center justify-center rounded-full text-[10px] font-black text-white shadow-sm"
                    style="background-color:#FF6900;">2</span>
            </a>

            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">Sistem</div>
            <a href="/admin/global/pengaturan"
                class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Pengaturan
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-auto">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </aside>

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        <header
            class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold">Memuat Tanggal...</p>
            </div>
            <div class="flex items-center gap-6">
                {{-- Bell dengan dropdown --}}
                <div class="relative" id="bellWrapper">
                    <button onclick="toggleDropdown()"
                        class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all focus:outline-none">
                        <i class="fa-solid fa-bell text-lg"></i>
                        <span id="bellBadge" class="absolute top-2.5 right-3 w-3 h-3 border-2 border-white rounded-full"
                            style="background-color:#FF6900;"></span>
                    </button>

                    {{-- Dropdown --}}
                    <div id="notifDropdown" class="notif-dropdown hidden" style="right:-20px;">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                            <span class="text-sm font-extrabold text-gray-900">Notifikasi Terbaru</span>
                            <span class="text-xs font-black px-2.5 py-1 rounded-xl"
                                style="background-color:#FFF3E8; color:#FF6900;">2 Baru</span>
                        </div>

                        <div class="notif-dropdown-item flex items-start gap-3">
                            <div class="notif-icon-wrap flex-shrink-0"
                                style="background-color:#FFF3E8; width:40px; height:40px; border-radius:12px;">
                                <i class="fa-solid fa-store text-sm" style="color:#FF6900;"></i>
                            </div>
                            <div class="flex-1 min-w-0 text-start">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-sm font-extrabold text-gray-900 leading-tight">Pendaftaran Kantin
                                        Baru: Warung</p>
                                    <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1"
                                        style="background-color:#FF6900;"></div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 leading-relaxed">Permohonan pendaftaran kantin baru
                                    telah diajukan.</p>
                                <p class="text-[11px] text-gray-300 font-semibold mt-1">10 mnt lalu</p>
                            </div>
                        </div>

                        <div class="px-5 py-3 border-t border-gray-50 text-center">
                            <a href="/admin/global/notifikasi"
                                class="text-sm font-extrabold transition-all hover:underline"
                                style="color:#FF6900;">Lihat Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="h-10 w-[1px] bg-gray-100"></div>
                <a href="/admin/global/profil" class="flex items-center gap-4 group">
                    <div class="text-right">
                        <p class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900]">Admin
                            Utama</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest" style="color:#FF6900;">Pusat Kendali
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg border transition-all shadow-sm group-hover:text-white"
                        style="background-color:#FFF3E8; color:#FF6900; border-color:#FFE0CC;">A</div>
                </a>
            </div>
        </header>

        {{-- Content --}}
        {{-- Content --}}
        <div class="p-10 space-y-4 max-w-4xl w-full text-start">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-black text-gray-900">Notifikasi</h1>
                    <p class="text-sm text-gray-400 font-bold mt-1">Pendaftaran kantin yang menunggu persetujuan</p>
                </div>
                <button onclick="tandaiSemuaDibaca()" class="text-sm font-extrabold hover:underline transition-all"
                    style="color:#FF6900;">
                    Tandai Semua Dibaca
                </button>
            </div>

            @if(session('success'))
            <div
                class="bg-green-50 text-green-700 px-6 py-4 rounded-2xl font-bold text-sm flex items-center gap-3 mb-4">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 text-red-600 px-6 py-4 rounded-2xl font-bold text-sm mb-4">
                {{ $errors->first() }}
            </div>
            @endif

            @forelse($registrations as $reg)
            <div id="notif-{{ $reg['_id'] }}" class="notif-card unread rounded-3xl border p-5 flex items-start gap-4">
                <div class="notif-icon-wrap" style="background-color:#FFF3E8;">
                    <i class="fa-solid fa-store text-lg" style="color:#FF6900;"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-extrabold text-gray-900">
                            Pendaftaran Kantin Baru: {{ $reg['name'] }}
                        </p>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-xs text-gray-400 font-semibold">
                                {{ \Carbon\Carbon::parse($reg['created_at'])->diffForHumans() }}
                            </span>
                            <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color:#FF6900;"></div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 font-medium mt-1 leading-relaxed">
                       Pemilik: <span class="font-bold text-gray-700">{{ $reg['admin_name'] }}</span>
@if(!empty($reg['location'])) • {{ $reg['location'] }} @endif
                    </p>
                    <div class="flex items-center gap-2 mt-3">
                        <a href="{{ route('admin.global.rev-pendaftaran', $reg['_id']) }}"
                            class="action-btn-primary px-5 py-2.5 rounded-2xl text-white text-xs font-extrabold shadow-sm"
                            style="background-color:#FF6900;">
                            Review Pendaftaran
                        </a>
                        <form action="{{ route('admin.global.notifikasi.reject', $reg['_id']) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="action-btn-secondary px-5 py-2.5 rounded-2xl border border-gray-200 text-xs font-bold text-red-500 bg-white hover:bg-red-50 transition-all">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-bell-slash text-3xl text-gray-200"></i>
                </div>
                <p class="text-lg font-black text-gray-300">Tidak ada notifikasi baru</p>
                <p class="text-sm text-gray-300 font-bold mt-1">Semua pendaftaran sudah diproses</p>
            </div>
            @endforelse
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('realtimeDate').textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    });

    function toggleDropdown() {
        const dd = document.getElementById('notifDropdown');
        dd.classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('bellWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('notifDropdown').classList.add('hidden');
        }
    });

    function markRead(id) {
        const card = document.getElementById(`notif-${id}`);
        if (!card) return;
        card.classList.remove('unread');
        card.classList.add('read');
        card.style.backgroundColor = 'white';
        card.style.borderColor = '#f3f4f6';
        const dot  = card.querySelector('.w-2\\.5.h-2\\.5.rounded-full.flex-shrink-0');
        const btns = card.querySelector('.flex.items-center.gap-2.mt-3');
        if (dot) dot.remove();
        if (btns) btns.remove();
        updateBadge();
    }

    function tandaiSemuaDibaca() {
        document.querySelectorAll('.notif-card.unread').forEach(card => {
            const id = card.id.replace('notif-', '');
            markRead(id);
        });
    }

    function updateBadge() {
        const unreadCount = document.querySelectorAll('.notif-card.unread').length;
        const badge = document.getElementById('sidebarBadge');
        const bellBadge = document.getElementById('bellBadge');

        if (unreadCount === 0) {
            if (badge) badge.classList.add('hidden');
            if (bellBadge) bellBadge.style.display = 'none';
        } else {
            if (badge) {
                badge.textContent = unreadCount;
                badge.classList.remove('hidden');
            }
        }
    }
</script>
@endpush