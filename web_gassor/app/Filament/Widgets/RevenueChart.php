<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan';

    protected static ?string $description = 'Pendapatan bulanan dari transaksi';

    protected static string $color = 'success';

    protected function getData(): array
    {
        // Get revenue data for the last 12 months
        $data = Transaction::where('transactions.payment_status', 'success')
            ->select(
                DB::raw('YEAR(transactions.created_at) as year'),
                DB::raw('MONTH(transactions.created_at) as month'),
                DB::raw('SUM(transactions.total_amount) as total')
            )
            ->where('transactions.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $revenues = [];

        // Generate last 12 months labels
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            // Find revenue for this month
            $monthRevenue = $data->where('year', $date->year)
                ->where('month', $date->month)
                ->first();

            $revenues[] = $monthRevenue ? $monthRevenue->total : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                    'fill' => true,
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
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
        ];
    }
}
