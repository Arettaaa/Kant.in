<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function token(): string
    {
        return Session::get('api_token', '');
    }

    private function canteenId(): string
    {
        return Session::get('user', [])['canteen_id'] ?? '';
    }

    // GET /admin/menu
    public function index(Request $request)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/menus"), [
                'search'   => $request->search,
                'category' => $request->category,
            ]);

        $menus = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('admin.kelola-menu', compact('menus'));
    }

    // GET /admin/menu/tambah
    public function create()
    {
        return view('admin.tambah-menu');
    }

    // POST /admin/menu
    public function store(Request $request)
    {
        $canteenId = $this->canteenId();

        $request->validate([
            'name'                   => 'required|string',
            'description'            => 'nullable|string',
            'price'                  => 'required|integer|min:0',
            'category'               => 'required|string',
            'estimated_cooking_time' => 'nullable|integer|min:1',
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $http = Http::withToken($this->token())->asMultipart();

        if ($request->hasFile('image')) {
            $http = $http->attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $http->post($this->apiUrl("/canteens/{$canteenId}/menus"), [
            'name'                   => $request->name,
            'description'            => $request->description,
            'price'                  => $request->price,
            'category'               => $request->category,
            'estimated_cooking_time' => $request->estimated_cooking_time,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.menu')->with('success', 'Menu berhasil ditambahkan.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal menambahkan menu.')->withInput();
    }

    // GET /admin/menu/{id}/edit
    public function edit($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/menus/{$id}"));

        if (!$response->successful()) {
            return redirect()->route('admin.menu')->with('error', 'Menu tidak ditemukan.');
        }

        $menu = $response->json()['data'];

        return view('admin.edit-menu', compact('menu'));
    }

    // PUT /admin/menu/{id}
    public function update(Request $request, $id)
    {
        $canteenId = $this->canteenId();

        $request->validate([
            'name'                   => 'sometimes|string',
            'description'            => 'nullable|string',
            'price'                  => 'sometimes|integer|min:0',
            'category'               => 'sometimes|string',
            'estimated_cooking_time' => 'nullable|integer|min:1',
            'image'                  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $http = Http::withToken($this->token())->asMultipart();

        if ($request->hasFile('image')) {
            $http = $http->attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $http->post($this->apiUrl("/canteens/{$canteenId}/menus/{$id}"), [
            'name'                   => $request->name,
            'description'            => $request->description,
            'price'                  => $request->price,
            'category'               => $request->category,
            'estimated_cooking_time' => $request->estimated_cooking_time,
            '_method'                => 'PUT',
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.menu')->with('success', 'Menu berhasil diperbarui.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal memperbarui menu.')->withInput();
    }

    // DELETE /admin/menu/{id}
    public function destroy($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->delete($this->apiUrl("/canteens/{$canteenId}/menus/{$id}"));

        if ($response->successful()) {
            return redirect()->route('admin.menu')->with('success', 'Menu berhasil dihapus.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal menghapus menu.');
    }

    // PUT /admin/menu/{id}/availability
    public function toggleAvailability(Request $request, $id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->put($this->apiUrl("/canteens/{$canteenId}/menus/{$id}/availabilities"), [
                'is_available' => $request->is_available,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Status menu berhasil diperbarui.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal memperbarui status menu.');
    }
}