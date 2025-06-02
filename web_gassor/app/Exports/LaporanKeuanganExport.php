<?php
namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKeuanganExport implements FromCollection, WithHeadings
{
    protected $user, $filter, $tanggal;
    protected $summary = [];

    public function __construct($user, $filter, $tanggal)
    {
        $this->user = $user;
        $this->filter = $filter;
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        $date = Carbon::parse($this->tanggal);
        $query = Transaction::whereHas('motorcycle', function($q) {
            $q->where('owner_id', $this->user->id);
        });
        if ($this->filter === 'harian') {
            $query->whereDate('start_date', $date);
        } elseif ($this->filter === 'mingguan') {
            $query->whereBetween('start_date', [
                $date->copy()->startOfWeek(),
                $date->copy()->endOfWeek()
            ]);
        } elseif ($this->filter === 'bulanan') {
            $query->whereMonth('start_date', $date->month)
                  ->whereYear('start_date', $date->year);
        }
        $transactions = $query->with(['motorcycle'])->orderBy('start_date', 'desc')->get();
        $this->summary = [
            'total_income' => $transactions->sum('total_amount'),
            'total_transactions' => $transactions->count(),
            'average_income' => $transactions->count() > 0 ? $transactions->sum('total_amount') / $transactions->count() : 0,
        ];
        return $transactions->map(function($trx) {
            return [
                'Tanggal' => Carbon::parse($trx->start_date)->isoFormat('D MMM YYYY'),
                'Motor' => $trx->motorcycle->name ?? '-',
                'Penyewa' => $trx->name,
                'Harga' => $trx->total_amount,
                'Status Pembayaran' => $trx->payment_status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Motor', 'Penyewa', 'Harga', 'Status Pembayaran'];
    }

    public function summary()
    {
        return $this->summary;
    }
}
