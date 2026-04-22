<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DetailMenuController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index($id)
    {
        $menuResponse = Http::timeout(15)->get($this->apiUrl('/menus/' . $id));

        if (!$menuResponse->successful()) {
            abort(404);
        }

        $menu = $menuResponse->json('data');

        // Ambil nama kantin via endpoint canteen
        $canteenName = 'Kantin';
        $canteenId   = $menu['canteen_id'] ?? null;

        if ($canteenId) {
            $canteenResponse = Http::timeout(15)->get($this->apiUrl('/canteens/' . $canteenId));
            if ($canteenResponse->successful()) {
                $canteenName = $canteenResponse->json('data.name') ?? 'Kantin';
            }
        }

        return view('pelanggan.detail-menu', compact('menu', 'canteenName'));
    }
}