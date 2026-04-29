<?php
// app/Http/Controllers/ForgotPasswordController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    // GET /lupa-sandi
    public function index()
    {
        return view('auth.lupa-sandi');
    }

    // POST /lupa-sandi
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|string']);

        $response = Http::timeout(15)
            ->post($this->apiUrl('/auth/forgot-password'), [
                'email' => $request->email,
            ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Email atau nomor HP tidak ditemukan.'
            ], 422);
        }

        session(['reset_email' => $request->email]);

        return response()->json(['success' => true]);
    }

    // GET /lupa-sandi/reset
    public function resetForm()
    {
        if (!session('reset_email')) {
            return redirect('/lupa-sandi');
        }
        return view('auth.reset-sandi');
    }

    // POST /lupa-sandi/reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email = session('reset_email');
        if (!$email) return redirect('/lupa-sandi');

        $response = Http::timeout(15)
            ->post($this->apiUrl('/auth/reset-password'), [
                'email'                 => $email,
                'password'              => $request->password,
                'password_confirmation' => $request->password_confirmation,
            ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => $response->json('message') ?? 'Gagal mereset password.'
            ], 422);
        }

        session()->forget('reset_email');
        return response()->json(['success' => true]);
    }
}
