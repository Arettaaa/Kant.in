<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->toArray();
        if ($user['role'] === 'pembeli') {
            unset($user['canteen_id']);
        }
        return response()->json([
            'success' => true,
            'data'    => $user,
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

        $data = $user->fresh()->toArray();
        if ($data['role'] === 'pembeli') {
            unset($data['canteen_id']);
        }
        if (!empty($data['photo_profile'])) {
            $data['photo_profile'] = asset('storage/' . $data['photo_profile']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $data,
        ]);
    }
}