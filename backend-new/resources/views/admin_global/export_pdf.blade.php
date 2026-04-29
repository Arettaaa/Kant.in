<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Global Kant.in</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #374151;
            margin: 0;
            padding: 20px;
        }

        /* Layout menggunakan tabel agar aman di DomPDF */
        table.layout-table {
            width: 100%;
            border: none;
            margin-bottom: 30px;
        }
        table.layout-table td {
            border: none;
            padding: 0;
        }

        /* Header Style */
        .brand-name {
            font-size: 28px;
            font-weight: 900;
            color: #FF6900;
            margin: 0;
            letter-spacing: -1px;
        }
        .brand-subtitle {
            font-size: 11px;
            color: #6B7280;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .report-title {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .report-period {
            font-size: 12px;
            color: #4B5563;
            margin: 0;
            background-color: #FFF3E8;
            color: #FF6900;
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        /* Summary Box */
        .summary-box {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
        }
        .summary-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #6B7280;
            letter-spacing: 1px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 18px;
            font-weight: 900;
            color: #111827;
        }
        .summary-value.highlight {
            color: #FF6900;
        }

        /* Main Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #111827;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 10px;
            text-align: left;
            border: none;
        }
        .data-table th.text-center { text-align: center; }
        .data-table th.text-right { text-align: right; }
        
        .data-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #E5E7EB;
            color: #374151;
            font-size: 12px;
        }
        .data-table .text-center { text-align: center; }
        .data-table .text-right { text-align: right; }
        .data-table .text-bold { font-weight: bold; color: #111827; }

        .data-table tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        /* Footer Total */
        .footer-total td {
            background-color: #FFF3E8;
            color: #FF6900;
            font-size: 14px;
            font-weight: 900;
            border-top: 2px solid #FF6900;
            border-bottom: none;
        }

        /* Footer Document */
        .print-footer {
            margin-top: 50px;
            border-top: 1px dashed #E5E7EB;
            padding-top: 15px;
            font-size: 10px;
            color: #9CA3AF;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT PROFESIONAL --}}
    <table class="layout-table">
        <tr>
            <td style="text-align: left; width: 50%;">
                <h1 class="brand-name">Kant.in</h1>
                <p class="brand-subtitle">Pusat Kendali Global Admin</p>
            </td>
            <td style="text-align: right; width: 50%;">
                <h2 class="report-title">Laporan Transaksi</h2>
                <p class="report-period">Periode: {{ strtoupper($labelPeriode) }}</p>
            </td>
        </tr>
    </table>

    {{-- RINGKASAN ATAS --}}
    <table class="layout-table summary-box">
        <tr>
            <td style="text-align: center; width: 50%; border-right: 1px solid #E5E7EB;">
                <div class="summary-title">Total Pesanan Selesai</div>
                <div class="summary-value">{{ number_format($data['grand_total_orders'] ?? 0, 0, ',', '.') }} Transaksi</div>
            </td>
            <td style="text-align: center; width: 50%;">
                <div class="summary-title">Total Pendapatan Bersih</div>
                <div class="summary-value highlight">Rp {{ number_format($data['grand_total_revenue'] ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA UTAMA --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No.</th>
                <th width="45%">Mitra Kantin</th>
                <th width="20%" class="text-center">Total Pesanan</th>
                <th width="30%" class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($canteens as $index => $c)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-bold">{{ $c['canteen_name'] }}</td>
                    <td class="text-center">{{ number_format($c['total_orders'], 0, ',', '.') }}</td>
                    <td class="text-right text-bold">Rp {{ number_format($c['total_revenue'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: #9CA3AF;">Tidak ada data transaksi yang ditemukan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="2" class="text-right">GRAND TOTAL :</td>
                <td class="text-center">{{ number_format($data['grand_total_orders'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data['grand_total_revenue'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- CAP WAKTU --}}
    <div class="print-footer">
        Dokumen ini digenerate otomatis oleh Sistem Kant.in pada {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d F Y, H:i') }} WIB.
    </div>

</body>
</html>