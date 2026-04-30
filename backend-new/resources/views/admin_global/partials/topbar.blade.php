@php
    $user = Session::get('user');
    $userName = $user['name'] ?? 'Admin Global';
    $pendingCount = Session::get('pending_count', 0);
    $initial = strtoupper(substr($userName, 0, 1));
@endphp

<header class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm">
    <div>
        <h2 class="text-2xl font-black text-gray-900 leading-none mb-1">Selamat Datang, {{ $userName }}</h2>
        <p id="realtimeDate" class="text-sm text-gray-400 font-bold">Memuat Tanggal...</p>
    </div>

    <div class="flex items-center gap-6">
        {{-- Bell --}}
        <div class="relative" id="bellWrapper">
            <button onclick="toggleDropdown()"
                class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all focus:outline-none">
                <i class="fa-solid fa-bell text-lg"></i>
                @if($pendingCount > 0)
                <span class="absolute top-2.5 right-3 w-3 h-3 border-2 border-white rounded-full" style="background-color:#FF6900;"></span>
                @endif
            </button>

            <div id="notifDropdown" class="notif-dropdown hidden" style="right:-20px;">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                    <span class="text-sm font-extrabold text-gray-900">Notifikasi Terbaru</span>
                    @if($pendingCount > 0)
                    <span class="text-xs font-black px-2.5 py-1 rounded-xl" style="background-color:#FFF3E8; color:#FF6900;">{{ $pendingCount }} Baru</span>
                    @endif
                </div>
                @if($pendingCount > 0)
                <div class="notif-dropdown-item flex items-start gap-3">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 flex-shrink-0">
                        <i class="fa-solid fa-store text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-extrabold text-gray-900 leading-tight">{{ $pendingCount }} Pendaftaran Menunggu</p>
                        <p class="text-xs text-gray-400 mt-1">Perlu ditinjau dan disetujui</p>
                    </div>
                </div>
                @else
                <div class="p-5 text-center text-sm text-gray-400 font-bold">Tidak ada notifikasi baru</div>
                @endif
                <div class="px-5 py-3 border-t border-gray-50 text-center">
                    <a href="{{ route('admin.global.notifikasi') }}" class="text-sm font-extrabold hover:underline" style="color:#FF6900;">Lihat Selengkapnya</a>
                </div>
            </div>
        </div>

        <div class="h-10 w-[1px] bg-gray-100"></div>

        <a href="{{ route('admin.global.profil') }}" class="flex items-center gap-4 group">
            <div class="text-right">
                <p class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900]">{{ $userName }}</p>
                <p class="text-[10px] font-bold text-[#FF6900] uppercase tracking-widest">Admin Global</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-[#FFF3E8] flex items-center justify-center text-[#FF6900] font-black text-lg border border-orange-100 group-hover:bg-[#FF6900] group-hover:text-white transition-all shadow-sm">
                {{ $initial }}
            </div>
        </a>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const el = document.getElementById('realtimeDate');
        if (el) el.textContent = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    });

    function toggleDropdown() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('bellWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('notifDropdown')?.classList.add('hidden');
        }
    });
</script>