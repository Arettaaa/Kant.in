<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private function getCanteenId(): string
    {
        return (string) session('user')['canteen_id'];
    }

    /**
     * Daftar semua menu milik kantin ini.
     */
    public function index()
    {
        $canteenId = $this->getCanteenId();

        $menus = Menu::where('canteen_id', $canteenId)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(fn($menu) => $this->formatMenu($menu));

        $categories = $menus->pluck('category')->unique()->sort()->values();

        return view('admin.menu', compact('menus', 'categories'));
    }

    /**
     * Halaman form tambah menu baru.
     */
    public function create()
    {
        return view('admin.menu-tambah');
    }

    /**
     * Simpan menu baru.
     */
    public function store(Request $request)
    {
        $canteenId = $this->getCanteenId();

        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'price'                  => 'required|integer|min:0',
            'category'               => 'required|string|max:100',
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estimated_cooking_time' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['canteen_id']   = $canteenId;
        $validated['is_available'] = true;

        Menu::create($validated);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Halaman form edit menu.
     */
    public function edit($menuId)
    {
        $canteenId = $this->getCanteenId();

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            abort(404, 'Menu tidak ditemukan.');
        }

        return view('admin.menu-edit', ['menu' => $this->formatMenu($menu)]);
    }

    /**
     * Simpan perubahan menu.
     */
    public function update(Request $request, $menuId)
    {
        $canteenId = $this->getCanteenId();

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            abort(404, 'Menu tidak ditemukan.');
        }

        $validated = $request->validate([
            'name'                   => 'sometimes|string|max:255',
            'description'            => 'nullable|string',
            'price'                  => 'sometimes|integer|min:0',
            'category'               => 'sometimes|string|max:100',
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'estimated_cooking_time' => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) Storage::disk('public')->delete($menu->image);
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Hapus menu.
     */
    public function destroy($menuId)
    {
        $canteenId = $this->getCanteenId();

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            abort(404, 'Menu tidak ditemukan.');
        }

        if ($menu->image) Storage::disk('public')->delete($menu->image);
        $menu->delete();

        return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Toggle ketersediaan menu — AJAX, return JSON.
     */
    public function toggleAvailability(Request $request, $menuId)
    {
        $request->validate([
            'is_available' => 'required|in:0,1,true,false',
        ]);

        $canteenId = $this->getCanteenId();

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        $isAvailable = filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ?? (bool)(int) $request->is_available;

        Menu::where('_id', $menuId)->update(['is_available' => $isAvailable]);

        return response()->json([
            'success'      => true,
            'message'      => $isAvailable ? 'Menu tersedia.' : 'Menu tidak tersedia.',
            'is_available' => $isAvailable,
        ]);
    }

    private function formatMenu($menu): array
    {
        $data        = $menu->toArray();
        $data['_id'] = (string) $menu->_id;

        if (!empty($data['image'])) {
            $data['image'] = asset('storage/' . $data['image']);
        }

        return $data;
    }
}