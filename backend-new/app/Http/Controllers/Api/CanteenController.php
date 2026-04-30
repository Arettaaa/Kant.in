<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CanteenController extends Controller
{
    public function index()
    {
        $canteens = Canteen::where('is_active', true)
            ->where('status', 'active')
            ->get()
            ->map(fn($canteen) => $this->formatCanteen($canteen));

        return response()->json(['success' => true, 'data' => $canteens]);
    }

    public function show($id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $this->formatCanteen($canteen)]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string',
            'location'              => 'required|string',
            'description'           => 'nullable|string',
            'phone'                 => 'nullable|string',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat'     => 'nullable|integer|min:0',
            'operating_hours'       => 'nullable|array',
            'operating_hours.open'  => 'nullable|string',
            'operating_hours.close' => 'nullable|string',
            'admin_name'            => 'required|string',
            'admin_email'           => 'required|email|unique:users,email',
            'admin_password'        => 'required|min:6',
            'admin_phone'           => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        $canteen = Canteen::create([
            'name'              => $validated['name'],
            'description'       => $validated['description'] ?? null,
            'location'          => $validated['location'],
            'phone'             => $validated['phone'] ?? null,
            'image'             => $validated['image'] ?? null,
            'qris_image'        => null,
            'delivery_fee_flat' => $validated['delivery_fee_flat'] ?? 0,
            'operating_hours'   => $validated['operating_hours'] ?? ['open' => '07:00', 'close' => '17:00'],
            'is_active'         => true,
            'is_open'           => true,
            'status'            => 'active',
        ]);

        $admin = User::create([
            'name'       => $validated['admin_name'],
            'email'      => $validated['admin_email'],
            'password'   => Hash::make($validated['admin_password']),
            'phone'      => $validated['admin_phone'] ?? null,
            'role'       => 'admin_kantin',
            'canteen_id' => (string) $canteen->_id,
            'status'     => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kantin dan akun admin berhasil dibuat.',
            'data'    => [
                'canteen' => $this->formatCanteen($canteen),
                'admin'   => [
                    'id'    => (string) $admin->_id,
                    'name'  => $admin->name,
                    'email' => $admin->email,
                ],
            ],
        ], 201);
    }

    // public function update(Request $request, $id)
    // {
    //     $canteen = Canteen::find($id);
    //     if (!$canteen) {
    //         return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
    //     }

    //     $validated = $request->validate([
    //         'name'                  => 'sometimes|string',
    //         'description'           => 'nullable|string',
    //         'location'              => 'sometimes|string',
    //         'phone'                 => 'nullable|string',
    //         'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    //         'delivery_fee_flat'     => 'sometimes|integer|min:0',
    //         'operating_hours'       => 'sometimes|array',
    //         'operating_hours.open'  => 'sometimes|string',
    //         'operating_hours.close' => 'sometimes|string',
    //         'is_active'             => 'sometimes|boolean',
    //         'status'                => 'sometimes|in:active,inactive',
    //     ]);

    //     if ($request->hasFile('image')) {
    //         if ($canteen->image) Storage::disk('public')->delete($canteen->image);
    //         $validated['image'] = $request->file('image')->store('canteens', 'public');
    //     }

    //     // sync is_active dengan status
    //     if (isset($validated['status'])) {
    //         $validated['is_active'] = $validated['status'] === 'active';
    //     }

    //     $canteen->update($validated);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Kantin berhasil diperbarui.',
    //         'data'    => $this->formatCanteen($canteen->fresh()),
    //     ]);
    // }

    public function destroy($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        // ✅ Hapus foto kantin
        if ($canteen->image) {
            Storage::disk('public')->delete($canteen->image);
        }
        if ($canteen->qris_image) {
            Storage::disk('public')->delete($canteen->qris_image);
        }

        // ✅ Hapus semua user admin kantin yang terkait
        $admins = User::where('canteen_id', (string) $id)->get();
        foreach ($admins as $admin) {
            if ($admin->photo_profile) {
                Storage::disk('public')->delete($admin->photo_profile);
            }
            $admin->delete();
        }

        $canteen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kantin dan akun admin berhasil dihapus.',
        ]);
    }

    // ✅ Sekarang include admin_name — aman untuk mobile (hanya nambah field)
    // private function formatCanteen($canteen)
    // {
    //     $data        = $canteen->toArray();
    //     $data['_id'] = (string) $canteen->_id;

    //     if (!empty($data['image'])) {
    //         $data['image'] = asset('storage/' . $data['image']);
    //     }
    //     if (!empty($data['qris_image'])) {
    //         $data['qris_image'] = asset('storage/' . $data['qris_image']);
    //     }

    //     // Ambil nama admin kantin
    //     $admin                    = User::where('canteen_id', (string) $canteen->_id)
    //                                     ->where('role', 'admin_kantin')
    //                                     ->first();
    //     $data['admin_name']       = $admin?->name ?? 'Belum ada pemilik';
    //     $data['admin_email']      = $admin?->email ?? null;

    //     return $data;
    // }

    private function formatCanteen($canteen)
    {
        $data        = $canteen->toArray();
        $data['_id'] = (string) $canteen->_id;

        if (!empty($data['image'])) {
            $data['image'] = asset('storage/' . $data['image']);
        }
        if (!empty($data['qris_image'])) {
            $data['qris_image'] = asset('storage/' . $data['qris_image']);
        }

        // ✅ Ambil data admin kantin sekalian
        $admin = User::where('canteen_id', (string) $canteen->_id)
            ->where('role', 'admin_kantin')
            ->first();

        $data['admin_name']  = $admin?->name  ?? 'Belum ada pemilik';
        $data['admin_email'] = $admin?->email ?? null;
        $data['admin_phone'] = $admin?->phone ?? null; // ✅ phone pemilik
        $data['admin_id']    = $admin ? (string) $admin->_id : null;

        // ✅ Pastikan delivery_fee_flat selalu integer
        $data['delivery_fee_flat'] = (int) ($data['delivery_fee_flat'] ?? 0);

        return $data;
    }

    public function update(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name'                  => 'sometimes|string',
            'description'           => 'nullable|string',
            'location'              => 'sometimes|string',
            'phone'                 => 'nullable|string',   // phone kantin
            'admin_phone'           => 'nullable|string',   // phone pemilik
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat'     => 'sometimes|integer|min:0',
            'operating_hours'       => 'sometimes|array',
            'operating_hours.open'  => 'sometimes|string',
            'operating_hours.close' => 'sometimes|string',
            'is_active'             => 'sometimes|boolean',
            'status'                => 'sometimes|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            if ($canteen->image) Storage::disk('public')->delete($canteen->image);
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        // Sync is_active dengan status
        if (isset($validated['status'])) {
            $validated['is_active'] = $validated['status'] === 'active';
        }

        // ✅ Update phone pemilik di tabel users
        if (array_key_exists('admin_phone', $validated)) {
            User::where('canteen_id', (string) $id)
                ->where('role', 'admin_kantin')
                ->update(['phone' => $validated['admin_phone']]);
            unset($validated['admin_phone']); // jangan ikut update ke canteen
        }

        $canteen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil diperbarui.',
            'data'    => $this->formatCanteen($canteen->fresh()),
        ]);
    }

    public function assignAdmin(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string',
        ]);

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'phone'      => $validated['phone'] ?? null,
            'role'       => 'admin_kantin',
            'canteen_id' => (string) $id,
            'status'     => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin kantin berhasil dibuat.',
            'data'    => [
                'canteen_id'   => $id,
                'canteen_name' => $canteen->name,
                'user_id'      => (string) $user->_id,
                'user_name'    => $user->name,
                'email'        => $user->email,
            ],
        ], 201);
    }

    public function registrations()
    {
        $canteens = Canteen::where('status', 'pending')
            ->get()
            ->map(fn($c) => $this->formatCanteen($c));

        return response()->json(['success' => true, 'data' => $canteens]);
    }

    public function approveRegistration($id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $canteen->update(['status' => 'active', 'is_active' => true]);
        User::where('canteen_id', (string) $id)->update(['status' => 'active']);

        return response()->json(['success' => true, 'message' => 'Registrasi kantin berhasil disetujui.']);
    }

    public function rejectRegistration(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $request->validate(['reason' => 'nullable|string']);
        $canteen->update(['status' => 'rejected']);
        User::where('canteen_id', (string) $id)->update(['status' => 'rejected']);

        return response()->json(['success' => true, 'message' => 'Registrasi kantin ditolak.']);
    }

    public function toggleOpen(Request $request, $id)
    {
        $user    = $request->user();
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate(['is_open' => 'required|in:0,1,true,false']);
        $isOpen = filter_var($request->is_open, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ?? (bool)(int) $request->is_open;

        Canteen::where('_id', $id)->update(['is_open' => $isOpen]);

        return response()->json([
            'success' => true,
            'message' => $isOpen ? 'Kantin sekarang buka.' : 'Kantin sekarang tutup.',
            'data'    => $this->formatCanteen(Canteen::find($id)),
        ]);
    }

    public function showSettings(Request $request, $id)
    {
        $user    = $request->user();
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        if ((string) $user->canteen_id !== (string) $id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json(['success' => true, 'data' => $this->formatCanteen($canteen)]);
    }

    public function updateSettings(Request $request, $id)
    {
        $user    = $request->user();
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        if ((string) $user->canteen_id !== (string) $id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $validated = $request->validate([
            'description'           => 'nullable|string',
            'phone'                 => 'nullable|string',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qris_image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat'     => 'sometimes|integer|min:0',
            'operating_hours'       => 'sometimes|array',
            'operating_hours.open'  => 'sometimes|string',
            'operating_hours.close' => 'sometimes|string',
        ]);

        if ($request->hasFile('image')) {
            if ($canteen->image) Storage::disk('public')->delete($canteen->image);
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        if ($request->hasFile('qris_image')) {
            if ($canteen->qris_image) Storage::disk('public')->delete($canteen->qris_image);
            $validated['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        }

        $canteen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan kantin berhasil diperbarui.',
            'data'    => $this->formatCanteen($canteen->fresh()),
        ]);
    }
}