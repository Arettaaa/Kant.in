<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KeamananController extends Controller
{
    public function index()
    {
        return view('pelanggan.keamanan-akun');
    }

    public function updatePassword(Request $request)
    {
        // 1. Validasi Input (Otomatis mengecek password lama)
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan sistem kami.',
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // 2. Proses Simpan dengan Try-Catch
        try {
            $request->user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Kembalikan session sukses untuk mentrigger modal animasi
            return back()->with('success_password', 'Kata sandi Anda berhasil diperbarui.');

        } catch (\Exception $e) {
            // Kalau gagal nyimpen (misal database bermasalah)
            return back()->with('error_password', 'Sistem gagal menyimpan perubahan. Silakan coba lagi nanti.');
        }
    }
}