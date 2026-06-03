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

    private function getUser(): User
    {
        $sessionUser = session('user');
        return User::find($sessionUser['_id'] ?? $sessionUser['id']);
    }


    public function index()
    {
        $user = $this->getUser();
        $canteen = Canteen::find((string) ($user->canteen_id ?? session('user')['canteen_id']));

        $menus = Menu::where('canteen_id', (string) $canteen->_id)->get();

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

    public function update(Request $request)
    {
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

        $user = $this->getUser();

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

        $user->save();

        $sessionUser = session('user');
        $sessionUser['name'] = $user->name;
        $sessionUser['phone'] = $user->phone;
        if ($user->photo_profile) {
            $sessionUser['photo_profile'] = $user->photo_profile;
        }
        session(['user' => $sessionUser]);

        $canteenId = $user->canteen_id ?? session('user')['canteen_id'];
        $canteen = Canteen::find((string) $canteenId);

        if ($canteen) {
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

    public function bantuan()
    {
        return view('admin.support');
    }
}