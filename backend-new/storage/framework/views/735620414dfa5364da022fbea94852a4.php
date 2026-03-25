<?php $__env->startSection('title', 'Data Diri - Kant.in'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 16px 14px 44px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #6B7280; /* Warna teks agak abu karena readonly */
        outline: none;
    }
    .input-wrap { position: relative; }
    .input-wrap .icon {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%); color: #9ca3af;
        width: 16px; height: 16px;
    }
    .edit-btn { transition: all 0.2s ease; }
    .edit-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255,105,0,0.3);
    }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        <nav class="flex flex-col gap-1.5 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-house w-5 text-center"></i> Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-magnifying-glass w-5 text-center"></i> Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-clipboard-list w-5 text-center"></i> Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8; color:#FF6900;">
                <i class="fa-solid fa-user w-5 text-center"></i> Profil
            </a>
        </nav>

        <form action="<?php echo e(route('logout')); ?>" method="POST" class="mt-auto">
            <?php echo csrf_field(); ?>
            <button type="submit" class="flex items-center w-full gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all">
                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
            </button>
        </form>
    </aside>

    
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB] hide-scrollbar">

        
        <div class="sticky top-0 z-30 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>
            <div>
                <h1 class="text-lg font-extrabold text-gray-900 leading-none">Data Diri</h1>
                <p class="text-xs text-gray-400 font-medium mt-1">Informasi detail profil Anda</p>
            </div>
        </div>

        <div class="px-10 py-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl mx-auto mt-4">

                
                <div class="flex justify-center mb-8">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center border-4 border-white shadow-md overflow-hidden bg-[#FEF3E2]"
                         style="<?php echo e($user->photo_profile ? 'background-image:url('.asset('storage/'.$user->photo_profile).'); background-size:cover; background-position:center;' : ''); ?>">
                        <?php if(!$user->photo_profile): ?>
                            <i class="fa-solid fa-user text-orange-200 text-4xl"></i>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="flex flex-col gap-5">
                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Nama Lengkap</label>
                        <div class="input-wrap">
                            <i class="fa-regular fa-user icon"></i>
                            <input type="text" class="input-field" value="<?php echo e($user->name); ?>" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Nomor Telepon</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-phone icon"></i>
                            <input type="text" class="input-field" value="<?php echo e($user->phone ?? '-'); ?>" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Email</label>
                        <div class="input-wrap">
                            <i class="fa-regular fa-envelope icon"></i>
                            <input type="text" class="input-field" value="<?php echo e($user->email); ?>" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-gray-700 mb-2 block ml-1">Role Akun</label>
                        <div class="input-wrap">
                            <i class="fa-solid fa-user-tag icon"></i>
                            <input type="text" class="input-field" value="<?php echo e(ucfirst($user->role)); ?>" readonly>
                        </div>
                    </div>
                </div>

                
                <a href="/profil/edit"
                   class="edit-btn mt-10 w-full py-4 rounded-2xl text-white font-extrabold text-sm shadow-lg flex items-center justify-center gap-2"
                   style="background: linear-gradient(135deg, #FF6900, #ea580c);">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Ubah Data Diri
                </a>

            </div>
        </div>
    </main>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\arett\AndroidStudioProjects\Kantin\backend-new\resources\views/pelanggan/data-diri.blade.php ENDPATH**/ ?>