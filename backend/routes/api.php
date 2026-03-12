<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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