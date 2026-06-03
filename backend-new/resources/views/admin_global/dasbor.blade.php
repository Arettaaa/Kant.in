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
                            {{-- Jika Naik (Hijau) --}}
                            <span
                                class="text-[10px] font-black px-2.5 py-1 bg-green-50 text-[#22C55E] rounded-lg text-start">
                                +{{ $revenuePercentage }}%
                            </span>
                            @elseif($revenueTrend == 'down')
                            {{-- Jika Turun (Merah) --}}
                            <span
                                class="text-[10px] font-black px-2.5 py-1 bg-red-50 text-red-500 rounded-lg text-start">
                                -{{ $revenuePercentage }}%
                            </span>
                            @else
                            {{-- Jika Stabil / Belum Ada Data (Abu-abu) --}}
                            <span
                                class="text-[10px] font-black px-2.5 py-1 bg-gray-50 text-gray-500 rounded-lg text-start">
                                {{ $revenuePercentage }}%
                            </span>
                            @endif

                            <span class="text-[10px] font-bold text-gray-300 italic text-start">Vs bulan lalu</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-wallet absolute -right-6 -bottom-6 text-8xl text-gray-50 opacity-50"></i>
                </div>

                {{-- ✅ KARTU TOTAL PESANAN --}}
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
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter text-start">Kantin
                            Performa Terbaik</h3>
                        <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-widest text-start">Volume
                            penjualan berdasarkan total pesanan selesai</p>
                    </div>
                    <div
                        class="px-5 py-2.5 bg-orange-50 text-[#FF6900] rounded-full text-[10px] font-black tracking-widest uppercase border border-orange-100 text-start">
                        Bulan Ini
                    </div>
                </div>
                <div class="h-[400px] w-full text-start">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

        </div>
    </main>
</div>

@push('scripts')
<script>
    // --- LOGIKA REAL TIME DATE ---
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('realtimeDate');
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);

        // --- CHART LOGIC DENGAN DATA DINAMIS ---
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#FF6900');
        gradient.addColorStop(1, '#FF9F59');

        // Mengambil data JSON dari Controller
        const labelsData = {!! json_encode($chartLabels ?? []) !!};
        const chartDataArray = {!! json_encode($chartData ?? []) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelsData.length > 0 ? labelsData : ['Belum ada data'],
                datasets: [{
                    label: 'Total Pesanan Selesai',
                    data: chartDataArray.length > 0 ? chartDataArray : [0],
                    backgroundColor: [gradient, '#FFBD80', '#FFBD80', '#FFBD80', '#FFBD80'], // Item pertama (ranking 1) warnanya menyala
                    borderRadius: 12,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F6',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                weight: '800',
                                size: 10
                            },
                            color: '#9CA3AF',
                            stepSize: 1 // Supaya pesanan angkanya gak koma-koma di garis y-axis
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                weight: '800',
                                size: 11
                            },
                            color: '#4B5563'
                        }
                    }
                }
            }
        });
    });

    //dropdown bell
    function toggleDropdown() {
        const dd = document.getElementById('notifDropdown');
        dd.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const wrapper = document.getElementById('bellWrapper');
        const dropdown = document.getElementById('notifDropdown');
        if (wrapper && !wrapper.contains(e.target)) {
            if (dropdown) dropdown.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection