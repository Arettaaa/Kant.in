<?php

namespace App\Http\Controllers\Api; // Atau namespace middleware kamu

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        // Cek apakah user sudah login dan rolenya sesuai
        if (!$request->user() || $request->user()->role !== $role) {
            
            // JIKA REQUEST DARI API / MOBILE (Minta JSON)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized. Role tidak sesuai.'
                ], 403);
            }

            // JIKA REQUEST DARI WEB (Buka Browser biasa)
            // Lempar ke halaman login atau beranda dengan pesan error
            return redirect('/login')->with('error', 'Kamu tidak punya akses ke halaman tersebut.');
        }

        return $next($request);
    }
}