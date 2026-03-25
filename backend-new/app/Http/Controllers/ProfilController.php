<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // Menampilkan Halaman Profil Utama
    public function index()
    {
        $user = Auth::user();
        return view('pelanggan.profil', compact('user'));
    }

    // Menampilkan Halaman Form Edit
    public function edit()
    {
        $user = Auth::user();
        return view('pelanggan.edit-profil', compact('user'));
    }

    // MENAMPILKAN HALAMAN DETAIL DATA DIRI (Ini yang tadi hilang)
    public function dataDiri()
    {
        $user = Auth::user();
        // Pastikan view-nya mengarah ke folder yang benar (pelanggan.data-diri)
        return view('pelanggan.data-diri', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->_id,
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            if ($request->hasFile('photo_profile')) {
                if ($user->photo_profile) {
                    Storage::disk('public')->delete($user->photo_profile);
                }
                $user->photo_profile = $request->file('photo_profile')->store('profiles', 'public');
            }

            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->save();

            return back()->with('success_update', true);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }
}