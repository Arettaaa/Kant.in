<?php $__env->startSection('title', 'Beranda - Kant.in'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-card {
        transition: all 0.2s ease;
    }

    .category-card:hover {
        transform: translateY(-2px);
    }

    .category-card.active {
        background-color: #FF6900;
        color: white;
    }

    .category-card.active .cat-icon-wrap {
        background-color: rgba(255, 255, 255, 0.25);
        color: white;
    }

    .category-card.active .cat-label {
        color: white;
    }

    .food-card:hover .food-img {
        transform: scale(1.04);
    }

    .food-img {
        transition: transform 0.3s ease;
    }

    .kantin-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    .kantin-card {
        transition: all 0.2s ease;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    
    <aside
        class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">

        
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        
        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all"
                style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>

            <a href="/jelajah"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Jelajah
            </a>

            <a href="/pesanan"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Pesanan
            </a>

            <a href="/profil"
                class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil
            </a>
        </nav>

        
        <?php if(auth()->guard()->check()): ?>
        
        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-auto">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="flex items-center w-full gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
        <?php else: ?>
        
        <a href="/login"
            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-orange-500 hover:bg-orange-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Masuk
        </a>
        <?php endif; ?>
    </aside>

    
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        
        <div
            class="sticky top-0 z-10 flex items-center justify-between px-10 py-5 bg-white/90 backdrop-blur-md border-b border-gray-100">

            
            <div>
                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-semibold mb-0.5">
                    <svg class="w-3.5 h-3.5" style="color:#FF6900;" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                    </svg>
                    <span style="color:#FF6900;">Sekolah Vokasi IPB</span>
                </div>
                <h1 class="text-xl font-extrabold text-gray-900 leading-tight">Halo, <?php echo e($namaDepan); ?>! 👋</h1>
            </div>

            
            <div class="flex items-center gap-3">
                
                <a href="/pesanan"
                    class="w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </a>
                
                <a href="/keranjang"
                    class="relative w-10 h-10 rounded-2xl bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 w-4 h-4 rounded-full text-[10px] font-black text-white flex items-center justify-center"
                        style="background-color:#FF6900;">2</span>
                </a>

                
                <a href="/profil"
                    class="w-10 h-10 rounded-full overflow-hidden border-2 border-orange-100 bg-orange-50 flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5 text-orange-300" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                    </svg>
                </a>
            </div>
        </div>

        
        <div class="px-10 py-8 flex flex-col gap-8">

            
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" placeholder="Mau makan apa hari ini?"
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:border-transparent transition-all duration-200"
                    style="focus:ring-color:#FF6900;"
                    onfocus="this.style.boxShadow='0 0 0 3px rgba(255,105,0,0.15)'; this.style.borderColor='#FF6900';"
                    onblur="this.style.boxShadow=''; this.style.borderColor='#e5e7eb';">
            </div>

            
            <section>
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Kategori</h2>
                <div class="grid grid-cols-4 gap-3">

                    
                    <button onclick="setCategory(this, 'nasi')"
                        class="category-card active flex flex-col items-center gap-2 py-5 px-3 rounded-2xl border border-transparent"
                        data-cat="nasi">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center"
                            style="background-color:rgba(255,255,255,0.25);">
                            <i class="fa-solid fa-fire text-lg text-white"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-white">Nasi</span>
                    </button>

                    
                    <button onclick="setCategory(this, 'mie')"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100"
                        data-cat="mie">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-pizza-slice text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Mie</span>
                    </button>

                    
                    <button onclick="setCategory(this, 'minuman')"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100"
                        data-cat="minuman">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-mug-hot text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Minuman</span>
                    </button>

                    
                    <button onclick="setCategory(this, 'camilan')"
                        class="category-card flex flex-col items-center gap-2 py-5 px-3 rounded-2xl bg-white border border-gray-100"
                        data-cat="camilan">
                        <div class="cat-icon-wrap w-11 h-11 rounded-2xl flex items-center justify-center bg-orange-50">
                            <i class="fa-solid fa-store text-lg" style="color:#FF6900;"></i>
                        </div>
                        <span class="cat-label text-[13px] font-bold text-gray-500">Camilan</span>
                    </button>

                </div>
            </section>

            
            <section>
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">Makanan Sedang Tren</h2>
                <div class="grid grid-cols-2 gap-4">

                    
                    <a href="/menu/nasi-goreng-spesial"
                        class="food-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all">
                        <div class="relative h-44 overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=600&q=80"
                                alt="Nasi Goreng Spesial" class="food-img w-full h-full object-cover">
                            <div
                                class="absolute top-3 left-3 flex items-center gap-1 bg-amber-400 text-white text-[11px] font-black px-2 py-1 rounded-xl shadow">
                                <i class="fa-solid fa-star text-[10px]"></i> 4.8
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-sm font-bold text-gray-800 mb-1">Nasi Goreng Spesial</p>
                            <p class="text-base font-extrabold" style="color:#FF6900;">Rp 25.000</p>
                        </div>
                    </a>

                    
                    <a href="/menu/mie-goreng-ayam"
                        class="food-card bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all">
                        <div class="relative h-44 overflow-hidden bg-gray-100">
                            <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600&q=80"
                                alt="Mie Goreng Ayam" class="food-img w-full h-full object-cover">
                            <div
                                class="absolute top-3 left-3 flex items-center gap-1 bg-amber-400 text-white text-[11px] font-black px-2 py-1 rounded-xl shadow">
                                <i class="fa-solid fa-star text-[10px]"></i> 4.6
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-sm font-bold text-gray-800 mb-1">Mie Siram Spesial</p>
                            <p class="text-base font-extrabold" style="color:#FF6900;">Rp 22.000</p>
                        </div>
                    </a>

                </div>
            </section>

            
            <section class="pb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-extrabold text-gray-900">Kantin Populer</h2>
                    <a href="/jelajah" class="text-sm font-bold hover:underline" style="color:#FF6900;">
                        Lihat Semua Kantin &rsaquo;
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    
                    <a href="/kantin/warung-bu-ani"
                        class="kantin-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=200&q=80"
                                alt="Warung Bu Ani" class="w-16 h-16 rounded-2xl object-cover">
                            <div
                                class="absolute -bottom-1 -right-1 flex items-center gap-0.5 bg-amber-400 text-white text-[10px] font-black px-1.5 py-0.5 rounded-lg shadow">
                                <i class="fa-solid fa-star text-[9px]"></i> 4.8
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900 truncate">Warung Bu Ani</p>
                            <p class="text-xs text-gray-400 font-medium mb-2">Indonesian • Nasi</p>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl"
                                style="background-color:#FFF3E8; color:#FF6900;">
                                <i class="fa-regular fa-clock text-[10px]"></i> Buka
                            </span>
                        </div>
                    </a>

                    
                    <a href="/kantin/noodle-ninja"
                        class="kantin-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1569050467447-ce54b3bbc37d?w=200&q=80"
                                alt="Noodle Ninja" class="w-16 h-16 rounded-2xl object-cover">
                            <div
                                class="absolute -bottom-1 -right-1 flex items-center gap-0.5 bg-amber-400 text-white text-[10px] font-black px-1.5 py-0.5 rounded-lg shadow">
                                <i class="fa-solid fa-star text-[9px]"></i> 4.6
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900 truncate">Noodle Ninja</p>
                            <p class="text-xs text-gray-400 font-medium mb-2">Japanese • Mie</p>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl"
                                style="background-color:#FFF3E8; color:#FF6900;">
                                <i class="fa-regular fa-clock text-[10px]"></i> Buka
                            </span>
                        </div>
                    </a>

                    
                    <a href="/kantin/fresh-sip"
                        class="kantin-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=200&q=80"
                                alt="Fresh Sip" class="w-16 h-16 rounded-2xl object-cover">
                            <div
                                class="absolute -bottom-1 -right-1 flex items-center gap-0.5 bg-amber-400 text-white text-[10px] font-black px-1.5 py-0.5 rounded-lg shadow">
                                <i class="fa-solid fa-star text-[9px]"></i> 4.9
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900 truncate">Fresh Sip</p>
                            <p class="text-xs text-gray-400 font-medium mb-2">Minuman • Dessert</p>
                            <span
                                class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl bg-gray-100 text-gray-400">
                                <i class="fa-regular fa-clock text-[10px]"></i> Tutup
                            </span>
                        </div>
                    </a>

                    
                    <a href="/kantin/asian-bowl-house"
                        class="kantin-card bg-white rounded-3xl p-4 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&q=80"
                                alt="Asian Bowl House" class="w-16 h-16 rounded-2xl object-cover">
                            <div
                                class="absolute -bottom-1 -right-1 flex items-center gap-0.5 bg-amber-400 text-white text-[10px] font-black px-1.5 py-0.5 rounded-lg shadow">
                                <i class="fa-solid fa-star text-[9px]"></i> 4.5
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-extrabold text-gray-900 truncate">Asian Bowl House</p>
                            <p class="text-xs text-gray-400 font-medium mb-2">Asian • Nasi</p>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl"
                                style="background-color:#FFF3E8; color:#FF6900;">
                                <i class="fa-regular fa-clock text-[10px]"></i> Buka
                            </span>
                        </div>
                    </a>

                </div>
            </section>

        </div>
    </main>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function setCategory(el, cat) {
        // Reset all
        document.querySelectorAll('.category-card').forEach(btn => {
            btn.classList.remove('active');
            btn.classList.add('bg-white', 'border-gray-100');
            btn.querySelector('.cat-icon-wrap').style.backgroundColor = '#FFF7ED';
            btn.querySelector('.cat-icon-wrap').style.color = '#FF6900';
            const icon = btn.querySelector('.cat-icon-wrap i');
            if (icon) { icon.classList.remove('text-white'); icon.style.color = '#FF6900'; }
            btn.querySelector('.cat-label').style.color = '#6B7280';
        });

        // Set active
        el.classList.add('active');
        el.classList.remove('bg-white', 'border-gray-100');
        const wrap = el.querySelector('.cat-icon-wrap');
        wrap.style.backgroundColor = 'rgba(255,255,255,0.25)';
        const icon = wrap.querySelector('i');
        if (icon) { icon.classList.add('text-white'); icon.style.color = 'white'; }
        el.querySelector('.cat-label').style.color = 'white';
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\arett\AndroidStudioProjects\Kantin\backend-new\resources\views/pelanggan/beranda.blade.php ENDPATH**/ ?>