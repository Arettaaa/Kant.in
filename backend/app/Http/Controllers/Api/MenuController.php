<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // PUBLIC: GET /canteens/{id}/menus?search=...
    public function index(Request $request, $canteenId)
    {
        $canteen = Canteen::find($canteenId);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $query = Menu::where('canteen_id', $canteenId);

        if ($request->has('search')) {
            $query->where('name', 'regex', '/' . $request->search . '/i');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->get();

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ]);
    }

    // PUBLIC: GET /canteens/{id}/menus/availabilities
    public function availabilities($canteenId)
    {
        $canteen = Canteen::find($canteenId);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $menus = Menu::where('canteen_id', $canteenId)
            ->get(['_id', 'name', 'is_available', 'stock']);

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ]);
    }

    // ADMIN KANTIN: POST /canteens/{id}/menus
    public function store(Request $request, $canteenId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $validated = $request->validate([
            'name'                   => 'required|string',
            'description'            => 'nullable|string',
            'price'                  => 'required|integer|min:0',
            'category'               => 'required|string',
            'stock'                  => 'required|integer|min:0',
            'image'                  => 'nullable|string',
            'estimated_cooking_time' => 'required|integer|min:1',
        ]);

        $validated['canteen_id']   = $canteenId;
        $validated['is_available'] = $validated['stock'] > 0;

        $menu = Menu::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data'    => $menu,
        ], 201);
    }

    // ADMIN KANTIN: PUT /canteens/{id}/menus/{menuId}
    public function update(Request $request, $canteenId, $menuId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name'                   => 'sometimes|string',
            'description'            => 'nullable|string',
            'price'                  => 'sometimes|integer|min:0',
            'category'               => 'sometimes|string',
            'stock'                  => 'sometimes|integer|min:0',
            'image'                  => 'nullable|string',
            'estimated_cooking_time' => 'sometimes|integer|min:1',
        ]);

        // Auto set is_available false jika stock 0
        if (isset($validated['stock']) && $validated['stock'] == 0) {
            $validated['is_available'] = false;
        }

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data'    => $menu,
        ]);
    }

    // ADMIN KANTIN: PUT /canteens/{id}/menus/{menuId}/availabilities
    public function updateAvailability(Request $request, $canteenId, $menuId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'is_available' => 'required|boolean',
        ]);

        // Tidak boleh set available jika stok 0
        if ($validated['is_available'] && $menu->stock == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mengaktifkan menu dengan stok 0.',
            ], 422);
        }

        $menu->update(['is_available' => $validated['is_available']]);

        return response()->json([
            'success' => true,
            'message' => 'Ketersediaan menu berhasil diperbarui.',
            'data'    => $menu,
        ]);
    }

    // ADMIN KANTIN: DELETE /canteens/{id}/menus/{menuId}
    public function destroy(Request $request, $canteenId, $menuId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus.',
        ]);
    }

    // Helper: pastikan admin kantin hanya bisa akses kantin miliknya
    private function authorizeAdminKantin(Request $request, $canteenId)
    {
        $user = $request->user();
        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $canteenId) {
            abort(response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kantin ini.',
            ], 403));
        }
    }
}