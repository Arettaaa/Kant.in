<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // POST /buyers/orders/{orderId}/ratings
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $userId = (string) $request->user()->_id;

        $order = Order::where('_id', $orderId)
            ->where('customer_snapshot.user_id', $userId)
            ->where('status', 'completed')
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau belum selesai.',
            ], 404);
        }

        foreach ($order->items as $item) {
            $menu = Menu::find($item['menu_id']);
            if (!$menu) continue;

            $reviews = $menu->reviews ?? [];

            $alreadyReviewed = collect($reviews)->contains(fn($r) =>
                (string)($r['order_id'] ?? '') === (string)$orderId &&
                (string)($r['user_id'] ?? '') === $userId
            );

            if ($alreadyReviewed) continue;

            $reviews[] = [
                'user_id'    => $userId,
                'user_name'  => $request->user()->name,
                'rating'     => (int) $request->rating,
                'order_id'   => (string) $orderId,
                'created_at' => now()->toDateTimeString(),
            ];

            $totalReviews  = count($reviews);
            $averageRating = round(collect($reviews)->avg(fn($r) => $r['rating']), 1);

            $menu->update([
                'reviews'        => $reviews,
                'total_reviews'  => $totalReviews,
                'average_rating' => $averageRating,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil dikirim. Terima kasih!',
        ]);
    }

    // GET /buyers/orders/{orderId}/ratings
    // Response: { has_rated: bool, rating: int (0 kalau belum) }
    public function check(Request $request, $orderId)
    {
        $userId = (string) $request->user()->_id;

        $order = Order::where('_id', $orderId)
            ->where('customer_snapshot.user_id', $userId)
            ->first();

        if (!$order || empty($order->items)) {
            return response()->json([
                'success' => true,
                'data'    => ['has_rated' => false, 'rating' => 0],
            ]);
        }

        $firstItem   = $order->items[0];
        $menu        = Menu::find($firstItem['menu_id']);
        $hasRated    = false;
        $ratingValue = 0;

        if ($menu && !empty($menu->reviews)) {
            $review = collect($menu->reviews)->first(fn($r) =>
                (string)($r['order_id'] ?? '') === (string)$orderId &&
                (string)($r['user_id'] ?? '') === $userId
            );

            if ($review) {
                $hasRated    = true;
                $ratingValue = (int) ($review['rating'] ?? 0);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'has_rated' => $hasRated,
                'rating'    => $ratingValue,
            ],
        ]);
    }
}