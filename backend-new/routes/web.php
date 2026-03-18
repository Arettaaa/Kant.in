<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/register', fn() => view('admin.register'))->name('admin.register');
Route::get('/admin/login',    fn() => view('admin.login'))->name('admin.login');

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
Route::get('/beranda',                  fn() => view('pelanggan.beranda'))->name('pelanggan.beranda');
Route::get('/keranjang',                fn() => view('pelanggan.keranjang'))->name('pelanggan.keranjang');
Route::get('/menu/{slug}',              fn() => view('pelanggan.detail-menu'))->name('pelanggan.detail-menu');
Route::get('/jelajah',                  fn() => view('pelanggan.jelajah'))->name('pelanggan.jelajah');
Route::get('/pesanan',                  fn() => view('pelanggan.pesanan'))->name('pelanggan.pesanan');
Route::get('/profil',                   fn() => view('pelanggan.profil'))->name('pelanggan.profil');
// Route::get('/login',                 fn() => view('pelanggan.login'))->name('pelanggan.login');

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
Route::get('/', fn() => redirect()->route('beranda'));