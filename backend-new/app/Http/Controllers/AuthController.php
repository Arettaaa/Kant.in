<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function processRegister(Request $request)
    {
        $role = $request->role ?? 'pembeli';

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required|string', // Pastikan phone divalidasi
        ];

        if ($role === 'admin_kantin') {
            $rules['canteen_name'] = 'required|string|max:255';
        }

        $request->validate($rules, [
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.min' => 'Kata sandi minimal 6 karakter.'
        ]);

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

        // Simpan User dengan Field Phone
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // DISIMPAN DI SINI
            'password' => Hash::make($request->password),
            'role' => $role,
            'canteen_id' => $canteenId,
            'status' => $role === 'admin_kantin' ? 'pending' : 'active',
        ]);

        $message = ($role === 'admin_kantin') 
            ? 'Pendaftaran Berhasil! Akun sedang menunggu persetujuan admin.' 
            : 'Akun Berhasil Dibuat! Silakan masuk untuk melanjutkan.';

        return redirect('/login')->with('success', $message);
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['message' => 'Email atau kata sandi salah.'])->withInput();
        }

        if ($user->status === 'pending') {
            return back()->withErrors(['message' => 'Akun kamu belum disetujui oleh admin. Mohon tunggu.']);
        }

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            if ($user->role === 'admin_kantin') {
                return redirect()->intended('/admin/pesanan');
            }
            return redirect()->intended('/beranda');
        }

        return back()->withErrors(['message' => 'Terjadi kesalahan sistem, silakan coba lagi.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pelanggan.login');
    }
}