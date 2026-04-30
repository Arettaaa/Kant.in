<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Halaman utama pesanan — tampil pesanan masuk (pending) & diproses (processing).
     * Sekaligus berfungsi sebagai dashboard utama admin kantin.
     */
    public function index()
    {
        $canteenId = (string) auth()->user()->canteen_id;
        $canteen   = Canteen::find($canteenId);

        if (!$canteen) {
            abort(404, 'Kantin tidak ditemukan.');
        }

        // Pesanan masuk: pending_verification & processing
        $pesananMasuk = Order::where('canteen_id', $canteenId)
            ->whereIn('status', [Order::STATUS_PENDING, 'processing'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pisahkan berdasarkan status untuk tampilan terpisah di view
        $menungguVerifikasi = $pesananMasuk->where('status', Order::STATUS_PENDING)->values();
        $sedangDiproses     = $pesananMasuk->where('status', 'processing')->values();

        return view('admin-kantin.pesanan.index', compact(
            'canteen',
            'menungguVerifikasi',
            'sedangDiproses',
        ));
    }

    /**
     * Update status pesanan: processing → ready → completed → cancelled.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Verifikasi bukti pembayaran — ubah status payment menjadi paid
     * dan status order menjadi processing.
     */
    public function verifyPayment($orderId)
    {
        $canteenId = (string) auth()->user()->canteen_id;

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order->payment['status'] !== 'pending_verification') {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        $payment             = $order->payment;
        $payment['status']   = 'paid';
        $payment['paid_at']  = now()->toDateTimeString();

        $order->update([
            'payment' => $payment,
            'status'  => 'processing',
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Pesanan masuk ke antrian.');
    }

    /**
     * Tolak bukti pembayaran — batalkan pesanan.
     */
    public function rejectPayment(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order->payment['status'] !== 'pending_verification') {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        $payment           = $order->payment;
        $payment['status'] = 'rejected';

        $order->update([
            'payment' => $payment,
            'status'  => Order::STATUS_CANCELLED,
        ]);

        return back()->with('success', 'Pembayaran ditolak. Pesanan dibatalkan.');
    }

    /**
     * Toggle buka / tutup kantin.
     * Dipanggil via AJAX dari halaman pesanan (toggle switch di dashboard).
     */
    public function toggleOpen(Request $request)
    {
        $request->validate([
            'is_open' => 'required|in:0,1,true,false',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;
        $canteen   = Canteen::find($canteenId);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        $isOpen = filter_var($request->is_open, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ?? (bool)(int) $request->is_open;

        Canteen::where('_id', $canteenId)->update(['is_open' => $isOpen]);

        return response()->json([
            'success' => true,
            'message' => $isOpen ? 'Kantin sekarang buka.' : 'Kantin sekarang tutup.',
            'is_open' => $isOpen,
        ]);
    }

    /**
     * Detail satu pesanan — untuk modal atau halaman detail.
     */
    public function show($orderId)
    {
        $canteenId = (string) auth()->user()->canteen_id;

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('admin-kantin.pesanan.show', compact('order'));
    }
}