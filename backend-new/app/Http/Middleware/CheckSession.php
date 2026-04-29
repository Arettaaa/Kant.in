<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('api_token')) {
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