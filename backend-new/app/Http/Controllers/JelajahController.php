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
            if ($search !== '') $params['search'] = $search;

            $response = Http::timeout(15)->get($this->apiUrl('/menus'), $params);
            if ($response->successful()) {
                $menus = $response->json('data') ?? [];
            }

            if ($category !== 'Semua') {
                $menus = array_filter(
                    $menus,
                    fn($m) => strtolower($m['category'] ?? '') === strtolower($category)
                );
                $menus = array_values($menus);
            }

            usort($menus, function ($a, $b) {
                $availA = $a['is_available'] ?? true;
                $availB = $b['is_available'] ?? true;

                if ($availA !== $availB) {
                    return $availA ? -1 : 1;
                }

                $ratingA = $a['average_rating'] ?? 0;
                $ratingB = $b['average_rating'] ?? 0;
                return $ratingB <=> $ratingA;
            });
        } else {
            $statusFilter = $request->get('status', 'semua');
            $searchKantin = $request->get('search', '');

            $response = Http::timeout(15)->get($this->apiUrl('/canteens'));
            if ($response->successful()) {
                $canteens = $response->json('data') ?? [];
            }

            if ($searchKantin !== '') {
                $canteens = array_filter(
                    $canteens,
                    fn($k) => str_contains(strtolower($k['name'] ?? ''), strtolower($searchKantin))
                );
            }

            if ($statusFilter === 'buka') {
                $canteens = array_filter($canteens, fn($k) => $k['is_open'] ?? false);
            } elseif ($statusFilter === 'tutup') {
                $canteens = array_filter($canteens, fn($k) => !($k['is_open'] ?? false));
            }

            $canteens = array_values($canteens);

            $canteenIds = array_column($canteens, '_id');

            $responses = Http::pool(
                fn($pool) =>
                array_map(
                    fn($id) => $pool->as($id)->timeout(10)->get($this->apiUrl('/canteens/' . $id . '/menus')),
                    $canteenIds
                )
            );

            $canteens = array_map(function ($kantin) use ($responses) {
                $res = $responses[$kantin['_id']] ?? null;
                $avgRating = null;

                if ($res && $res->successful()) {
                    $menus = $res->json('data') ?? [];
                    $total = 0;
                    $count = 0;
                    foreach ($menus as $menu) {
                        if (($menu['total_reviews'] ?? 0) > 0) {
                            $total += $menu['average_rating'] ?? 0;
                            $count++;
                        }
                    }
                    if ($count > 0) $avgRating = round($total / $count, 1);
                }

                $kantin['computed_rating'] = $avgRating;
                return $kantin;
            }, $canteens);

            usort($canteens, function ($a, $b) {
                $openA = $a['is_open'] ?? false;
                $openB = $b['is_open'] ?? false;

                if ($openA !== $openB) {
                    return $openA ? -1 : 1;
                }

                $ratingA = $a['computed_rating'] ?? 0;
                $ratingB = $b['computed_rating'] ?? 0;
                return $ratingB <=> $ratingA;
            });
        }

        return view('pelanggan.jelajah', compact('menus', 'canteens', 'tab', 'category', 'search', 'searchKantin', 'statusFilter'));
    }
}