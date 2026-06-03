@extends('layouts.app')

@section('title', 'Dasbor Global Admin - Kant.in')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    .stat-card {
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    /* Memastikan Canvas chart responsif */
    #performanceChart {
        width: 100% !important;
        height: 100% !important;
    }

    /* Bell Dropdown */
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

    .notif-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

  {{-- ======================== SIDEBAR ======================== --}}
    @include('admin_global.partials.sidebar')

    {{-- ======================== MAIN ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col">

        {{-- Header --}}
        @include('admin_global.partials.topbar')

        <div class="p-10 space-y-10 text-start">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-start">

                {{-- ✅ KARTU TOTAL PENDAPATAN --}}
                <div
                    class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start">
                    <div class="relative z-10 text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start">TOTAL
                            PENDAPATAN</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </h3>
                        <div class="mt-4 flex items-center gap-2 text-start">
                        @if($revenueTrend == 'up')
                            <span class="text-[10px] font-black px-2.5 py-1 bg-green-50 text-[#22C55E] rounded-lg">
                                +{{ $revenuePercentage }}%
                            </span>
                        @elseif($revenueTrend == 'down')
                            <span class="text-[10px] font-black px-2.5 py-1 bg-red-50 text-red-500 rounded-lg">
                                -{{ $revenuePercentage }}%
                            </span>
                        @elseif($revenueTrend == 'none')
                            <span class="text-[10px] font-black px-2.5 py-1 bg-gray-50 text-gray-400 rounded-lg">
                                —
                            </span>
                        @else
                            <span class="text-[10px] font-black px-2.5 py-1 bg-gray-50 text-gray-500 rounded-lg">
                                {{ $revenuePercentage }}%
                            </span>
                        @endif

                        {{-- Label pembanding juga dinamis --}}
                        <span class="text-[10px] font-bold text-gray-300 italic">
                            @switch($periode)
                                @case('hari') Vs kemarin @break
                                @case('minggu') Vs minggu lalu @break
                                @case('bulan_lalu') Vs 2 bulan lalu @break
                                @case('tahun') Vs tahun lalu @break
                                @case('tahun_lalu') Vs 2 tahun lalu @break
                                @case('semua') @break
                                @default Vs bulan lalu
                            @endswitch
                        </span>
                        </div>
                    </div>
                    <i class="fa-solid fa-wallet absolute -right-6 -bottom-6 text-8xl text-gray-50 opacity-50"></i>
                </div>

                <div
                    class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start">
                    <div class="relative z-10 text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start">TOTAL
                            PESANAN</p>
                        <div class="flex items-center gap-4 text-start">
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start">
                                {{ number_format($totalPesanan, 0, ',', '.') }}
                            </h3>
                            <div
                                class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-start">
                                <i class="fa-solid fa-receipt text-lg text-start"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-300 mt-4 italic text-start">Berdasarkan Kantin Aktif
                            Bulan Ini</p>
                    </div>
                </div>

                {{-- ✅ KARTU KANTIN AKTIF --}}
                <div
                    class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start">
                    <div class="relative z-10 text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start">
                            KANTIN AKTIF</p>
                        <div class="flex items-center gap-4 text-start">
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start">
                                {{ $kantinAktif }}
                            </h3>
                            <div
                                class="w-10 h-10 bg-green-50 text-[#22C55E] rounded-xl flex items-center justify-center text-start">
                                <i class="fa-solid fa-shop text-lg text-start"></i>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-300 mt-4 italic text-start">
                            {{ $kantinPending }} Mitra menunggu verifikasi
                        </p>
                    </div>
                </div>
            </div>

           <div class="bg-white p-10 rounded-[44px] border border-gray-100 shadow-sm text-start">
    <div class="flex justify-between items-center mb-6 text-start">
        <div class="text-start">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter" id="chartTitle">
                Kantin Performa Terbaik
            </h3>
            <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-widest" id="chartSubtitle">
                Volume penjualan berdasarkan total pesanan selesai
            </p>
        </div>

        <div class="flex items-center gap-3">

            <div class="relative" id="chartTypeContainer">
                    <div onclick="toggleChartType()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-gray-50 text-gray-600 rounded-full text-[10px] font-black tracking-widest uppercase border border-gray-200 cursor-pointer select-none">
                        <i class="fa-solid fa-chart-bar text-[10px]" id="chartTypeIcon"></i>
                        <span id="chartTypeLabel">Bar Chart</span>
                        <i class="fa-solid fa-chevron-down text-[10px]" id="chartTypeChevron"></i>
                    </div>
                    <div id="chartTypeDropdown"
                    class="hidden absolute top-full right-0 mt-2 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 py-2">
                    <button onclick="switchChart('bar')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Bar — Performa Kantin
                    </button>
                    <button onclick="switchChart('line-revenue')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Line — Tren Pendapatan
                    </button>
                    <button onclick="switchChart('line-orders')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Line — Tren Pesanan
                    </button>
                    <button onclick="switchChart('line-both')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Line — Pendapatan & Pesanan
                    </button>
                    <button onclick="switchChart('donut')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Donut — Proporsi per Kantin
                    </button>
                    <button onclick="switchChart('bar-horiz')"
                        class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">
                        Bar — Ranking Pendapatan
                    </button>
                </div>
                </div>

                {{-- Dropdown periode --}}
                <div class="relative" id="dashDateContainer">
                    <div onclick="toggleDashDate()"
                        class="flex items-center gap-2 px-5 py-2.5 bg-orange-50 text-[#FF6900] rounded-full text-[10px] font-black tracking-widest uppercase border border-orange-100 cursor-pointer select-none">
                        {{ $labelPeriode }}
                        <i class="fa-solid fa-chevron-down text-[10px]" id="dashChevron"></i>
                    </div>
                    <div id="dashDateDropdown"
                        class="hidden absolute top-full right-0 mt-2 w-44 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 py-2">
                        <a href="?periode=hari"       class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Hari Ini</a>
                        <a href="?periode=minggu"     class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Minggu Ini</a>
                        <a href="?periode=bulan"      class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Bulan Ini</a>
                        <a href="?periode=bulan_lalu" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Bulan Lalu</a>
                        <a href="?periode=tahun"      class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Tahun Ini</a>
                        <a href="?periode=tahun_lalu" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Tahun Lalu</a>
                        <a href="?periode=semua"      class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Semua Periode</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[400px] w-full">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>

        </div>
    </main>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dateEl = document.getElementById('realtimeDate');
    if (dateEl) {
        dateEl.innerText = new Date().toLocaleDateString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    }

    // ── DATA DARI BLADE ──────────────────────────────
    const barLabels    = {!! json_encode($chartLabels ?? []) !!};
    const barData      = {!! json_encode($chartData ?? []) !!};
    const tlLabels     = {!! json_encode($timelineLabels ?? []) !!};
    const tlRevenue    = {!! json_encode($timelineRevenue ?? []) !!};
    const tlOrders     = {!! json_encode($timelineOrders ?? []) !!};
    const donutLabels  = {!! json_encode($donutLabels ?? []) !!};
    const donutData    = {!! json_encode($donutData ?? []) !!};
    const horizLabels  = {!! json_encode($horizLabels ?? []) !!};
    const horizRevenue = {!! json_encode($horizRevenue ?? []) !!};

    // ── GRADIENTS ────────────────────────────────────
    const ctx = document.getElementById('performanceChart').getContext('2d');

    const gradBar = ctx.createLinearGradient(0, 0, 0, 400);
    gradBar.addColorStop(0, '#FF6900');
    gradBar.addColorStop(1, '#FF9F59');

    const gradRevenue = ctx.createLinearGradient(0, 0, 0, 400);
    gradRevenue.addColorStop(0, 'rgba(255,105,0,0.25)');
    gradRevenue.addColorStop(1, 'rgba(255,105,0,0)');

    const gradOrders = ctx.createLinearGradient(0, 0, 0, 400);
    gradOrders.addColorStop(0, 'rgba(59,130,246,0.25)');
    gradOrders.addColorStop(1, 'rgba(59,130,246,0)');

    // ── HELPER UPDATE HEADER ─────────────────────────
    function setHeader(title, subtitle, label) {
        document.getElementById('chartTitle').innerText     = title;
        document.getElementById('chartSubtitle').innerText  = subtitle;
        document.getElementById('chartTypeLabel').innerText = label;
    }

    // ── BASE SCALE OPTIONS ───────────────────────────
    function scaleXY(formatY = false) {
        return {
            y: {
                beginAtZero: true,
                grid: { color: '#F3F4F6', drawBorder: false },
                ticks: {
                    font: { weight: '800', size: 10 }, color: '#9CA3AF',
                    callback: v => formatY
                        ? (v >= 1_000_000 ? 'Rp '+(v/1_000_000).toFixed(1)+'jt'
                            : v >= 1_000 ? 'Rp '+(v/1_000).toFixed(0)+'rb' : 'Rp '+v)
                        : v
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { weight: '800', size: 11 }, color: '#4B5563' }
            }
        };
    }

    // ── CHART CONFIGS ────────────────────────────────
    function getConfig(mode) {
        switch (mode) {

            case 'bar':
                setHeader('Kantin Performa Terbaik', 'Volume penjualan berdasarkan total pesanan selesai', 'Bar — Performa');
                return {
                    type: 'bar',
                    data: {
                        labels: barLabels.length ? barLabels : ['Belum ada data'],
                        datasets: [{ label: 'Total Pesanan', data: barData.length ? barData : [0],
                            backgroundColor: barData.map((_, i) => i === 0 ? gradBar : '#FFBD80'),
                            borderRadius: 12, barThickness: 60 }]
                    },
                    options: { responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } }, scales: scaleXY() }
                };

            case 'line-revenue':
                setHeader('Tren Pendapatan', 'Total pendapatan per periode waktu', 'Line — Pendapatan');
                return {
                    type: 'line',
                    data: {
                        labels: tlLabels,
                        datasets: [{ label: 'Pendapatan (Rp)', data: tlRevenue,
                            borderColor: '#FF6900', backgroundColor: gradRevenue,
                            borderWidth: 2.5, pointRadius: 3, pointBackgroundColor: '#FF6900',
                            tension: 0.4, fill: true }]
                    },
                    options: { responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false },
                            tooltip: { callbacks: { label: c => ' Rp '+c.parsed.y.toLocaleString('id-ID') } } },
                        scales: scaleXY(true) }
                };

            case 'line-orders':
                setHeader('Tren Pesanan', 'Jumlah pesanan selesai per periode waktu', 'Line — Pesanan');
                return {
                    type: 'line',
                    data: {
                        labels: tlLabels,
                        datasets: [{ label: 'Jumlah Pesanan', data: tlOrders,
                            borderColor: '#3B82F6', backgroundColor: gradOrders,
                            borderWidth: 2.5, pointRadius: 3, pointBackgroundColor: '#3B82F6',
                            tension: 0.4, fill: true }]
                    },
                    options: { responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } }, scales: scaleXY() }
                };

            case 'line-both':
                setHeader('Tren Pendapatan & Pesanan', 'Perbandingan pendapatan dan jumlah pesanan', 'Line — Keduanya');
                return {
                    type: 'line',
                    data: {
                        labels: tlLabels,
                        datasets: [
                            { label: 'Pendapatan (Rp)', data: tlRevenue,
                                borderColor: '#FF6900', backgroundColor: 'transparent',
                                borderWidth: 2.5, pointRadius: 3, pointBackgroundColor: '#FF6900',
                                tension: 0.4, yAxisID: 'yRevenue' },
                            { label: 'Jumlah Pesanan', data: tlOrders,
                                borderColor: '#3B82F6', backgroundColor: 'transparent',
                                borderWidth: 2.5, pointRadius: 3, pointBackgroundColor: '#3B82F6',
                                tension: 0.4, yAxisID: 'yOrders' }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: {
                            yRevenue: { type: 'linear', position: 'left', beginAtZero: true,
                                grid: { color: '#F3F4F6' },
                                ticks: { font: { weight: '800', size: 10 }, color: '#FF6900',
                                    callback: v => v >= 1_000_000 ? 'Rp '+(v/1_000_000).toFixed(1)+'jt'
                                        : v >= 1_000 ? 'Rp '+(v/1_000).toFixed(0)+'rb' : 'Rp '+v }},
                            yOrders: { type: 'linear', position: 'right', beginAtZero: true,
                                grid: { drawOnChartArea: false },
                                ticks: { font: { weight: '800', size: 10 }, color: '#3B82F6' }},
                            x: { grid: { display: false },
                                ticks: { font: { weight: '800', size: 11 }, color: '#4B5563' }}
                        }
                    }
                };

            case 'donut':
                setHeader('Proporsi Pesanan per Kantin', 'Persentase jumlah pesanan tiap kantin', 'Donut — Proporsi');
                return {
                    type: 'doughnut',
                    data: {
                        labels: donutLabels.length ? donutLabels : ['Belum ada data'],
                        datasets: [{ data: donutData.length ? donutData : [1],
                            backgroundColor: ['#FF6900','#FF9F59','#3B82F6','#22C55E','#F59E0B','#8B5CF6','#EC4899','#14B8A6'],
                            borderWidth: 3, borderColor: '#ffffff', hoverOffset: 8 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '65%',
                        plugins: {
                            legend: { display: true, position: 'right',
                                labels: { font: { weight: '700', size: 11 }, color: '#4B5563',
                                    padding: 16, usePointStyle: true, pointStyleWidth: 10 }},
                            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed} pesanan` } }
                        }
                    }
                };

            case 'bar-horiz':
                setHeader('Ranking Pendapatan Kantin', 'Total pendapatan tertinggi per mitra kantin', 'Bar — Pendapatan');
                return {
                    type: 'bar',
                    data: {
                        labels: horizLabels.length ? horizLabels : ['Belum ada data'],
                        datasets: [{ label: 'Total Pendapatan (Rp)', data: horizRevenue.length ? horizRevenue : [0],
                            backgroundColor: horizRevenue.map((_, i) => i === 0 ? '#FF6900' : i === 1 ? '#FF8533' : '#FFBD80'),
                            borderRadius: 8, barThickness: 24 }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false },
                            tooltip: { callbacks: { label: c => ' Rp '+c.parsed.x.toLocaleString('id-ID') } }},
                        scales: {
                            x: { beginAtZero: true, grid: { color: '#F3F4F6' },
                                ticks: { font: { weight: '800', size: 10 }, color: '#9CA3AF',
                                    callback: v => v >= 1_000_000 ? 'Rp '+(v/1_000_000).toFixed(1)+'jt'
                                        : v >= 1_000 ? 'Rp '+(v/1_000).toFixed(0)+'rb' : 'Rp '+v }},
                            y: { grid: { display: false },
                                ticks: { font: { weight: '800', size: 11 }, color: '#4B5563' }}
                        }
                    }
                };
        }
    }

    // ── RENDER ───────────────────────────────────────
    let currentChart = null;

    function renderChart(mode) {
        if (currentChart) currentChart.destroy();
        const cfg = getConfig(mode);
        currentChart = new Chart(ctx, cfg);
    }

    renderChart('bar');

    // ── SWITCH ───────────────────────────────────────
    window.switchChart = function(mode) {
        renderChart(mode);
        document.getElementById('chartTypeDropdown').classList.add('hidden');
        document.getElementById('chartTypeChevron').style.transform = 'rotate(0deg)';
    };

    // ── DROPDOWNS ────────────────────────────────────
    window.toggleChartType = function() {
        const dd = document.getElementById('chartTypeDropdown');
        const ch = document.getElementById('chartTypeChevron');
        dd.classList.toggle('hidden');
        ch.style.transform = dd.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    window.toggleDashDate = function() {
        const dd = document.getElementById('dashDateDropdown');
        const ch = document.getElementById('dashChevron');
        dd.classList.toggle('hidden');
        ch.style.transform = dd.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    };

    window.toggleDropdown = function() {
        document.getElementById('notifDropdown')?.classList.toggle('hidden');
    };

    window.addEventListener('click', function(e) {
        [
            ['chartTypeContainer', 'chartTypeDropdown', 'chartTypeChevron'],
            ['dashDateContainer',  'dashDateDropdown',  'dashChevron'],
            ['bellWrapper',        'notifDropdown',      null],
        ].forEach(([wrap, drop, chev]) => {
            const w = document.getElementById(wrap);
            const d = document.getElementById(drop);
            if (w && !w.contains(e.target)) {
                d?.classList.add('hidden');
                if (chev) document.getElementById(chev).style.transform = 'rotate(0deg)';
            }
        });
    });

});
</script>
@endpush
@endsection