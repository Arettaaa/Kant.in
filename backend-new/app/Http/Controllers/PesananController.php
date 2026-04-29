<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PesananController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function token(Request $request): string
    {
        return $request->session()->get('api_token', '');
    }

    public function index(Request $request)
    {
        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl('/buyers/orders/histories'));

        $orders = [];
        if ($response->successful()) {
            $orders = $response->json('data') ?? [];
        }

        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        foreach ($orders as &$order) {
            // Format waktu WIB
            if (!empty($order['created_at'])) {
                $dt = \Carbon\Carbon::parse($order['created_at'])->timezone('Asia/Jakarta');
                $order['created_at_formatted'] = $dt->day . ' ' . $bulan[$dt->month] . ' ' . $dt->year . ' • ' . $dt->format('H:i') . ' WIB';
            } else {
                $order['created_at_formatted'] = '-';
            }

            if (($order['status'] ?? '') === 'completed') {
                $orderId = $order['_id'] ?? $order['id'] ?? null;
                if (!$orderId) continue;

                $ratingResponse = Http::withToken($this->token($request))
                    ->timeout(15)
                    ->get($this->apiUrl("/buyers/orders/{$orderId}/ratings"));

                if ($ratingResponse->successful()) {
                    $ratingData = $ratingResponse->json('data');
                    $order['has_rated']    = $ratingData['has_rated'] ?? false;
                    $order['rating_value'] = $ratingData['rating'] ?? 0;
                } else {
                    $order['has_rated']    = false;
                    $order['rating_value'] = 0;
                }
            }
        }

        return view('pelanggan.pesanan', compact('orders'));
    }
}
