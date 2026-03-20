@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .transaction-card { transition: all 0.2s ease; cursor: pointer; }
    .transaction-card:hover { transform: translateY(-2px); border-color: #FF6900; }

    /* Style Kapsul Pill sesuai gambar */
    .time-filter-container {
        background-color: #F3F4F6;
        padding: 6px;
        border-radius: 99px;
        display: inline-flex;
        gap: 4px;
        margin-top: 20px;
    }
    .time-tab { padding: 10px 32px; border-radius: 99px; font-size: 14px; font-weight: 800; transition: all 0.3s ease; }
    .time-tab.active { background-color: white; color: #1A1A1A; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .time-tab.inactive { color: #9CA3AF; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR --}}
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-store text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kantin</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/admin/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg> Pesanan</a>
            <a href="/admin/menu" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg> Kelola Menu</a>
            <a href="/admin/riwayat" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg> Riwayat Transaksi</a>
            <a href="/admin/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Profil Kantin</a>
        </nav>
    </aside>

    <main class="flex-1 flex overflow-hidden">
        {{-- KIRI --}}
        <div class="flex-1 flex flex-col h-full bg-[#F9FAFB] border-r border-gray-100 overflow-hidden text-start">
            <div class="px-10 py-6 bg-white border-b border-gray-100 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <a href="/admin/pesanan" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all"><i class="fa-solid fa-arrow-left text-gray-400"></i></a>
                    <div><h2 class="text-xl font-extrabold text-gray-900 leading-none mb-1">Riwayat Transaksi</h2><p class="text-[12px] text-gray-400 font-medium">Lacak semua pendapatan masuk</p></div>
                </div>
                <button onclick="toggleModal('exportModal')" class="w-10 h-10 flex items-center justify-center rounded-full bg-orange-50 text-[#FF6900] hover:bg-orange-100 transition-all shadow-sm"><i class="fa-solid fa-download text-sm"></i></button>
            </div>

            <div class="flex-1 overflow-y-auto hide-scrollbar px-10 py-8 space-y-8">
                {{-- Dashboard Cards --}}
                <div class="grid grid-cols-2 gap-5">
                    <div class="bg-[#22c55e] rounded-[32px] p-6 text-white shadow-lg shadow-green-100"><p class="text-[10px] font-black opacity-80 uppercase tracking-widest mb-1">QRIS Terverifikasi</p><h3 class="text-2xl font-black">Rp 290.000</h3></div>
                    <div class="bg-white rounded-[32px] p-6 border border-gray-100 flex items-center gap-5 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-[#22c55e]"><i class="fa-solid fa-receipt text-xl"></i></div>
                        <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p><h3 class="text-xl font-black text-gray-800">4 <span class="text-xs font-bold text-[#22c55e]">Selesai</span></h3></div>
                    </div>
                </div>

                {{-- PILL TAB POSISI DI BAWAH CARD --}}
                <div class="time-filter-container">
                    <button onclick="updateTimeTab('hari')" id="tab-hari" class="time-tab active">Hari Ini</button>
                    <button onclick="updateTimeTab('minggu')" id="tab-minggu" class="time-tab inactive">Minggu Ini</button>
                    <button onclick="updateTimeTab('bulan')" id="tab-bulan" class="time-tab inactive">Bulan Ini</button>
                </div>

                {{-- SEARCH BAR --}}
                <div class="flex items-center gap-4 mt-8">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-6 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        <input type="text" id="searchInput" onkeyup="searchTransaksi()" placeholder="Cari berdasarkan nama pelanggan..." class="w-full pl-14 pr-6 py-3.5 rounded-2xl bg-white border border-gray-100 shadow-sm focus:border-[#FF6900] outline-none font-bold text-gray-800 transition-all">
                    </div>
                    <button onclick="toggleModal('filterModal')" class="w-[54px] h-[54px] rounded-2xl border border-gray-100 bg-white flex items-center justify-center text-gray-400 hover:text-[#FF6900] transition-all shadow-sm"><i class="fa-solid fa-filter text-xl" style="color: rgb(203, 203, 203);"></i></button>
                </div>

                {{-- LIST TRANSAKSI --}}
                <div class="space-y-6">
                    <h4 class="font-black text-gray-800">Transaksi Terbaru</h4>
                    <div id="transactionGrid" class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @php $histories = [
                            ['name' => 'Budi Santoso', 'id' => '001', 'item' => '2 item', 'price' => '45.000', 'status' => 'SELESAI', 'color' => '#22c55e', 'link' => '/admin/riwayat/detail'],
                            ['name' => 'Pratama', 'id' => '003', 'item' => '4 item', 'price' => '120.000', 'status' => 'SELESAI', 'color' => '#22c55e', 'link' => '/admin/riwayat/detail'],
                            ['name' => 'Megawati', 'id' => '004', 'item' => '1 item', 'price' => '15.000', 'status' => 'DIBATALKAN', 'color' => '#ef4444', 'link' => '/admin/pesanan/cancel'],
                        ]; @endphp

                        @foreach($histories as $h)
                        <a href="{{ $h['link'] }}?name={{ urlencode($h['name']) }}&order=ORD-{{ $h['id'] }}&status={{ $h['status'] }}" 
                           class="transaction-card bg-white p-4 rounded-[28px] border border-gray-100 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center" style="background-color: {{ $h['color'] }}10; color: {{ $h['color'] }};"><i class="fa-solid fa-receipt text-sm"></i></div>
                                <div>
                                    <p class="cust-name font-black text-gray-800 text-sm leading-tight">{{ $h['name'] }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">ORD-{{ $h['id'] }} • {{ $h['item'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-gray-800 text-sm">Rp {{ $h['price'] }}</p>
                                <p class="text-[9px] font-black uppercase" style="color: {{ $h['color'] }}">{{ $h['status'] }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- AREA KANAN --}}
        <div class="w-[340px] h-full bg-white flex flex-col p-9 shadow-[-10px_0_30px_rgba(0,0,0,0.015)] z-10 border-l border-gray-100 flex-shrink-0 text-start">
            <h3 class="text-lg font-black text-gray-900 mb-8">Ringkasan Hari Ini</h3>
            <div class="space-y-6 flex-1">
                <div class="bg-[#FFF8F3] p-8 rounded-[32px] border border-orange-100 relative overflow-hidden"><p class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mb-1 relative z-10">Pendapatan Total</p><h2 class="text-2xl font-black text-gray-900 relative z-10">Rp 290.000</h2><i class="fa-solid fa-wallet absolute -right-4 -bottom-4 text-7xl text-orange-500/5 rotate-12"></i></div>
                <div class="bg-gray-50 p-8 rounded-[32px] border border-gray-100 relative overflow-hidden"><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 relative z-10">Total Pesanan</p><h2 class="text-2xl font-black text-gray-900 relative z-10">4 <span class="text-xs text-gray-400 font-bold ml-1">selesai</span></h2><i class="fa-solid fa-box-archive absolute -right-4 -bottom-4 text-7xl text-gray-900/5 rotate-12"></i></div>
            </div>
            <button onclick="toggleModal('exportModal')" class="w-full py-4 bg-[#1A1A1A] text-white rounded-2xl font-black text-sm flex items-center justify-center gap-3 shadow-xl hover:bg-black transition-all mt-auto"><i class="fa-solid fa-download"></i> Export Laporan</button>
        </div>
    </main>
</div>

{{-- MODAL FILTER SESUAI GAMBAR (Tanpa Menunggu) --}}
<div id="filterModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6">
    <div class="bg-white w-full max-w-sm rounded-[36px] p-10 shadow-2xl relative">
        <button onclick="toggleModal('filterModal')" class="absolute right-8 top-8 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
        <h3 class="text-xl font-black text-gray-900 leading-tight mb-1 text-start">Filter Transaksi</h3>
        <p class="text-xs text-gray-400 font-bold mb-8 text-start">Cari data yang ingin kamu lihat</p>
        
        <div class="space-y-8 text-start">
            <div>
                <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest mb-4">STATUS</p>
                <div class="flex flex-wrap gap-2">
                    <button class="px-5 py-2.5 rounded-2xl border-2 border-[#22C55E] bg-[#F0FDF4] text-[#166534] font-black text-xs">Semua</button>
                    <button class="px-5 py-2.5 rounded-2xl border border-gray-100 bg-white text-gray-500 font-bold text-xs">Selesai</button>
                    <button class="px-5 py-2.5 rounded-2xl border border-gray-100 bg-white text-gray-500 font-bold text-xs">Dibatalkan</button>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-sm">Atur Ulang</button>
                <button class="flex-2 px-10 py-4 bg-[#22C55E] text-white rounded-2xl font-black text-sm shadow-lg shadow-green-100 flex items-center gap-2"><i class="fa-solid fa-check"></i> Terapkan</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EXPORT --}}
<div id="exportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6">
    <div class="bg-white w-full max-w-md rounded-[36px] p-10 shadow-2xl">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-black text-gray-900 leading-tight text-start">Unduh Laporan</h3>
            <button onclick="toggleModal('exportModal')" class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <button class="p-6 rounded-3xl border-2 border-[#FF6900] bg-[#FFF8F3] flex flex-col items-center gap-3"><i class="fa-solid fa-file-pdf text-3xl text-[#FF6900]"></i><span class="font-black text-[11px] text-[#FF6900]">PDF Dokumen</span></button>
                <button class="p-6 rounded-3xl border-2 border-gray-50 bg-white flex flex-col items-center gap-3 grayscale opacity-60"><i class="fa-solid fa-file-excel text-3xl text-gray-400"></i><span class="font-black text-[11px] text-gray-400">CSV Excel</span></button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block text-start">Mulai</label><input type="date" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]"></div>
                <div><label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block text-start">Selesai</label><input type="date" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]"></div>
            </div>
            <button class="w-full py-4 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-200 hover:brightness-110 flex items-center justify-center gap-3">Unduh Laporan</button>
        </div>
    </div>
</div>

<script>
    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

    function updateTimeTab(time) {
        document.querySelectorAll('.time-tab').forEach(t => { t.classList.remove('active'); t.classList.add('inactive'); });
        const active = document.getElementById('tab-' + time);
        active.classList.add('active'); active.classList.remove('inactive');
    }

    // LOGIKA SEARCH BAR
    function searchTransaksi() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('transaction-card');
        
        for (let i = 0; i < cards.length; i++) {
            let name = cards[i].querySelector('.cust-name').innerText.toLowerCase();
            if (name.includes(input)) {
                cards[i].style.display = "";
            } else {
                cards[i].style.display = "none";
            }
        }
    }
</script>
@endsection