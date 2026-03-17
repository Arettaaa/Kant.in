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
Route::get('/admin/pesanan',       fn() => view('admin.pesanan'))->name('admin.pesanan');
Route::get('/admin/pesanan/tolak', fn() => view('admin.pesanan'))->name('admin.pesanan.tolak');

Route::get('/admin/pesanan/status', fn() => view('admin.status'))->name('admin.pesanan.status');
Route::get('/admin/pesanan/rincian', fn() => view('admin.rincian'))->name('admin.pesanan.rincian');

Route::get('/admin/menu',          fn() => view('admin.pesanan'))->name('admin.menu');
Route::get('/admin/profil',        fn() => view('admin.pesanan'))->name('admin.profil');

/*
|--------------------------------------------------------------------------
| Redirect Root → Login
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('admin.login'));