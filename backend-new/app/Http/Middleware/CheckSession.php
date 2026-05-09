<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('api_token')) {
            // Kalau request AJAX/fetch → return JSON 401, bukan redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi telah berakhir. Silakan login kembali.',
                ], 401);
            }

            // Admin → redirect ke /admin/login
            if ($request->is('admin/*')) {
                return redirect('/admin/login');
            }

            // Pelanggan → redirect ke /login
            return redirect()->route('pelanggan.login');
        }

        return $next($request);
    }
}