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
    // PUBLIC: GET /canteens
    public function index()
    {
        $canteens = Canteen::where('is_active', true)
            ->where('status', 'active') // tambahkan ini
            ->get()
            ->map(fn($canteen) => $this->formatCanteen($canteen));

        return response()->json([
            'success' => true,
            'data' => $canteens,
        ]);
    }

    // PUBLIC: GET /canteens/{id}
    public function show($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatCanteen($canteen),
        ]);
    }

    // ADMIN GLOBAL: POST /canteens
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data kantin
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'required|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat' => 'required|integer|min:0',
            'operating_hours' => 'required|array',
            'operating_hours.open' => 'required|string',
            'operating_hours.close' => 'required|string',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Data admin kantin
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6',
            'admin_phone' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        if ($request->hasFile('qris_image')) {
            $validated['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        }

        // Buat kantin
        $canteen = Canteen::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'],
            'phone' => $validated['phone'] ?? null,
            'image' => $validated['image'] ?? null,
            'qris_image' => $validated['qris_image'] ?? null,
            'delivery_fee_flat' => $validated['delivery_fee_flat'],
            'operating_hours' => $validated['operating_hours'],
            'is_active' => true,
            'is_open' => true,
            'status' => 'active',
        ]);

        // Buat akun admin kantin
        $admin = User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'phone' => $validated['admin_phone'] ?? null,
            'role' => 'admin_kantin',
            'canteen_id' => (string) $canteen->_id,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kantin dan akun admin berhasil dibuat.',
            'data' => [
                'canteen' => $this->formatCanteen($canteen),
                'admin' => [
                    'id' => (string) $admin->_id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                ],
            ],
        ], 201);
    }

    // ADMIN GLOBAL: PUT /canteens/{id}
    public function update(Request $request, $id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'location' => 'sometimes|string',
            'phone' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_fee_flat' => 'sometimes|integer|min:0',
            'operating_hours' => 'sometimes|array',
            'operating_hours.open' => 'sometimes|string',
            'operating_hours.close' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Hapus foto lama
            if ($canteen->image) {
                Storage::disk('public')->delete($canteen->image);
            }
            $validated['image'] = $request->file('image')->store('canteens', 'public');
        }

        if ($request->hasFile('qris_image')) {
            if ($canteen->qris_image) {
                Storage::disk('public')->delete($canteen->qris_image);
            }
            $validated['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        }

        $canteen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil diperbarui.',
            'data' => $this->formatCanteen($canteen),
        ]);
    }

    // ADMIN GLOBAL: DELETE /canteens/{id}
    public function destroy($id)
    {
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        // Hapus foto jika ada
        if ($canteen->image) {
            Storage::disk('public')->delete($canteen->image);
        }

        $canteen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kantin berhasil dihapus.',
        ]);
    }

    // Helper: format image URL
    private function formatCanteen($canteen)
    {
        $data = $canteen->toArray();
        if (!empty($data['image'])) {
            $data['image'] = asset('storage/' . $data['image']);
        }
        if (!empty($data['qris_image'])) {
            $data['qris_image'] = asset('storage/' . $data['qris_image']);
        }
        return $data;
    }

    // ADMIN GLOBAL: POST /canteens/{id}/admins
    // ADMIN GLOBAL: POST /canteens/{id}/admins
    public function assignAdmin(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'admin_kantin',
            'canteen_id' => (string) $id,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin kantin berhasil dibuat dan di-assign ke kantin.',
            'data' => [
                'canteen_id' => $id,
                'canteen_name' => $canteen->name,
                'user_id' => (string) $user->_id,
                'user_name' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    // ADMIN GLOBAL: GET /registrations
    public function registrations()
    {
        $canteens = Canteen::where('status', 'pending')->get();

        return response()->json([
            'success' => true,
            'data' => $canteens,
        ]);
    }

    // ADMIN GLOBAL: POST /registrations/{id}/approve
    public function approveRegistration($id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $canteen->update(['status' => 'active', 'is_active' => true]);

        // Aktifkan user admin kantin
        User::where('canteen_id', (string) $id)->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi kantin berhasil disetujui.',
        ]);
    }

    // ADMIN GLOBAL: POST /registrations/{id}/reject
    public function rejectRegistration(Request $request, $id)
    {
        $canteen = Canteen::find($id);
        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $canteen->update(['status' => 'rejected']);

        // Nonaktifkan user admin kantin
        User::where('canteen_id', (string) $id)->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi kantin ditolak.',
        ]);
    }

    // ADMIN KANTIN: PUT /canteens/{id}/availability
    public function toggleOpen(Request $request, $id)
    {
        $user = $request->user();
        $canteen = Canteen::find($id);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        // Pastikan admin kantin hanya bisa toggle kantinnya sendiri
        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'is_open' => 'required|in:0,1,true,false',
        ]);

        $isOpen = filter_var($request->is_open, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_null($isOpen)) {
            $isOpen = (bool) (int) $request->is_open;
        }

        Canteen::where('_id', $id)->update(['is_open' => $isOpen]);
        $canteen = Canteen::find($id);

        return response()->json([
            'success' => true,
            'message' => $isOpen ? 'Kantin sekarang buka.' : 'Kantin sekarang tutup.',
            'data' => $this->formatCanteen($canteen),
        ]);
    }
}
