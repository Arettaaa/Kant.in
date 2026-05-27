@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Kant.in')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .transaction-card { transition: all 0.2s ease; cursor: pointer; }
    .transaction-card:hover { transform: translateY(-2px); border-color: #FF6900; }

    .time-filter-container {
        background-color: #F3F4F6;
        padding: 6px;
        border-radius: 99px;
        display: flex; 
        width: 100%;   
        gap: 4px;
        margin-bottom: 20px;
    }
    .time-tab { 
        flex: 1;       
        padding: 12px 0; 
        border-radius: 99px; 
        font-size: 14px; 
        font-weight: 800; 
        transition: all 0.3s ease; 
        text-align: center;
    }
    .time-tab.active { background-color: white; color: #1A1A1A; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .time-tab.inactive { color: #9CA3AF; }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- SIDEBAR --}}
    @include('admin.partials.sidebar')

    <main class="flex-1 flex overflow-hidden">
        <div class="flex-1 flex flex-col h-full bg-[#F9FAFB] border-r border-gray-100 overflow-hidden text-start">
            
            {{-- HEADER BARU (Search di atas) --}}
            <div class="px-10 py-6 bg-white border-b border-gray-100 flex items-center justify-between sticky top-0 z-10 text-start">
                <div class="flex items-center gap-4 text-start">
                    <h2 class="text-2xl font-extrabold text-gray-900 leading-none">Riwayat Transaksi</h2>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Search Bar dipindah ke sini --}}
                    <div class="relative group">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 focus-within:text-[#FF6900]"></i>
                        <input type="text" id="searchInput" onkeyup="searchTransaksi()" placeholder="Cari nama pelanggan..." class="w-64 pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-semibold text-gray-800 focus:outline-none focus:bg-white focus:border-[#FF6900] transition-all">
                    </div>
                    
                    <button onclick="toggleModal('filterModal')" class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 hover:text-[#FF6900] transition-all">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    
                    <button onclick="toggleModal('exportModal')" class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-[#FF6900] hover:bg-orange-100 transition-all shadow-sm">
                        <i class="fa-solid fa-download"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto hide-scrollbar px-10 py-8 space-y-8">
                
                {{-- SUMMARY CARDS --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 text-start">
                    <div class="lg:col-span-2 bg-[#22c55e] rounded-[32px] p-8 text-white shadow-lg shadow-green-100 flex flex-col justify-center">
                        <p class="text-xs font-black opacity-80 uppercase tracking-widest mb-1">Total Pendapatan Terverifikasi</p>
                        <h3 class="text-4xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="lg:col-span-1 bg-white rounded-[32px] p-8 border border-gray-100 flex items-center gap-5 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-[#22c55e]"><i class="fa-solid fa-receipt text-2xl"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                            <h3 class="text-2xl font-black text-gray-800">{{ $totalOrders }} <span class="text-sm font-bold text-[#22c55e]">Selesai</span></h3>
                        </div>
                    </div>
                </div>

                {{-- PILL TAB PERIODE (Disesuaikan dengan Controller in:7,30,90) --}}
                <div class="time-filter-container">
                    <button onclick="updateChart(7, 'tab-7')" id="tab-7" class="time-tab active">7 Hari Terakhir</button>
                    <button onclick="updateChart(30, 'tab-30')" id="tab-30" class="time-tab inactive">30 Hari Terakhir</button>
                    <button onclick="updateChart(90, 'tab-90')" id="tab-90" class="time-tab inactive">3 Bulan Terakhir</button>
                </div>

                {{-- AREA GRAFIK (Dari Controller API) --}}
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm mb-8 w-full">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-black text-gray-900">Tren Pendapatan</h3>
                        <p id="chartTotalValue" class="text-sm font-bold text-[#FF6900]"></p>
                    </div>
                    <div class="w-full h-72">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- LIST TRANSAKSI --}}
                <div class="space-y-6 text-start">
                    <h4 class="font-black text-gray-800">Transaksi Terbaru</h4>
                    <div id="transactionGrid" class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @forelse($orders as $order)
                        <a href="{{ route('admin.riwayat.detail', $order->_id) }}" class="transaction-card bg-white p-4 rounded-[28px] border border-gray-100 flex items-center justify-between shadow-sm">
                            <div class="flex items-center gap-4">
                                @if($order->status === 'completed')
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-green-50 text-[#22c55e]"><i class="fa-solid fa-check text-sm"></i></div>
                                @else
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center bg-red-50 text-red-500"><i class="fa-solid fa-xmark text-sm"></i></div>
                                @endif
                                <div>
                                    <p class="cust-name font-black text-gray-800 text-sm leading-tight">{{ $order->customer_snapshot['name'] ?? 'Pelanggan' }}</p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">{{ $order->order_code }} • {{ count($order->items) }} item</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-gray-800 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <p class="text-[9px] font-black uppercase {{ $order->status === 'completed' ? 'text-[#22c55e]' : 'text-red-500' }}">
                                    {{ $order->status === 'completed' ? 'SELESAI' : 'DIBATALKAN' }}
                                </p>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-2 text-center py-10 text-gray-400 font-medium">Belum ada riwayat transaksi.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- MODALS (Tetap dipertahankan) --}}
{{-- ... kode modal export dan filter milikmu sebelumnya biarkan di sini agar tidak hilang ... --}}

@push('scripts')
<script>
    // -------------------------------------------------------------
    // LOGIKA GRAFIK MENGGUNAKAN API CONTROLLER
    // -------------------------------------------------------------
    let revenueChartInstance = null;

    // Fungsi untuk mengambil data dari API dan menggambar ulang grafik
    function updateChart(periode, tabId) {
        // 1. Ubah tampilan Tab yang aktif
        document.querySelectorAll('.time-tab').forEach(t => { 
            t.classList.remove('active'); 
            t.classList.add('inactive'); 
        }); 
        const active = document.getElementById(tabId); 
        active.classList.add('active'); 
        active.classList.remove('inactive'); 

        // 2. Fetch API AJAX dari Controller
        fetch(`/admin/riwayat/chart-data?periode=${periode}`)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    drawChart(res.data.labels, res.data.revenue);
                    // Tampilkan total di pojok grafik
                    document.getElementById('chartTotalValue').innerText = 'Total: Rp ' + res.data.total_revenue.toLocaleString('id-ID');
                }
            })
            .catch(error => console.error("Error fetching chart data:", error));
    }

    // Fungsi menggambar Chart.js
    function drawChart(labels, data) {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Hancurkan grafik lama jika sudah ada (mencegah tumpang tindih)
        if (revenueChartInstance) {
            revenueChartInstance.destroy();
        }

        // Buat grafik baru
        revenueChartInstance = new Chart(ctx, {
            type: 'bar', // Menggunakan grafik batang
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    backgroundColor: '#FF6900', // Warna orange Kantin
                    borderRadius: 8, // Ujung batang melengkung
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Sembunyikan legenda
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5] },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Muat grafik otomatis saat halaman pertama kali dibuka (Default: 7 hari terakhir)
    document.addEventListener('DOMContentLoaded', () => {
        updateChart(7, 'tab-7');
    });

    // -------------------------------------------------------------
    // LOGIKA PENCARIAN & MODAL
    // -------------------------------------------------------------
    function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
    
    function searchTransaksi() { 
        let input = document.getElementById('searchInput').value.toLowerCase(); 
        let cards = document.getElementsByClassName('transaction-card'); 
        for (let i = 0; i < cards.length; i++) { 
            let name = cards[i].querySelector('.cust-name').innerText.toLowerCase(); 
            cards[i].style.display = name.includes(input) ? "flex" : "none"; 
        } 
    }
</script>
@endpush
@endsection