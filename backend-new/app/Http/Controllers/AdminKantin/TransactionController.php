<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TransactionController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function token(): string
    {
        return Session::get('api_token', '');
    }

    private function canteenId(): string
    {
        return Session::get('user', [])['canteen_id'] ?? '';
    }

    // GET /admin/transaksi
    public function index(Request $request)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/transactions"));

        $data = $response->successful() ? ($response->json()['data'] ?? []) : [];
        $orders = $data['orders'] ?? [];
        $totalRevenue = $data['total_revenue'] ?? 0;
        $totalOrders = $data['total_orders'] ?? 0;

        return view('admin.transaksi', compact('orders', 'totalRevenue', 'totalOrders'));
    }

    // GET /admin/transaksi/export?format=pdf&start_date=...&end_date=...
    public function export(Request $request)
    {
        $canteenId = $this->canteenId();

        $request->validate([
            'format' => 'required|in:pdf,xlsx',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $format = $request->input('format');  // ← ganti ini

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/export"), [
                'format' => $format,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

        if (!$response->successful()) {
            return back()->with('error', 'Gagal mengekspor laporan.');
        }

        $contentType = $format === 'xlsx'
            ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            : 'application/pdf';

        $filename = 'Laporan_Kantin_' . now()->format('Ymd_His') . '.' . $format;

        return response($response->body(), 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}