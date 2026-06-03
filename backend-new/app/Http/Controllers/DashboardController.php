<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index(Request $request)
    {
        $token   = Session::get('api_token');
        $periode = $request->query('periode', 'bulan');

        $response = Http::timeout(15)
            ->withToken($token)
            ->get($this->apiUrl('/dashboard'), ['periode' => $periode]);

        if ($response->successful()) {
            $data = $response->json('data');
            Session::put('pending_count', $data['kantinPending'] ?? 0);

            $timeline = $this->buildTimeline($periode);
            $donut    = $this->buildDonut($periode);
            $barHoriz = $this->buildBarHorizontal($periode);

            return view('admin_global.dasbor', [
                'totalPendapatan'   => $data['totalPendapatan'] ?? 0,
                'totalPesanan'      => $data['totalPesanan'] ?? 0,
                'kantinAktif'       => $data['kantinAktif'] ?? 0,
                'kantinPending'     => $data['kantinPending'] ?? 0,
                'chartLabels'       => $data['chartLabels'] ?? [],
                'chartData'         => $data['chartData'] ?? [],
                'revenuePercentage' => $data['revenuePercentage'] ?? 0,
                'revenueTrend'      => $data['revenueTrend'] ?? 'flat',
                'labelPeriode'      => $this->getLabelPeriode($periode),
                'periode'           => $periode,
                'timelineLabels'    => $timeline['labels'],
                'timelineRevenue'   => $timeline['revenue'],
                'timelineOrders'    => $timeline['orders'],
                'donutLabels'       => $donut['labels'],
                'donutData'         => $donut['data'],
                'horizLabels'       => $barHoriz['labels'],
                'horizRevenue'      => $barHoriz['revenue'],
            ]);
        }

        return redirect()->route('admin.login')->withErrors('Sesi habis atau gagal memuat data.');
    }

    private function getLabelPeriode(string $periode): string
    {
        return match($periode) {
            'hari'       => 'Hari Ini',
            'minggu'     => 'Minggu Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun'      => 'Tahun Ini',
            'tahun_lalu' => 'Tahun Lalu',
            'semua'      => 'Semua Periode',
            default      => 'Bulan Ini',
        };
    }

    private function getRange(string $periode): array
    {
        return match($periode) {
            'hari'       => [now()->startOfDay(), now()->endOfDay()],
            'minggu'     => [now()->startOfWeek(), now()->endOfWeek()],
            'bulan_lalu' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'tahun'      => [now()->startOfYear(), now()->endOfYear()],
            'tahun_lalu' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            'semua'      => [null, null],
            default      => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function buildTimeline(string $periode): array
    {
        try {
            $labels = $revenue = $orders = [];

            if ($periode === 'hari') {
                for ($h = 0; $h < 24; $h++) {
                    $start = now()->startOfDay()->addHours($h);
                    $end   = $start->copy()->addHour();
                    $rows  = \App\Models\Order::where('status', 'completed')
                                ->whereBetween('created_at', [$start, $end])->get();
                    $labels[]  = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
                    $revenue[] = $rows->sum('total_amount');
                    $orders[]  = $rows->count();
                }
            } elseif ($periode === 'minggu') {
                $days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
                for ($d = 0; $d < 7; $d++) {
                    $day   = now()->startOfWeek()->addDays($d);
                    $rows  = \App\Models\Order::where('status', 'completed')
                                ->whereDate('created_at', $day)->get();
                    $labels[]  = $days[$d];
                    $revenue[] = $rows->sum('total_amount');
                    $orders[]  = $rows->count();
                }
            } elseif (in_array($periode, ['bulan', 'bulan_lalu'])) {
                $start       = $periode === 'bulan_lalu' ? now()->subMonth()->startOfMonth() : now()->startOfMonth();
                $daysInMonth = (int) $start->format('t');
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $day   = $start->copy()->setDay($d);
                    $rows  = \App\Models\Order::where('status', 'completed')
                                ->whereDate('created_at', $day)->get();
                    $labels[]  = $d;
                    $revenue[] = $rows->sum('total_amount');
                    $orders[]  = $rows->count();
                }
            } elseif (in_array($periode, ['tahun', 'tahun_lalu'])) {
                $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                $year  = $periode === 'tahun_lalu' ? now()->year - 1 : now()->year;
                for ($m = 1; $m <= 12; $m++) {
                    $rows = \App\Models\Order::where('status', 'completed')
                                ->whereYear('created_at', $year)
                                ->whereMonth('created_at', $m)->get();
                    $labels[]  = $bulan[$m - 1];
                    $revenue[] = $rows->sum('total_amount');
                    $orders[]  = $rows->count();
                }
            } else {
                for ($y = now()->year - 4; $y <= now()->year; $y++) {
                    $rows = \App\Models\Order::where('status', 'completed')
                                ->whereYear('created_at', $y)->get();
                    $labels[]  = (string) $y;
                    $revenue[] = $rows->sum('total_amount');
                    $orders[]  = $rows->count();
                }
            }

            return compact('labels', 'revenue', 'orders');
        } catch (\Throwable $e) {
            return ['labels' => [], 'revenue' => [], 'orders' => []];
        }
    }

    private function buildDonut(string $periode): array
    {
        try {
            [$start, $end] = $this->getRange($periode);
            $query = \App\Models\Order::where('status', 'completed');
            if ($start && $end) $query->whereBetween('created_at', [$start, $end]);

            $orders  = $query->get();
            $grouped = $orders->groupBy('canteen_id');
            $labels  = [];
            $data    = [];

            foreach ($grouped as $canteenId => $rows) {
                $canteen  = \App\Models\Canteen::find($canteenId);
                $labels[] = $canteen ? $canteen->name : 'Unknown';
                $data[]   = $rows->count();
            }

            array_multisort($data, SORT_DESC, $labels);
            return compact('labels', 'data');
        } catch (\Throwable $e) {
            return ['labels' => [], 'data' => []];
        }
    }

    private function buildBarHorizontal(string $periode): array
    {
        try {
            [$start, $end] = $this->getRange($periode);
            $query = \App\Models\Order::where('status', 'completed');
            if ($start && $end) $query->whereBetween('created_at', [$start, $end]);

            $grouped = $query->get()
                ->groupBy('canteen_id')
                ->map(fn($rows) => [
                    'revenue'    => $rows->sum('total_amount'),
                    'canteen_id' => $rows->first()->canteen_id,
                ])
                ->sortByDesc('revenue')
                ->take(8)
                ->values();

            $labels = $revenue = [];
            foreach ($grouped as $item) {
                $canteen  = \App\Models\Canteen::find($item['canteen_id']);
                $labels[] = $canteen ? $canteen->name : 'Unknown';
                $revenue[] = $item['revenue'];
            }

            return compact('labels', 'revenue');
        } catch (\Throwable $e) {
            return ['labels' => [], 'revenue' => []];
        }
    }

    public function pengaturan()
    {
        return view('admin_global.pengaturan');
    }
}