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
            'canteen_id' => 'required|string',
            'menu_ids' => 'required|array|min:1',
            'menu_ids.*' => 'required|string',
            'delivery_method' => 'required|in:pickup,delivery',
            'location_note' => 'required_if:delivery_method,delivery|nullable|string',
            'order_notes' => 'nullable|string',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $userId = (string) $request->user()->_id;
        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart || empty($cart->canteens)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 422);
        }

        // Cari kantin di cart
        $canteenCart = null;
        foreach ($cart->canteens as $c) {
            if ((string) $c['canteen_id'] === $validated['canteen_id']) {
                $canteenCart = $c;
                break;
            }
        }

        if (!$canteenCart) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan di keranjang.'], 404);
        }

        $canteen = Canteen::find($validated['canteen_id']);
        if (!$canteen || !$canteen->is_active) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak tersedia.'], 422);
        }

        if (!$canteen->is_open) {
            return response()->json(['success' => false, 'message' => 'Kantin sedang tutup. Tidak dapat melakukan pemesanan.'], 422);
        }

        // Validasi jam operasional
        $now = now()->timezone('Asia/Jakarta')->format('H:i');
        $open = $canteen->operating_hours['open'] ?? '00:00';
        $close = $canteen->operating_hours['close'] ?? '23:59';

        if ($now < $open || $now > $close) {
            return response()->json(['success' => false, 'message' => 'Kantin sudah tutup.'], 422);
        }

        // Filter items yang dipilih
        $selectedItems = array_values(array_filter(
            $canteenCart['items'],
            fn($item) => in_array((string) $item['menu_id'], $validated['menu_ids'])
        ));

        if (empty($selectedItems)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih.'], 422);
        }

        // Validasi ketersediaan menu
        foreach ($selectedItems as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu || !$menu->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => "Menu '{$item['name']}' tidak tersedia.",
                ], 422);
            }
        }

        // Upload bukti bayar
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $deliveryFee = $validated['delivery_method'] === 'delivery'
            ? ($canteen->delivery_fee_flat ?? 0)
            : 0;

        $orderItems = array_map(function ($item) {
            $menu = Menu::find($item['menu_id']);
            return [
                'menu_id' => $item['menu_id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'notes' => $item['notes'] ?? null,
                'estimated_cooking_time' => $menu->estimated_cooking_time ?? 0,
                'subtotal' => $item['subtotal'],
            ];
        }, $selectedItems);

        $user = $request->user();
        $orderCode = 'KANTIN-' . date('Y') . '-' . strtoupper(Str::random(6));
        $subtotal = array_sum(array_column($selectedItems, 'subtotal'));
        $total = $subtotal + $deliveryFee;

        $order = Order::create([
            'order_code' => $orderCode,
            'customer_snapshot' => [
                'user_id' => (string) $user->_id,
                'name' => $user->name,
                'phone' => $user->phone,
            ],
            'canteen_id' => $validated['canteen_id'],
            'items' => $orderItems,
            'order_notes' => $validated['order_notes'] ?? null,
            'subtotal_amount' => $subtotal,
            'delivery_details' => [
                'method' => $validated['delivery_method'],
                'fee' => $deliveryFee,
                'location_note' => $validated['location_note'] ?? null,
            ],
            'total_amount' => $total,
            'payment' => [
                'method' => 'qris',
                'status' => 'pending_verification',
                'proof' => $paymentProofPath, // <--- Cukup simpan nama filenya aja
                'paid_at' => null,
            ],
            'status' => Order::STATUS_PENDING,
        ]);

        // Hapus hanya item yang sudah di-checkout dari cart
        $this->removeCheckedOutItems($cart, $validated['canteen_id'], $validated['menu_ids']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat. Menunggu verifikasi pembayaran.',
            'data' => $order,
        ], 201);
    }

    // Helper: hapus item yang sudah di-checkout dari cart
    private function removeCheckedOutItems($cart, $canteenId, $menuIds)
    {
        $canteens = $cart->canteens ?? [];

        foreach ($canteens as &$canteen) {
            if ((string) $canteen['canteen_id'] === $canteenId) {
                $canteen['items'] = array_values(
                    array_filter($canteen['items'], fn($item) => !in_array((string) $item['menu_id'], $menuIds))
                );
                $canteen['subtotal'] = array_sum(array_column($canteen['items'], 'subtotal'));
                break;
            }
        }

        // Hapus kantin kalau sudah kosong
        $canteens = array_values(array_filter($canteens, fn($c) => count($c['items']) > 0));

        if (empty($canteens)) {
            $cart->delete();
        } else {
            $cart->update(['canteens' => $canteens]);
        }
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

        $timeout = env('ORDER_CANCEL_TIMEOUT', 1);
        $secondsSinceOrder = $order->created_at->diffInSeconds(now());

        if ($secondsSinceOrder > 30) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan. Batas waktu pembatalan adalah 30 detik.',
            ], 422);
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan.',
            'data' => $order->fresh(),
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

        $query = Order::where('canteen_id', $canteenId)
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return response()->json(['success' => true, 'data' => $orders]);
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
            'data' => $order->fresh(),
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

    // POST /canteens/{id}/orders/{orderId}/payments/verify
    public function verifyPayment(Request $request, $canteenId, $orderId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $order = Order::where('_id', $orderId)->where('canteen_id', $canteenId)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->payment['status'] !== 'pending_verification') {
            return response()->json(['success' => false, 'message' => 'Pembayaran sudah diverifikasi.'], 422);
        }

        $payment = $order->payment;
        $payment['status'] = 'paid';
        $payment['paid_at'] = now()->toDateTimeString();

        $order->update([
            'payment' => $payment,
            'status' => Order::STATUS_PROCESSING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi.',
            'data' => $order->fresh(),
        ]);
    }

    // POST /canteens/{id}/orders/{orderId}/payments/reject
    public function rejectPayment(Request $request, $canteenId, $orderId)
    {
        $this->authorizeAdminKantin($request, $canteenId);

        $order = Order::where('_id', $orderId)->where('canteen_id', $canteenId)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->payment['status'] !== 'pending_verification') {
            return response()->json(['success' => false, 'message' => 'Pembayaran sudah diverifikasi.'], 422);
        }

        $request->validate(['reason' => 'nullable|string']);

        $payment = $order->payment;
        $payment['status'] = 'rejected';

        $order->update([
            'payment' => $payment,
            'status' => Order::STATUS_CANCELLED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran ditolak. Pesanan dibatalkan.',
            'data' => $order->fresh(),
        ]);
    }
}
