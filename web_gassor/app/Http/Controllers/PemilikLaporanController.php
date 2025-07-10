<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikLaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', Carbon::now()->toDateString());
        $date = Carbon::parse($tanggal);

        // hanya success dan finished
        $query = Transaction::where('payment_status', 'success')
            ->where('rental_status', 'finished') // rental_status per transaction
            ->whereHas('motorcycle', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            });

        // Filter berdasarkan pilihan
        if ($filter === 'harian') {
            $query->whereDate('start_date', $date);
        } elseif ($filter === 'mingguan') {
            $query->whereBetween('start_date', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek(),
            ]);
        } elseif ($filter === 'bulanan') {
            $query->whereMonth('start_date', $date->month)
                ->whereYear('start_date', $date->year);
        }

        $transactions = $query->with(['motorcycle'])->orderBy('start_date', 'desc')->get();

        // PPN tidak dihitung untuk pemilik
        $total_income_no_ppn = $transactions->sum(function ($trx) {
            return $trx->total_amount / 1.11;
        });
        $total_transactions = $transactions->count();
        $average_income = $total_transactions > 0 ? $total_income_no_ppn / $total_transactions : 0;

        // Grafik: group by tanggal
        $chartLabels = [];
        $chartData = [];
        if ($filter === 'harian') {
            $chartLabels[] = $date->isoFormat('D MMM YYYY');
            $chartData[] = $total_income_no_ppn;
        } elseif ($filter === 'mingguan') {
            for ($d = $date->copy()->startOfWeek(); $d <= $date->copy()->endOfWeek(); $d->addDay()) {
                $label = $d->isoFormat('ddd, D');
                $chartLabels[] = $label;
                $chartData[] = $transactions->filter(function ($trx) use ($d) {
                    return Carbon::parse($trx->start_date)->isSameDay($d);
                })->sum(function ($trx) {
                    return $trx->total_amount / 1.11;
                });
            }
        } elseif ($filter === 'bulanan') {
            $daysInMonth = $date->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $d = $date->copy()->day($i);
                $label = $d->isoFormat('D');
                $chartLabels[] = $label;
                $chartData[] = $transactions->filter(function ($trx) use ($d) {
                    return Carbon::parse($trx->start_date)->isSameDay($d);
                })->sum(function ($trx) {
                    return $trx->total_amount / 1.11;
                });
            }
        }

        $summary = [
            'total_income' => $total_income_no_ppn,
            'total_transactions' => $total_transactions,
            'average_income' => $average_income,
        ];

        return view('pages.pemilik.laporan_keuangan', [
            'transactions' => $transactions,
            'summary' => $summary,
            'chartData' => [
                'labels' => $chartLabels,
                'data' => $chartData,
            ],
        ]);
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $filename = 'laporan_keuangan_'.$filter.'_'.$tanggal.'.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanKeuanganExport($user, $filter, $tanggal), $filename);
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'harian');
        $tanggal = $request->get('tanggal', now()->toDateString());
        $export = new \App\Exports\LaporanKeuanganExport($user, $filter, $tanggal);
        $data = $export->collection();
        $summary = $export->summary();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pages.pemilik.laporan_keuangan_pdf', compact('data', 'summary', 'filter', 'tanggal'));
        $filename = 'laporan_keuangan_'.$filter.'_'.$tanggal.'.pdf';

        return $pdf->download($filename);
    }
}
