<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\Request;

class CanteenController extends Controller
{
    // PUBLIC: GET /canteens
    public function index()
    {
        $canteens = Canteen::where('is_active', true)->get();

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
            'data' => $canteen,
        ]);
    }

    // ADMIN GLOBAL: POST /canteens
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string',
            'description'        => 'nullable|string',
            'location'           => 'required|string',
            'phone'              => 'nullable|string',
            'image'              => 'nullable|string',
            'delivery_fee_flat'  => 'required|integer|min:0',
            'operating_hours'    => 'required|array',
            'operating_hours.open'  => 'required|string',
            'operating_hours.close' => 'required|string',
        ]);

        $validated['is_active'] = true;

        $canteen = Canteen::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil ditambahkan.',
            'data'    => $canteen,
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
            'name'               => 'sometimes|string',
            'description'        => 'nullable|string',
            'location'           => 'sometimes|string',
            'phone'              => 'nullable|string',
            'image'              => 'nullable|string',
            'delivery_fee_flat'  => 'sometimes|integer|min:0',
            'operating_hours'    => 'sometimes|array',
            'operating_hours.open'  => 'sometimes|string',
            'operating_hours.close' => 'sometimes|string',
            'is_active'          => 'sometimes|boolean',
        ]);

        $canteen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil diperbarui.',
            'data'    => $canteen,
        ]);
    }

    // ADMIN GLOBAL: DELETE /canteens/{id}
    public function destroy($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $canteen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil dihapus.',
        ]);
    }
}