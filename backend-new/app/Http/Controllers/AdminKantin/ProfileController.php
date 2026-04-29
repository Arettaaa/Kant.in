<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
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

    private function canteenId(): string
    {
        return Session::get('user', [])['canteen_id'] ?? '';
    }

    // GET /admin/profil
    public function show()
    {
        $user      = Session::get('user', []);
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/settings"));

        $canteen = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('admin.profil', compact('user', 'canteen'));
    }

    // GET /admin/profil/edit
    public function edit()
    {
        $user      = Session::get('user', []);
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/settings"));

        $canteen = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('admin.edit-profil', compact('user', 'canteen'));
    }

    // POST /admin/profil
    public function update(Request $request)
    {
        $request->validate([
            'name'          => 'nullable|string',
            'phone'         => 'nullable|string',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        $http = Http::withToken($this->token())->asMultipart();

        if ($request->hasFile('photo_profile')) {
            $http = $http->attach(
                'photo_profile',
                file_get_contents($request->file('photo_profile')->getRealPath()),
                $request->file('photo_profile')->getClientOriginalName()
            );
        }

        $fields = [];
        if ($request->filled('name')) $fields['name'] = $request->name;
        if ($request->filled('phone')) $fields['phone'] = $request->phone;
        if ($request->filled('password')) {
            $fields['password']              = $request->password;
            $fields['password_confirmation'] = $request->password_confirmation;
        }

        $response = $http->post($this->apiUrl('/admin/profiles'), $fields);

        if ($response->successful()) {
            // Update session user
            $updatedUser = $response->json()['data'] ?? [];
            Session::put('user', array_merge(Session::get('user', []), $updatedUser));

            return redirect()->route('admin.profil')->with('success', 'Profil berhasil diperbarui.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal memperbarui profil.')->withInput();
    }

    // GET /admin/profil/jam-operasional
    public function settings()
    {
        $canteenId = $this->canteenId();

        $response = Http::withToken($this->token())
            ->get($this->apiUrl("/canteens/{$canteenId}/settings"));

        $canteen = $response->successful() ? ($response->json()['data'] ?? []) : [];

        return view('admin.jam-operasional', compact('canteen'));
    }

    // POST /admin/profil/settings
    public function updateSettings(Request $request)
    {
        $canteenId = $this->canteenId();

        $request->validate([
            'description'           => 'nullable|string',
            'phone'                 => 'nullable|string',
            'delivery_fee_flat'     => 'nullable|integer|min:0',
            'operating_hours.open'  => 'nullable|string',
            'operating_hours.close' => 'nullable|string',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qris_image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_open'               => 'nullable|in:0,1',
        ]);

        $http = Http::withToken($this->token())->asMultipart();

        if ($request->hasFile('image')) {
            $http = $http->attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            );
        }

        if ($request->hasFile('qris_image')) {
            $http = $http->attach(
                'qris_image',
                file_get_contents($request->file('qris_image')->getRealPath()),
                $request->file('qris_image')->getClientOriginalName()
            );
        }

        $fields = [];
        if ($request->filled('description')) $fields['description'] = $request->description;
        if ($request->filled('phone')) $fields['phone'] = $request->phone;
        if ($request->filled('delivery_fee_flat')) $fields['delivery_fee_flat'] = $request->delivery_fee_flat;
        if ($request->filled('operating_hours.open')) $fields['operating_hours[open]'] = $request->input('operating_hours.open');
        if ($request->filled('operating_hours.close')) $fields['operating_hours[close]'] = $request->input('operating_hours.close');
        if ($request->has('is_open')) $fields['is_open'] = $request->is_open;
        $fields['_method'] = 'PUT';

        $response = $http->post($this->apiUrl("/canteens/{$canteenId}/settings"), $fields);

        if ($response->successful()) {
            return redirect()->route('admin.profil.jam')->with('success', 'Pengaturan kantin berhasil diperbarui.');
        }

        return back()->with('error', $response->json()['message'] ?? 'Gagal memperbarui pengaturan.')->withInput();
    }
}