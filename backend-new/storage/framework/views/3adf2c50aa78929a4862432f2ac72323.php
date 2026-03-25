<?php $__env->startSection('title', 'Profil - Kant.in'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden text-start">

    
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        <nav class="flex flex-col gap-2 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-house w-5 text-center"></i> Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i> Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8;color:#FF6900;">
                <i class="fa-solid fa-user w-5 text-center"></i> Profil
            </a>
        </nav>

        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-auto border-t border-gray-50 pt-6">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center w-full gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
            </button>
        </form>
    </aside>

    
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-[#F9FAFB] text-start relative">

        <div class="absolute top-0 left-0 w-full h-[220px] bg-[#FF6900] rounded-b-[48px] z-0 flex flex-col items-center pt-12">
            <h1 class="text-2xl font-black text-white">Profil & Pengaturan</h1>
        </div>

        <div class="relative z-10 w-full h-full overflow-y-auto hide-scrollbar px-10 pt-36 pb-20 flex flex-col items-center">
            
            <div class="w-full max-w-4xl bg-white rounded-[40px] p-8 shadow-sm border border-gray-100 mb-10 text-center">
                <div class="relative w-28 h-28 mx-auto mb-4">
                    <div class="w-full h-full rounded-full bg-orange-100 flex items-center justify-center border-4 border-white shadow-md">
                        
                        <i class="fa-solid fa-user text-[#FF6900] text-4xl"></i>
                    </div>
                </div>

                
                <h2 class="text-2xl font-black text-gray-900 mb-1"><?php echo e($user->name); ?></h2>
                <div class="flex items-center justify-center gap-2 mb-8">
                    <span class="px-4 py-1.5 bg-gray-50 rounded-full text-[11px] font-black text-gray-400 uppercase tracking-widest border border-gray-100">
                        
                        <i class="fa-solid fa-user-check mr-1 text-[10px]"></i> <?php echo e(ucfirst($user->role)); ?>

                    </span>
                </div>

                <a href="/profil/edit" class="inline-block bg-[#FF6900] text-white text-sm font-bold px-6 py-2.5 rounded-2xl hover:scale-105 transition shadow">
                    Edit Profil
                </a>
            </div>

            <div class="w-full max-w-4xl space-y-8">
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 ml-2">Manajemen Profil</h3>
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100">
                        
                        <a href="/profil/data-diri" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all border-b border-gray-100">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500">
                                    <i class="fa-solid fa-address-card text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Data Diri</p>
                                    <p class="text-[12px] text-gray-400 font-medium"><?php echo e($user->email); ?></p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>

                        <a href="/profil/keamanan" class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-500">
                                    <i class="fa-solid fa-shield-halved text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[15px] font-black text-gray-800">Keamanan Akun</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Ubah Kata Sandi</p>
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right text-gray-300 text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\arett\AndroidStudioProjects\Kantin\backend-new\resources\views/pelanggan/profil.blade.php ENDPATH**/ ?>