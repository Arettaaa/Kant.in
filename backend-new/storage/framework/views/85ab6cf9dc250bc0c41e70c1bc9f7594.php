<?php $__env->startSection('title', 'Pesanan Saya - Kant.in'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .tab-underline { position: relative; transition: color 0.2s ease; }
    .tab-underline.active { color: #FF6900; font-weight: 800; }
    .tab-underline.active::after {
        content: ''; position: absolute; bottom: -1px; left: 0; right: 0;
        height: 2px; background-color: #FF6900; border-radius: 2px;
    }
    .tab-underline:not(.active) { color: #9ca3af; font-weight: 600; }

    /* ---- Step tracker ---- */
    .step-track {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        flex: 0 0 auto;
    }
    .step-circle {
        width: 34px; height: 34px; border-radius: 50%;
        border: 2px solid #e5e7eb; background: white;
        display: flex; align-items: center; justify-content: center;
        color: #d1d5db; font-size: 13px; flex-shrink: 0;
        transition: all 0.2s;
    }
    .step-circle.done  { border-color: #FF6900; color: #FF6900; }
    .step-circle.active { border-color: #FF6900; background-color: #FF6900; color: white; }
    .step-label { font-size: 11px; font-weight: 600; color: #9ca3af; white-space: nowrap; }
    .step-label.active { font-weight: 800; color: #FF6900; }
    .step-label.done   { font-weight: 600; color: #9ca3af; }

    .step-connector {
        flex: 1;
        height: 2px;
        background-color: #e5e7eb;
        margin: 0 3px;
        position: relative;
        top: 17px; /* center with circle */
        flex-shrink: 1;
    }
    .step-connector.done { background-color: #FF6900; }

    .order-card { transition: all 0.2s ease; }
    .order-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }

    .riwayat-badge-selesai { background-color: #F0FDF4; color: #16a34a; }
    .riwayat-badge-batal   { background-color: #FEF2F2; color: #dc2626; }
    .riwayat-card  { transition: all 0.2s ease; }
    .riwayat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
    .action-btn { transition: all 0.15s ease; }
    .action-btn:hover { filter: brightness(0.95); transform: translateY(-1px); }

    .star-rating .star {
        cursor: pointer; transition: transform 0.15s ease;
        color: #d1d5db; font-size: 2.5rem;
    }
    .star-rating .star:hover,
    .star-rating .star.active { color: #f59e0b; transform: scale(1.15); }

    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.9) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-card { animation: modalIn 0.22s cubic-bezier(0.34,1.56,0.64,1); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil
            </a>
        </nav>
        <a href="/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </aside>

    
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">
        <div class="px-10 py-8 flex flex-col gap-6">

            <h1 class="text-2xl font-extrabold text-gray-900">Pesanan Saya</h1>

            
            <div class="border-b border-gray-200">
                <div class="flex gap-8">
                    <button id="tabAktifBtn" onclick="switchTab('aktif')" class="tab-underline active pb-3 text-sm tracking-wide">Aktif</button>
                    <button id="tabRiwayatBtn" onclick="switchTab('riwayat')" class="tab-underline pb-3 text-sm tracking-wide">Riwayat</button>
                </div>
            </div>

            
            <div id="tabAktif" class="grid grid-cols-2 gap-4 pb-8">

                
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8492</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 30.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:30 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Warung Bu Ani</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Nasi Goreng Spesial, 1x Es Teh Manis</p>
                        </div>
                    </div>

                    
                    <div class="step-track">
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-solid fa-shield-halved text-xs"></i></div>
                            <span class="step-label done">Validasi</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-regular fa-clock text-xs"></i></div>
                            <span class="step-label done">Menunggu</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle active"><i class="fa-solid fa-fire text-xs"></i></div>
                            <span class="step-label active">Dimasak</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-solid fa-box-open text-xs"></i></div>
                            <span class="step-label">Siap Diambil</span>
                        </div>
                    </div>
                </div>

                
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8493</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 44.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:45 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Mie Nusantara</p>
                            <p class="text-xs text-gray-400 mt-0.5">2x Mie Goreng Ayam</p>
                        </div>
                    </div>

                    
                    <div class="step-track">
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-solid fa-shield-halved text-xs"></i></div>
                            <span class="step-label done">Validasi</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle active"><i class="fa-regular fa-clock text-xs"></i></div>
                            <span class="step-label active">Menunggu</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-solid fa-fire text-xs"></i></div>
                            <span class="step-label">Dimasak</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-solid fa-box-open text-xs"></i></div>
                            <span class="step-label">Siap Diambil</span>
                        </div>
                    </div>
                </div>

                
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8488</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 15.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:15 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Fresh Sip</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Es Kopi Susu</p>
                        </div>
                    </div>

                    
                    <div class="step-track">
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-solid fa-shield-halved text-xs"></i></div>
                            <span class="step-label done">Validasi</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-regular fa-clock text-xs"></i></div>
                            <span class="step-label done">Menunggu</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle done"><i class="fa-solid fa-fire text-xs"></i></div>
                            <span class="step-label done">Dimasak</span>
                        </div>
                        <div class="step-connector done"></div>
                        <div class="step-item">
                            <div class="step-circle active"><i class="fa-solid fa-box-open text-xs"></i></div>
                            <span class="step-label active">Siap Diambil</span>
                        </div>
                    </div>
                </div>

                
                <div class="order-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                                <i class="fa-solid fa-receipt text-xs" style="color:#FF6900;"></i>
                            </div>
                            <span class="text-sm font-extrabold text-gray-800">ORD-8490</span>
                        </div>
                        <span class="text-sm font-extrabold" style="color:#FF6900;">Rp 22.000</span>
                    </div>
                    <p class="text-xs text-gray-400 font-medium mb-4">Today • 12:50 PM</p>

                    <div class="bg-gray-50 rounded-2xl px-4 py-3 mb-5 flex items-start gap-2">
                        <i class="fa-solid fa-store text-gray-300 text-sm mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-gray-700">Geprek Bensu</p>
                            <p class="text-xs text-gray-400 mt-0.5">1x Ayam Geprek Jumbo</p>
                        </div>
                    </div>

                    
                    <div class="step-track">
                        <div class="step-item">
                            <div class="step-circle active"><i class="fa-solid fa-shield-halved text-xs"></i></div>
                            <span class="step-label active">Validasi</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-regular fa-clock text-xs"></i></div>
                            <span class="step-label">Menunggu</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-solid fa-fire text-xs"></i></div>
                            <span class="step-label">Dimasak</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-item">
                            <div class="step-circle"><i class="fa-solid fa-box-open text-xs"></i></div>
                            <span class="step-label">Siap Diambil</span>
                        </div>
                    </div>
                </div>

            </div>

            
            <div id="tabRiwayat" class="hidden flex flex-col gap-4 pb-8">
                <div class="grid grid-cols-2 gap-4">

                    
                    <div class="riwayat-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-2">12 Oct 2023 • 01:20 PM</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa-solid fa-store text-gray-300 text-xs"></i>
                                    <p class="text-sm font-extrabold text-gray-900">Warung Bu Ani</p>
                                </div>
                                <p class="text-xs text-gray-400 ml-5">2x Nasi Goreng Spesial</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-selesai flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-[11px] text-gray-400 font-medium">Total Belanja</p>
                                <p class="text-base font-extrabold text-gray-900">Rp 50.000</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="openRating('Warung Bu Ani', 1)" class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-amber-200 text-amber-500 bg-amber-50">
                                    <i class="fa-regular fa-star text-xs"></i> Nilai
                                </button>
                                <a href="/kantin/warung-bu-ani" class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-orange-200 bg-orange-50" style="color:#FF6900;">
                                    <i class="fa-solid fa-rotate-right text-xs"></i> Pesan Lagi
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <div class="riwayat-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-2">11 Oct 2023 • 12:05 PM</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa-solid fa-store text-gray-300 text-xs"></i>
                                    <p class="text-sm font-extrabold text-gray-900">Ayam Geprek Kampus</p>
                                </div>
                                <p class="text-xs text-gray-400 ml-5">1x Paket Geprek Leleh</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-batal flex-shrink-0">
                                <i class="fa-solid fa-circle-xmark text-[10px]"></i> Dibatalkan
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-[11px] text-gray-400 font-medium">Total Belanja</p>
                                <p class="text-base font-extrabold text-gray-900">Rp 22.000</p>
                            </div>
                            <a href="/kantin/ayam-geprek-kampus" class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-orange-200 bg-orange-50" style="color:#FF6900;">
                                <i class="fa-solid fa-rotate-right text-xs"></i> Pesan Lagi
                            </a>
                        </div>
                    </div>

                    
                    <div class="riwayat-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-2">10 Oct 2023 • 11:45 AM</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa-solid fa-store text-gray-300 text-xs"></i>
                                    <p class="text-sm font-extrabold text-gray-900">Geprek Bensu</p>
                                </div>
                                <p class="text-xs text-gray-400 ml-5">1x Ayam Geprek Jumbo, 1x Es Teh Manis</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-selesai flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-[11px] text-gray-400 font-medium">Total Belanja</p>
                                <p class="text-base font-extrabold text-gray-900">Rp 25.000</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                </div>
                                <a href="/kantin/geprek-bensu" class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-orange-200 bg-orange-50" style="color:#FF6900;">
                                    <i class="fa-solid fa-rotate-right text-xs"></i> Pesan Lagi
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <div class="riwayat-card bg-white rounded-3xl p-5 shadow-sm border border-gray-100 flex flex-col gap-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-gray-400 font-medium mb-2">05 Oct 2023 • 06:30 PM</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fa-solid fa-store text-gray-300 text-xs"></i>
                                    <p class="text-sm font-extrabold text-gray-900">Sate Khas Senayan</p>
                                </div>
                                <p class="text-xs text-gray-400 ml-5">1x Sate Ayam Madura</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-1 rounded-xl riwayat-badge-selesai flex-shrink-0">
                                <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-[11px] text-gray-400 font-medium">Total Belanja</p>
                                <p class="text-base font-extrabold text-gray-900">Rp 28.000</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-solid fa-star text-amber-400 text-sm"></i>
                                    <i class="fa-regular fa-star text-amber-300 text-sm"></i>
                                </div>
                                <a href="/kantin/sate-khas-senayan" class="action-btn flex items-center gap-1.5 px-3 py-2 rounded-2xl text-xs font-bold border border-orange-200 bg-orange-50" style="color:#FF6900;">
                                    <i class="fa-solid fa-rotate-right text-xs"></i> Pesan Lagi
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>


<div id="ratingModal" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,0.4); backdrop-filter:blur(5px);">
    <div class="modal-card bg-white rounded-3xl shadow-2xl w-[420px] mx-4 overflow-hidden">
        <div class="px-8 pt-8 pb-7 flex flex-col items-center gap-5">
            <div class="text-center">
                <h2 class="text-xl font-extrabold text-gray-900 mb-2">Beri Nilai Pesanan</h2>
                <p class="text-sm text-gray-400 font-medium leading-relaxed">
                    Bagaimana makanan Anda? Penilaian Anda<br>membantu orang lain menemukan makanan terbaik!
                </p>
            </div>
            <div class="star-rating flex items-center gap-2 py-2" id="starContainer">
                <i class="fa-solid fa-star star" data-val="1" onclick="setRating(1)"></i>
                <i class="fa-solid fa-star star" data-val="2" onclick="setRating(2)"></i>
                <i class="fa-solid fa-star star" data-val="3" onclick="setRating(3)"></i>
                <i class="fa-solid fa-star star" data-val="4" onclick="setRating(4)"></i>
                <i class="fa-solid fa-star star" data-val="5" onclick="setRating(5)"></i>
            </div>
            <div class="flex gap-3 w-full mt-1">
                <button onclick="closeRating()" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">Batal</button>
                <button onclick="submitRating()" class="flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md hover:brightness-110 transition-all" style="background:linear-gradient(135deg,#FF6900,#ea580c);">Kirim Penilaian</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function switchTab(tab) {
        const aktif   = document.getElementById('tabAktif');
        const riwayat = document.getElementById('tabRiwayat');
        const btnA    = document.getElementById('tabAktifBtn');
        const btnR    = document.getElementById('tabRiwayatBtn');
        if (tab === 'aktif') {
            aktif.classList.remove('hidden'); riwayat.classList.add('hidden');
            btnA.classList.add('active');     btnR.classList.remove('active');
        } else {
            riwayat.classList.remove('hidden'); aktif.classList.add('hidden');
            btnR.classList.add('active');        btnA.classList.remove('active');
        }
    }

    let currentRating = 0;
    function openRating(kantinName, cardId) {
        currentRating = 0;
        document.querySelectorAll('.star-rating .star').forEach(s => {
            s.style.color = '#d1d5db'; s.style.transform = 'scale(1)';
        });
        document.getElementById('ratingModal').classList.remove('hidden');
        document.getElementById('ratingModal').classList.add('flex');
    }
    function closeRating() {
        document.getElementById('ratingModal').classList.add('hidden');
        document.getElementById('ratingModal').classList.remove('flex');
    }
    function setRating(val) {
        currentRating = val;
        document.querySelectorAll('.star-rating .star').forEach((s, i) => {
            s.style.color = i < val ? '#f59e0b' : '#d1d5db';
            s.style.transform = i < val ? 'scale(1.15)' : 'scale(1)';
        });
    }
    document.querySelectorAll('.star-rating .star').forEach((star, idx) => {
        star.addEventListener('mouseenter', () => {
            document.querySelectorAll('.star-rating .star').forEach((s, i) => {
                s.style.color = i <= idx ? '#fbbf24' : '#d1d5db';
            });
        });
        star.addEventListener('mouseleave', () => {
            document.querySelectorAll('.star-rating .star').forEach((s, i) => {
                s.style.color = i < currentRating ? '#f59e0b' : '#d1d5db';
            });
        });
    });
    function submitRating() {
        if (currentRating === 0) { alert('Pilih bintang terlebih dahulu!'); return; }
        closeRating();
    }
    document.getElementById('ratingModal').addEventListener('click', function(e) {
        if (e.target === this) closeRating();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\arett\AndroidStudioProjects\Kantin\backend-new\resources\views/pelanggan/pesanan.blade.php ENDPATH**/ ?>