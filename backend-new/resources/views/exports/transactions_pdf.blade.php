<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Kantin</title>
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
        <h2>LAPORAN PENJUALAN KANTIN</h2>
        <h3>{{ strtoupper($canteen->name ?? 'KANTIN') }}</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d
            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="20%">ID Pesanan</th>
                <th width="20%">Tanggal & Waktu</th>
                <th width="25%">Nama Pelanggan</th>
                <th width="15%">Metode</th>
                <th width="15%">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse($orders as $index => $order)
                @php $totalAmount += $order->total_amount; @endphp
                <tr class="{{ $index % 2 == 0 ? 'bg-light' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                    <td>{{ $order->customer_snapshot['name'] ?? 'Pelanggan' }}</td>
                    <td class="text-center">{{ strtoupper($order->payment['method'] ?? 'CASH') }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="print-date">
        Laporan dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
    </div>

</body>

</html>