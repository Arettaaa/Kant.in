<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Canteen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

// PhpSpreadsheet — install via: composer require phpoffice/phpspreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Entry point utama — arahkan ke PDF atau Excel sesuai parameter.
     */
    public function export(Request $request)
    {
        $request->validate([
            'format'     => 'required|in:pdf,xlsx',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $canteen   = Canteen::findOrFail(auth()->user()->canteen_id);
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate   = Carbon::parse($request->end_date)->endOfDay();

        $orders = Order::where('canteen_id', $canteen->id)
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
    //  EXCEL EXPORT — Format Laporan Resmi dengan PhpSpreadsheet
    // =========================================================================

    protected function exportExcel($orders, $canteen, $startDate, $endDate): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Penjualan');

        // ------------------------------------------------------------------
        // BAGIAN 1 — HEADER LAPORAN (baris 1–5)
        // ------------------------------------------------------------------
        $this->writeReportHeader($sheet, $canteen, $startDate, $endDate);

        // ------------------------------------------------------------------
        // BAGIAN 2 — HEADER TABEL (baris 7)
        // ------------------------------------------------------------------
        $tableHeaderRow = 7;
        $columns        = ['A', 'B', 'C', 'D', 'E', 'F'];
        $headers        = ['No.', 'ID Pesanan', 'Tanggal & Waktu', 'Nama Pelanggan', 'Metode Pembayaran', 'Total Harga (Rp)'];

        foreach ($columns as $i => $col) {
            $cell = $sheet->getCell("{$col}{$tableHeaderRow}");
            $cell->setValue($headers[$i]);
        }

        $sheet->getStyle("A{$tableHeaderRow}:F{$tableHeaderRow}")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A5F'],   // Biru navy profesional
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => $this->borderStyle('FF1E3A5F'),
        ]);
        $sheet->getRowDimension($tableHeaderRow)->setRowHeight(22);

        // ------------------------------------------------------------------
        // BAGIAN 3 — BARIS DATA
        // ------------------------------------------------------------------
        $currentRow    = $tableHeaderRow + 1;
        $totalAmount   = 0;
        $no            = 1;
        $dataStartRow  = $currentRow;

        foreach ($orders as $order) {
            $isEven = ($no % 2 === 0);
            $bgColor = $isEven ? 'FFF0F4FA' : 'FFFFFFFF';   // Zebra striping lembut

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

            // Format mata uang kolom F
            $sheet->getStyle("F{$currentRow}")->getNumberFormat()
                ->setFormatCode('"Rp "#,##0');

            // Alignment khusus
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Style seluruh baris
            $sheet->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
                'font' => ['name' => 'Arial', 'size' => 10],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bgColor],
                ],
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
            $sheet->getStyle("A{$currentRow}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $currentRow++;
        }

        // ------------------------------------------------------------------
        // BAGIAN 4 — BARIS TOTAL
        // ------------------------------------------------------------------
        $totalRow = $currentRow + 1;

        $sheet->mergeCells("A{$totalRow}:E{$totalRow}");
        $sheet->getCell("A{$totalRow}")->setValue('TOTAL PENDAPATAN');
        $sheet->getCell("F{$totalRow}")->setValue($totalAmount);

        $sheet->getStyle("F{$totalRow}")->getNumberFormat()
            ->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("F{$totalRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("A{$totalRow}:F{$totalRow}")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2D6A4F'],   // Hijau tua untuk total
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => $this->borderStyle('FF1A5C3A'),
        ]);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // ------------------------------------------------------------------
        // BAGIAN 5 — FOOTER LAPORAN
        // ------------------------------------------------------------------
        $footerRow = $totalRow + 2;
        
        // Gabungkan sel dari A sampai F agar teks tidak melemahkan kolom A
        $sheet->mergeCells("A{$footerRow}:F{$footerRow}");
        
        $sheet->getCell("A{$footerRow}")->setValue(
            'Laporan dicetak pada: ' . \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
        );
        $sheet->getStyle("A{$footerRow}")->getFont()
            ->setItalic(true)->setSize(9)->setName('Arial')->getColor()->setARGB('FF666666');

        // ------------------------------------------------------------------
        // BAGIAN 6 — AUTO-WIDTH KOLOM (sesuai panjang konten)
        // ------------------------------------------------------------------
        // Kita eksekusi auto-size HANYA untuk kolom B sampai F
        $autoColumns = ['B', 'C', 'D', 'E', 'F'];
        $this->autoSizeColumns($sheet, $autoColumns);

        // Kunci kolom No. (A) agar tidak melar
        $sheet->getColumnDimension('A')->setAutoSize(false);
        $sheet->getColumnDimension('A')->setWidth(6);
        
        // Penyesuaian manual kolom agar lebih proporsional
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(22);

        // ------------------------------------------------------------------
        // STREAM KE BROWSER
        // ------------------------------------------------------------------
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
    //  HELPER — Tulis blok header laporan (nama kantin, periode, dll.)
    // =========================================================================

    protected function writeReportHeader($sheet, $canteen, $startDate, $endDate): void
    {
        // Merge untuk judul utama
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('A4:F4');

        $sheet->getCell('A1')->setValue('LAPORAN PENJUALAN KANTIN');
        $sheet->getCell('A2')->setValue(strtoupper($canteen->name ?? 'Kantin'));
        $sheet->getCell('A3')->setValue(
            'Periode: ' .
            $startDate->format('d M Y') . ' s/d ' .
            $endDate->format('d M Y')
        );
        $sheet->getCell('A4')->setValue('');  // baris spasi

        // Style judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setName('Arial')
            ->getColor()->setARGB('FF1E3A5F');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12)->setName('Arial')
            ->getColor()->setARGB('FF1E3A5F');
        $sheet->getStyle('A3')->getFont()->setSize(10)->setName('Arial')
            ->getColor()->setARGB('FF555555');

        foreach (['A1', 'A2', 'A3'] as $cell) {
            $sheet->getStyle($cell)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
        }

        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(18);

        // Garis pemisah bawah blok header
        $sheet->getStyle('A1:F3')->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setARGB('FFCCCCCC');
    }

    // =========================================================================
    //  HELPER — Definisi border style (agar tidak duplikasi kode)
    // =========================================================================

    protected function borderStyle(string $argbColor = 'FF000000'): array
    {
        $border = [
            'borderStyle' => Border::BORDER_THIN,
            'color'       => ['argb' => $argbColor],
        ];

        return [
            'top'    => $border,
            'bottom' => $border,
            'left'   => $border,
            'right'  => $border,
        ];
    }

    // =========================================================================
    //  HELPER — Auto-size lebar kolom berdasarkan konten terpanjang
    // =========================================================================

    protected function autoSizeColumns($sheet, array $columns): void
    {
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Paksa kalkulasi ulang dimensi
        $sheet->calculateColumnWidths();
    }

    // =========================================================================
    //  PDF EXPORT — Tetap menggunakan DomPDF (tidak berubah)
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
}