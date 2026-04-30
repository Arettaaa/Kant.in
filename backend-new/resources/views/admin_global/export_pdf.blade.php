<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Global Kant.in</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1E3A5F;
            padding-bottom: 10px;
        }

        .header h2,
        .header h3 {
            margin: 5px 0;
            color: #1E3A5F;
        }

        .header p {
            margin: 5px 0;
            color: #555;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px;
        }

        th {
            background-color: #1E3A5F;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        .bg-light {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer-total {
            background-color: #2D6A4F;
            color: #ffffff;
            font-weight: bold;
        }

        .print-date {
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN TRANSAKSI GLOBAL</h2>
        <h3>KANT.IN</h3>
        <p>Periode: {{ strtoupper($labelPeriode) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="55%">Nama Mitra Kantin</th>
                <th width="20%">Total Pesanan Selesai</th>
                <th width="20%">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($canteens as $index => $c)
                <tr class="{{ $index % 2 == 0 ? 'bg-light' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $c['canteen_name'] }}</td>
                    <td class="text-center">{{ number_format($c['total_orders'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($c['total_revenue'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="2" class="text-right">GRAND TOTAL:</td>
                <td class="text-center">{{ number_format($data['grand_total_orders'] ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data['grand_total_revenue'] ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="print-date">
        Laporan dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
    </div>

</body>

</html>