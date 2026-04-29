<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Canteen;
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
        $periode = $request->query('periode', 'bulan');
        $query = Order::where('status', 'completed');

        // Filter Waktu
        if ($periode == 'hari') {
            $query->whereDate('created_at', today());
        } elseif ($periode == 'minggu') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        }

        $orders = $query->get();
        $canteens = Canteen::where('is_active', true)->get();

        $result = $canteens->map(function($c) use ($orders) {
            $cOrders = $orders->where('canteen_id', (string)$c->_id);
            return [
                'canteen_id'    => (string)$c->_id,
                'canteen_name'  => $c->name,
                'canteen_image' => $c->image ? asset('storage/' . $c->image) : null,
                'total_orders'  => $cOrders->count(),
                'total_revenue' => $cOrders->sum('total_amount'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'grand_total_revenue' => $result->sum('total_revenue'),
                'grand_total_orders'  => $result->sum('total_orders'),
                'canteens'            => $result,
            ],
        ]);
    }

    // GET /dashboard (admin global - semua kantin)
   public function globalDashboard(Request $request)
    {
        // ✅ FIX: Ganti constant dengan string 'completed' langsung
        $statusCompleted = 'completed';

        // Bulan ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        // Bulan lalu
        $startOfLastMonth = now()->subMonth()->startOfMonth();
        $endOfLastMonth   = now()->subMonth()->endOfMonth();

        // Pendapatan bulan ini
        $totalPendapatan = Order::where('status', $statusCompleted)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        // Total pesanan bulan ini
        $totalPesanan = Order::where('status', $statusCompleted)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Pendapatan bulan lalu
        $pendapatanBulanLalu = Order::where('status', $statusCompleted)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');

        // Hitung persentase
        $persentase = 0;
        $trend = 'flat';

        if ($pendapatanBulanLalu > 0) {
            $persentase = (($totalPendapatan - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100;
        } elseif ($totalPendapatan > 0) {
            $persentase = 100;
        }

        if ($persentase > 0) {
            $trend = 'up';
        } elseif ($persentase < 0) {
            $trend = 'down';
        }

        // Statistik kantin
        $kantinAktif   = Canteen::where('is_active', true)->where('status', 'active')->count();
        $kantinPending = Canteen::where('status', 'pending')->count();

        // Top kantin
        $topKantin = Order::where('status', $statusCompleted)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy('canteen_id')
            ->map(function ($orders) {
                return [
                    'total' => $orders->count(),
                    'canteen_id' => $orders->first()->canteen_id,
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $chartLabels = [];
        $chartData   = [];

        foreach ($topKantin as $item) {
            $canteen = Canteen::find($item['canteen_id']);
            $chartLabels[] = $canteen ? $canteen->name : 'Unknown';
            $chartData[]   = $item['total'];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'totalPendapatan'   => $totalPendapatan,
                'totalPesanan'      => $totalPesanan,
                'kantinAktif'       => $kantinAktif,
                'kantinPending'     => $kantinPending,
                'chartLabels'       => $chartLabels,
                'chartData'         => $chartData,
                'revenuePercentage' => round(abs($persentase), 1),
                'revenueTrend'      => $trend
            ]
        ]);
    }
}
