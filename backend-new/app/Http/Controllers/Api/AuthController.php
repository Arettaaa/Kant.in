<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // PENTING: Tambahkan ini untuk Web Session

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $role = $request->role ?? 'pembeli';

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
            'role' => 'nullable|in:admin_kantin,pembeli',
        ];

        if ($role === 'admin_kantin') {
            $rules['canteen_name'] = 'required|string';
        }

        $request->validate($rules);

        $canteenId = null;
        if ($role === 'admin_kantin') {
            $canteen = Canteen::create([
                'name' => $request->canteen_name,
                'is_active' => false,
                'status' => 'pending',
                'delivery_fee_flat' => 0,
                'operating_hours' => ['open' => '08:00', 'close' => '17:00'],
            ]);
            $canteenId = (string) $canteen->_id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $role,
            'canteen_id' => $canteenId,
            'status' => $role === 'admin_kantin' ? 'pending' : 'active',
        ]);

        if ($role === 'admin_kantin') {
            return response()->json([
                'message' => 'Registrasi berhasil! Menunggu persetujuan admin.',
                'user' => $user,
                'redirect' => '/login' // Beritahu web untuk ke halaman login
            ], 201);
        }

        // 1. MOBILE: Generate Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 2. WEB: Langsung login-kan user pakai Session setelah register
        Auth::login($user);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $this->formatUser($user),
            'redirect' => '/beranda' // Beritahu web untuk langsung masuk
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        if ($user->status === 'pending') {
            return response()->json(['message' => 'Akun kamu belum disetujui oleh admin. Mohon tunggu.'], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json(['message' => 'Akun kamu telah ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.'], 403);
        }

        // 1. UNTUK MOBILE: Generate API Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 2. UNTUK WEB: Daftarkan Web Session
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        }

        // 3. Tentukan rute redirect untuk Web berdasarkan role
        $redirectUrl = '/beranda';
        if ($user->role === 'admin_global') {
            $redirectUrl = '/admin/global/dasbor';
        } elseif ($user->role === 'admin_kantin') {
            $redirectUrl = '/admin/pesanan';
        }

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token, // Mobile akan ambil ini
            'user' => $this->formatUser($user),
            'redirect' => $redirectUrl // Web akan ambil ini
        ]);
    }

    public function logout(Request $request)
    {
        // 1. UNTUK MOBILE: Hapus Token API (jika ada request user)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        // 2. UNTUK WEB: Hapus Session dan Cookie
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout berhasil',
            'redirect' => '/login' // Beritahu Javascript web untuk balik ke login
        ]);
    }

    private function formatUser($user)
    {
        $data = $user->toArray();
        if ($data['role'] === 'pembeli') {
            unset($data['canteen_id']);
        }
        return $data;
    }
}