<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request, $canteenId)
    {
        $canteen = Canteen::find($canteenId);

        // Tambahkan pengecekan is_active dan status di sini
        if (!$canteen || !$canteen->is_active || $canteen->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan atau sedang tidak aktif.'], 404);
        }

        $query = Menu::where('canteen_id', $canteenId);

        if ($request->has('search')) {
            $query->where('name', 'regex', '/' . $request->search . '/i');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $menus = $query->get()->map(fn($menu) => $this->formatMenu($menu));

        return response()->json(['success' => true, 'data' => $menus]);
    }

    public function availabilities($canteenId)
    {
        $canteen = Canteen::find($canteenId);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $menus = Menu::where('canteen_id', $canteenId)->get(['_id', 'name', 'is_available']);

        return response()->json(['success' => true, 'data' => $menus]);
    }

    public function store(Request $request, $canteenId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $validated = $request->validate([
            'name'                   => 'required|string',
            'description'            => 'nullable|string',
            'price'                  => 'required|integer|min:0',
            'category'               => 'required|string',
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estimated_cooking_time' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['canteen_id']   = $canteenId;
        $validated['is_available'] = true;

        $menu = Menu::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data'    => $this->formatMenu($menu),
        ], 201);
    }

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
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estimated_cooking_time' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data'    => $this->formatMenu($menu->fresh()),
        ]);
    }

    public function updateAvailability(Request $request, $canteenId, $menuId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        $request->validate([
            'is_available' => 'required|in:0,1,true,false',
        ]);

        $isAvailable = filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_null($isAvailable)) {
            $isAvailable = (bool)(int) $request->is_available;
        }

        Menu::where('_id', $menuId)->update(['is_available' => $isAvailable]);
        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();

        return response()->json([
            'success' => true,
            'message' => 'Ketersediaan menu berhasil diperbarui.',
            'data'    => $this->formatMenu($menu),
        ]);
    }

    public function destroy(Request $request, $canteenId, $menuId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $menu = Menu::where('_id', $menuId)->where('canteen_id', $canteenId)->first();
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return response()->json(['success' => true, 'message' => 'Menu berhasil dihapus.']);
    }

    private function formatMenu($menu)
    {
        $data = $menu->toArray();
        $data['_id'] = (string) $menu->_id; // ← paksa jadi string
        if (!empty($data['image'])) {
            $data['image'] = asset('storage/' . $data['image']);
        }
        return $data;
    }

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

    public function allMenus(Request $request)
    {
        $activeCanteens = Canteen::where('is_active', true)
            ->where('status', 'active')
            ->get();

        // Buat map id => name untuk lookup cepat
        $canteenMap = [];
        foreach ($activeCanteens as $canteen) {
            $canteenMap[(string) $canteen->_id] = $canteen->name;
        }

        $activeCanteenIds = [];
        foreach ($activeCanteens as $canteen) {
            $activeCanteenIds[] = $canteen->_id;
            $activeCanteenIds[] = (string) $canteen->_id;
        }

        $query = Menu::whereIn('canteen_id', $activeCanteenIds);

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'regex', '/' . $request->search . '/i');
        }

        if ($request->has('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        $menus = $query->get()->map(function ($menu) use ($canteenMap) {
            $data = $this->formatMenu($menu);
            $data['canteen_name'] = $canteenMap[(string) $menu->canteen_id] ?? 'Kantin';
            return $data;
        });

        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatMenu($menu)
        ]);
    }
}
