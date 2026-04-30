<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class BerandaController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index(Request $request)
    {
        $user = Session::get('user');
        $namaDepan = 'Sobat Kantin';
        if ($user && !empty($user['name'])) {
            $namaDepan = explode(' ', trim($user['name']))[0];
        }

        $token = $request->session()->get('api_token', '');

        // Jalankan semua request sequential, BUKAN pool
        // (pool deadlock di php artisan serve karena single-threaded)
        try {
            $menusResponse   = Http::timeout(10)->get($this->apiUrl('/menus'));
            $canteensResponse = Http::timeout(10)->get($this->apiUrl('/canteens'));

            $allMenus    = $menusResponse->successful()   ? ($menusResponse->json('data')   ?? []) : [];
            $allCanteens = $canteensResponse->successful() ? ($canteensResponse->json('data') ?? []) : [];
        } catch (\Exception $e) {
            $allMenus    = [];
            $allCanteens = [];
        }

        $cartCount = 0;
        if ($token) {
            try {
                $cartResponse = Http::withToken($token)->timeout(10)->get($this->apiUrl('/buyers/carts'));
                if ($cartResponse->successful()) {
                    $cart = $cartResponse->json('data');
                    if ($cart && !empty($cart['canteens'])) {
                        foreach ($cart['canteens'] as $canteen) {
                            foreach ($canteen['items'] as $item) {
                                $cartCount += $item['quantity'];
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $cartCount = 0;
            }
        }

        // ---- MENU POPULER ----
        // Sort: rating × log(1 + total_reviews), ambil 4 teratas
        usort($allMenus, function ($a, $b) {
            $scoreA = ($a['total_reviews'] ?? 0) > 0
                ? ($a['average_rating'] ?? 0) * log1p($a['total_reviews'])
                : 0;
            $scoreB = ($b['total_reviews'] ?? 0) > 0
                ? ($b['average_rating'] ?? 0) * log1p($b['total_reviews'])
                : 0;
            return $scoreB <=> $scoreA;
        });
        $menuPopuler = array_slice($allMenus, 0, 3);

        // ---- KANTIN REKOMENDASI ----
        $ratingSum   = [];
        $ratingCount = [];
        foreach ($allMenus as $menu) {
            $cid = $menu['canteen_id'] ?? null;
            if ($cid && ($menu['total_reviews'] ?? 0) > 0) {
                $ratingSum[$cid]   = ($ratingSum[$cid]   ?? 0) + ($menu['average_rating'] ?? 0);
                $ratingCount[$cid] = ($ratingCount[$cid] ?? 0) + 1;
            }
        }

        $allCanteens = array_map(function ($k) use ($ratingSum, $ratingCount) {
            $cid = $k['_id'] ?? null;
            $k['computed_rating'] = ($cid && isset($ratingCount[$cid]) && $ratingCount[$cid] > 0)
                ? round($ratingSum[$cid] / $ratingCount[$cid], 1)
                : null;
            return $k;
        }, $allCanteens);

        // Filter null DULU sebelum sort — ini yang bikin error sebelumnya
        $allCanteens = array_values(array_filter($allCanteens, fn($k) => $k['computed_rating'] !== null));
        usort($allCanteens, fn($a, $b) => $b['computed_rating'] <=> $a['computed_rating']);
        $kantinRekomendasi = array_slice($allCanteens, 0, 5);


        // Data nama menu per kantin (untuk search hints)
        $allMenuNames    = array_column($allMenus,    'name');
        $allCanteenNames = array_column($allCanteens, 'name');

        return view('pelanggan.beranda', compact(
            'namaDepan',
            'cartCount',
            'menuPopuler',
            'kantinRekomendasi',
            'allMenuNames',
            'allCanteenNames'
        ));
    }
}
