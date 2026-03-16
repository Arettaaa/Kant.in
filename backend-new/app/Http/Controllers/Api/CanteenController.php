<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CanteenController extends Controller
{
    // PUBLIC: GET /canteens
    public function index()
    {
        $canteens = Canteen::where('is_active', true)->get()
            ->map(fn($canteen) => $this->formatCanteen($canteen));

        return response()->json([
            'success' => true,
            'data' => $canteens,
        ]);
    }

    // PUBLIC: GET /canteens/{id}
    public function show($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatCanteen($canteen),
        ]);
    }

    // ADMIN GLOBAL: POST /canteens
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat' => 'required|integer|min:0',
            'operating_hours' => 'required|array',
            'operating_hours.open' => 'required|string',
            'operating_hours.close' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        $validated['is_active'] = true;

        $canteen = Canteen::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil ditambahkan.',
            'data' => $this->formatCanteen($canteen),
        ], 201);
    }

    // ADMIN GLOBAL: PUT /canteens/{id}
    public function update(Request $request, $id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'location' => 'sometimes|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat' => 'sometimes|integer|min:0',
            'operating_hours' => 'sometimes|array',
            'operating_hours.open' => 'sometimes|string',
            'operating_hours.close' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($canteen->image) {
                Storage::disk('public')->delete($canteen->image);
            }
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        $canteen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil diperbarui.',
            'data' => $this->formatCanteen($canteen),
        ]);
    }

    // ADMIN GLOBAL: DELETE /canteens/{id}
    public function destroy($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        // Hapus foto jika ada
        if ($canteen->image) {
            Storage::disk('public')->delete($canteen->image);
        }

        $canteen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil dihapus.',
        ]);
    }

    // Helper: format image URL
    private function formatCanteen($canteen)
    {
        $data = $canteen->toArray();
        if (!empty($data['image'])) {
            $data['image'] = asset('storage/' . $data['image']);
        }
        return $data;
    }

    // ADMIN GLOBAL: POST /canteens/{id}/admins
    public function assignAdmin(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'user_id' => 'required|string',
        ]);

        $user = \App\Models\User::find($validated['user_id']);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        if ($user->role !== 'admin_kantin') {
            return response()->json(['success' => false, 'message' => 'User bukan admin kantin.'], 422);
        }

        \App\Models\User::where('_id', $validated['user_id'])
            ->update(['canteen_id' => (string) $id]);

        return response()->json([
            'success' => true,
            'message' => 'Admin kantin berhasil di-assign ke kantin.',
            'data' => [
                'canteen_id' => $id,
                'user_id' => $validated['user_id'],
                'user_name' => $user->name,
            ]
        ]);
    }
}