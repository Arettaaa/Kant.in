<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter; // Import library baru kita!
use Carbon\Carbon;

class ReportController extends Controller
{
    public function export(Request $request, $canteenId)
    {
        // 1. Otorisasi
        $user = $request->user();
        if ($user->role === 'admin_kantin' && (string) $user->canteen_id !== (string) $canteenId) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        // 2. Ambil Parameter
        $format = $request->query('format', 'pdf'); 
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // 3. Query Data Transaksi
        $query = Order::where('canteen_id', $canteenId)->where('status', 'completed');

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            $query->where('created_at', '>=', $start)
                  ->where('created_at', '<=', $end);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data transaksi pada rentang tanggal tersebut.'], 404);
        }

        $canteen = Canteen::find($canteenId);

        // 4. Eksekusi Export sesuai Format
        if (strtolower($format) === 'csv' || strtolower($format) === 'excel') {
            return $this->exportExcel($orders); // Panggil fungsi Excel baru
        } else {
            return $this->exportPdf($orders, $canteen, $startDate, $endDate);
        }
    }

    /**
     * Logika Export XLSX Asli (Menggunakan Spatie Simple Excel)
     */
    private function exportExcel($orders)
    {
        $fileName = 'Laporan_Kantin_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // Buat file excel yang langsung di-download ke browser (Stream)
        $writer = SimpleExcelWriter::streamDownload($fileName);

        foreach ($orders as $order) {
            // Data langsung ditulis berurutan sesuai judul kolom
            $writer->addRow([
                'ID Pesanan'        => $order->order_code,
                'Tanggal'           => Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d-M-Y H:i'),
                'Nama Pelanggan'    => $order->customer_snapshot['name'] ?? 'Pelanggan',
                'Metode Pembayaran' => strtoupper($order->payment['method'] ?? 'QRIS'),
                'Total Harga (Rp)'  => $order->total_amount
            ]);
        }

        return $writer->toBrowser();
    }

    /**
     * Logika Export PDF (Menggunakan Barryvdh DOMPDF)
     */
    private function exportPdf($orders, $canteen, $startDate, $endDate)
    {
        $fileName = 'Laporan_Kantin_' . Carbon::now()->format('Ymd_His') . '.pdf';
        
        $pdf = Pdf::loadView('exports.transactions_pdf', [
            'orders' => $orders,
            'canteen' => $canteen,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        
        return $pdf->download($fileName);
    }
}