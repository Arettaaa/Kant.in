<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DetailKantinController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index($id)
    {
        // Ambil data kantin
        $kantinResponse = Http::timeout(15)->get($this->apiUrl('/canteens/' . $id));
        if (!$kantinResponse->successful()) abort(404);

        $kantin = $kantinResponse->json('data');

        // Ambil menu kantin
        $menuResponse = Http::timeout(15)->get($this->apiUrl('/canteens/' . $id . '/menus'));
        $menus = [];
        if ($menuResponse->successful()) {
            $menus = $menuResponse->json('data') ?? [];
        }

        // Kelompokkan menu berdasarkan kategori
        $menuByKategori = [];
        foreach ($menus as $menu) {
            $kat = ucfirst($menu['category'] ?? 'Lainnya');
            $menuByKategori[$kat][] = $menu;
        }

        return view('pelanggan.detail-kantin', compact('kantin', 'menuByKategori'));
    }
}