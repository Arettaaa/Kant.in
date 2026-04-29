<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,  // ← jangan diubah
            'check.session' => \App\Http\Middleware\CheckSession::class,    // ← tambah ini saja
            'admin.kantin'  => \App\Http\Middleware\CheckAdminKantin::class, // tambah baru
        ]);

        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
        // Admin Global → /admin/login
        if ($request->is('admin/global/*')) {
            return '/admin/login';
        }
        // Admin Kantin → /admin/login
        if ($request->is('admin/*')) {
            return '/admin/login';
        }
        // Pelanggan → /login
        return '/login';
    });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    
