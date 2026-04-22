<?php
// app/Http/Controllers/ProfilController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfilController extends Controller
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

    private function fetchUser(): ?object
    {
        $user = Session::get('user');
        if (!$user) return null;

        $role = $user['role'] ?? 'pembeli';
        $prefix = $role === 'admin_kantin' ? '/admin' : '/buyers';
        $endpoint = $prefix . '/profiles';

        $response = Http::timeout(15)
            ->withToken($this->apiToken())
            ->get($this->apiUrl($endpoint));

        if (!$response->successful()) return (object) $user;

        $data = $response->json('data');

        // Sync session supaya nama di beranda ikut terupdate
        Session::put('user', $data);

        return (object) $data;
    }

    public function index()
    {
        $user = $this->fetchUser();
        if (!$user) return redirect()->route('pelanggan.login');

        $views = [
            'admin_global' => 'admin_global.profil',
            'admin_kantin' => 'admin_kantin.profil',
            'pembeli'      => 'pelanggan.profil',
        ];

        $view = $views[$user->role] ?? 'pelanggan.profil';
        return view($view, compact('user'));
    }

    public function edit()
    {
        $user = $this->fetchUser();
        if (!$user) return redirect()->route('pelanggan.login');

        $views = [
            'admin_global' => 'admin_global.edit-profil',
            'admin_kantin' => 'admin_kantin.edit-profil',
            'pembeli'      => 'pelanggan.edit-profil',
        ];

        $view = $views[$user->role] ?? 'pelanggan.edit-profil';
        return view($view, compact('user'));
    }

    public function dataDiri()
    {
        $user = $this->fetchUser();
        if (!$user) return redirect()->route('pelanggan.login');

        $views = [
            'admin_global' => 'admin_global.data-diri',
            'admin_kantin' => 'admin_kantin.data-diri',
            'pembeli'      => 'pelanggan.data-diri',
        ];

        $view = $views[$user->role] ?? 'pelanggan.data-diri';
        return view($view, compact('user'));
    }

    public function update(Request $request)
    {
        $user = Session::get('user');
        if (!$user) return redirect()->route('pelanggan.login');

        $role     = $user['role'] ?? 'pembeli';
        $prefix   = $role === 'admin_kantin' ? '/admin' : '/buyers';
        $endpoint = $prefix . '/profiles';

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $http = Http::timeout(15)->withToken($this->apiToken());

        if ($request->hasFile('photo_profile')) {
            $http = $http->attach(
                'photo_profile',
                file_get_contents($request->file('photo_profile')->getRealPath()),
                $request->file('photo_profile')->getClientOriginalName()
            );
        }

        $response = $http->post($this->apiUrl($endpoint), [
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        if (!$response->successful()) {
            $message = $response->json('message') ?? 'Gagal menyimpan profil.';
            return back()->withErrors(['error' => $message]);
        }

        Session::put('user', $response->json('data'));

        return back()->with('success_update', true);
    }
}
