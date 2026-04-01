<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->formatUserData($request->user()),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'     => 'nullable|string|max:255',
            'phone'    => 'nullable|string',
            'password' => 'nullable|min:8|confirmed', // Mencari password_confirmation
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // 2. Logika Ganti Password (Jika input password ada)
        if ($request->filled('password')) {
            // Cek apakah password lama dikirim dan benar
            if ($request->has('old_password')) {
                if (!Hash::check($request->old_password, $user->password)) {
                    return response()->json(['success' => false, 'message' => 'Password lama salah!'], 400);
                }
            }
            $user->password = Hash::make($request->password);
        }

        // 3. Update Data Lain
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('phone')) $user->phone = $request->phone;

        // 4. Upload Foto
        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile) Storage::disk('public')->delete($user->photo_profile);
            $user->photo_profile = $request->file('photo_profile')->store('profiles', 'public');
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
            'data'    => $this->formatUserData($user)
        ]);
    }

    private function formatUserData($user)
    {
        return [
            'id'            => (string) $user->_id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'role'          => $user->role,
            'photo_profile' => $user->photo_profile ? asset('storage/' . $user->photo_profile) : null,
        ];
    }
}