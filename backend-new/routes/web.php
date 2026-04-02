<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KeamananController;
/*
|--------------------------------------------------------------------------
| Auth Pelanggan (shared login & register dari admin.blade, re-route)
|--------------------------------------------------------------------------
*/
// Halaman Login & Register
Route::get('/login', fn() => view('auth.login'))->name('pelanggan.login');
Route::get('/register', fn() => view('auth.register'))->name('pelanggan.register');

// Proses Login & Register ke Controller
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Lupa kata sandi (Tetap di folder auth)
Route::get('/lupa-sandi',            fn() => view('auth.lupa-sandi'));
Route::get('/lupa-sandi/verifikasi', fn() => view('auth.verifikasi-otp'));
Route::get('/lupa-sandi/reset',      fn() => view('auth.reset-sandi'));

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/register', fn() => view('admin.register'))->name('admin.register');
Route::get('/admin/login',    fn() => view('admin.login'))->name('admin.login');


/*
|--------------------------------------------------------------------------
| Admin Global Routes
|--------------------------------------------------------------------------
*/
// Dasbor Utama
Route::get('/admin/global/dasbor', fn() => view('admin_global.dasbor'))->name('admin.global.dasbor');

// Menu Lainnya
Route::get('/admin/global/kantin-mitra', fn() => view('admin_global.kantin'))->name('admin.global.kantin');
Route::get('/admin/global/transaksi',    fn() => view('admin_global.transaksi'))->name('admin.global.transaksi');
Route::get('/admin/global/notifikasi',   fn() => view('admin_global.notifikasi'))->name('admin.global.notifikasi');
Route::get('/admin/global/pengaturan',   fn() => view('admin_global.pengaturan'))->name('admin.global.pengaturan');
Route::get('/admin/global/profil',       fn() => view('admin_global.profil'))->name('admin.global.profil');

/*
|--------------------------------------------------------------------------
| Admin Kantin Routes
|--------------------------------------------------------------------------
*/
//pesanan
Route::get('/admin/pesanan',        fn() => view('admin.pesanan'))->name('admin.pesanan');
Route::get('/admin/pesanan/tolak',  fn() => view('admin.pesanan'))->name('admin.pesanan.tolak');
Route::get('/admin/pesanan/status', fn() => view('admin.status'))->name('admin.pesanan.status');
Route::get('/admin/pesanan/rincian', fn() => view('admin.rincian'))->name('admin.pesanan.rincian');
Route::get('/admin/pesanan/cancel', fn() => view('admin.cancel'))->name('admin.pesanan.cancel');

//menu
Route::get('/admin/menu',           fn() => view('admin.kelola-menu'))->name('admin.menu');
Route::get('/admin/menu/tambah',    fn() => view('admin.tambah-menu'))->name('admin.menu.tambah');
Route::get('/admin/menu/edit',      fn() => view('admin.edit-menu'))->name('admin.menu.edit');
Route::get('/admin/menu/delete',    fn() => view('admin.delete-menu'))->name('admin.menu.delete');

//riwayat
Route::get('/admin/riwayat', fn() => view('admin.riwayat'))->name('admin.riwayat');
Route::get('/admin/riwayat/detail',     fn() => view('admin.detail-pesanan'))->name('admin.riwayat.detail');

//profil
Route::get('/admin/profil', fn() => view('admin.profil'))->name('admin.profil');
Route::get('/admin/profil/edit', fn() => view('admin.edit-profil'))->name('admin.profil.edit');
Route::get('/admin/profil/jam-operasional', fn() => view('admin.jam-operasional'))->name('admin.profil.jam');
Route::get('/admin/pusat-bantuan', fn() => view('admin.support'))->name('admin.support');

/*
|--------------------------------------------------------------------------
| Pelanggan Routes
|--------------------------------------------------------------------------
*/
Route::get('/beranda', [BerandaController::class, 'index'])->name('pelanggan.beranda');Route::get('/keranjang',                fn() => view('pelanggan.keranjang'))->name('pelanggan.keranjang');
Route::get('/menu/{slug}',              fn() => view('pelanggan.detail-menu'))->name('pelanggan.detail-menu');
Route::get('/pembayaran',               fn() => view('pelanggan.pembayaran'))->name('pelanggan.pembayaran');
Route::get('/jelajah',                  fn() => view('pelanggan.jelajah'))->name('pelanggan.jelajah');
Route::get('/pesanan',                  fn() => view('pelanggan.pesanan'))->name('pelanggan.pesanan');
Route::get('/profil', [ProfilController::class, 'index'])->name('pelanggan.profil');
Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('pelanggan.edit-profil');
Route::get('/profil/data-diri', [ProfilController::class, 'dataDiri'])->name('pelanggan.data-diri');


Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('pelanggan.profil');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('pelanggan.edit-profil');
    Route::get('/profil/data-diri', [ProfilController::class, 'dataDiri'])->name('pelanggan.data-diri');
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('pelanggan.profil.update');
    Route::get('/profil/keamanan', [KeamananController::class, 'index'])->name('pelanggan.keamanan');
    Route::post('/profil/keamanan', [KeamananController::class, 'updatePassword'])->name('pelanggan.password.update');
});

/*
|--------------------------------------------------------------------------
| Pelanggan Routes detail kantin
|--------------------------------------------------------------------------
*/
Route::get('/kantin/{slug}', function ($slug) {

    $kantins = [
        'warung-bu-ani' => [
            'nama' => 'Warung Bu Ani',
            'deskripsi' => 'Menyediakan nasi goreng khas dengan bumbu rempah pilihan.',
            'rating' => 4.8
        ],
        'noodle-ninja' => [
            'nama' => 'Noodle Ninja',
            'deskripsi' => 'Mie Jepang autentik dengan kuah khas.',
            'rating' => 4.6
        ],
        'fresh-sip' => [
            'nama' => 'Fresh Sip',
            'deskripsi' => 'Minuman segar dan dessert kekinian.',
            'rating' => 4.9
        ],
        'asian-bowl-house' => [
            'nama' => 'Asian Bowl House',
            'deskripsi' => 'Menu rice bowl khas Asia.',
            'rating' => 4.5
        ],
    ];

    $kantin = $kantins[$slug] ?? null;

    if (!$kantin) {
        abort(404);
    }

    return view('pelanggan.detail-kantin', compact('kantin'));
})->name('pelanggan.detail-kantin');

/*
|--------------------------------------------------------------------------
| Redirect Root → Login
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/beranda');