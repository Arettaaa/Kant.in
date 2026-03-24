<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\Canteen;
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
                'data' => null,
                'message' => 'Keranjang masih kosong.',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $cart,
        ]);
    }

    // POST /buyers/carts/items
    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($validated['menu_id']);
        if (!$menu) {
            return response()->json(['success' => false, 'message' => 'Menu tidak ditemukan.'], 404);
        }

        if (!$menu->is_available) {
            return response()->json(['success' => false, 'message' => 'Menu tidak tersedia.'], 422);
        }

        $canteen = Canteen::find($menu->canteen_id);
        if (!$canteen || !$canteen->is_active) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak tersedia.'], 422);
        }

        $userId = (string) $request->user()->_id;
        $canteenId = (string) $menu->canteen_id;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'canteens' => [],
            ]);
        }

        $canteens = $cart->canteens ?? [];
        $canteenIdx = null;

        // Cari index kantin di cart
        foreach ($canteens as $i => $c) {
            if ((string) $c['canteen_id'] === $canteenId) {
                $canteenIdx = $i;
                break;
            }
        }

        // Kalau kantin belum ada di cart, tambahkan
        if ($canteenIdx === null) {
            $canteens[] = [
                'canteen_id' => $canteenId,
                'canteen_name' => $canteen->name,
                'items' => [],
                'subtotal' => 0,
            ];
            $canteenIdx = count($canteens) - 1;
        }

        // Cari item di kantin tersebut
        $items = $canteens[$canteenIdx]['items'];
        $found = false;

        foreach ($items as &$item) {
            if ((string) $item['menu_id'] === (string) $menu->_id) {
                $item['quantity'] += $validated['quantity'];
                $item['subtotal'] = $item['price'] * $item['quantity'];
                $found = true;
                break;
            }
        }

        if (!$found) {
            $items[] = [
                'menu_id' => (string) $menu->_id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $validated['quantity'],
                'subtotal' => $menu->price * $validated['quantity'],
                'image' => $menu->image ?? null,
            ];
        }

        $canteens[$canteenIdx]['items'] = $items;
        $canteens[$canteenIdx]['subtotal'] = array_sum(array_column($items, 'subtotal'));

        $cart->update(['canteens' => $canteens]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang.',
            'data' => $cart->fresh(),
        ]);
    }

    // PUT /buyers/carts/items/{itemId}
    public function updateItem(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = (string) $request->user()->_id;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Keranjang tidak ditemukan.'], 404);
        }

        $canteens = $cart->canteens ?? [];
        $found = false;

        foreach ($canteens as &$canteen) {
            foreach ($canteen['items'] as &$item) {
                if ((string) $item['menu_id'] === $itemId) {
                    $item['quantity'] = $validated['quantity'];
                    $item['subtotal'] = $item['price'] * $validated['quantity'];
                    $found = true;
                    break 2;
                }
            }
            $canteen['subtotal'] = array_sum(array_column($canteen['items'], 'subtotal'));
        }

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        // Recalculate subtotal per kantin
        foreach ($canteens as &$canteen) {
            $canteen['subtotal'] = array_sum(array_column($canteen['items'], 'subtotal'));
        }

        $cart->update(['canteens' => $canteens]);

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil diperbarui.',
            'data' => $cart->fresh(),
        ]);
    }

    // DELETE /buyers/carts/items/{itemId}
    public function removeItem(Request $request, $itemId)
    {
        $userId = (string) $request->user()->_id;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Keranjang tidak ditemukan.'], 404);
        }

        $canteens = $cart->canteens ?? [];
        $found = false;

        foreach ($canteens as &$canteen) {
            $before = count($canteen['items']);
            $canteen['items'] = array_values(
                array_filter($canteen['items'], fn($item) => (string) $item['menu_id'] !== $itemId)
            );
            if (count($canteen['items']) < $before) {
                $found = true;
                $canteen['subtotal'] = array_sum(array_column($canteen['items'], 'subtotal'));
            }
        }

        if (!$found) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan di keranjang.'], 404);
        }

        $canteens = array_values(array_filter($canteens, fn($c) => count($c['items']) > 0));

        if (empty($canteens)) {
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item dihapus. Keranjang sekarang kosong.',
            ]);
        }

        $cart->update(['canteens' => $canteens]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang.',
            'data' => $cart->fresh(),
        ]);
    }
}