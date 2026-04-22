<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JelajahController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index(Request $request)
    {
        $tab      = $request->get('tab', 'makanan');
        $category = $request->get('category', 'Semua');
        $search   = $request->get('search', '');

        $menus   = [];
        $canteens = [];
        $searchKantin = '';
        $statusFilter = 'semua';

        if ($tab === 'makanan') {
            $params = [];
            if ($category !== 'Semua') $params['category'] = $category;
            if ($search !== '')        $params['search']   = $search;

            $response = Http::timeout(15)->get($this->apiUrl('/menus'), $params);
            if ($response->successful()) {
                $menus = $response->json('data') ?? [];
            }
        } else {
            $statusFilter = $request->get('status', 'semua');

            $response = Http::timeout(15)->get($this->apiUrl('/canteens'));
            if ($response->successful()) {
                $canteens = $response->json('data') ?? [];
            }

            // Filter buka/tutup & search di sisi web (karena API tidak support filter ini)
            $searchKantin = $request->get('search', '');

            if ($searchKantin !== '') {
                $canteens = array_filter(
                    $canteens,
                    fn($k) =>
                    str_contains(strtolower($k['name'] ?? ''), strtolower($searchKantin))
                );
            }

            if ($statusFilter === 'buka') {
                $canteens = array_filter($canteens, fn($k) => $k['is_open'] ?? false);
            } elseif ($statusFilter === 'tutup') {
                $canteens = array_filter($canteens, fn($k) => !($k['is_open'] ?? false));
            }

            $canteens = array_values($canteens);
        }

        return view('pelanggan.jelajah', compact('menus', 'canteens', 'tab', 'category', 'search', 'searchKantin', 'statusFilter'));
    }
}
