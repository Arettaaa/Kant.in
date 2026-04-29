<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RatingController extends Controller
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

    // POST /rating/{orderId}
    public function store(Request $request, string $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->post($this->apiUrl("/buyers/orders/{$orderId}/ratings"), [
                'rating' => (int) $request->rating,
            ]);

        return response()->json($response->json(), $response->status());
    }

    // GET /rating/{orderId}/check
    public function check(Request $request, string $orderId)
    {
        $response = Http::withToken($this->token($request))
            ->timeout(15)
            ->get($this->apiUrl("/buyers/orders/{$orderId}/ratings"));

        return response()->json($response->json(), $response->status());
    }
}