<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Canteen;
use App\Models\Order;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    /**
     * Ambil canteen_id dari session user (bukan auth()->user()).
     */
    private function getCanteenId(): string
    {
        return (string) session('user')['canteen_id'];
    }

    /**
     * Halaman utama pesanan — tampil pesanan masuk & diproses.
     */
    public function index()
    {
        $canteenId = $this->getCanteenId();
        $canteen   = Canteen::find($canteenId);

        if (!$canteen) {
            abort(404, 'Kantin tidak ditemukan.');
        }

        $pesananMasuk = Order::where('canteen_id', $canteenId)
            ->whereIn('status', [Order::STATUS_PENDING, 'processing'])
            ->orderBy('created_at', 'desc')
            ->get();

        $menungguVerifikasi = $pesananMasuk->where('status', Order::STATUS_PENDING)->values();
        $sedangDiproses     = $pesananMasuk->where('status', 'processing')->values();

        return view('admin.pesanan', compact(
            'canteen',
            'menungguVerifikasi',
            'sedangDiproses',
        ));
    }

    /**
     * Update status pesanan.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:processing,ready,completed,cancelled',
        ]);

        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.pesanan', ['tab' => 'diproses'])
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Verifikasi pembayaran → status jadi processing → redirect ke halaman status.
     */
    public function verifyPayment($orderId)
    {
        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order->payment['status'] !== 'pending_verification') {
            return back()->with('error', 'Pembayaran sudah diverifikasi sebelumnya.');
        }

        $payment            = $order->payment;
        $payment['status']  = 'paid';
        $payment['paid_at'] = now()->toDateTimeString();

        $order->update([
            'payment' => $payment,
            'status'  => 'processing',
        ]);

        // Redirect ke halaman status setelah terima
        return redirect()->route('admin.pesanan.status', $orderId)
            ->with('success', 'Pembayaran diverifikasi. Pesanan sedang dimasak.');
    }

    /**
     * Tolak pembayaran → batalkan pesanan → redirect ke halaman cancel.
     */
    public function rejectPayment(Request $request, $orderId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        $canteenId = $this->getCanteenId();

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

        // Redirect ke halaman cancel setelah tolak
        return redirect()->route('admin.pesanan.cancelPage', $orderId);
    }

    /**
     * Toggle buka/tutup kantin — AJAX, return JSON.
     */
    public function toggleOpen(Request $request)
    {
        $canteenId = $this->getCanteenId();
        $canteen   = Canteen::find($canteenId);

        if (!$canteen) {
            return response()->json(['success' => false, 'message' => 'Kantin tidak ditemukan.'], 404);
        }

        // Handle JSON boolean true/false maupun string "true"/"false"/"1"/"0"
        $raw    = $request->input('is_open');
        $isOpen = filter_var($raw, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (is_null($isOpen)) {
            return response()->json(['success' => false, 'message' => 'Nilai is_open tidak valid.'], 422);
        }

        Canteen::where('_id', $canteenId)->update(['is_open' => $isOpen]);

        return response()->json([
            'success' => true,
            'message' => $isOpen ? 'Kantin sekarang buka.' : 'Kantin sekarang tutup.',
            'is_open' => $isOpen,
        ]);
    }

    /**
     * Halaman rincian pesanan lengkap — verifikasi / tolak pembayaran di sini.
     */
    public function rincian($orderId)
    {
        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('admin.pesanan-rincian', compact('order'));
    }

    /**
     * Batalkan pesanan.
     */
    public function cancel($orderId)
    {
        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if (!in_array($order->status, [Order::STATUS_PENDING, 'processing'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        $order->update(['status' => Order::STATUS_CANCELLED]);

        return redirect()->route('admin.pesanan.cancelPage', $orderId);
    }

    /**
     * Halaman status pesanan — admin ubah dari processing → ready.
     * GET /pesanan/{id}/status
     */
    public function statusPage($orderId)
    {
        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('admin.status', compact('order'));
    }

    /**
     * Halaman cancel — read only, tampil info pesanan yang dibatalkan.
     * GET /pesanan/{id}/cancel
     */
    public function cancelPage($orderId)
    {
        $canteenId = $this->getCanteenId();

        $order = Order::where('_id', $orderId)
            ->where('canteen_id', $canteenId)
            ->first();

        if (!$order) {
            abort(404, 'Pesanan tidak ditemukan.');
        }

        return view('admin.cancel', compact('order'));
    }
}