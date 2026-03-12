<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // GET /canteens/{id}/transactions  (admin kantin & admin global)
    public function index(Request $request, $canteenId)
    {
        $user = $request->user();

        // Admin kantin hanya bisa lihat kantinnya sendiri
        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $canteenId) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $orders = Order::where('canteen_id', $canteenId)
            ->whereIn('status', ['completed'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders  = $orders->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'total_revenue' => $totalRevenue,
                'total_orders'  => $totalOrders,
                'orders'        => $orders,
            ],
        ]);
    }

    // GET /canteens/{id}/dashboard  (admin global)
    public function dashboard(Request $request, $canteenId)
    {
        $allOrders = Order::where('canteen_id', $canteenId)
            ->orderBy('created_at', 'desc')
            ->get();

        $byStatus = $allOrders->groupBy('status')->map->count();

        $completedOrders = $allOrders->where('status', 'completed');
        $totalRevenue    = $completedOrders->sum('total_amount');

        return response()->json([
            'success' => true,
            'data'    => [
                'total_orders'   => $allOrders->count(),
                'total_revenue'  => $totalRevenue,
                'orders_by_status' => $byStatus,
                'recent_orders'  => $allOrders->take(10)->values(),
            ],
        ]);
    }
}