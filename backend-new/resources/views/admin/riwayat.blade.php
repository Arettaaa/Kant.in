@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    /* Sembunyikan scrollbar agar clean */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .transaction-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR (SERAGAM) ======================== --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight text-start">Kantin</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Kelola Menu
            </a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color:#FFF3E8;color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Riwayat Transaksi
            </a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Kantin
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto text-start border-t border-gray-50 pt-6">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 flex overflow-hidden text-start">
        
        {{-- AREA KIRI: DAFTAR TRANSAKSI --}}
        <div class="flex-1 flex flex-col h-full bg-[#F9FAFB] border-r border-gray-100 overflow-hidden text-start">
            {{-- Header Kiri --}}
            <div class="px-10 py-6 bg-white border-b border-gray-100 flex items-center justify-between text-start">
                <div class="flex items-center gap-4 text-start">
                    <a href="/admin/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all text-start">
                        <i class="fa-solid fa-arrow-left text-gray-400 text-start"></i>
                    </a>
                    <div class="text-start">
                        <h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1 text-start">Riwayat Transaksi</h2>
                        <p class="text-[12px] text-gray-400 font-medium text-start">Riwayat penjualan & pendapatan</p>
                    </div>
                </div>
                <button onclick="openExportModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-orange-50 text-[#FF6900] hover:bg-orange-100 transition-all text-start shadow-sm">
                    <i class="fa-solid fa-download text-sm text-start"></i>
                </button>
            </div>

            {{-- Body Kiri (Scrollable) --}}
            <div class="flex-1 overflow-y-auto hide-scrollbar px-10 py-8 space-y-8 text-start">
                
                {{-- Search & Filter Bar --}}
                <div class="flex items-center gap-4 text-start">
                    <div class="relative flex-1 text-start">
                        <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 text-start"></i>
                        <input type="text" placeholder="Cari berdasarkan ID pesanan atau nama..." class="w-full pl-14 pr-6 py-4 rounded-[24px] bg-white border border-gray-100 shadow-sm focus:outline-none focus:border-[#FF6900] font-bold text-gray-800 transition-all text-start text-start">
                    </div>
                    <button onclick="openFilter()" class="w-[58px] h-[58px] rounded-2xl border border-gray-100 bg-white flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all text-start shadow-sm">
                        <i class="fa-solid fa-sliders text-xl text-start"></i>
                    </button>
                </div>

                {{-- Dashboard Cards --}}
                <div class="grid grid-cols-2 gap-5 text-start">
                    <div class="bg-[#22c55e] rounded-[32px] p-8 text-white relative overflow-hidden shadow-lg shadow-green-100 text-start">
                         <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-6 text-start">
                            <i class="fa-solid fa-chart-line text-sm text-start"></i>
                         </div>
                         <p class="text-[11px] font-bold opacity-80 uppercase tracking-widest text-start mb-1">QRIS Terverifikasi</p>
                         <h3 class="text-3xl font-black text-start tracking-tight">Rp 290.000</h3>
                    </div>
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 flex items-center gap-6 text-start shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-[#22c55e] text-start">
                            <i class="fa-solid fa-receipt text-2xl text-start"></i>
                        </div>
                        <div class="text-start">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest text-start mb-1">Total Pesanan</p>
                            <h3 class="text-2xl font-black text-gray-800 text-start">4 <span class="text-xs font-bold text-[#22c55e]">Selesai</span></h3>
                        </div>
                    </div>
                </div>

                {{-- Time Tabs --}}
                <div class="flex bg-gray-100 p-1.5 rounded-[22px] w-fit text-start">
                    <button onclick="updateData('hari')" id="tab-hari" class="time-tab px-10 py-3 rounded-[18px] bg-white shadow-sm text-sm font-black text-gray-800 transition-all text-start">Hari Ini</button>
                    <button onclick="updateData('minggu')" id="tab-minggu" class="time-tab px-10 py-3 rounded-[18px] text-sm font-bold text-gray-400 transition-all text-start">Minggu Ini</button>
                    <button onclick="updateData('bulan')" id="tab-bulan" class="time-tab px-10 py-3 rounded-[18px] text-sm font-bold text-gray-400 transition-all text-start">Bulan Ini</button>
                </div>

                {{-- Transaction List --}}
                <div class="space-y-6 text-start">
                    <div class="flex items-center justify-between text-start">
                        <h4 class="font-black text-gray-800 text-start">Transaksi Terbaru</h4>
                        <div class="flex items-center gap-2 text-gray-400 text-start">
                            <i class="fa-regular fa-calendar text-xs text-start"></i>
                            <span class="text-xs font-bold text-start">Okt 25, 2023</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 text-start">
                        @php
                            $history = [
                                ['name' => 'Budi Santoso', 'id' => '001', 'item' => '2 item', 'price' => '45.000', 'status' => 'SELESAI', 'color' => '#22c55e'],
                                ['name' => 'Pratama', 'id' => '003', 'item' => '4 item', 'price' => '120.000', 'status' => 'SELESAI', 'color' => '#22c55e'],
                                ['name' => 'Megawati', 'id' => '004', 'item' => '1 item', 'price' => '15.000', 'status' => 'BATAL', 'color' => '#ef4444'],
                                ['name' => 'Andhika', 'id' => '005', 'item' => '3 item', 'price' => '75.000', 'status' => 'SELESAI', 'color' => '#22c55e'],
                            ];
                        @endphp

                        @foreach($history as $item)
                        <div class="transaction-card bg-white p-5 rounded-[28px] border border-gray-100 flex items-center justify-between transition-all duration-300 text-start shadow-sm">
                            <div class="flex items-center gap-4 text-start">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-start" style="background-color: {{ $item['color'] }}15; color: {{ $item['color'] }};">
                                    <i class="fa-solid fa-receipt text-start text-start"></i>
                                </div>
                                <div class="text-start">
                                    <p class="font-black text-gray-800 text-sm text-start">{{ $item['name'] }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold text-start uppercase tracking-wider">ORD-{{ $item['id'] }} • {{ $item['item'] }}</p>
                                </div>
                            </div>
                            <div class="text-right text-start">
                                <p class="font-black text-gray-800 text-sm text-start">Rp {{ $item['price'] }}</p>
                                <p class="text-[9px] font-black uppercase tracking-widest text-start" style="color: {{ $item['color'] }}">{{ $item['status'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- AREA KANAN: RINGKASAN --}}
        <div class="w-[360px] h-full bg-white flex flex-col p-10 shadow-[-10px_0_30px_rgba(0,0,0,0.02)] z-10 text-start border-l border-gray-100 flex-shrink-0">
            <h3 class="text-xl font-black text-gray-900 mb-8 text-start">Ringkasan Hari Ini</h3>
            
            <div class="space-y-6 text-start flex-1">
                <div class="bg-[#FFF8F3] p-8 rounded-[36px] border border-orange-100 text-start relative overflow-hidden group">
                    <div class="relative z-10 text-start">
                        <p class="text-[11px] font-black text-[#FF6900] uppercase tracking-widest mb-2 text-start">Pendapatan Total</p>
                        <h2 class="text-3xl font-black text-gray-900 text-start">Rp 290.000</h2>
                    </div>
                    <i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-7xl text-orange-500/5 rotate-12 text-start"></i>
                </div>

                <div class="bg-gray-50 p-8 rounded-[36px] border border-gray-100 text-start relative overflow-hidden">
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 text-start text-start">Total Pesanan</p>
                    <h2 class="text-2xl font-black text-gray-900 text-start">4 <span class="text-sm text-gray-400 font-bold ml-1 text-start">selesai</span></h2>
                    <i class="fa-solid fa-box-archive absolute -right-4 -bottom-4 text-7xl text-gray-900/5 rotate-12 text-start"></i>
                </div>
            </div>

            <button onclick="openExportModal()" class="w-full py-5 bg-[#1A1A1A] text-white rounded-[24px] font-black text-sm flex items-center justify-center gap-3 shadow-xl hover:bg-black transition-all text-start mt-auto">
                <i class="fa-solid fa-download text-start"></i> Export Laporan
            </button>
        </div>

    </main>
</div>

{{-- MODAL FILTER --}}
<div id="filterModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6 text-start">
    <div class="bg-white w-full max-w-sm rounded-[40px] p-10 shadow-2xl scale-95 transition-all text-start">
        <div class="flex items-center justify-between mb-8 text-start">
            <h3 class="text-xl font-black text-gray-900 text-start">Filter Transaksi</h3>
            <button onclick="closeFilter()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="space-y-6 text-start">
            <div class="text-start">
                <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">Status Transaksi</p>
                <div class="flex flex-wrap gap-2 text-start">
                    <button class="px-6 py-2.5 rounded-full border border-[#FF6900] bg-orange-50 text-[#FF6900] font-bold text-xs">Semua</button>
                    <button class="px-6 py-2.5 rounded-full border border-gray-100 bg-white text-gray-500 font-bold text-xs">Selesai</button>
                    <button class="px-6 py-2.5 rounded-full border border-gray-100 bg-white text-gray-500 font-bold text-xs">Batal</button>
                </div>
            </div>
            <button class="w-full py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-lg text-center">Terapkan</button>
        </div>
    </div>
</div>

{{-- MODAL EXPORT --}}
<div id="exportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6 text-start">
    <div class="bg-white w-full max-w-lg rounded-[40px] p-10 shadow-2xl text-start">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black text-gray-900">Ekspor Laporan</h3>
            <button onclick="closeExportModal()" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-8">
            <button class="p-6 rounded-3xl border-2 border-[#FF6900] bg-orange-50 flex flex-col items-center gap-3">
                <i class="fa-solid fa-file-pdf text-3xl text-[#FF6900]"></i>
                <span class="font-black text-xs">Dokumen PDF</span>
            </button>
            <button class="p-6 rounded-3xl border-2 border-gray-50 bg-white flex flex-col items-center gap-3 grayscale opacity-60">
                <i class="fa-solid fa-file-excel text-3xl text-gray-400"></i>
                <span class="font-black text-xs">Excel CSV</span>
            </button>
        </div>
        <button class="w-full py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm flex items-center justify-center gap-3 text-center">
            <i class="fa-solid fa-download"></i> Unduh PDF
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function updateData(time) {
        document.querySelectorAll('.time-tab').forEach(t => {
            t.classList.remove('bg-white', 'shadow-sm', 'text-gray-800', 'font-black');
            t.classList.add('text-gray-400', 'font-bold');
        });
        const active = document.getElementById('tab-' + time);
        active.classList.add('bg-white', 'shadow-sm', 'text-gray-800', 'font-black');
        active.classList.remove('text-gray-400', 'font-bold');
    }
    function openFilter() { document.getElementById('filterModal').classList.remove('hidden'); }
    function closeFilter() { document.getElementById('filterModal').classList.add('hidden'); }
    function openExportModal() { document.getElementById('exportModal').classList.remove('hidden'); }
    function closeExportModal() { document.getElementById('exportModal').classList.add('hidden'); }
</script>
@endpush