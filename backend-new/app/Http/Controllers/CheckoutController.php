<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
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

    // POST /pembayaran/session
    // Dipanggil dari JS keranjang sebelum redirect
    public function saveSession(Request $request)
    {
        $request->validate([
            'canteen_id' => 'required|string',
            'menu_ids'   => 'required|array|min:1',
            'notes'      => 'nullable|array',
            'metode'     => 'required|in:ambil,kurir',
            'alamat'     => 'nullable|string',
            'subtotal'   => 'required|integer',
            'ongkir'     => 'required|integer',
            'total'      => 'required|integer',
        ]);

        $request->session()->put('checkout', [
            'canteen_id' => $request->canteen_id,
            'menu_ids'   => $request->menu_ids,
            'notes'      => $request->notes ?? [],
            'metode'     => $request->metode,
            'alamat'     => $request->alamat,
            'subtotal'   => $request->subtotal,
            'ongkir'     => $request->ongkir,
            'total'      => $request->total,
        ]);

        return response()->json(['success' => true]);
    }

    // GET /pembayaran
    public function index(Request $request)
    {
        $checkout = $request->session()->get('checkout');

        // Kalau session kosong, balik ke keranjang
        if (!$checkout) {
            return redirect('/keranjang')->with('error', 'Sesi checkout tidak ditemukan.');
        }

        // Fetch data kantin (untuk nama + QRIS)
        $canteen = null;
        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl("/canteens/{$checkout['canteen_id']}"));

        if ($response->successful()) {
            $canteen = $response->json('data'); // ← qris_image sudah full URL di sini
        }

        // Fetch detail item yang dipilih dari cart
        $cartResponse = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl('/buyers/carts'));

        $selectedItems = [];
        if ($cartResponse->successful()) {
            $cart = $cartResponse->json('data');
            foreach ($cart['canteens'] ?? [] as $c) {
                if ($c['canteen_id'] === $checkout['canteen_id']) {
                    foreach ($c['items'] as $item) {
                        if (in_array($item['menu_id'], $checkout['menu_ids'])) {
                            // Sertakan notes per item
                            $idx = array_search($item['menu_id'], $checkout['menu_ids']);
                            $item['notes'] = $checkout['notes'][$idx] ?? null;
                            $selectedItems[] = $item;
                        }
                    }
                    break;
                }
            }
        }

        return view('pelanggan.pembayaran', compact('checkout', 'canteen', 'selectedItems'));
    }

    // POST /pembayaran
    public function store(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $checkout = $request->session()->get('checkout');

        if (!$checkout) {
            return back()->with('error', 'Sesi checkout tidak ditemukan.');
        }

        $delivery_method = $checkout['metode'] === 'kurir' ? 'delivery' : 'pickup';

        // Build multipart form
        $formData = [
            ['name' => 'canteen_id',      'contents' => $checkout['canteen_id']],
            ['name' => 'delivery_method', 'contents' => $delivery_method],
            ['name' => 'order_notes',     'contents' => ''],
        ];

        // menu_ids[] dan notes[]
        foreach ($checkout['menu_ids'] as $i => $menuId) {
            $formData[] = ['name' => 'menu_ids[]', 'contents' => $menuId];
            $formData[] = ['name' => 'notes[]',    'contents' => $checkout['notes'][$i] ?? ''];
        }

        if ($delivery_method === 'delivery' && $checkout['alamat']) {
            $formData[] = ['name' => 'location_note', 'contents' => $checkout['alamat']];
        }

        // File bukti bayar
        $file = $request->file('payment_proof');
        $formData[] = [
            'name'     => 'payment_proof',
            'contents' => fopen($file->getRealPath(), 'r'),
            'filename' => $file->getClientOriginalName(),
        ];

        $response = Http::withToken($this->token($request))
            ->timeout(30)
            ->attach('payment_proof', fopen($file->getRealPath(), 'r'), $file->getClientOriginalName())
            ->post($this->apiUrl('/buyers/checkouts'), array_merge(
                [
                    'canteen_id'      => $checkout['canteen_id'],
                    'delivery_method' => $delivery_method,
                    'order_notes'     => '',
                    'location_note'   => $checkout['alamat'] ?? '',
                ],
                // Kirim sebagai array biasa, Laravel API akan baca notes[] dan menu_ids[]
                ['menu_ids' => $checkout['menu_ids']],
                ['notes'    => $checkout['notes']],
            ));

        if ($response->successful()) {
            $order = $response->json('data');
            // Hapus session checkout
            $request->session()->forget('checkout');

            return response()->json([
                'success'  => true,
                'order_id' => $order['_id'] ?? $order['id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response->json('message') ?? 'Gagal membuat pesanan.',
        ], $response->status());
    }

    // POST /pembayaran/batalkan
    public function cancel(Request $request)
    {
        $request->validate(['order_id' => 'required|string']);

        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->post($this->apiUrl("/buyers/orders/{$request->order_id}/cancellations"));

        $request->session()->forget('checkout');

        return response()->json($response->json(), $response->status());
    }
}
