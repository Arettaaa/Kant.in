@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Kant.in')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Styling scrollbar halus */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #E5E7EB;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB;
        }

        .transaction-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            border-color: #FF6900;
        }

        /* Pill tab ringkas */
        .time-tab {
            flex: 1;
            padding: 10px 0;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 800;
            transition: all 0.3s ease;
            text-align: center;
        }

        .time-tab.active {
            background-color: white;
            color: #1A1A1A;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .time-tab.inactive {
            color: #9CA3AF;
        }

        .selectable-btn.active {
            border-color: #FF6900;
            background-color: #FFF8F3;
            color: #FF6900;
        }
    </style>
@endpush

@section('content')
    <div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

        {{-- SIDEBAR --}}
        @include('admin.partials.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] relative">

            {{-- HEADER --}}
            <div
                class="sticky top-0 z-20 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100 flex items-center justify-between text-start shadow-sm">
                <h2 class="text-2xl font-extrabold text-gray-900 leading-none">Riwayat Transaksi</h2>

                <div class="flex items-center gap-3">
                    {{-- Search Bar --}}
                    <div class="relative group">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#FF6900] transition-colors"></i>
                        <input type="text" id="searchInput" onkeyup="applyFilters()"
                            placeholder="Cari nama atau ID Pesanan..."
                            class="w-72 pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-[#FF6900] transition-all">
                    </div>

                    {{-- Tombol Filter --}}
                    <button onclick="toggleModal('filterModal')"
                        class="w-11 h-11 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 hover:text-[#FF6900] transition-all relative">
                        <i class="fa-solid fa-filter"></i>
                        <span id="filterBadge"
                            class="hidden absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>

                    {{-- Tombol Download --}}
                    <button onclick="toggleModal('exportModal')"
                        class="w-11 h-11 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-[#FF6900] hover:bg-orange-100 transition-all shadow-sm">
                        <i class="fa-solid fa-download"></i>
                    </button>
                </div>
            </div>

            {{-- AREA KONTEN --}}
            <div class="p-8 space-y-6">

                {{-- TOP ROW: Cards & Grafik --}}
                <div class="grid grid-cols-12 gap-6">

                    {{-- KIRI: Filter & Info Cards --}}
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                        {{-- Filter Pill Utama --}}
                        <div class="bg-gray-200/50 p-1.5 rounded-full flex w-full">
                            <button onclick="setPeriodeTab('day')" id="tab-day" class="time-tab active">Hari Ini</button>
                            <button onclick="setPeriodeTab('week')" id="tab-week" class="time-tab inactive">Minggu
                                Ini</button>
                            <button onclick="setPeriodeTab('month')" id="tab-month" class="time-tab inactive">Bulan
                                Ini</button>
                        </div>

                        {{-- Card Pendapatan --}}
                        <div
                            class="flex-1 bg-[#22c55e] rounded-[24px] p-6 text-white shadow-lg shadow-green-100/50 flex flex-col justify-center">
                            <p class="text-[10px] font-black opacity-80 uppercase tracking-widest mb-1">Total Pendapatan
                                Terverifikasi</p>
                            <h3 id="cardTotalRevenue" class="text-3xl font-black truncate">Memuat...</h3>
                        </div>

                        {{-- Card Pesanan --}}
                        <div
                            class="flex-1 bg-white rounded-[24px] p-6 border border-gray-100 flex items-center gap-4 shadow-sm">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-[#22c55e]">
                                <i class="fa-solid fa-receipt text-xl"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Total
                                    Pesanan</p>
                                <h3 class="text-xl font-black text-gray-800"><span id="cardTotalOrders">0</span> <span
                                        class="text-xs font-bold text-[#22c55e]">Selesai</span></h3>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: Grafik --}}
                    <div
                        class="col-span-12 lg:col-span-8 bg-white rounded-[24px] p-6 border border-gray-100 shadow-sm flex flex-col min-h-[300px]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-base font-black text-gray-900">Tren Pendapatan</h3>
                            <p id="chartTotalValue"
                                class="text-xs font-bold text-[#FF6900] bg-orange-50 px-3 py-1 rounded-full"></p>
                        </div>
                        <div class="flex-1 w-full relative">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- BOTTOM ROW: Daftar Transaksi --}}
                <div class="bg-white rounded-[24px] p-6 border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-black text-gray-900">Daftar Transaksi</h3>
                        <p class="text-xs text-gray-400 font-bold"><span id="visibleListCount">{{ count($orders) }}</span>
                            data ditemukan</p>
                    </div>

                    <div id="transactionGrid"
                        class="flex-1 overflow-y-auto hide-scrollbar grid grid-cols-1 xl:grid-cols-2 gap-4 content-start">
                        @forelse($orders as $order)
                            <a href="{{ route('admin.riwayat.detail', $order->_id) }}"
                                data-date="{{ \Carbon\Carbon::parse($order->created_at)->format('Y-m-d') }}"
                                data-status="{{ $order->status }}"
                                class="transaction-card bg-gray-50/50 p-4 rounded-[20px] border border-gray-100 flex items-center justify-between">

                                <div class="flex items-center gap-4">
                                    @if($order->status === 'completed')
                                        <div
                                            class="w-10 h-10 rounded-xl flex items-center justify-center bg-green-100 text-[#16a34a]">
                                            <i class="fa-solid fa-receipt text-sm"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-red-100 text-red-600">
                                            <i class="fa-solid fa-receipt text-sm"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="cust-name font-black text-gray-800 text-sm leading-tight">
                                            {{ $order->customer_snapshot['name'] ?? 'Pelanggan' }}</p>
                                        <p class="order-id text-[10px] text-gray-400 font-bold uppercase mt-0.5">
                                            {{ $order->order_code }} • {{ count($order->items) }} item</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-black text-gray-800 text-sm">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p
                                        class="text-[9px] font-black uppercase mt-0.5 {{ $order->status === 'completed' ? 'text-[#16a34a]' : 'text-red-600' }}">
                                        {{ $order->status === 'completed' ? 'SELESAI' : 'DIBATALKAN' }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-10 opacity-50">
                                <i class="fa-solid fa-receipt text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm font-bold text-gray-400">Belum ada riwayat transaksi</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Empty State jika filter tidak cocok --}}
                    <div id="emptySearch" class="hidden flex-col items-center justify-center py-10 opacity-50">
                        <i class="fa-solid fa-magnifying-glass text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm font-bold text-gray-400">Tidak ada transaksi yang sesuai filter</p>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- ================= MODAL FILTER ================= --}}
    <div id="filterModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6 text-start">
        <div class="bg-white w-full max-w-md rounded-[36px] p-8 shadow-2xl relative text-start">
            <button onclick="toggleModal('filterModal')"
                class="absolute right-6 top-6 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 transition-all"><i
                    class="fa-solid fa-xmark"></i></button>
            <h3 class="text-xl font-black text-gray-900 mb-1">Filter Transaksi</h3>
            <p class="text-xs text-gray-400 font-bold mb-6">Cari data spesifik yang ingin ditampilkan</p>

            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Status Transaksi</p>
                    <div class="flex flex-wrap gap-2" id="filterStatusGroup">
                        <button onclick="selectFilter('status', 'all', this)"
                            class="selectable-btn active px-4 py-2 rounded-xl border-2 border-[#FF6900] bg-[#FFF8F3] text-[#FF6900] font-bold text-xs">Semua</button>
                        <button onclick="selectFilter('status', 'completed', this)"
                            class="selectable-btn px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-500 font-bold text-xs hover:bg-gray-50">Selesai</button>
                        <button onclick="selectFilter('status', 'cancelled', this)"
                            class="selectable-btn px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-500 font-bold text-xs hover:bg-gray-50">Dibatalkan</button>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Rentang Tanggal (Kustom)
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Dari Tanggal</label>
                            <input type="date" id="filterStart"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]">
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Sampai Tanggal</label>
                            <input type="date" id="filterEnd"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]">
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">*Abaikan jika ingin menggunakan filter Hari/Minggu/Bulan di
                        halaman utama.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button onclick="resetFilters()"
                        class="flex-1 py-3.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-2xl font-black text-sm transition-all">Atur
                        Ulang</button>
                    <button onclick="applyFilters(); toggleModal('filterModal')"
                        class="flex-1 py-3.5 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-200 hover:brightness-110 transition-all">Terapkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL EXPORT ================= --}}
    <div id="exportModal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-6 text-start">
        <div class="bg-white w-full max-w-sm rounded-[36px] p-8 shadow-2xl relative text-start">
            <button onclick="toggleModal('exportModal')"
                class="absolute right-6 top-6 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-red-500 transition-all"><i
                    class="fa-solid fa-xmark"></i></button>
            <h3 class="text-xl font-black text-gray-900 mb-6">Unduh Laporan</h3>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-3" id="exportFormatGroup">
                    <button onclick="selectFilter('format', 'pdf', this)"
                        class="selectable-btn active p-5 rounded-2xl border-2 border-[#FF6900] bg-[#FFF8F3] flex flex-col items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-2xl text-[#FF6900]"></i>
                        <span class="font-black text-[10px] text-[#FF6900]">PDF Dokumen</span>
                    </button>
                    <button onclick="selectFilter('format', 'xlsx', this)"
                        class="selectable-btn p-5 rounded-2xl border border-gray-200 bg-white flex flex-col items-center gap-2 transition-all hover:bg-gray-50">
                        <i class="fa-solid fa-file-excel text-2xl text-[#16a34a]"></i>
                        <span class="font-black text-[10px] text-gray-500">Excel / CSV</span>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Mulai</label>
                        <input type="date" id="exportStart"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]"
                            required>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Selesai</label>
                        <input type="date" id="exportEnd"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold outline-none focus:border-[#FF6900]"
                            required>
                    </div>
                </div>

                <div id="exportError" class="hidden text-xs text-red-500 font-bold bg-red-50 p-3 rounded-xl text-center border border-red-100 transition-all">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> Mohon pilih tanggal Mulai dan Selesai!
                </div>

                <button onclick="downloadReport()"
                    class="w-full py-3.5 bg-[#FF6900] text-white rounded-2xl font-black text-sm shadow-lg shadow-orange-200 hover:brightness-110 flex items-center justify-center gap-2 transition-all">
                    <i class="fa-solid fa-download"></i> Unduh Sekarang
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let revenueChartInstance = null;
            
            // Global Filter State
            let state = {
                periode: 'day',
                status: 'all',
                startDate: '',
                endDate: '',
                exportFormat: 'pdf'
            };

            function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }

            // ── HELPER: PARSE TANGGAL LOKAL (Fix Bug Timezone UTC) ─────────────────
            // Fungsi ini memaksa tanggal string (YYYY-MM-DD) dibaca sebagai waktu lokal,
            // bukan standar UTC. Hal ini mencegah tanggal meleset/mundur 1 hari.
            function parseLocalDate(dateStr) {
                if (!dateStr) return null;
                let [y, m, d] = dateStr.split('-');
                return new Date(y, m - 1, d);
            }

            function syncStateToURL() {
                const url = new URL(window.location);
                url.searchParams.set('periode', state.periode);
                url.searchParams.set('status', state.status);
                
                if (state.startDate) url.searchParams.set('start_date', state.startDate);
                else url.searchParams.delete('start_date');
                
                if (state.endDate) url.searchParams.set('end_date', state.endDate);
                else url.searchParams.delete('end_date');
                
                window.history.replaceState({}, '', url);
            }

            function syncURLToState() {
                const params = new URLSearchParams(window.location.search);
                if(params.has('periode')) state.periode = params.get('periode');
                if(params.has('status')) state.status = params.get('status');
                if(params.has('start_date')) state.startDate = params.get('start_date');
                if(params.has('end_date')) state.endDate = params.get('end_date');

                document.getElementById('filterStart').value = state.startDate;
                document.getElementById('filterEnd').value = state.endDate;

                document.querySelectorAll('.time-tab').forEach(t => { 
                    t.classList.remove('active'); 
                    t.classList.add('inactive'); 
                }); 
                const activeTab = document.getElementById('tab-' + state.periode); 
                if(activeTab) {
                    activeTab.classList.add('active'); 
                    activeTab.classList.remove('inactive'); 
                }

                const statusGroup = document.getElementById('filterStatusGroup');
                if(statusGroup) {
                    statusGroup.querySelectorAll('.selectable-btn').forEach(btn => {
                        if (btn.getAttribute('onclick').includes("'" + state.status + "'")) {
                            btn.classList.add('active', 'border-[#FF6900]', 'bg-[#FFF8F3]', 'border-2');
                            btn.classList.remove('border-gray-200', 'bg-white', 'border');
                            btn.classList.replace('text-gray-500', 'text-[#FF6900]');
                        } else {
                            btn.classList.remove('active', 'border-[#FF6900]', 'bg-[#FFF8F3]', 'border-2');
                            btn.classList.add('border-gray-200', 'bg-white', 'border');
                            btn.classList.replace('text-[#FF6900]', 'text-gray-500');
                        }
                    });
                }
            }

            function selectFilter(type, value, btnElement) {
                state[type === 'format' ? 'exportFormat' : type] = value;
                const group = btnElement.parentElement;
                group.querySelectorAll('.selectable-btn').forEach(b => {
                    b.classList.remove('active', 'border-[#FF6900]', 'bg-[#FFF8F3]', 'border-2');
                    b.classList.add('border-gray-200', 'bg-white', 'border');
                    if (type === 'format') {
                        b.querySelector('span').classList.replace('text-[#FF6900]', 'text-gray-500');
                    } else {
                        b.classList.replace('text-[#FF6900]', 'text-gray-500');
                    }
                });
                
                btnElement.classList.add('active', 'border-[#FF6900]', 'bg-[#FFF8F3]', 'border-2');
                btnElement.classList.remove('border-gray-200', 'bg-white', 'border');
                if (type === 'format') {
                    btnElement.querySelector('span').classList.replace('text-gray-500', 'text-[#FF6900]');
                } else {
                    btnElement.classList.replace('text-gray-500', 'text-[#FF6900]');
                }
            }

            function setPeriodeTab(periode) {
                state.periode = periode;
                document.getElementById('filterStart').value = '';
                document.getElementById('filterEnd').value = '';
                state.startDate = ''; 
                state.endDate = '';

                document.querySelectorAll('.time-tab').forEach(t => { 
                    t.classList.remove('active'); 
                    t.classList.add('inactive'); 
                }); 
                const active = document.getElementById('tab-' + periode); 
                if(active) {
                    active.classList.add('active'); 
                    active.classList.remove('inactive'); 
                }
                applyFilters();
            }

            function resetFilters() {
                document.getElementById('filterStart').value = '';
                document.getElementById('filterEnd').value = '';
                document.getElementById('searchInput').value = '';
                selectFilter('status', 'all', document.querySelector('#filterStatusGroup .selectable-btn'));
                setPeriodeTab('day'); 
                toggleModal('filterModal');
            }

            function applyFilters() {
                state.startDate = document.getElementById('filterStart').value;
                state.endDate = document.getElementById('filterEnd').value;

                const badge = document.getElementById('filterBadge');
                if(state.status !== 'all' || state.startDate !== '') {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }

                let url = `/admin/riwayat/chart-data?type=${state.periode}`;
                if (state.startDate && state.endDate) {
                    url = `/admin/riwayat/chart-data?start_date=${state.startDate}&end_date=${state.endDate}`;
                    document.querySelectorAll('.time-tab').forEach(t => { t.classList.remove('active'); t.classList.add('inactive'); }); 
                }

                fetch(url)
                    .then(res => res.json())
                    .then(res => {
                        if(res.success) {
                            drawChart(res.data.labels, res.data.revenue);
                            document.getElementById('chartTotalValue').innerText = 'Total: Rp ' + res.data.total_revenue.toLocaleString('id-ID');
                            document.getElementById('cardTotalRevenue').innerText = 'Rp ' + res.data.total_revenue.toLocaleString('id-ID');
                            document.getElementById('cardTotalOrders').innerText = res.data.total_orders;
                        }
                    });

                let input = document.getElementById('searchInput').value.toLowerCase(); 
                let cards = document.getElementsByClassName('transaction-card'); 
                let visibleCount = 0;

                let now = new Date();
                let filterStart, filterEnd;
                
                // MENGGUNAKAN HELPER parseLocalDate AGAR TIDAK TERKENA BUG ZONA WAKTU
                let customStart = state.startDate ? parseLocalDate(state.startDate).setHours(0,0,0,0) : null;
                let customEnd = state.endDate ? parseLocalDate(state.endDate).setHours(23,59,59,999) : null;

                if (customStart && customEnd) {
                    filterStart = customStart;
                    filterEnd = customEnd;
                } else {
                    if (state.periode === 'week') {
                        let day = now.getDay();
                        let diff = now.getDate() - day + (day === 0 ? -6 : 1);
                        filterStart = new Date(now.setDate(diff)).setHours(0,0,0,0);
                        filterEnd = new Date(filterStart + 6 * 24 * 60 * 60 * 1000).setHours(23,59,59,999);
                    } else if (state.periode === 'month') {
                        filterStart = new Date(now.getFullYear(), now.getMonth(), 1).setHours(0,0,0,0);
                        filterEnd = new Date(now.getFullYear(), now.getMonth() + 1, 0).setHours(23,59,59,999);
                    } else { 
                        filterStart = new Date().setHours(0,0,0,0);
                        filterEnd = new Date().setHours(23,59,59,999);
                    }
                }

                for (let card of cards) { 
                    let name = card.querySelector('.cust-name').innerText.toLowerCase(); 
                    let orderId = card.querySelector('.order-id').innerText.toLowerCase(); 
                    let status = card.getAttribute('data-status');
                    let dateStr = card.getAttribute('data-date');
                    
                    // MENGGUNAKAN HELPER parseLocalDate
                    let orderDate = parseLocalDate(dateStr).getTime();

                    let matchSearch = name.includes(input) || orderId.includes(input);
                    let matchStatus = (state.status === 'all') || (status === state.status);
                    let matchDate = (orderDate >= filterStart && orderDate <= filterEnd);

                    if (matchSearch && matchStatus && matchDate) {
                        card.style.display = "flex";
                        visibleCount++;
                    } else {
                        card.style.display = "none";
                    }
                } 
                
                document.getElementById('visibleListCount').innerText = visibleCount;
                const emptyState = document.getElementById('emptySearch');
                if(visibleCount === 0) {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                } else {
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                }

                syncStateToURL();
            }

            function drawChart(labels, data) {
                const ctx = document.getElementById('revenueChart').getContext('2d');
                if (revenueChartInstance) revenueChartInstance.destroy();

                revenueChartInstance = new Chart(ctx, {
                    type: 'bar', 
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: '#FF6900',
                            borderRadius: 6,
                            borderSkipped: false,
                            barPercentage: 0.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { borderDash: [4, 4], color: '#F3F4F6' },
                                ticks: {
                                    callback: function(value) {
                                        if (value === 0) return 'Rp 0';
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                        if (value >= 1000) return 'Rp ' + (value / 1000) + 'k';
                                        return 'Rp ' + value;
                                    },
                                    font: { size: 10, weight: 'bold' }, color: '#9CA3AF',
                                    stepSize: 5000 
                                }
                            },
                            x: {
                                border: { display: false }, grid: { display: false },
                                ticks: { font: { size: 10, weight: 'bold' }, color: '#9CA3AF' }
                            }
                        }
                    }
                });
            }

            function downloadReport() {
                let sd = document.getElementById('exportStart').value;
                let ed = document.getElementById('exportEnd').value;
                let errorNotif = document.getElementById('exportError');
                
                if(!sd || !ed) { 
                    errorNotif.classList.remove('hidden'); 
                    setTimeout(() => {
                        errorNotif.classList.add('hidden');
                    }, 3000);
                    
                    return; 
                }
                
                errorNotif.classList.add('hidden'); // Sembunyikan error jika sukses
                window.location.href = `/admin/riwayat/export?format=${state.exportFormat}&start_date=${sd}&end_date=${ed}`;
                toggleModal('exportModal');
            }

            document.addEventListener('DOMContentLoaded', () => {
                syncURLToState(); 
                applyFilters(); 
            });
        </script>
    @endpush
@endsection