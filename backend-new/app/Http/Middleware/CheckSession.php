<?php
// app/Http/Middleware/CheckSession.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('api_token')) {
            return redirect()->route('pelanggan.login');
        }

        return $next($request);
    }
}