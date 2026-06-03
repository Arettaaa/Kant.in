<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;

class ProfilController extends Controller
{
    /**
     * Ambil user object dari session.
     */
    private function getUser(): User
    {
        $sessionUser = session('user');
        return User::find($sessionUser['_id'] ?? $sessionUser['id']);
    }

    /**
     * Halaman profil — tampil data admin dan kantin sekaligus.
     */
    public function index()
    {
        $user = $this->getUser();
        $canteen = Canteen::find((string) ($user->canteen_id ?? session('user')['canteen_id']));

        // --- LOGIKA MENGHITUNG RATING ---
        $menus = Menu::where('canteen_id', (string) $canteen->_id)->get();

        // Kumpulkan semua review dari semua menu
        $allReviews = $menus->flatMap(fn($m) => $m->reviews ?? []);

        $totalReviews = $allReviews->count();
        $averageRating = $totalReviews > 0
            ? round(collect($allReviews)->avg('rating'), 1)
            : 0;

        return view('admin.profil', compact('user', 'canteen', 'averageRating', 'totalReviews'));
    }

    public function edit()
    {
        $user = $this->getUser();
        $canteen = Canteen::find((string) ($user->canteen_id ?? session('user')['canteen_id']));

        return view('admin.edit-profil', compact('user', 'canteen'));
    }

    /**
     * Update profil admin + kantin dalam satu form submit.
     */
    public function update(Request $request)
    {
        // 1. Validasi Input (Semua dibuat nullable agar fleksibel)
        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'old_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'canteen_phone' => 'nullable|string|max:20',
            'delivery_fee_flat' => 'nullable|integer|min:0',
            'operating_hours' => 'nullable|array',
            'operating_hours.open' => 'nullable|string',
            'operating_hours.close' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. UPDATE DATA PENGELOLA (ADMIN)
        $user = $this->getUser();

        // Gunakan assignment langsung agar menembus batas $fillable
        if ($request->has('name'))
            $user->name = $request->name;
        if ($request->has('phone'))
            $user->phone = $request->phone;

        if ($request->filled('password')) {
            if (!$request->filled('old_password') || !Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama salah.'])->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo_profile')) {
            if ($user->photo_profile)
                Storage::disk('public')->delete($user->photo_profile);
            $user->photo_profile = $request->file('photo_profile')->store('profiles', 'public');
        }

        // Simpan paksa perubahan ke tabel users
        $user->save();

        // Segarkan session agar foto dan nama di pojok layar langsung berubah
        $sessionUser = session('user');
        $sessionUser['name'] = $user->name;
        $sessionUser['phone'] = $user->phone;
        if ($user->photo_profile) {
            $sessionUser['photo_profile'] = $user->photo_profile;
        }
        session(['user' => $sessionUser]);

        // 3. UPDATE DATA KANTIN
        $canteenId = $user->canteen_id ?? session('user')['canteen_id'];
        $canteen = Canteen::find((string) $canteenId);

        if ($canteen) {
            // Gunakan assignment langsung agar menembus batas $fillable
            if ($request->has('description'))
                $canteen->description = $request->description;
            if ($request->has('location'))
                $canteen->location = $request->location;
            if ($request->has('canteen_phone'))
                $canteen->phone = $request->canteen_phone;

            if ($request->has('delivery_fee_flat')) {
                $canteen->delivery_fee_flat = (int) $request->delivery_fee_flat;
            }

            if ($request->has('operating_hours')) {
                $canteen->operating_hours = $request->operating_hours;
            }

            if ($request->hasFile('image')) {
                if ($canteen->image)
                    Storage::disk('public')->delete($canteen->image);
                $canteen->image = $request->file('image')->store('canteens', 'public');
            }

            if ($request->hasFile('qris_image')) {
                if ($canteen->qris_image)
                    Storage::disk('public')->delete($canteen->qris_image);
                $canteen->qris_image = $request->file('qris_image')->store('qris', 'public');
            }

            $canteen->save();
        }

        return redirect()->route('admin.profil')->with('success', 'Profil dan pengaturan berhasil diperbarui.');
    }

    /**
     * Halaman pusat bantuan — statis.
     */
    public function bantuan()
    {
        return view('admin.support');
    }
}