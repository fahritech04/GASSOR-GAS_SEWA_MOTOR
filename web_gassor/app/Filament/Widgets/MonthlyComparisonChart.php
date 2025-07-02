<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Bulanan';
    protected static ?string $description = 'Perbandingan pendapatan dan transaksi 6 bulan terakhir';
    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Transaction::where('transactions.payment_status', 'success')
            ->select(
                DB::raw('YEAR(transactions.created_at) as year'),
                DB::raw('MONTH(transactions.created_at) as month'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_revenue')
            )
            ->where('transactions.created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $transactionCounts = [];
        $revenues = [];

        // Generate last 6 months labels
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            // Find data for this month
            $monthData = $data->where('year', $date->year)
                             ->where('month', $date->month)
                             ->first();

            $transactionCounts[] = $monthData ? $monthData->transaction_count : 0;
            $revenues[] = $monthData ? $monthData->total_revenue / 1000000 : 0; // Convert to millions
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $transactionCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue (Juta Rp)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Transaksi',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue (Juta Rp)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
