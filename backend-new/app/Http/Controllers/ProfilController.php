<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    // Menampilkan Halaman Profil Utama
    public function index(Request $request)
    {
        $user = $request->user();

        $views = [
            'admin_global' => 'admin_global.profil',
            'admin_kantin' => 'admin_kantin.profil',
            'pembeli' => 'pelanggan.profil',
        ];

        $view = $views[$user->role] ?? 'pelanggan.profil';

        return view($view, ['user' => $user]);
    }

    // Menampilkan Halaman Form Edit
    public function edit(Request $request)
    {
        $user = $request->user();

        $views = [
            'admin_global' => 'admin_global.edit-profil',
            'admin_kantin' => 'admin_kantin.edit-profil',
            'pembeli' => 'pelanggan.edit-profil',
        ];

        $view = $views[$user->role] ?? 'pelanggan.edit-profil';

        return view($view, ['user' => $user]);
    }
    // Menampilkan Halaman Detail Data Diri
    public function dataDiri(Request $request)
    {
        $user = $request->user();

        $views = [
            'admin_global' => 'admin_global.data-diri',
            'admin_kantin' => 'admin_kantin.data-diri',
            'pembeli' => 'pelanggan.data-diri',
        ];

        $view = $views[$user->role] ?? 'pelanggan.data-diri';

        return view($view, ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validasi Input dan simpan hasilnya ke variabel $validated
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->_id)],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        try {
            // 2. Cek dan Proses Upload Foto
            if ($request->hasFile('photo_profile')) {
                // Hapus foto lama jika ada
                if ($user->photo_profile) {
                    Storage::disk('public')->delete($user->photo_profile);
                }

                // Timpa nilai 'photo_profile' di array $validated dengan path file yang baru
                $validated['photo_profile'] = $request->file('photo_profile')->store('profiles', 'public');
            }

            // 3. Simpan perubahan menggunakan Mass Assignment
            $user->update($validated);

            return back()->with('success_update', true);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }
}
