@extends('layouts.app')

@section('content')
<div id="Content-Container" class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-white overflow-x-hidden" style="box-shadow:0 4px 32px 0 rgba(0,0,0,0.08);">
    <div class="flex flex-col gap-6 p-8">
        <div class="laporan-header mb-4">
            <a href="{{ route('pemilik.dashboard') }}" class="flex items-center justify-center w-12 h-12 overflow-hidden bg-white rounded-full shrink-0">
                <img src="{{ asset('assets/images/icons/arrow-left.svg') }}" class="w-[28px] h-[28px]" alt="icon" />
            </a>
            <h1 class="display-5 fw-bold mb-3 mt-3">Laporan Keuangan</h1>
            <form method="GET" action="" class="laporan-filterbar print:hidden" style="display:flex;gap:12px;align-items:end;width:100%;flex-wrap:nowrap;">
                <div style="min-width:120px;max-width:180px;flex:1;">
                    <label for="filter" class="form-label mb-0">Filter</label>
                    <select name="filter" id="filter" class="form-select">
                        <option value="harian" {{ request('filter') == 'harian' ? 'selected' : '' }}>Per Hari</option>
                        <option value="mingguan" {{ request('filter') == 'mingguan' ? 'selected' : '' }}>Per Minggu</option>
                        <option value="bulanan" {{ request('filter') == 'bulanan' ? 'selected' : '' }}>Per Bulan</option>
                    </select>
                </div>
                <div style="min-width:140px;max-width:200px;flex:1;">
                    <label for="tanggal" class="form-label mb-0">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal', date('Y-m-d')) }}">
                </div>
                <div style="flex-shrink:0;">
                    <button type="submit" class="btn fw-bold text-white" style="white-space:nowrap; background:#000000; border-color:#000000;"><i class="fas fa-filter me-1"></i> Terapkan</button>
                </div>
            </form>
            <div class="laporan-actionbar print:hidden">
                {{-- <a href="{{ route('pemilik.laporan-keuangan.export-excel', request()->all()) }}" class="btn btn-primary fw-bold"><i class="fas fa-file-excel me-1"></i> Export Excel</a> --}}
                <a href="{{ route('pemilik.laporan-keuangan.export-pdf', request()->all()) }}" class="btn fw-bold text-white" style="background:#000000; border-color:#000000;"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
                <button type="button" onclick="window.print()" class="btn fw-bold text-white" style="background:#000000; border-color:#000000;"><i class="fas fa-print me-1"></i> Cetak</button>
            </div>
        </div>
        <div class="laporan-summary row justify-content-center">
            <div class="summary-card col-md-3 mx-2 mb-2">
                <span class="icon"><i class="fas fa-wallet" style="color:#000000;"></i></span>
                <span class="fs-6 fw-semibold">Total Pendapatan</span>
                <span class="fs-4 fw-bold">Rp {{ number_format($summary['total_income'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-card col-md-3 mx-2 mb-2">
                <span class="icon"><i class="fas fa-receipt" style="color:#000000;"></i></span>
                <span class="fs-6 fw-semibold">Total Transaksi</span>
                <span class="fs-4 fw-bold">{{ $summary['total_transactions'] ?? 0 }}</span>
            </div>
            <div class="summary-card col-md-3 mx-2 mb-2">
                <span class="icon"><i class="fas fa-chart-line" style="color:#000000;"></i></span>
                <span class="fs-6 fw-semibold">Pendapatan Rata-rata</span>
                <span class="fs-4 fw-bold">Rp {{ number_format($summary['average_income'] ?? 0, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="bg-white p-4 shadow overflow-auto">
            <h2 class="fw-bold mb-4 fs-5 d-flex align-items-center gap-2"><i class="fas fa-table" style="color:#000000;"></i> Detail Transaksi</h2>
            <table class="table table-bordered laporan-table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Motor</th>
                        <th>Penyewa</th>
                        <th>Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Status Sewa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($trx->start_date)->isoFormat('D MMM YYYY') }}</td>
                            <td>{{ $trx->motorcycle->name ?? '-' }}</td>
                            <td>{{ $trx->name }}</td>
                            <td>Rp {{ number_format($trx->total_amount / 1.11, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $trx->payment_status == 'success' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2 fw-bold">
                                    {{ strtoupper($trx->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $rentalStatus = $trx->rental_status ?? 'pending';
                                    $statusLabel = match($rentalStatus) {
                                        'pending' => 'MENUNGGU',
                                        'on_going' => 'SEDANG BERJALAN',
                                        'finished' => 'SELESAI',
                                        'cancelled' => 'DIBATALKAN',
                                        default => 'TIDAK DIKETAHUI'
                                    };
                                    $statusClass = match($rentalStatus) {
                                        'pending' => 'bg-warning text-dark',
                                        'on_going' => 'bg-primary',
                                        'finished' => 'bg-success',
                                        'cancelled' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2 fw-bold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-gassor-grey">Tidak ada data transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
<style>
    body { background: #f8fafc; }
    #Content-Container { box-shadow: 0 4px 32px 0 rgba(0,0,0,0.08); }
    .laporan-header {
        background: linear-gradient(90deg, #e6a43b 0%, #e6a43b 100%);
        color: #fff;
        padding: 32px 24px 24px 24px;
        margin-bottom: 32px;
        box-shadow: 0 2px 16px 0 rgba(230,164,59,0.10);
    }
    .laporan-header h1 {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -1px;
    }
    .laporan-actionbar a, .laporan-actionbar button {
        transition: transform 0.1s;
    }
    .laporan-actionbar a:hover, .laporan-actionbar button:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 2px 8px 0 rgba(0,0,0,0.10);
    }
    .laporan-summary {
        margin-bottom: 24px;
    }
    .laporan-summary .summary-card {
        background: linear-gradient(120deg, #fff7e6 60%, #ffe0b2 100%);
        box-shadow: 0 1px 4px 0 rgba(230,164,59,0.08);
        padding: 18px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    .laporan-summary .summary-card .icon {
        font-size: 1.7rem;
        margin-bottom: 4px;
    }
    .laporan-table th, .laporan-table td {
        text-align: center;
    }
    .laporan-table th {
        color: #e6a43b;
        letter-spacing: 0.5px;
    }
    .laporan-actionbar { display: flex; gap: 12px; margin-bottom: 18px; }
    .laporan-filterbar { margin-bottom: 18px; }
    @media (max-width: 600px) {
        .laporan-filterbar { gap: 6px !important; }
        .laporan-filterbar > div { min-width: 0 !important; max-width: 100% !important; }
        .laporan-filterbar button { font-size: 0.95rem; padding: 6px 10px; }
    }
    @media print {
        .print:hidden { display: none !important; }
        #Content-Container { box-shadow: none; }
    }
</style>
