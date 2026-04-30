<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class CanteenController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    private function token(): string
    {
        return Session::get('api_token', '');
    }

    public function index()
    {
        $response = Http::timeout(15)
            ->withToken($this->token())
            ->get($this->apiUrl('/canteens'));

        if (!$response->successful()) {
            return redirect()->route('admin.global.dasbor')
                ->withErrors('Gagal menyambung ke server API.');
        }

        $canteens    = collect($response->json('data') ?? []);
        $totalKantin = $canteens->count();
        $kantinAktif = $canteens->where('status', 'active')->count();

        return view('admin_global.kantin', compact('canteens', 'totalKantin', 'kantinAktif'));
    }

    public function store(Request $request)
    {
        $http = Http::withToken($this->token());

        // Handle file upload dengan multipart
        if ($request->hasFile('image')) {
            $http = $http->attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $http->post($this->apiUrl('/canteens'), [
            'name'                  => $request->name,
            'location'              => $request->location,
            'description'           => $request->description,
            'phone'                 => $request->phone,
            'delivery_fee_flat'     => $request->delivery_fee_flat,
            'operating_hours'       => $request->operating_hours,
            'operating_hours[open]' => $request->input('operating_hours.open'),
            'operating_hours[close]' => $request->input('operating_hours.close'),
            'admin_name'            => $request->admin_name,
            'admin_email'           => $request->admin_email,
            'admin_password'        => $request->admin_password,
            'admin_phone'           => $request->admin_phone,
        ]);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Kantin berhasil ditambahkan!');
        }

        $errors = $response->json('errors') ?? [];
        return redirect()->back()->withErrors($errors)->withInput();
    }

    public function update(Request $request, $id)
    {
        $token = $this->token();

        $payload = [
            'name'              => $request->name,
            'location'          => $request->location,
            'phone'             => $request->phone,
            'admin_phone'       => $request->admin_phone,
            'status'            => $request->status,
            'delivery_fee_flat' => $request->delivery_fee_flat !== null ? (int) $request->delivery_fee_flat : null,
            'operating_hours'   => $request->operating_hours, // ✅ tambah ini
        ];

        $payload = array_filter($payload, fn($v) => !is_null($v) && $v !== '');

        $response = Http::withToken($token)
            ->put($this->apiUrl("/canteens/{$id}"), $payload);

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Kantin berhasil diperbarui!');
        }

        return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui kantin. ' . $response->body()]);
    }
    public function destroy($id)
    {
        $response = Http::withToken($this->token())
            ->delete($this->apiUrl("/canteens/{$id}"));

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Kantin berhasil dihapus!');
        }

        return redirect()->back()->withErrors(['msg' => 'Gagal menghapus kantin.']);
    }
}
