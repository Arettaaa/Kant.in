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
    <div class="flex justify-between items-center mb-10 text-start">
        <div class="text-start">
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Kantin Performa Terbaik</h3>
            <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-widest">Volume penjualan berdasarkan total pesanan selesai</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Toggle Chart --}}
            <div class="flex gap-1.5">
                <button id="btnBar" onclick="switchChart('bar')"
                    class="w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all border-orange-400 bg-orange-50 text-orange-500"
                    title="Bar Chart">
                    <i class="fa-solid fa-chart-bar"></i>
                </button>
                <button id="btnDonut" onclick="switchChart('donut')"
                    class="w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all border-gray-200 text-gray-400 hover:border-orange-300 hover:text-orange-400"
                    title="Donut Chart">
                    <i class="fa-solid fa-chart-pie"></i>
                </button>
            </div>
            {{-- Filter Periode --}}
            <div class="relative" id="dashDateContainer">
                <div onclick="toggleDashDate()"
                    class="flex items-center gap-2 px-5 py-2.5 bg-orange-50 text-[#FF6900] rounded-full text-[10px] font-black tracking-widest uppercase border border-orange-100 cursor-pointer select-none">
                    {{ $labelPeriode }}
                    <i class="fa-solid fa-chevron-down text-[10px]" id="dashChevron"></i>
                </div>
                <div id="dashDateDropdown"
                    class="hidden absolute top-full right-0 mt-2 w-44 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 py-2">
                    <a href="?periode=hari" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Hari Ini</a>
                    <a href="?periode=minggu" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Minggu Ini</a>
                    <a href="?periode=bulan" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Bulan Ini</a>
                    <a href="?periode=bulan_lalu" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Bulan Lalu</a>
                    <a href="?periode=tahun" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Tahun Ini</a>
                    <a href="?periode=tahun_lalu" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Tahun Lalu</a>
                    <a href="?periode=semua" class="block px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-[#FF6900]">Semua Periode</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Legend (muncul saat donut) --}}
    <div id="chartLegend" class="hidden flex-wrap gap-x-5 gap-y-2 mb-6"></div>

    <div class="h-[400px] w-full">
        <canvas id="performanceChart"></canvas>
    </div>
</div>

        </div>
    </main>
</div>

@push('scripts')
<script>
   const labelsData = {!! json_encode($chartLabels ?? []) !!};
const chartDataArray = {!! json_encode($chartData ?? []) !!};
const chartColors = ['#FF6900','#FFBD80','#FFD4A8','#FFE5CC','#FFF0E0'];

let currentChartType = 'bar';
let chartInstance = null;

function buildChart() {
    if (chartInstance) chartInstance.destroy();
    const ctx = document.getElementById('performanceChart').getContext('2d');

    if (currentChartType === 'bar') {
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsData.length ? labelsData : ['Belum ada data'],
                datasets: [{
                    label: 'Total Pesanan Selesai',
                    data: chartDataArray.length ? chartDataArray : [0],
                    backgroundColor: chartColors,
                    borderRadius: 12,
                    barThickness: 60,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6', drawBorder: false },
                        ticks: { font: { weight: '800', size: 10 }, color: '#9CA3AF', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: '800', size: 11 }, color: '#4B5563' }
                    }
                }
            }
        });
        document.getElementById('chartLegend').classList.add('hidden');
        document.getElementById('chartLegend').classList.remove('flex');
    } else {
        chartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labelsData.length ? labelsData : ['Belum ada data'],
                datasets: [{
                    data: chartDataArray.length ? chartDataArray : [0],
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                                const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                                return ` ${ctx.parsed} pesanan (${pct}%)`;
                            }
                        }
                    }
                }
            }
        });
        const total = chartDataArray.reduce((a,b)=>a+b,0);
        const legend = document.getElementById('chartLegend');
        legend.classList.remove('hidden');
        legend.classList.add('flex');
        legend.innerHTML = labelsData.map((l,i) => `
            <span style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:#6B7280;">
                <span style="width:10px;height:10px;border-radius:2px;background:${chartColors[i]};flex-shrink:0;"></span>
                ${l}
                <strong style="color:#111827;">${total > 0 ? Math.round(chartDataArray[i]/total*100) : 0}%</strong>
            </span>`).join('');
    }
}

function switchChart(type) {
    currentChartType = type;
    const btnBar   = document.getElementById('btnBar');
    const btnDonut = document.getElementById('btnDonut');
    const activeClass   = 'border-orange-400 bg-orange-50 text-orange-500';
    const inactiveClass = 'border-gray-200 text-gray-400 hover:border-orange-300 hover:text-orange-400';
    if (type === 'bar') {
        btnBar.className   = `w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all ${activeClass}`;
        btnDonut.className = `w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all ${inactiveClass}`;
    } else {
        btnDonut.className = `w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all ${activeClass}`;
        btnBar.className   = `w-9 h-9 rounded-xl border flex items-center justify-center text-sm transition-all ${inactiveClass}`;
    }
    buildChart();
}

document.addEventListener('DOMContentLoaded', function() {
    // realtime date
    const dateElement = document.getElementById('realtimeDate');
    if (dateElement) {
        dateElement.innerText = new Date().toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    }
    buildChart();
});
</script>
@endpush
@endsection