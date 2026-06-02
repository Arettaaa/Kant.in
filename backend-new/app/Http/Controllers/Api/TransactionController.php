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
        } elseif ($periode == 'bulan' || $periode == '') {
            $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
        } elseif ($periode == 'bulan_lalu') {
            $query->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ]);
        } elseif ($periode == 'tahun') {
            $query->whereYear('created_at', now()->year);
        } elseif ($periode == 'tahun_lalu') {
            $query->whereYear('created_at', now()->year - 1);
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
        $periode = $request->query('periode', 'bulan');

        [$start, $end] = match($periode) {
            'hari'       => [now()->startOfDay(), now()->endOfDay()],
            'minggu'     => [now()->startOfWeek(), now()->endOfWeek()],
            'bulan_lalu' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'tahun'      => [now()->startOfYear(), now()->endOfYear()],
            'tahun_lalu' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'semua'      => [null, null],
            default      => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $query = Order::where('status', 'completed');
        if ($start && $end) $query->whereBetween('created_at', [$start, $end]);

        $orders = $query->get();

                // Pembanding dinamis sesuai periode
        [$startPrev, $endPrev] = match($periode) {
            'hari'       => [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()],
            'minggu'     => [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()],
            'bulan_lalu' => [now()->subMonths(2)->startOfMonth(), now()->subMonths(2)->endOfMonth()],
            'tahun'      => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'tahun_lalu' => [now()->subYears(2)->startOfYear(), now()->subYears(2)->endOfYear()],
            'semua'      => [null, null],
            default      => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
        };

        $pendapatanPembanding = ($startPrev && $endPrev)
            ? Order::where('status', 'completed')
                ->whereBetween('created_at', [$startPrev, $endPrev])
                ->sum('total_amount')
            : null;

        $totalPendapatan = $orders->sum('total_amount');
        $totalPesanan    = $orders->count();

       $persentase = 0;
        $trend = 'flat';

        if ($pendapatanPembanding === null) {
            // Periode 'semua' → tidak ada pembanding
            $trend = 'none';
        } elseif ($pendapatanPembanding > 0) {
            $persentase = (($totalPendapatan - $pendapatanPembanding) / $pendapatanPembanding) * 100;
            if ($persentase > 0) $trend = 'up';
            elseif ($persentase < 0) $trend = 'down';
        } elseif ($totalPendapatan > 0) {
            $persentase = 100;
            $trend = 'up';
        }
        elseif ($persentase < 0) $trend = 'down';

        $kantinAktif   = Canteen::where('is_active', true)->where('status', 'active')->count();
        $kantinPending = Canteen::where('status', 'pending')->count();

        $topKantin = $orders->groupBy('canteen_id')
            ->map(fn($o) => ['total' => $o->count(), 'canteen_id' => $o->first()->canteen_id])
            ->sortByDesc('total')->take(5)->values();

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
                'revenueTrend'      => $trend,
            ]
        ]);
    }
}
