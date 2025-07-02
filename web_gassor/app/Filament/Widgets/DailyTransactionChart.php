<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyTransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi Harian';

    protected static ?string $description = 'Tren transaksi dalam 30 hari terakhir';

    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Transaction::where('transactions.payment_status', 'success')
            ->select(
                DB::raw('DATE(transactions.created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(transactions.total_amount) as revenue')
            )
            ->where('transactions.created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $counts = [];
        $revenues = [];

        // Generate last 30 days labels
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d/m');

            // Find data for this date
            $dayData = $data->where('date', $date->format('Y-m-d'))->first();

            $counts[] = $dayData ? $dayData->count : 0;
            $revenues[] = $dayData ? $dayData->revenue : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $counts,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Transaksi',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Tanggal',
                    ],
                ],
            ],
        ];
    }
}
