<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminKantin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('api_token')) {
            return redirect()->route('admin.login');
        }

        $user = $request->session()->get('user');

        if (!$user || $user['role'] !== 'admin_kantin') {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}