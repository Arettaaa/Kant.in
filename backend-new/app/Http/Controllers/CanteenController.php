<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
// ✅ TAMBAHKAN BARIS INI BIAR NGGAK ERROR LAGI:
use App\Models\User; 

class CanteenController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index()
    {
        $token = Session::get('api_token');

        // 1. Ambil data kantin dari API
        $response = Http::timeout(15)
            ->withToken($token)
            ->get($this->apiUrl('/canteens'));

        if ($response->successful()) {
            $rawCanteens = $response->json('data') ?? [];

            // 2. Kita gabungkan data dari API dengan data User (Pemilik) dari Database Lokal
            $canteens = collect($rawCanteens)->map(function($kantin) {
                // Ambil ID Kantin (support format MongoDB _id atau ID biasa)
                $idKantin = $kantin['_id'] ?? $kantin['id'] ?? null;
                
                // Cari user yang punya canteen_id tersebut dan rolenya admin_kantin
                $pemilik = User::where('canteen_id', (string)$idKantin)
                               ->where('role', 'admin_kantin')
                               ->first();
                
                // Masukkan nama pemilik ke dalam array kantin
                $kantin['admin_kantin_name'] = $pemilik ? $pemilik->name : 'Belum ada pemilik';
                return $kantin;
            });

            // Filter supaya yang statusnya 'pending' nggak muncul di halaman ini
            $canteens = $canteens->filter(fn($c) => ($c['status'] ?? '') !== 'pending');

            $totalKantin = $canteens->count();
            $kantinAktif = $canteens->where('status', 'active')->count();

            return view('admin_global.kantin', compact('canteens', 'totalKantin', 'kantinAktif'));
        }

        return redirect()->route('admin.global.dasbor')->withErrors('Gagal menyambung ke server API.');
    }

    // Fungsi store, update, destroy tetap arahkan ke API...
    public function store(Request $request)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->post($this->apiUrl('/canteens'), $request->all());
        return redirect()->back()->with('success', 'Kantin berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $token = Session::get('api_token');
        $response = Http::withToken($token)->delete($this->apiUrl("/canteens/{$id}"));
        return redirect()->back()->with('success', 'Kantin berhasil dihapus!');
    }
}