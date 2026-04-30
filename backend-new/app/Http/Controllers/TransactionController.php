<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    private function apiUrl(string $path): string
    {
        $base = env('API_INTERNAL_URL', config('app.url'));
        return rtrim($base, '/') . '/api' . $path;
    }

    public function index(Request $request)
    {
        $token = Session::get('api_token');
        $periode = $request->query('periode', 'bulan');

        $response = Http::timeout(15)
            ->withToken($token)
            ->get($this->apiUrl('/transactions'), [
                'periode' => $periode
            ]);

        if ($response->successful()) {
            $data = $response->json('data');

            $labelPeriode = 'Bulan Ini';
            if ($periode == 'hari')   $labelPeriode = 'Hari Ini';
            if ($periode == 'minggu') $labelPeriode = 'Minggu Ini';
            if ($periode == 'semua')  $labelPeriode = 'Semua Periode';

            return view('admin_global.transaksi', [
                'grandTotalRevenue' => $data['grand_total_revenue'] ?? 0,
                'grandTotalOrders'  => $data['grand_total_orders'] ?? 0,
                'canteens'          => $data['canteens'] ?? [],
                'labelPeriode'      => $labelPeriode
            ]);
        }

        return redirect()->route('admin.global.dasbor')->withErrors('Gagal memuat data transaksi dari API.');
    }

    public function export(Request $request)
    {
        $token   = Session::get('api_token');
        $periode = $request->query('periode', 'bulan');
        $format  = $request->query('format', 'pdf');

        $response = Http::timeout(15)->withToken($token)->get($this->apiUrl('/transactions'), ['periode' => $periode]);

        if (!$response->successful()) {
            return back()->withErrors('Gagal mengambil data dari server untuk laporan.');
        }

        $data      = $response->json('data');
        $canteens  = $data['canteens'] ?? [];
        $timestamp = now()->format('Ymd_His');

        $namaPeriode  = 'Bulan_Ini';
        $labelPeriode = 'Bulan Ini';
        if ($periode == 'hari')   { $namaPeriode = 'Hari_Ini';       $labelPeriode = 'Hari Ini'; }
        if ($periode == 'minggu') { $namaPeriode = 'Minggu_Ini';     $labelPeriode = 'Minggu Ini'; }
        if ($periode == 'semua')  { $namaPeriode = 'Semua_Periode';  $labelPeriode = 'Semua Periode'; }

        $filename = "Laporan_Transaksi_Global_{$namaPeriode}_{$timestamp}";

        if ($format === 'excel') {
            return $this->exportExcel($canteens, $data, $labelPeriode, $filename);
        }

        $pdf = Pdf::loadView('admin_global.export_pdf', [
            'canteens'     => $canteens,
            'labelPeriode' => $labelPeriode,
            'data'         => $data,
        ]);

        return $pdf->download($filename . '.pdf');
    }

    // =========================================================================
    //  EXCEL EXPORT
    // =========================================================================

    protected function exportExcel(array $canteens, array $data, string $labelPeriode, string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Transaksi');

        // ------------------------------------------------------------------
        // BAGIAN 1 — HEADER LAPORAN (baris 1–4)
        // ------------------------------------------------------------------
        $this->writeReportHeader($sheet, $labelPeriode);

        // ------------------------------------------------------------------
        // BAGIAN 2 — HEADER TABEL (baris 6)
        // ------------------------------------------------------------------
        $tableHeaderRow = 6;
        $headers = [
            'A' => 'No.',
            'B' => 'Nama Mitra Kantin',
            'C' => 'Total Pesanan Selesai',
            'D' => 'Total Pendapatan (Rp)',
        ];

        foreach ($headers as $col => $label) {
            $sheet->getCell("{$col}{$tableHeaderRow}")->setValue($label);
        }

        $sheet->getStyle("A{$tableHeaderRow}:D{$tableHeaderRow}")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A5F'],
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
        $currentRow  = $tableHeaderRow + 1;
        $no          = 1;
        $totalOrders = 0;
        $totalRevenue = 0;

        foreach ($canteens as $c) {
            $isEven  = ($no % 2 === 0);
            $bgColor = $isEven ? 'FFF0F4FA' : 'FFFFFFFF';

            $sheet->getCell("A{$currentRow}")->setValue($no);
            $sheet->getCell("B{$currentRow}")->setValue($c['canteen_name']);
            $sheet->getCell("C{$currentRow}")->setValue($c['total_orders']);
            $sheet->getCell("D{$currentRow}")->setValue($c['total_revenue']);

            $sheet->getStyle("D{$currentRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle("A{$currentRow}:D{$currentRow}")->applyFromArray([
                'font'    => ['name' => 'Arial', 'size' => 10],
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $bgColor]],
                'borders' => $this->borderStyle('FFB8C8D8'),
            ]);

            $totalOrders  += $c['total_orders'];
            $totalRevenue += $c['total_revenue'];
            $currentRow++;
            $no++;
        }

        if (empty($canteens)) {
            $sheet->mergeCells("A{$currentRow}:D{$currentRow}");
            $sheet->getCell("A{$currentRow}")->setValue('Tidak ada data transaksi pada periode ini.');
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $currentRow++;
        }

        // ------------------------------------------------------------------
        // BAGIAN 4 — BARIS GRAND TOTAL
        // ------------------------------------------------------------------
        $totalRow = $currentRow + 1;

        $sheet->mergeCells("A{$totalRow}:B{$totalRow}");
        $sheet->getCell("A{$totalRow}")->setValue('GRAND TOTAL');
        $sheet->getCell("C{$totalRow}")->setValue($data['grand_total_orders'] ?? $totalOrders);
        $sheet->getCell("D{$totalRow}")->setValue($data['grand_total_revenue'] ?? $totalRevenue);

        $sheet->getStyle("D{$totalRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle("A{$totalRow}:D{$totalRow}")->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 10,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2D6A4F'],
            ],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => $this->borderStyle('FF1A5C3A'),
        ]);
        $sheet->getRowDimension($totalRow)->setRowHeight(22);

        // ------------------------------------------------------------------
        // BAGIAN 5 — FOOTER
        // ------------------------------------------------------------------
        $footerRow = $totalRow + 2;
        $sheet->mergeCells("A{$footerRow}:D{$footerRow}");
        $sheet->getCell("A{$footerRow}")->setValue(
            'Laporan dicetak pada: ' . \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
        );
        $sheet->getStyle("A{$footerRow}")->getFont()
            ->setItalic(true)->setSize(9)->setName('Arial')->getColor()->setARGB('FF666666');

        // ------------------------------------------------------------------
        // BAGIAN 6 — AUTO-WIDTH KOLOM
        // ------------------------------------------------------------------
        $sheet->getColumnDimension('A')->setAutoSize(false);
        $sheet->getColumnDimension('A')->setWidth(6);
        $this->autoSizeColumns($sheet, ['B', 'C', 'D']);
        $sheet->getColumnDimension('C')->setWidth(24);
        $sheet->getColumnDimension('D')->setWidth(24);

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // =========================================================================
    //  HELPER — Header laporan
    // =========================================================================

    protected function writeReportHeader($sheet, string $labelPeriode): void
    {
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');

        $sheet->getCell('A1')->setValue('LAPORAN TRANSAKSI GLOBAL');
        $sheet->getCell('A2')->setValue('KANT.IN');
        $sheet->getCell('A3')->setValue('Periode: ' . strtoupper($labelPeriode));
        $sheet->getCell('A4')->setValue('');

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

        $sheet->getStyle('A1:D3')->getBorders()->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setARGB('FFCCCCCC');
    }

    // =========================================================================
    //  HELPER — Border style
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
    //  HELPER — Auto-size kolom
    // =========================================================================

    protected function autoSizeColumns($sheet, array $columns): void
    {
        foreach ($columns as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet->calculateColumnWidths();
    }
}