<?php $__env->startSection('title', 'Keamanan Akun - Kant.in'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .input-field {
        width: 100%;
        padding: 14px 44px 14px 16px;
        background-color: #F9FAFB;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        font-size: 14px;
        color: #374151;
        outline: none;
        transition: all 0.2s ease;
        letter-spacing: 0.05em;
    }

    .input-field:focus {
        border-color: #FF6900;
        box-shadow: 0 0 0 3px rgba(255, 105, 0, 0.12);
        background-color: #fff;
    }

    .input-field::placeholder {
        color: #d1d5db;
        letter-spacing: normal;
    }

    .input-wrap {
        position: relative;
    }

    .toggle-eye {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.15s ease;
    }

    .toggle-eye:hover {
        color: #FF6900;
    }

    .label-tag {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #111827;
        margin-bottom: 8px;
        display: block;
    }

    .save-btn {
        transition: all 0.2s ease;
    }

    .save-btn:hover {
        filter: brightness(1.08);
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 105, 0, 0.3);
    }

    .save-btn:active {
        transform: translateY(0);
    }

    .strength-bar div {
        height: 4px;
        border-radius: 99px;
        transition: background 0.3s ease, width 0.3s ease;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<?php if(session('success_password')): ?>
<div id="autoSuccessModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-4 text-center transform scale-100 transition-transform duration-300">
        
        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #FFF3E8;">
            <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembaruan Berhasil!</h3>
        <p class="text-gray-500 text-sm mb-6"><?php echo e(session('success_password')); ?></p>
        
        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden mb-2">
            <div id="autoProgressBar" class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full transition-all duration-[2500ms] ease-linear" style="width:0%"></div>
        </div>
        <p class="text-[10px] text-gray-400 font-medium">Menutup otomatis...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Jalankan animasi progress bar
        setTimeout(() => { 
            document.getElementById('autoProgressBar').style.width = '100%'; 
        }, 50);

        // Tutup modal otomatis setelah animasi selesai (2.8 detik)
        setTimeout(() => { 
            const modal = document.getElementById('autoSuccessModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none'; 
            }, 300); // Waktu untuk efek fade out
        }, 2800);
    });
</script>
<?php endif; ?>

<div class="flex w-full h-screen bg-[#F9FAFB] overflow-hidden">

    
    <aside class="w-[240px] h-screen bg-white flex flex-col py-8 px-6 shadow-sm flex-shrink-0 z-20 border-r border-gray-100">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg" style="background-color:#FF6900;">
                <i class="fa-solid fa-fire text-lg text-white"></i>
            </div>
            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Kant.in</span>
        </div>

        <p class="text-[10px] font-black text-gray-300 tracking-widest uppercase px-2 mb-3">Menu Pelanggan</p>

        <nav class="flex flex-col gap-1.5 flex-1">
            <a href="/beranda" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>
            <a href="/jelajah" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Jelajah
            </a>
            <a href="/pesanan" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:bg-gray-50 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Pesanan
            </a>
            <a href="/profil" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold transition-all" style="background-color:#FFF3E8; color:#FF6900;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Profil
            </a>
        </nav>

        <a href="/login" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-[15px] font-bold text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all mt-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
        </a>
    </aside>

    
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-[#F9FAFB]">

        
        <div class="sticky top-0 z-10 w-full flex items-center gap-4 px-10 py-6 bg-white/90 backdrop-blur-md border-b border-gray-100">
            <a href="/profil" class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400"></i>
            </a>

            <div class="px-1 pt-8 pb-6 bg-white border-b border-gray-100">
                <h1 class="text-lg font-extrabold text-gray-900">Keamanan Akun</h1>
                <p class="text-sm text-gray-400 font-medium mt-0.5">Detail informasi keamanan Anda</p>
            </div>
        </div>

        <div class="px-10 py-8 flex justify-center">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-2xl w-full">

                
                <div class="flex items-center gap-3 mb-7">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background-color:#FFF3E8;">
                        <i class="fa-solid fa-shield-halved text-base" style="color:#FF6900;"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-gray-900">Perbarui Kata Sandi</h2>
                </div>

                
                <?php if(session('error_password')): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                    <p class="text-sm font-medium text-red-600"><?php echo e(session('error_password')); ?></p>
                </div>
                <?php endif; ?>

                
                <form method="POST" action="<?php echo e(route('pelanggan.password.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="flex flex-col gap-5">

                        
                        <div>
                            <label class="label-tag">Kata Sandi Saat Ini</label>
                            <div class="input-wrap">
                                <input type="password" name="current_password" id="currentPass" class="input-field <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Masukkan kata sandi saat ini" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('currentPass', this)"></i>
                            </div>
                            
                            <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs mt-1.5 font-bold text-red-500"><i class="fa-solid fa-xmark mr-1"></i> <?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label class="label-tag">Kata Sandi Baru</label>
                            <div class="input-wrap">
                                <input type="password" name="new_password" id="newPass" class="input-field <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Masukkan kata sandi baru" oninput="checkStrength(this.value)" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('newPass', this)"></i>
                            </div>
                            
                            
                            <div class="mt-2 flex gap-1.5 strength-bar" id="strengthBar">
                                <div id="s1" class="flex-1 bg-gray-200"></div>
                                <div id="s2" class="flex-1 bg-gray-200"></div>
                                <div id="s3" class="flex-1 bg-gray-200"></div>
                                <div id="s4" class="flex-1 bg-gray-200"></div>
                            </div>
                            <p id="strengthLabel" class="text-xs text-gray-400 mt-1 font-medium"></p>
                            
                            
                            <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-xs mt-1.5 font-bold text-red-500"><i class="fa-solid fa-xmark mr-1"></i> <?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label class="label-tag">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-wrap">
                                <input type="password" name="new_password_confirmation" id="confirmPass" class="input-field" placeholder="Konfirmasi kata sandi baru" oninput="checkMatch()" required>
                                <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('confirmPass', this)"></i>
                            </div>
                            <p id="matchLabel" class="text-xs mt-1 font-medium hidden"></p>
                        </div>

                    </div>

                    
                    <div class="flex gap-3 mt-8">
                        <a href="/profil" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all text-center flex items-center justify-center">
                            Batal
                        </a>
                        <button type="submit" class="save-btn flex-1 py-3.5 rounded-2xl text-white text-sm font-extrabold shadow-md flex items-center justify-center gap-2" style="background:linear-gradient(135deg,#FF6900,#ea580c);">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Toggle password visibility
    function togglePass(id, icon) {
        const input = document.getElementById(id);
        const isPass = input.type === 'password';
        input.type = isPass ? 'text' : 'password';
        icon.className = isPass
            ? 'fa-regular fa-eye-slash toggle-eye'
            : 'fa-regular fa-eye toggle-eye';
    }

    // Password strength checker
    function checkStrength(val) {
        const bars   = ['s1','s2','s3','s4'];
        const label  = document.getElementById('strengthLabel');
        let score    = 0;
        
        if (val.length >= 6)                        score++;
        if (val.length >= 10)                       score++;
        if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val))              score++;

        const colors  = ['#ef4444','#f97316','#eab308','#22c55e'];
        const labels  = ['Lemah','Cukup','Kuat','Sangat Kuat'];
        const lblClrs = ['text-red-500','text-orange-500','text-yellow-500','text-green-500'];

        bars.forEach((b, i) => {
            const el = document.getElementById(b);
            el.style.backgroundColor = i < score ? colors[score - 1] : '#e5e7eb';
        });

        if (val.length === 0) {
            label.textContent = '';
        } else {
            label.textContent = labels[score - 1] || 'Lemah';
            label.className   = `text-xs mt-1 font-medium ${lblClrs[score - 1] || 'text-red-500'}`;
        }
    }

    // Match checker
    function checkMatch() {
        const newP  = document.getElementById('newPass').value;
        const confP = document.getElementById('confirmPass').value;
        const label = document.getElementById('matchLabel');
        
        if (!confP) { label.classList.add('hidden'); return; }
        label.classList.remove('hidden');
        
        if (newP === confP) {
            label.innerHTML  = '<i class="fa-solid fa-check mr-1"></i> Kata sandi cocok';
            label.className    = 'text-xs mt-1.5 font-bold text-green-500';
        } else {
            label.innerHTML  = '<i class="fa-solid fa-xmark mr-1"></i> Kata sandi tidak cocok';
            label.className    = 'text-xs mt-1.5 font-bold text-red-500';
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\arett\AndroidStudioProjects\Kantin\backend-new\resources\views/pelanggan/keamanan-akun.blade.php ENDPATH**/ ?>