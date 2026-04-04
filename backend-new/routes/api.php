<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CanteenController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

// Public (tanpa login)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/sessions', [AuthController::class, 'login']);

// Protected (harus login)
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/auth/sessions', [AuthController::class, 'logout']);
});

/*
|--------------------------------------------------------------------------
| Public Routes — Canteen & Menu
|--------------------------------------------------------------------------
*/
Route::get('/canteens', [CanteenController::class, 'index']);
Route::get('/menus', [MenuController::class, 'allMenus']);
Route::get('/canteens/{id}', [CanteenController::class, 'show']);
Route::get('/canteens/{id}/menus', [MenuController::class, 'index']);
Route::get('/canteens/{id}/menus/availabilities', [MenuController::class, 'availabilities']);

/*
|--------------------------------------------------------------------------
| Pembeli Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:pembeli'])->prefix('buyers')->group(function () {
    // Cart
    Route::get('/carts', [CartController::class, 'show']);
    Route::post('/carts/items', [CartController::class, 'addItem']);
    Route::put('/carts/items/{itemId}', [CartController::class, 'updateItem']);
    Route::delete('/carts/items/{itemId}', [CartController::class, 'removeItem']);

    // Order
    Route::post('/checkouts', [OrderController::class, 'checkout']);
    Route::get('/orders/histories', [OrderController::class, 'history']);
    Route::get('/orders/{orderId}', [OrderController::class, 'show']);
    Route::get('/orders/{orderId}/statuses', [OrderController::class, 'status']);
    Route::post('/orders/{orderId}/cancellations', [OrderController::class, 'cancel']);

    // Profile
    Route::get('/profiles', [ProfileController::class, 'show']);
    Route::post('/profiles', [ProfileController::class, 'update']);});

/*
|--------------------------------------------------------------------------
| Admin Kantin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin_kantin'])->group(function () {
    // Menu management
    Route::post('/canteens/{id}/menus', [MenuController::class, 'store']);
    Route::put('/canteens/{id}/menus/{menuId}', [MenuController::class, 'update']);
    Route::delete('/canteens/{id}/menus/{menuId}', [MenuController::class, 'destroy']);
    Route::put('/canteens/{id}/menus/{menuId}/availabilities', [MenuController::class, 'updateAvailability']);

    // Order management
    Route::get('/canteens/{id}/orders', [OrderController::class, 'canteenOrders']);
    Route::put('/canteens/{id}/orders/{orderId}/statuses', [OrderController::class, 'updateStatus']);
    Route::post('/canteens/{id}/orders/{orderId}/payments/verify', [OrderController::class, 'verifyPayment']);
    Route::post('/canteens/{id}/orders/{orderId}/payments/reject', [OrderController::class, 'rejectPayment']);

    // Profile
    Route::get('/admin/profiles', [ProfileController::class, 'show']);
    Route::post('/admin/profiles', [ProfileController::class, 'update']);
    
    Route::put('/canteens/{id}/availability', [CanteenController::class, 'toggleOpen']);
});

/*
|--------------------------------------------------------------------------
| Admin Global Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'role:admin_global'])->group(function () {
    // Canteen management
    Route::post('/canteens', [CanteenController::class, 'store']);
    Route::put('/canteens/{id}', [CanteenController::class, 'update']);
    Route::delete('/canteens/{id}', [CanteenController::class, 'destroy']);

    Route::get('/registrations', [CanteenController::class, 'registrations']);
    Route::post('/registrations/{id}/approve', [CanteenController::class, 'approveRegistration']);
    Route::post('/registrations/{id}/reject', [CanteenController::class, 'rejectRegistration']);

    Route::get('/transactions', [TransactionController::class, 'globalTransactions']);
    Route::get('/dashboard', [TransactionController::class, 'globalDashboard']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/canteens/{id}/transactions', [TransactionController::class, 'index']);
    Route::get('/canteens/{id}/dashboard', [TransactionController::class, 'dashboard']);
});