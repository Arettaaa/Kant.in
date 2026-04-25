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

        if ($user->role === 'pembeli') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $canteenId) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $orders = Order::where('canteen_id', $canteenId)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hanya hitung revenue dari transaksi yang selesai (bukan cancelled)
        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $totalOrders  = $orders->where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'orders' => $orders,
            ],
        ]);
    }

    public function dashboard(Request $request, $canteenId)
    {
        $user = $request->user();

        if ($user->role !== 'admin_global') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $allOrders = Order::where('canteen_id', $canteenId)
            ->orderBy('created_at', 'desc')
            ->get();

        $byStatus = $allOrders->groupBy('status')->map->count();
        $completedOrders = $allOrders->where('status', 'completed');
        $totalRevenue = $completedOrders->sum('total_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'total_orders' => $allOrders->count(),
                'total_revenue' => $totalRevenue,
                'orders_by_status' => $byStatus,
                'recent_orders' => $allOrders->take(10)->values(),
            ],
        ]);
    }

    // GET /transactions (admin global - semua kantin)
    public function globalTransactions(Request $request)
    {
        $canteens = \App\Models\Canteen::where('is_active', true)->get();

        $result = [];
        $grandTotalRevenue = 0;
        $grandTotalOrders = 0;

        foreach ($canteens as $canteen) {
            $orders = Order::where('canteen_id', (string) $canteen->_id)
                ->where('status', 'completed')
                ->get();

            $totalRevenue = $orders->sum('total_amount');
            $totalOrders = $orders->count();

            $grandTotalRevenue += $totalRevenue;
            $grandTotalOrders += $totalOrders;

            $result[] = [
                'canteen_id' => (string) $canteen->_id,
                'canteen_name' => $canteen->name,
                'canteen_image' => $canteen->image ? asset('storage/' . $canteen->image) : null,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'grand_total_revenue' => $grandTotalRevenue,
                'grand_total_orders' => $grandTotalOrders,
                'canteens' => $result,
            ],
        ]);
    }

    // GET /dashboard (admin global - semua kantin)
    public function globalDashboard(Request $request)
    {
        $totalActiveCanteens = \App\Models\Canteen::where('is_active', true)->count();
        $canteens = \App\Models\Canteen::where('is_active', true)->get();

        $grandTotalRevenue = 0;
        $grandTotalOrders = 0;
        $canteenPerformance = [];

        foreach ($canteens as $canteen) {
            $completedOrders = Order::where('canteen_id', (string) $canteen->_id)
                ->where('status', 'completed')
                ->get();

            $totalRevenue = $completedOrders->sum('total_amount');
            $totalOrders = $completedOrders->count();

            $grandTotalRevenue += $totalRevenue;
            $grandTotalOrders += $totalOrders;

            $canteenPerformance[] = [
                'canteen_id' => (string) $canteen->_id,
                'canteen_name' => $canteen->name,
                'canteen_image' => $canteen->image ? asset('storage/' . $canteen->image) : null,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ];
        }

        // Sort by total_orders descending
        usort($canteenPerformance, fn($a, $b) => $b['total_orders'] - $a['total_orders']);

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $grandTotalRevenue,
                'total_orders' => $grandTotalOrders,
                'total_active_canteens' => $totalActiveCanteens,
                'canteen_performance' => $canteenPerformance,
            ],
        ]);
    }
}