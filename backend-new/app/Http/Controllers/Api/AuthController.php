<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

        // Tambah validasi canteen_name jika admin_kantin
        if ($role === 'admin_kantin') {
            $rules['canteen_name']        = 'required|string';
            $rules['canteen_location']    = 'nullable|string';    // ← tambah
            $rules['canteen_description'] = 'nullable|string';    // ← tambah
            $rules['canteen_phone']       = 'nullable|string';    // ← tambah
        }

        $request->validate($rules);

        $canteenId = null;
        if ($role === 'admin_kantin') {
            $canteen = Canteen::create([
                'name'              => $request->canteen_name,
                'location'          => $request->canteen_location ?? null,    // ← tambah
                'description'       => $request->canteen_description ?? null, // ← tambah
                'phone'             => $request->canteen_phone ?? null,       // ← tambah
                'is_active'         => false,
                'status'            => 'pending',
                'delivery_fee_flat' => 0,
                'operating_hours'   => ['open' => '08:00', 'close' => '17:00'],
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

        // Admin kantin belum dapat token, harus tunggu approve
        if ($role === 'admin_kantin') {
            return response()->json([
                'message' => 'Registrasi berhasil! Menunggu persetujuan admin.',
                'user' => $user,
            ], 201);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => $this->formatUser($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if ($user->status === 'pending') {
            return response()->json([
                'message' => 'Akun kamu belum disetujui oleh admin. Mohon tunggu.',
            ], 403);
        }

        if ($user->status === 'rejected') {
            return response()->json([
                'message' => 'Akun kamu telah ditolak oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $this->formatUser($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil',
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

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->orWhere('phone', $request->email)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau nomor HP tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email ditemukan',
            'email'   => $user->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|string',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui',
        ]);
    }
}
