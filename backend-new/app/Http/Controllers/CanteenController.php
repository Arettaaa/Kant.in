<?php

namespace App\Http\Controllers;

use App\Models\Canteen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CanteenController extends Controller
{
    // TAMPILAN UTAMA: List Kantin
    public function index()
    {
        // Ambil semua kantin KECUALI yang statusnya 'pending'
        $canteens = Canteen::with('admin')
            ->where('status', '!=', 'pending')
            ->latest()
            ->get();

        // Data Statistik
        $totalKantin = $canteens->count(); // Total dari data yang ditarik (bukan pending)
        $kantinAktif = $canteens->where('status', 'active')->count(); // Total yang aktif saja

        return view('admin_global.kantin', compact('canteens', 'totalKantin', 'kantinAktif'));
    }

    // PROSES: Simpan Kantin Baru (Dari Modal Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'admin_name' => 'required|string',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:6',
            'location' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // 1. Upload Gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('canteens', 'public');
        }

        // 2. Buat Kantin (Gunakan data default seperti di API kamu)
        $canteen = Canteen::create([
            'name' => $request->name,
            'location' => $request->location,
            'image' => $imagePath,
            'delivery_fee_flat' => 5000, // Contoh default
            'operating_hours' => ['open' => '08:00', 'close' => '17:00'],
            'status' => 'active',
            'is_active' => true,
            'is_open' => true,
        ]);

        // 3. Buat User Admin Kantin (Relasi)
        User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin_kantin',
            'canteen_id' => $canteen->id,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Kantin dan Admin berhasil didaftarkan!');
    }

    // PROSES: Update Kantin
    public function update(Request $request, $id)
    {
        $canteen = Canteen::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
            // Validasi 'status' dihapus karena form edit tidak mengirimkan data status
        ]);

        if ($request->hasFile('image')) {
            if ($canteen->image) Storage::disk('public')->delete($canteen->image);
            $data['image'] = $request->file('image')->store('canteens', 'public');
        }

        $canteen->update($data);

        return redirect()->back()->with('success', 'Data kantin berhasil diperbarui!');
    }

    // PROSES: Hapus Kantin
    public function destroy($id)
    {
        $canteen = Canteen::findOrFail($id);

        // Hapus foto agar tidak nyampah di storage
        if ($canteen->image) {
            Storage::disk('public')->delete($canteen->image);
        }

        // Hapus kantin (User adminnya juga bisa dihapus otomatis jika ada cascade/manual)
        $canteen->delete();

        return redirect()->back()->with('success', 'Kantin telah dihapus.');
    }
}
