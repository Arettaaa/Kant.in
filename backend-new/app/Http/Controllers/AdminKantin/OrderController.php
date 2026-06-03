<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
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

    private function user(): array
    {
        return Session::get('user', []);
    }

    private function canteenId(): string
    {
        return $this->user()['canteen_id'] ?? '';
    }

    // GET /admin/pesanan
    public function index(Request $request)
    {
        $canteenId = $this->canteenId();

        // Ambil pending orders
        $pendingResponse = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), ['status' => 'pending']);

        // Ambil processing + ready orders
        $processingResponse = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), ['status' => 'processing']);

        $readyResponse = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), ['status' => 'ready']);

        // Ambil data kantin
        $canteenResponse = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/settings"));

        $pendingOrders = $pendingResponse->successful() ? ($pendingResponse->json()['data'] ?? []) : [];
        $processingOrders = array_merge(
            $processingResponse->successful() ? ($processingResponse->json()['data'] ?? []) : [],
            $readyResponse->successful() ? ($readyResponse->json()['data'] ?? []) : []
        );
        $canteen = $canteenResponse->successful() ? ($canteenResponse->json()['data'] ?? []) : [];

        return view('admin.pesanan', [
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'pendingCount' => count($pendingOrders),
            'processingCount' => count($processingOrders),
            'canteen' => $canteen,
        ]);
    }

    // GET /admin/pesanan/{id}/rincian
    public function rincian($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), [
                'status' => 'pending',
            ]);

        $orders = $response->json()['data'] ?? [];
        $order = collect($orders)->firstWhere('id', $id);

        if (!$order) {
            return redirect()->route('admin.pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('admin.rincian', compact('order'));
    }

    // POST /admin/pesanan/{id}/verify
    public function verify($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->post($this->apiUrl("/canteens/{$canteenId}/orders/{$id}/payments/verify"));

        if ($response->successful()) {
            return redirect()->route('admin.pesanan')->with('success', 'Pembayaran berhasil diverifikasi.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal verifikasi pembayaran.');
    }

    // POST /admin/pesanan/{id}/reject
    public function reject(Request $request, $id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->post($this->apiUrl("/canteens/{$canteenId}/orders/{$id}/payments/reject"), [
                'reason' => $request->reason,
            ]);

        if ($response->successful()) {
            return redirect()->route('admin.pesanan')->with('success', 'Pembayaran ditolak.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal menolak pembayaran.');
    }

    // PUT /admin/pesanan/{id}/status
    public function updateStatus(Request $request, $id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->put($this->apiUrl("/canteens/{$canteenId}/orders/{$id}/statuses"), [
                'status' => $request->status,
            ]);

        if ($response->successful()) {
            return redirect()->route('admin.pesanan')->with('success', 'Status pesanan berhasil diperbarui.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal update status.');
    }

    // POST /admin/pesanan/{id}/cancel
    public function cancel($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->put($this->apiUrl("/canteens/{$canteenId}/orders/{$id}/statuses"), [
                'status' => 'cancelled',
            ]);

        if ($response->successful()) {
            return redirect()->route('admin.pesanan')->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal membatalkan pesanan.');
    }

    // GET /admin/riwayat
    public function history(Request $request)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), [
                'status' => 'completed',
            ]);

        $orders = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('admin.riwayat', compact('orders'));
    }

    // GET /admin/riwayat/{id}
    public function historyDetail($id)
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/orders"), [
                'status' => 'completed',
            ]);

        $orders = $response->json()['data'] ?? [];
        $order = collect($orders)->firstWhere('id', $id);

        if (!$order) {
            return redirect()->route('admin.riwayat')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('admin.detail-pesanan', compact('order'));
    }
}