<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    /**
     * Halaman profil — tampil data admin dan data kantin sekaligus.
     */
    public function index()
    {
        $user    = auth()->user();
        $canteen = Canteen::find((string) $user->canteen_id);

        return view('admin.profil', compact('user', 'canteen'));
    }

    /**
     * Update profil admin dan kantin sekaligus dalam satu form submit.
     */
    public function update(Request $request)
    {
        $request->validate([
            // Data admin
            'name'                  => 'nullable|string|max:255',
            'phone'                 => 'nullable|string|max:20',
            'photo_profile'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'old_password'          => 'nullable|string',
            'password'              => 'nullable|string|min:8|confirmed',

            // Data kantin
            'description'           => 'nullable|string',
            'location'              => 'nullable|string|max:255',
            'canteen_phone'         => 'nullable|string|max:20',  // beda field agar tidak bentrok dengan phone admin
            'delivery_fee_flat'     => 'nullable|integer|min:0',
            'operating_hours'       => 'nullable|array',
            'operating_hours.open'  => 'nullable|string',
            'operating_hours.close' => 'nullable|string',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qris_image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ---------------------------------------------------------------
        // UPDATE DATA ADMIN
        // ---------------------------------------------------------------
        $user = auth()->user();

        if ($request->filled('name'))  $user->name  = $request->name;
        if ($request->filled('phone')) $user->phone = $request->phone;

        // Ganti password hanya jika diisi
        if ($request->filled('password')) {
            if (!$request->filled('old_password') || !Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama salah.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        // Upload foto profil admin
        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $user->photo_profile = $request->file('photo_profile')->store('profiles', 'public');
        }

        $user->save();

        // ---------------------------------------------------------------
        // UPDATE DATA KANTIN
        // ---------------------------------------------------------------
        $canteen = Canteen::find((string) $user->canteen_id);

        if ($canteen) {
            $canteenData = array_filter([
                'description'       => $request->description,
                'location'          => $request->location,
                'phone'             => $request->canteen_phone,
                'delivery_fee_flat' => $request->delivery_fee_flat,
                'operating_hours'   => $request->operating_hours,
            ], fn($v) => !is_null($v));

            // Upload foto kantin
            if ($request->hasFile('image')) {
                if ($canteen->image) Storage::disk('public')->delete($canteen->image);
                $canteenData['image'] = $request->file('image')->store('canteens', 'public');
            }

            // Upload foto QRIS
            if ($request->hasFile('qris_image')) {
                if ($canteen->qris_image) Storage::disk('public')->delete($canteen->qris_image);
                $canteenData['qris_image'] = $request->file('qris_image')->store('qris', 'public');
            }

            if (!empty($canteenData)) {
                $canteen->update($canteenData);
            }
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Halaman pusat bantuan — statis, tidak butuh query DB.
     */
    public function bantuan()
    {
        return view('admin.support');
    }
}