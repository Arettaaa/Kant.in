<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // GET /buyers/profiles  &  GET /admin/profiles
    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    }

    // PUT /buyers/profiles  &  PUT /admin/profiles
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => 'sometimes|string',
            'phone'         => 'sometimes|string',
            'photo_profile' => 'sometimes|string',
            'password'      => 'sometimes|string|min:8|confirmed',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => $user->fresh(),
        ]);
    }
}