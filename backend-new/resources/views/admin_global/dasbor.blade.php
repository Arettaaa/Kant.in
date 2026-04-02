@extends('layouts.app')

@section('title', 'Dasbor Global Admin - Kant.in')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .sidebar-link.active {
        background-color: #FFF3E8;
        color: #FF6900 !important;
    }
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-5px); }

    /* Memastikan Canvas chart responsif */
    #performanceChart {
        width: 100% !important;
        height: 100% !important;
    }
</style>
@endpush

@section('content')
<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    {{-- ======================== SIDEBAR GLOBAL ADMIN ======================== --}}
    <aside class="w-[260px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100 text-start">
        <div class="flex items-center gap-3 mb-12 px-2 text-start">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-utensils text-lg text-white"></i>
            </div>
            <div class="flex flex-col text-start">
                <span class="text-xl font-black text-gray-900 leading-none">Kant.in</span>
                <span class="text-[10px] font-black text-[#FF6900] uppercase tracking-widest mt-1">Global Admin</span>
            </div>
        </div>

        <nav class="flex flex-col gap-2 flex-1 text-start">
            <a href="/admin/global/dasbor" class="sidebar-link active flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold transition-all text-start" style="background-color: #FFF3E8; color: #FF6900 !important;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dasbor
            </a>
            <a href="/admin/global/kantin-mitra" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Kantin Mitra
            </a>
            <a href="/admin/global/transaksi" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Transaksi
            </a>
            <a href="/admin/global/notifikasi" class="sidebar-link flex items-center justify-between px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <div class="flex items-center gap-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg> Notifikasi</div>
                <span class="w-5 h-5 bg-[#FF6900] text-white text-[10px] flex items-center justify-center rounded-full shadow-sm font-black">2</span>
            </a>

            <div class="mt-8 mb-4 px-4 text-[10px] font-black text-gray-300 uppercase tracking-[0.2em] text-start">Sistem</div>
            <a href="/admin/global/pengaturan" class="sidebar-link flex items-center gap-3 px-4 py-3.5 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all text-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Pengaturan
            </a>
        </nav>

        <a href="/admin/login" class="flex items-center gap-3 px-4 py-4 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all border-t border-gray-50 mt-auto text-start">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Keluar
        </a>
    </aside>

    {{-- ======================== MAIN CONTENT ======================== --}}
    <main class="flex-1 h-screen overflow-y-auto hide-scrollbar flex flex-col text-start">
        
        <header class="w-full bg-white border-b border-gray-100 px-10 py-6 sticky top-0 z-50 flex justify-between items-center shadow-sm text-start">
            <div class="text-start">
                <h2 class="text-2xl font-black text-gray-900 leading-none mb-1 text-start">Selamat Datang, Admin</h2>
                <p id="realtimeDate" class="text-sm text-gray-400 font-bold text-start">Memuat Tanggal...</p>
            </div>
            
            <div class="flex items-center gap-6 text-start">
                <button class="relative w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-[#FF6900] border border-gray-100 transition-all text-start">
                    <i class="fa-solid fa-bell text-lg"></i>
                    <span class="absolute top-2.5 right-3 w-3 h-3 bg-[#FF6900] border-2 border-white rounded-full"></span>
                </button>

                <div class="h-10 w-[1px] bg-gray-100"></div>

                <a href="/admin/global/profil" class="flex items-center gap-4 group text-start">
                    <div class="text-right text-start">
                        <p class="text-sm font-black text-gray-900 leading-none mb-1 group-hover:text-[#FF6900] text-start">Admin Utama</p>
                        <p class="text-[10px] font-bold text-[#FF6900] uppercase tracking-widest text-start">Pusat Kendali</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#FFF3E8] flex items-center justify-center text-[#FF6900] font-black text-lg border border-orange-100 group-hover:bg-[#FF6900] group-hover:text-white transition-all shadow-sm">
                        A
                    </div>
                </a>
            </div>
        </header>

        <div class="p-10 space-y-10 text-start">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-start">
                <div class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start text-start">
                    <div class="relative z-10 text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start">TOTAL PENDAPATAN</p>
                        <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start">Rp 1.245.000</h3>
                        <div class="mt-4 flex items-center gap-2 text-start">
                            <span class="text-[10px] font-black px-2.5 py-1 bg-green-50 text-[#22C55E] rounded-lg text-start">+12.5%</span>
                            <span class="text-[10px] font-bold text-gray-300 italic text-start">Vs bulan lalu</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-wallet absolute -right-6 -bottom-6 text-8xl text-gray-50 opacity-50"></i>
                </div>

                <div class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start">
                    <div class="relative z-10 text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start">TOTAL PESANAN</p>
                        <div class="flex items-center gap-4 text-start">
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start">4.821</h3>
                            <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-start"><i class="fa-solid fa-receipt text-lg text-start"></i></div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-300 mt-4 italic text-start">Berdasarkan 3 Kantin Aktif</p>
                    </div>
                </div>

                <div class="stat-card bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden text-start">
                    <div class="relative z-10 text-start text-start text-start">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3 text-start text-start text-start">KANTIN AKTIF</p>
                        <div class="flex items-center gap-4 text-start text-start text-start">
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight text-start text-start text-start">24</h3>
                            <div class="w-10 h-10 bg-green-50 text-[#22C55E] rounded-xl flex items-center justify-center text-start text-start text-start"><i class="fa-solid fa-shop text-lg text-start"></i></div>
                        </div>
                        <p class="text-[10px] font-bold text-gray-300 mt-4 italic text-start text-start text-start">8 Mitra menunggu verifikasi</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[44px] border border-gray-100 shadow-sm text-start">
                <div class="flex justify-between items-center mb-10 text-start">
                    <div class="text-start">
                        <h3 class="text-xl font-black text-gray-900 uppercase tracking-tighter text-start">Kantin Performa Terbaik</h3>
                        <p class="text-xs text-gray-400 font-bold mt-1 uppercase tracking-widest text-start">Volume penjualan berdasarkan total pesanan selesai</p>
                    </div>
                    <div class="px-5 py-2.5 bg-orange-50 text-[#FF6900] rounded-full text-[10px] font-black tracking-widest uppercase border border-orange-100 text-start">
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
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.innerText = now.toLocaleDateString('id-ID', options);

        // --- CHART LOGIC ---
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#FF6900');
        gradient.addColorStop(1, '#FF9F59');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Geprek Bensu', 'Warung Bu Ani', 'Sate Senayan', 'Mie Gacoan', 'Kopi Kenangan'],
                datasets: [{
                    label: 'Total Pesanan',
                    data: [420, 310, 240, 210, 160],
                    backgroundColor: [gradient, '#FFBD80', '#FFBD80', '#FFBD80', '#FFBD80'],
                    borderRadius: 12,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F3F4F6', drawBorder: false }, ticks: { font: { weight: '800', size: 10 }, color: '#9CA3AF' } },
                    x: { grid: { display: false }, ticks: { font: { weight: '800', size: 11 }, color: '#4B5563' } }
                }
            }
        });
    });
</script>
@endpush
@endsection