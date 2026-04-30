<?php

namespace App\Http\Controllers\AdminKantin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Canteen;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    /**
     * Halaman riwayat transaksi.
     * Chart.js di view akan hit endpoint chartData() via AJAX.
     */
    public function index()
    {
        $canteenId = (string) auth()->user()->canteen_id;
        $canteen   = Canteen::find($canteenId);

        // Transaksi selesai dan dibatalkan untuk tabel riwayat
        $orders = Order::where('canteen_id', $canteenId)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->where('status', 'completed')->sum('total_amount');
        $totalOrders  = $orders->where('status', 'completed')->count();

        return view('admin-kantin.transaksi.index', compact(
            'canteen',
            'orders',
            'totalRevenue',
            'totalOrders',
        ));
    }

    /**
     * API endpoint untuk Chart.js — data grafik pendapatan per hari (30 hari terakhir).
     * Dipanggil via AJAX dari view, sesuai syarat dosen (grafik harus hit API).
     *
     * GET /admin-kantin/transaksi/chart-data?periode=30
     */
    public function chartData(Request $request)
    {
        $request->validate([
            'periode' => 'nullable|integer|in:7,30,90',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;
        $periode   = (int) $request->input('periode', 30);

        $startDate = now()->subDays($periode - 1)->startOfDay();
        $endDate   = now()->endOfDay();

        $orders = Order::where('canteen_id', $canteenId)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['total_amount', 'created_at']);

        // Group by tanggal, sum revenue per hari
        $grouped = $orders->groupBy(fn($o) =>
            Carbon::parse($o->created_at)->timezone('Asia/Jakarta')->format('Y-m-d')
        );

        // Buat array lengkap semua hari dalam periode (termasuk hari 0 transaksi)
        $labels  = [];
        $data    = [];

        for ($i = $periode - 1; $i >= 0; $i--) {
            $date      = now()->subDays($i)->timezone('Asia/Jakarta')->format('Y-m-d');
            $labels[]  = Carbon::parse($date)->translatedFormat('d M');
            $data[]    = $grouped->has($date)
                ? (int) $grouped[$date]->sum('total_amount')
                : 0;
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'labels'        => $labels,
                'revenue'       => $data,
                'total_revenue' => array_sum($data),
                'periode'       => $periode,
            ],
        ]);
    }

    /**
     * Export laporan penjualan — PDF atau Excel.
     * Reuse logic dari Api\ReportController.
     *
     * GET /admin-kantin/transaksi/export?format=pdf&start_date=2024-01-01&end_date=2024-01-31
     */
    public function export(Request $request)
    {
        $request->validate([
            'format'     => 'required|in:pdf,xlsx',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $canteenId = (string) auth()->user()->canteen_id;
        $canteen   = Canteen::findOrFail($canteenId);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::where('canteen_id', $canteenId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->input('format') === 'xlsx') {
            return $this->exportExcel($orders, $canteen, $startDate, $endDate);
        }

        return $this->exportPdf($orders, $canteen, $startDate, $endDate);
    }

    // =========================================================================
    //  EXCEL EXPORT
    // =========================================================================

    protected function exportExcel($orders, $canteen, $startDate, $endDate): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // Header laporan
        $this->writeReportHeader($sheet, $canteen, $startDate, $endDate);

        // Header tabel
        $tableHeaderRow = 7;
        $columns        = ['A', 'B', 'C', 'D', 'E', 'F'];
        $headers        = ['No.', 'ID Pesanan', 'Tanggal & Waktu', 'Nama Pelanggan', 'Metode Pembayaran', 'Total Harga (Rp)'];

        foreach ($columns as $i => $col) {
            $sheet->getCell("{$col}{$tableHeaderRow}")->setValue($headers[$i]);
        }

        $sheet->getStyle("A{$tableHeaderRow}:F{$tableHeaderRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10, 'name' => 'Arial'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A5F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => $this->borderStyle('FF1E3A5F'),
        ]);
        $sheet->getRowDimension($tableHeaderRow)->setRowHeight(22);

        // Baris data
        $currentRow  = $tableHeaderRow + 1;
        $totalAmount = 0;
        $no          = 1;

        foreach ($orders as $order) {
            $bgColor = ($no % 2 === 0) ? 'FFF0F4FA' : 'FFFFFFFF';

            $rowData = [
                'A' => $no,
                'B' => $order->order_code,
                'C' => Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i'),
                'D' => $order->customer_snapshot['name'] ?? 'Pelanggan',
                'E' => strtoupper($order->payment['method'] ?? 'CASH'),
                'F' => $order->total_amount,
            ];

            foreach ($rowData as $col => $value) {
                $sheet->getCell("{$col}{$currentRow}")->setValue($value);
            }

            $sheet->getStyle("F{$currentRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
                'font'    => ['name' => 'Arial', 'size' => 10],
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders' => $this->borderStyle('FFB8C8D8'),
            ]);

            $totalAmount += $order->total_amount;
            $currentRow++;
            $no++;
        }

        // Baris kosong jika tidak ada data
        if ($orders->isEmpty()) {
            $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
            $sheet->getCell("A{$currentRow}")->setValue('Tidak ada data transaksi pada periode ini.');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $currentRow++;
        }

        // Baris total
        $totalRow = $currentRow + 1;
        $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
        $sheet->getCell("A{$totalRow}")->setValue('TOTAL PENDAPATAN');
        $sheet->getCell("F{$totalRow}")->setValue($totalAmount);

        $sheet->getStyle("F{$totalRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("F{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A{$totalRow}:F{$totalRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 10, 'name' => 'Arial'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2D6A4F']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => $this->borderStyle('FF1A5C3A'),
        ]);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // Footer
        $footerRow = $totalRow + 2;
        $sheet->mergeCells("A{$footerRow}:F{$footerRow}");
        $sheet->getCell("A{$footerRow}")->setValue(
            'Laporan dicetak pada: ' . Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
        );
        $sheet->getStyle("A{$footerRow}")->getFont()->setItalic(true)->setSize(9)->setName('Arial')->getColor()->setARGB('FF666666');

        // Auto width
        foreach (['B', 'C', 'D', 'E', 'F'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->getColumnDimension('A')->setAutoSize(false)->setWidth(6);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);

        $fileName = 'Laporan_Penjualan_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // =========================================================================
    //  PDF EXPORT
    // =========================================================================

    protected function exportPdf($orders, $canteen, $startDate, $endDate)
    {
        $fileName = 'Laporan_Kantin_' . Carbon::now()->format('Ymd_His') . '.pdf';

        $pdf = Pdf::loadView('exports.transactions_pdf', [
            'orders'    => $orders,
            'canteen'   => $canteen,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }

    // =========================================================================
    //  HELPER
    // =========================================================================

    protected function writeReportHeader($sheet, $canteen, $startDate, $endDate): void
    {
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:F4');

        $sheet->getCell('A1')->setValue('LAPORAN PENJUALAN KANTIN');
        $sheet->getCell('A2')->setValue(strtoupper($canteen->name ?? 'Kantin'));
        $sheet->getCell('A3')->setValue('Periode: ' . $startDate->format('d M Y') . ' s/d ' . $endDate->format('d M Y'));
        $sheet->getCell('A4')->setValue('');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setName('Arial')->getColor()->setARGB('FF1E3A5F');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->setName('Arial')->getColor()->setARGB('FF1E3A5F');
        $sheet->getStyle('A3')->getFont()->setSize(10)->setName('Arial')->getColor()->setARGB('FF555555');

        foreach (['A1', 'A2', 'A3'] as $cell) {
            $sheet->getStyle($cell)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(18);

        $sheet->getStyle('A1:F3')->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setARGB('FFCCCCCC');
    }

    protected function borderStyle(string $argbColor = 'FF000000'): array
    {
        $border = ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $argbColor]];
        return ['top' => $border, 'bottom' => $border, 'left' => $border, 'right' => $border];
    }
}