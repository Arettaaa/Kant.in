<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KeamananController extends Controller
{
    public function index()
    {
        return view('pelanggan.keamanan-akun');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // 2. Cek Password Lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan sistem kami.']);
        }

        // 3. Proses Simpan dengan Try-Catch
        try {
            $user->password = bcrypt($request->new_password);
            $user->save();

            // Kembalikan session sukses untuk mentrigger modal animasi
            return back()->with('success_password', 'Kata sandi Anda berhasil diperbarui.');

        } catch (\Exception $e) {
            // Kalau gagal nyimpen (misal server error)
            return back()->with('error_password', 'Sistem gagal menyimpan perubahan. Silakan coba lagi nanti.');
        }
    }
}