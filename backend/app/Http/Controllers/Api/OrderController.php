<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // POST /buyers/checkouts
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'delivery_method' => 'required|in:pickup,delivery',
            'location_note'   => 'required_if:delivery_method,delivery|nullable|string',
            'payment_method'  => 'required|in:qris,cash',
            'order_notes'     => 'nullable|string',
        ]);

        $userId = (string) $request->user()->_id;
        $cart   = Cart::where('user_id', $userId)->first();

        if (!$cart || empty($cart->items)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 422);
        }

        $canteen = Canteen::find($cart->canteen_id);
        if (!$canteen || !$canteen->is_active) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak tersedia.'], 422);
        }

        // Validasi stok semua item sebelum checkout
        foreach ($cart->items as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu || !$menu->is_available || $menu->stock < $item['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok menu '{$item['name']}' tidak mencukupi.",
                ], 422);
            }
        }

        // Hitung delivery fee
        $deliveryFee = $validated['delivery_method'] === 'delivery'
            ? ($canteen->delivery_fee_flat ?? 0)
            : 0;

        // Build items snapshot dengan estimated_cooking_time
        $orderItems = array_map(function ($item) {
            $menu = Menu::find($item['menu_id']);
            return [
                'menu_id'                => $item['menu_id'],
                'name'                   => $item['name'],
                'price'                  => $item['price'],
                'quantity'               => $item['quantity'],
                'notes'                  => $item['notes'] ?? null,
                'estimated_cooking_time' => $menu->estimated_cooking_time ?? 0,
                'subtotal'               => $item['subtotal'],
            ];
        }, $cart->items);

        $user       = $request->user();
        $orderCode  = 'KANTIN-' . date('Y') . '-' . strtoupper(Str::random(6));
        $subtotal   = $cart->total_amount;
        $total      = $subtotal + $deliveryFee;

        $order = Order::create([
            'order_code'        => $orderCode,
            'customer_snapshot' => [
                'user_id' => (string) $user->_id,
                'name'    => $user->name,
                'phone'   => $user->phone,
            ],
            'canteen_id'        => (string) $cart->canteen_id,
            'items'             => $orderItems,
            'order_notes'       => $validated['order_notes'] ?? null,
            'subtotal_amount'   => $subtotal,
            'delivery_details'  => [
                'method'        => $validated['delivery_method'],
                'fee'           => $deliveryFee,
                'location_note' => $validated['location_note'] ?? null,
            ],
            'total_amount'      => $total,
            'payment'           => [
                'method'  => $validated['payment_method'],
                'status'  => 'unpaid',
                'paid_at' => null,
            ],
            'status'            => Order::STATUS_PENDING,
        ]);

        // Kurangi stok menu
        foreach ($cart->items as $item) {
            Menu::where('_id', $item['menu_id'])->decrement('stock', $item['quantity']);
            // Auto set is_available false jika stok habis
            $menu = Menu::find($item['menu_id']);
            if ($menu && $menu->stock <= 0) {
                $menu->update(['is_available' => false]);
            }
        }

        // Hapus cart setelah checkout
        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'data'    => $order,
        ], 201);
    }

    // GET /buyers/orders/{orderId}
    public function show(Request $request, $orderId)
    {
        $order = Order::where('_id', $orderId)
            ->where('customer_snapshot.user_id', (string) $request->user()->_id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $order]);
    }

    // GET /buyers/orders/{orderId}/statuses
    public function status(Request $request, $orderId)
    {
        $order = Order::where('_id', $orderId)
            ->where('customer_snapshot.user_id', (string) $request->user()->_id)
            ->first(['_id', 'order_code', 'status', 'payment', 'updated_at']);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $order]);
    }

    // POST /buyers/orders/{orderId}/cancellations
    public function cancel(Request $request, $orderId)
    {
        $order = Order::where('_id', $orderId)
            ->where('customer_snapshot.user_id', (string) $request->user()->_id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan karena sudah diproses.',
            ], 422);
        }

        // Kembalikan stok
        foreach ($order->items as $item) {
            $menu = Menu::find($item['menu_id']);
            if ($menu) {
                $menu->increment('stock', $item['quantity']);
                if ($menu->stock > 0 && !$menu->is_available) {
                    $menu->update(['is_available' => true]);
                }
            }
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan.',
            'data'    => $order->fresh(),
        ]);
    }

    // GET /buyers/orders/histories
    public function history(Request $request)
    {
        $orders = Order::where('customer_snapshot.user_id', (string) $request->user()->_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // =====================
    // ADMIN KANTIN
    // =====================

    // GET /canteens/{id}/orders
    public function canteenOrders(Request $request, $canteenId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $orders = Order::where('canteen_id', $canteenId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // POST /canteens/{id}/orders/{orderId}/processes
    public function process(Request $request, $canteenId, $orderId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $order = Order::where('_id', $orderId)->where('canteen_id', $canteenId)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->status !== Order::STATUS_PENDING) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak dalam status pending.'], 422);
        }

        $order->update(['status' => Order::STATUS_PROCESSING]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan sedang diproses.',
            'data'    => $order->fresh(),
        ]);
    }

    // PUT /canteens/{id}/orders/{orderId}/statuses
    public function updateStatus(Request $request, $canteenId, $orderId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $validated = $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled',
        ]);

        $order = Order::where('_id', $orderId)->where('canteen_id', $canteenId)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        $order->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui.',
            'data'    => $order->fresh(),
        ]);
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
}