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

        if ($tab === 'makanan') {
            $params = [];
            if ($category !== 'Semua') $params['category'] = $category;
            if ($search !== '')        $params['search']   = $search;

            $response = Http::timeout(15)->get($this->apiUrl('/menus'), $params);
            if ($response->successful()) {
                $menus = $response->json('data') ?? [];
            }
        } else {
            $response = Http::timeout(15)->get($this->apiUrl('/canteens'));
            if ($response->successful()) {
                $canteens = $response->json('data') ?? [];
            }
        }

        return view('pelanggan.jelajah', compact('menus', 'canteens', 'tab', 'category', 'search'));
    }
}