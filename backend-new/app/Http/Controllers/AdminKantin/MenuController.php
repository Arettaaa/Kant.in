<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Daftar semua menu milik kantin ini.
     */
    public function index()
    {
        $canteenId = (string) auth()->user()->canteen_id;

        $menus = Menu::where('canteen_id', $canteenId)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(fn($menu) => $this->formatMenu($menu));

        // Ambil kategori unik untuk filter di view
        $categories = $menus->pluck('category')->unique()->sort()->values();

        return view('admin-kantin.menu.index', compact('menus', 'categories'));
    }

    /**
     * Halaman form tambah menu baru.
     */
    public function create()
    {
        return view('admin-kantin.menu.create');
    }

    /**
     * Simpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $canteenId = (string) auth()->user()->canteen_id;

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

        return redirect()
            ->route('admin-kantin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Halaman form edit menu.
     */
    public function edit($menuId)
    {
        $canteenId = (string) auth()->user()->canteen_id;

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            abort(404, 'Menu tidak ditemukan.');
        }

        return view('admin-kantin.menu.edit', [
            'menu' => $this->formatMenu($menu),
        ]);
    }

    /**
     * Simpan perubahan menu.
     */
    public function update(Request $request, $menuId)
    {
        $canteenId = (string) auth()->user()->canteen_id;

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
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($validated);

        return redirect()
            ->route('admin-kantin.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Hapus menu.
     */
    public function destroy($menuId)
    {
        $canteenId = (string) auth()->user()->canteen_id;

        $menu = Menu::where('_id', $menuId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$menu) {
            abort(404, 'Menu tidak ditemukan.');
        }

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()
            ->route('admin-kantin.menu.index')
            ->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Toggle ketersediaan menu (tersedia / tidak tersedia).
     * Dipanggil via AJAX — toggle switch di halaman daftar menu.
     */
    public function updateAvailability(Request $request, $menuId)
    {
        $request->validate([
            'is_available' => 'required|in:0,1,true,false',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;

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

    /**
     * Format menu untuk dikirim ke view (URL gambar lengkap, ID string).
     */
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