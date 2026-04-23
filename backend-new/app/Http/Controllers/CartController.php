<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function token(Request $request): string
    {
        return $request->session()->get('api_token', '');
    }

    // GET /keranjang/ongkir/{canteenId}
    // GET /keranjang/ongkir/{canteenId}
    public function getOngkir(Request $request, string $canteenId)
    {
        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl("/canteens/{$canteenId}"));

        if ($response->successful()) {
            $canteen = $response->json('data');
            return response()->json([
                'success'      => true,
                'delivery_fee' => $canteen['delivery_fee_flat'] ?? 0,
            ]);
        }

        return response()->json(['success' => false, 'delivery_fee' => 0]);
    }


    // GET /keranjang
    public function index(Request $request)
    {

        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl('/buyers/carts'));

        $cart = null;
        if ($response->successful()) {
            $cart = $response->json('data');
        }

        return view('pelanggan.keranjang', compact('cart'));
    }

    // POST /keranjang/items
    public function addItem(Request $request)
    {
        $request->validate([
            'menu_id'  => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->post($this->apiUrl('/buyers/carts/items'), [
                'menu_id'  => $request->menu_id,
                'quantity' => (int) $request->quantity,
            ]);

        if ($request->expectsJson()) {
            return response()->json($response->json(), $response->status());
        }

        if ($response->successful()) {
            return back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
        }

        return back()->with('error', $response->json('message') ?? 'Gagal menambahkan item.');
    }

    // PUT /keranjang/items/{menuId}
    public function updateItem(Request $request, string $menuId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->put($this->apiUrl("/buyers/carts/items/{$menuId}"), [
                'quantity' => (int) $request->quantity,
            ]);

        return response()->json($response->json(), $response->status());
    }

    // DELETE /keranjang/items/{menuId}
    public function removeItem(Request $request, string $menuId)
    {
        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->delete($this->apiUrl("/buyers/carts/items/{$menuId}"));

        return response()->json($response->json(), $response->status());
    }

    // DELETE /keranjang (hapus semua item yang dipilih)
    public function clearSelected(Request $request)
    {
        $request->validate([
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'string',
        ]);

        $errors = [];
        foreach ($request->menu_ids as $menuId) {
            $response = Http::withToken($this->token($request))
                ->timeout(15)
                ->delete($this->apiUrl("/buyers/carts/items/{$menuId}"));

            if (!$response->successful()) {
                $errors[] = $menuId;
            }
        }

        if (!empty($errors)) {
            return response()->json(['success' => false, 'message' => 'Beberapa item gagal dihapus.'], 422);
        }

        return response()->json(['success' => true, 'message' => 'Item berhasil dihapus.']);
    }
}
