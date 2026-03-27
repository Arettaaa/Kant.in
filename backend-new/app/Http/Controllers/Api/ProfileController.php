<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $data = [
            'id'            => (string) $user->_id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'role'          => $user->role,
            'status'        => $user->status,
            'photo_profile' => !empty($user->photo_profile)
                                ? asset('storage/' . $user->photo_profile)
                                : null,
        ];

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => 'sometimes|string',
            'phone'         => 'sometimes|string',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'      => 'sometimes|string|min:8|confirmed',
        ]);

        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $validated['photo_profile'] = $request->file('photo_profile')->store('profiles', 'public');
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        $user->refresh();

        $data = [
            'id'            => (string) $user->_id,
            'name'          => $user->name,
            'email'         => $user->email,
            'phone'         => $user->phone,
            'role'          => $user->role,
            'status'        => $user->status,
            'photo_profile' => !empty($user->photo_profile)
                                ? asset('storage/' . $user->photo_profile)
                                : null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $data,
        ]);
    }
}