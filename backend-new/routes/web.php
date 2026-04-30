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
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminKantin\PesananController as AdminPesananController;
use App\Http\Controllers\AdminKantin\MenuController as AdminMenuController;
use App\Http\Controllers\AdminKantin\TransactionController as AdminTransactionController;
use App\Http\Controllers\AdminKantin\ProfilController as AdminProfilController;





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


Route::get('/lupa-sandi', [ForgotPasswordController::class, 'index']);
Route::post('/lupa-sandi', [ForgotPasswordController::class, 'checkEmail']);
Route::get('/lupa-sandi/reset', [ForgotPasswordController::class, 'resetForm']);
Route::post('/lupa-sandi/reset', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/lupa-sandi/verifikasi', fn() => view('auth.verifikasi-otp'));
/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/register', fn() => view('auth.register'))->name('admin.register');
Route::get('/admin/login', fn() => view('auth.login'))->name('admin.login');


/*
|--------------------------------------------------------------------------
| Admin Global Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['check.session'])->prefix('admin/global')->name('admin.global.')->group(function () {

    // Dasbor
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dasbor');

    // Kantin
    Route::get('/kantin-mitra', [CanteenController::class, 'index'])->name('kantin');
    Route::get('/kantin-mitra/filter', [CanteenController::class, 'filter'])->name('kantin.filter');

    // Transaksi & Notifikasi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi');
    Route::get('/transaksi/ekspor', [TransactionController::class, 'export'])->name('transaksi.export');
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifikasi');


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
Route::middleware(['check.session', 'admin.kantin'])->prefix('admin')->name('admin.')->group(function () {

    // Pesanan
    Route::get('/pesanan',                  [AdminPesananController::class, 'index'])->name('pesanan');
    Route::get('/pesanan/{id}',             [AdminPesananController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{id}/rincian',     [AdminPesananController::class, 'rincian'])->name('pesanan.rincian');
    Route::post('/pesanan/{id}/verify',     [AdminPesananController::class, 'verifyPayment'])->name('pesanan.verify');
    Route::post('/pesanan/{id}/reject',     [AdminPesananController::class, 'rejectPayment'])->name('pesanan.reject');
    Route::put('/pesanan/{id}/status',      [AdminPesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::post('/pesanan/{id}/cancel',     [AdminPesananController::class, 'cancel'])->name('pesanan.cancel');
    Route::post('/kantin/toggle-open',      [AdminPesananController::class, 'toggleOpen'])->name('kantin.toggleOpen');

    // Riwayat Transaksi
    Route::get('/riwayat',                  [AdminTransactionController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{id}',             [AdminTransactionController::class, 'detail'])->name('riwayat.detail');
    Route::get('/riwayat/chart-data',       [AdminTransactionController::class, 'chartData'])->name('riwayat.chart');
    Route::get('/riwayat/export',           [AdminTransactionController::class, 'export'])->name('riwayat.export');

    // Menu
    Route::get('/menu',                     [AdminMenuController::class, 'index'])->name('menu');
    Route::get('/menu/tambah',              [AdminMenuController::class, 'create'])->name('menu.tambah');
    Route::post('/menu',                    [AdminMenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit',           [AdminMenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}',                [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}',             [AdminMenuController::class, 'destroy'])->name('menu.delete');
    Route::put('/menu/{id}/availability',   [AdminMenuController::class, 'updateAvailability'])->name('menu.availability');

    // Profil
    Route::get('/profil',                   [AdminProfilController::class, 'index'])->name('profil');
    Route::put('/profil',                   [AdminProfilController::class, 'update'])->name('profil.update');
    Route::get('/pusat-bantuan',            [AdminProfilController::class, 'bantuan'])->name('support');
});
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

Route::post('/rating/{orderId}', [RatingController::class, 'store'])->name('pelanggan.rating.store');
Route::get('/rating/{orderId}/check', [RatingController::class, 'check'])->name('pelanggan.rating.check');

Route::post('/pembayaran/session', [CheckoutController::class, 'saveSession']);
Route::get('/pembayaran', [CheckoutController::class, 'index']);
Route::post('/pembayaran', [CheckoutController::class, 'store']);
Route::post('/pembayaran/batalkan', [CheckoutController::class, 'cancel']);
Route::get('/jelajah', [JelajahController::class, 'index'])->name('pelanggan.jelajah');
Route::get('/pesanan', [PesananController::class, 'index'])->name('pelanggan.pesanan');
Route::post('/pesanan/{orderId}/complete', [PesananController::class, 'complete']);
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
