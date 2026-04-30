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
    @include('admin_global.partials.sidebar')

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        @include('admin_global.partials.topbar')

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
                                {{ \Carbon\Carbon::parse($reg['created_at'])->locale('id')->diffForHumans() }} </span>
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