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

        $canteen     = null;
        $canteenName = 'Kantin';
        $canteenId   = $menu['canteen_id'] ?? null;

        if ($canteenId) {
            $canteenResponse = Http::timeout(15)->get($this->apiUrl('/canteens/' . $canteenId));
            if ($canteenResponse->successful()) {
                $canteen     = $canteenResponse->json('data');
                $canteenName = $canteen['name'] ?? 'Kantin';
            }
        }

        $bisaPesan = false;
        $isOpen    = $canteen['is_open'] ?? false;
        $open      = $canteen['operating_hours']['open']  ?? '00:00';
        $close     = $canteen['operating_hours']['close'] ?? '23:59';

        if ($isOpen) {
            $now       = now(); // sudah Jakarta karena timezone di config sudah diubah
            $nowMins   = ($now->hour * 60) + $now->minute;

            [$openH, $openM]   = explode(':', $open);
            [$closeH, $closeM] = explode(':', $close);

            $openMins  = (int)$openH  * 60 + (int)$openM;
            $closeMins = (int)$closeH * 60 + (int)$closeM;

            $bisaPesan = $nowMins >= $openMins && $nowMins < $closeMins;
        }

        return view('pelanggan.detail-menu', compact('menu', 'canteen', 'canteenName', 'bisaPesan', 'open', 'close', 'isOpen'));
    }
}