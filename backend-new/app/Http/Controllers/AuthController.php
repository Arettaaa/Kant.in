<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function processRegister(Request $request)
    {
        $role = $request->role ?? 'pembeli';

        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/'],
            'phone'    => 'required|string',
        ];

        if ($role === 'admin_kantin') {
            $rules['canteen_name']        = 'required|string|max:255';
            $rules['canteen_location']    = 'nullable|string|max:255';
            $rules['canteen_description'] = 'nullable|string|max:500';
            $rules['canteen_phone']       = 'nullable|string|max:20';
        }

        $request->validate($rules, [
            'email.unique'   => 'Email ini sudah terdaftar.',
            'password.min'   => 'Kata sandi minimal 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, dan angka.',
        ]);

        // Panggil langsung tanpa HTTP
        $apiController = new \App\Http\Controllers\Api\AuthController();
        $apiResponse = $apiController->register($request);

        $data = json_decode($apiResponse->getContent(), true);
        $status = $apiResponse->getStatusCode();

        if ($status >= 400) {
            $errors = $data['errors'] ?? ['message' => [$data['message'] ?? 'Terjadi kesalahan.']];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['errors' => $errors], $status);
            }

            return back()->withErrors($errors)->withInput();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/login',
            ]);
        }

        $msg = $role === 'admin_kantin'
            ? 'Pendaftaran berhasil! Akun sedang menunggu persetujuan admin.'
            : 'Akun berhasil dibuat! Silakan masuk.';

        return redirect('/login')->with('success', $msg);
    }
    public function processLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Panggil langsung tanpa HTTP
        $apiController = new \App\Http\Controllers\Api\AuthController();
        $apiResponse = $apiController->login($request);

        $data = json_decode($apiResponse->getContent(), true);
        $status = $apiResponse->getStatusCode();

        if ($status !== 200) {
            $message = $data['message'] ?? 'Email atau kata sandi salah.';
            return back()->withErrors(['message' => $message])->withInput();
        }

        Session::put('api_token', $data['token']);
        Session::put('user', $data['user']);

        $role = $data['user']['role'] ?? 'pembeli';

        return match ($role) {
            'admin_global' => redirect()->intended('/admin/global/dasbor'),
            'admin_kantin' => redirect()->intended('/admin/pesanan'),
            default        => redirect()->intended('/beranda'),
        };
    }

    public function logout(Request $request)
    {
        $token = Session::get('api_token');

        if ($token) {
            Http::timeout(15)->withToken($token)->delete($this->apiUrl('/auth/sessions'));
        }

        Session::forget(['api_token', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pelanggan.login');
    }
}
