<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class KeamananController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function apiToken(): string
    {
        return Session::get('api_token', '');
    }

    public function index()
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('pelanggan.login');

        $role = $user['role'] ?? 'pembeli';

        $views = [
            'admin_global' => 'admin_global.keamanan',
            'admin_kantin' => 'admin_kantin.keamanan',
            'pembeli'      => 'pelanggan.keamanan-akun',
        ];

        return view($views[$role] ?? 'pelanggan.keamanan-akun');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => [
                'required', 'confirmed', 'min:8',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'
            ],
        ], [
            'current_password.required'  => 'Kata sandi saat ini wajib diisi.',
            'new_password.required'      => 'Kata sandi baru wajib diisi.',
            'new_password.confirmed'     => 'Konfirmasi kata sandi baru tidak cocok.',
            'new_password.min'           => 'Kata sandi minimal 8 karakter.',
            'new_password.regex'         => 'Kata sandi harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        $user = Session::get('user');
        if (!$user) return redirect()->route('pelanggan.login');

        $role     = $user['role'] ?? 'pembeli';
        $prefix   = $role === 'admin_kantin' ? '/admin' : '/buyers';
        $endpoint = $prefix . '/profiles';

        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->post($this->apiUrl($endpoint), [
                'old_password' => $request->current_password,
                'password'     => $request->new_password,
                'password_confirmation' => $request->new_password_confirmation,
            ]);

        if (!$response->successful()) {
            $message = $response->json('message') ?? 'Kata sandi saat ini salah.';
            return back()->withErrors(['current_password' => $message]);
        }

        return back()->with('success_password', 'Kata sandi Anda berhasil diperbarui.');
    }
}