<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function apiToken(): string
    {
        return Session::get('api_token', '');
    }

    public function index()
    {
        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->get($this->apiUrl('/registrations'));

        $registrations = $response->json('data') ?? [];
        Session::put('pending_count', count($registrations));

        return view('admin_global.notifikasi', compact('registrations'));
    }
    public function approve(string $id)
    {
        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->post($this->apiUrl("/registrations/{$id}/approve"));

        if (!$response->successful()) {
            return redirect()->route('admin.global.notifikasi')
                ->withErrors(['error' => $response->json('message') ?? 'Gagal menyetujui.']);
        }

        return redirect()->route('admin.global.notifikasi')
            ->with('success', 'Kantin berhasil disetujui dan diaktifkan!');
    }

    public function reject(Request $request, string $id)
    {
        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->post($this->apiUrl("/registrations/{$id}/reject"), [
                'reason' => $request->reason ?? '',
            ]);

        if (!$response->successful()) {
            return redirect()->route('admin.global.notifikasi')
                ->withErrors(['error' => $response->json('message') ?? 'Gagal menolak.']);
        }

        return redirect()->route('admin.global.notifikasi')
            ->with('success', 'Pendaftaran kantin berhasil ditolak.');
    }

    public function review(string $id)
    {
        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->get($this->apiUrl("/canteens/{$id}"));

        if (!$response->successful()) {
            return redirect()->route('admin.global.notifikasi')
                ->withErrors('Kantin tidak ditemukan.');
        }

        $canteen = $response->json('data');

        return view('admin_global.rev-pendaftaran', compact('canteen'));
    }
}
