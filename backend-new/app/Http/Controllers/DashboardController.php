<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Canteen; // Tambahkan ini untuk hitung pending kantin

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
        $periode = $request->query('periode', 'bulan'); // tambah ini

        $response = Http::timeout(15)
            ->withToken($token)
            ->get($this->apiUrl('/dashboard'), [
                'periode' => $periode  // kirim ke API
            ]);

        if ($response->successful()) {
            $data = $response->json('data');
            Session::put('pending_count', $data['kantinPending'] ?? 0);

            return view('admin_global.dasbor', [
                'totalPendapatan'   => $data['totalPendapatan'] ?? 0,
                'totalPesanan'      => $data['totalPesanan'] ?? 0,
                'kantinAktif'       => $data['kantinAktif'] ?? 0,
                'kantinPending'     => $data['kantinPending'] ?? 0,
                'chartLabels'       => $data['chartLabels'] ?? [],
                'chartData'         => $data['chartData'] ?? [],
                'revenuePercentage' => $data['revenuePercentage'] ?? 0,
                'revenueTrend'      => $data['revenueTrend'] ?? 'flat',
                'labelPeriode'      => $this->getLabelPeriode($periode), // tambah ini
                'periode'           => $periode,
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

    public function pengaturan()
    {
        return view('admin_global.pengaturan');
    }
}