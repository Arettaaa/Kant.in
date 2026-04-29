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
use PhpOffice\PhpSpreadsheet\Style\Color;

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
            if ($periode == 'hari') $labelPeriode = 'Hari Ini';
            if ($periode == 'minggu') $labelPeriode = 'Minggu Ini';
            if ($periode == 'semua') $labelPeriode = 'Semua Periode';

            return view('admin_global.transaksi', [
                'grandTotalRevenue' => $data['grand_total_revenue'] ?? 0,
                'grandTotalOrders'  => $data['grand_total_orders'] ?? 0,
                'canteens'          => $data['canteens'] ?? [],
                'labelPeriode'      => $labelPeriode
            ]);
        }

        return redirect()->route('admin.global.dasbor')->withErrors('Gagal memuat data transaksi dari API.');
    }

    /**
     * ✅ FUNGSI EXPORT MENGGUNAKAN RAW PHPSPREADSHEET & DOMPDF
     */
   public function export(Request $request)
    {
        $token = Session::get('api_token');
        $periode = $request->query('periode', 'bulan');
        $format = $request->query('format', 'pdf');

        $response = Http::timeout(15)->withToken($token)->get($this->apiUrl('/transactions'), ['periode' => $periode]);

        if (!$response->successful()) {
            return back()->withErrors('Gagal mengambil data dari server untuk laporan.');
        }

        $data = $response->json('data');
        $canteens = $data['canteens'] ?? [];
        $timestamp = now()->format('Ymd_His');

        $namaPeriode = 'Bulan_Ini';
        $labelPeriode = 'Bulan Ini';
        if ($periode == 'hari') { $namaPeriode = 'Hari_Ini'; $labelPeriode = 'Hari Ini'; }
        if ($periode == 'minggu') { $namaPeriode = 'Minggu_Ini'; $labelPeriode = 'Minggu Ini'; }
        if ($periode == 'semua') { $namaPeriode = 'Semua_Periode'; $labelPeriode = 'Semua Periode'; }

        $filename = "Laporan_Transaksi_Global_{$namaPeriode}_{$timestamp}";

        // =======================================================
        // ✅ 2. EKSPOR EXCEL FULL STYLE (PhpSpreadsheet)
        // =======================================================
        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Laporan Transaksi');

            // --- A. KOP LAPORAN ---
            $sheet->mergeCells('A1:D1');
            $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI GLOBAL KANT.IN');
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setARGB('FFFF6900'); // Warna Oren
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'Periode: ' . strtoupper($labelPeriode));
            $sheet->getStyle('A2')->getFont()->setBold(true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // --- B. HEADER TABEL ---
            $sheet->setCellValue('A4', 'No.');
            $sheet->setCellValue('B4', 'Nama Mitra Kantin');
            $sheet->setCellValue('C4', 'Total Pesanan Selesai');
            $sheet->setCellValue('D4', 'Total Pendapatan');

            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF111827']], // Hitam Pekat
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ];
            $sheet->getStyle('A4:D4')->applyFromArray($headerStyle);
            $sheet->getRowDimension(4)->setRowHeight(25); // Bikin header agak tinggi

            // --- C. ISI DATA KANTIN ---
            $row = 5;
            $no = 1;
            foreach ($canteens as $c) {
                $sheet->setCellValue('A' . $row, $no);
                $sheet->setCellValue('B' . $row, $c['canteen_name']);
                $sheet->setCellValue('C' . $row, $c['total_orders']);
                $sheet->setCellValue('D' . $row, $c['total_revenue']);

                // Alignment & Format Angka (Format Rupiah Excel asli)
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');

                // Bikin Zebra Striping (Baris selang-seling warna abu-abu muda)
                if ($row % 2 == 0) {
                    $sheet->getStyle("A{$row}:D{$row}")->getFill()
                          ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF9FAFB');
                }
                $row++;
                $no++;
            }

            // --- D. BARIS GRAND TOTAL ---
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->setCellValue("A{$row}", 'GRAND TOTAL');
            $sheet->setCellValue("C{$row}", $data['grand_total_orders'] ?? 0);
            $sheet->setCellValue("D{$row}", $data['grand_total_revenue'] ?? 0);

            $totalStyle = [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFF6900']], // Teks Oren
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFF3E8']], // Background Oren Muda
                'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFFF6900']]],
            ];
            $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($totalStyle);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('"Rp" #,##0');

            // --- E. AUTO-SIZE KOLOM ---
            foreach (range('A', 'D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Stream Download file ke browser
            return response()->streamDownload(function() use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }, $filename . ".xlsx", [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }

        // =======================================================
        // 3. EKSPOR PDF (DomPDF)
        // =======================================================
        $pdf = Pdf::loadView('admin_global.export_pdf', [
            'canteens' => $canteens,
            'labelPeriode' => $labelPeriode,
            'data' => $data
        ]);

        return $pdf->download($filename . ".pdf");
    }
}