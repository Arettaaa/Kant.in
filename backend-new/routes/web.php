<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KeamananController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CanteenController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\JelajahController;
use App\Http\Controllers\DetailMenuController;
use App\Http\Controllers\DetailKantinController;
use App\Http\Controllers\CartController;

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
Route::middleware(['auth'])->prefix('admin/global')->name('admin.global.')->group(function () {

    // Dasbor
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dasbor');

    // Kantin
    Route::get('/kantin-mitra', [CanteenController::class, 'index'])->name('kantin');
    Route::get('/kantin-mitra/filter', [CanteenController::class, 'filter'])->name('kantin.filter');

    // Transaksi & Notifikasi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi');
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');
    // Ubah 'review-pendaftaran' jadi 'rev-pendaftaran'
    Route::get('/notifikasi/review', fn() => view('admin_global.rev-pendaftaran'))->name('rev-pendaftaran');


    // Profil & Keamanan
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::get('/keamanan', [KeamananController::class, 'index'])->name('keamanan');

    Route::post('/kantin-mitra', [CanteenController::class, 'store'])->name('kantin.store');
    Route::put('/kantin-mitra/{id}', [CanteenController::class, 'update'])->name('kantin.update');
    Route::delete('/kantin-mitra/{id}', [CanteenController::class, 'destroy'])->name('kantin.destroy');
    
    Route::get('/pengaturan', [DashboardController::class, 'pengaturan'])->name('pengaturan');

});
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
Route::get('/beranda', [BerandaController::class, 'index'])->name('pelanggan.beranda');
Route::get('/menu/{id}', [DetailMenuController::class, 'index'])->name('pelanggan.detail-menu');
Route::get('/kantin/{id}', [DetailKantinController::class, 'index'])->name('pelanggan.detail-kantin');

// Keranjang
Route::get('/keranjang', [CartController::class, 'index'])->name('pelanggan.keranjang');
Route::post('/keranjang/items', [CartController::class, 'addItem'])->name('pelanggan.keranjang.add');
Route::put('/keranjang/items/{menuId}', [CartController::class, 'updateItem'])->name('pelanggan.keranjang.update');
Route::delete('/keranjang/items/{menuId}', [CartController::class, 'removeItem'])->name('pelanggan.keranjang.remove');
Route::delete('/keranjang', [CartController::class, 'clearSelected'])->name('pelanggan.keranjang.clear');
Route::get('/keranjang/ongkir/{canteenId}', [CartController::class, 'getOngkir'])->name('pelanggan.keranjang.ongkir');

Route::get('/pembayaran',               fn() => view('pelanggan.pembayaran'))->name('pelanggan.pembayaran');
Route::get('/jelajah', [JelajahController::class, 'index'])->name('pelanggan.jelajah');
Route::get('/pesanan',                  fn() => view('pelanggan.pesanan'))->name('pelanggan.pesanan');
Route::get('/profil', [ProfilController::class, 'index'])->name('pelanggan.profil');
Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('pelanggan.edit-profil');
Route::get('/profil/data-diri', [ProfilController::class, 'dataDiri'])->name('pelanggan.data-diri');


Route::middleware('check.session')->group(function () {
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

/*
|--------------------------------------------------------------------------
| Redirect Root → Login
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/beranda');