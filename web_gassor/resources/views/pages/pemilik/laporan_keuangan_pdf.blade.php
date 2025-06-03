<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            color: #222;
            margin: 0;
            padding: 0;
        }
        .pdf-container {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 32px 0 rgba(0,0,0,0.08);
            padding: 36px 36px 24px 36px;
        }
        .pdf-title {
            font-size: 2rem;
            font-weight: 800;
            color: #e6a43b;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }
        .pdf-meta {
            font-size: 1rem;
            color: #888;
            margin-bottom: 18px;
        }
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .pdf-table th, .pdf-table td {
            border: 1px solid #f1f1f1;
            padding: 10px 8px;
            text-align: center;
        }
        .pdf-table th {
            background: #fff7e6;
            color: #e6a43b;
            font-size: 1rem;
            font-weight: 700;
        }
        .pdf-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .pdf-summary {
            margin-top: 24px;
            padding: 18px;
            background: linear-gradient(90deg, #fff7e6 0%, #ffe0b2 100%);
            border-radius: 12px;
            box-shadow: 0 1px 4px 0 rgba(230,164,59,0.08);
            font-size: 1.1rem;
        }
        .pdf-summary strong {
            color: #e6a43b;
        }
    </style>
</head>
<body>
<div class="pdf-container">
    <div class="pdf-title">Laporan Keuangan</div>
    <div class="pdf-meta">Filter: {{ ucfirst($filter) }} | Tanggal: {{ $tanggal }}</div>
    <table class="pdf-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Motor</th>
                <th>Penyewa</th>
                <th>Harga</th>
                <th>Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $trx)
            <tr>
                <td>{{ $trx['Tanggal'] }}</td>
                <td>{{ $trx['Motor'] }}</td>
                <td>{{ $trx['Penyewa'] }}</td>
                <td>Rp {{ number_format($trx['Harga'], 0, ',', '.') }}</td>
                <td>{{ strtoupper($trx['Status Pembayaran']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pdf-summary">
        <div><strong>Total Pendapatan:</strong> Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</div>
        <div><strong>Total Transaksi:</strong> {{ $summary['total_transactions'] }}</div>
        <div><strong>Pendapatan Rata-rata:</strong> Rp {{ number_format($summary['average_income'], 0, ',', '.') }}</div>
    </div>
</div>
</body>
</html>
