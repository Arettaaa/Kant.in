<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // GET /buyers/carts
    public function show(Request $request)
    {
        $cart = Cart::where('user_id', (string) $request->user()->_id)->first();

        if (!$cart) {
            return response()->json([
                'success' => true,
                'data'    => null,
                'message' => 'Keranjang masih kosong.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => $cart,
        ]);
    }

    // POST /buyers/carts/items
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'menu_id'  => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($validated['menu_id']);

        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        if (!$menu->is_available || $menu->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Menu tidak tersedia.'], 422);
        }

        $userId    = (string) $request->user()->_id;
        $canteenId = (string) $menu->canteen_id;
        $cart      = Cart::where('user_id', $userId)->first();

        // Cek apakah cart sudah berisi item dari kantin lain
        if ($cart && $cart->canteen_id && (string) $cart->canteen_id !== $canteenId) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kamu berisi item dari kantin lain. Kosongkan keranjang terlebih dahulu.',
                'code'    => 'DIFFERENT_CANTEEN',
            ], 422);
        }

        if (!$cart) {
            $cart = Cart::create([
                'user_id'      => $userId,
                'canteen_id'   => $canteenId,
                'items'        => [],
                'total_amount' => 0,
            ]);
        }

        $items  = $cart->items ?? [];
        $found  = false;

        foreach ($items as &$item) {
            if ((string) $item['menu_id'] === (string) $menu->_id) {
                $item['quantity'] += $validated['quantity'];
                $item['subtotal']  = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = [
                'menu_id'  => (string) $menu->_id,
                'name'     => $menu->name,
                'price'    => $menu->price,
                'quantity' => $validated['quantity'],
                'subtotal' => $menu->price * $validated['quantity'],
            ];
        }

        $total = array_sum(array_column($items, 'subtotal'));

        $cart->update([
            'canteen_id'   => $canteenId,
            'items'        => $items,
            'total_amount' => $total,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang.',
            'data'    => $cart->fresh(),
        ]);
    }

    // PUT /buyers/carts/items/{itemId}
    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = (string) $request->user()->_id;
        $cart   = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Keranjang tidak ditemukan.'], 404);
        }

        $items = $cart->items ?? [];
        $found = false;

        foreach ($items as &$item) {
            if ((string) $item['menu_id'] === $itemId) {
                $item['quantity'] = $validated['quantity'];
                $item['subtotal'] = $item['price'] * $validated['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        $total = array_sum(array_column($items, 'subtotal'));

        $cart->update([
            'items'        => $items,
            'total_amount' => $total,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui.',
            'data'    => $cart->fresh(),
        ]);
    }

    // DELETE /buyers/carts/items/{itemId}
    public function removeItem(Request $request, $itemId)
    {
        $userId = (string) $request->user()->_id;
        $cart   = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Keranjang tidak ditemukan.'], 404);
        }

        $items    = $cart->items ?? [];
        $filtered = array_values(array_filter($items, fn($item) => (string) $item['menu_id'] !== $itemId));

        if (count($filtered) === count($items)) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        // Jika keranjang kosong setelah remove, hapus cart
        if (empty($filtered)) {
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item dihapus. Keranjang sekarang kosong.',
            ]);
        }

        $total = array_sum(array_column($filtered, 'subtotal'));

        $cart->update([
            'items'        => $filtered,
            'total_amount' => $total,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang.',
            'data'    => $cart->fresh(),
        ]);
    }
}