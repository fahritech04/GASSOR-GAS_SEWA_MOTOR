<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopPerformingOwnersChart extends ChartWidget
{
    protected static ?string $heading = 'Top Pemilik Rental';

    protected static ?string $description = 'Pemilik dengan pendapatan tertinggi bulan ini';

    protected static string $color = 'indigo';

    protected function getData(): array
    {
        // Get top performing owners based on revenue this month
        $data = Transaction::where('transactions.payment_status', 'success')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->join('motorbike_rentals', 'transactions.motorbike_rental_id', '=', 'motorbike_rentals.id')
            ->join('motorcycles', 'transactions.motorcycle_id', '=', 'motorcycles.id')
            ->join('users', 'motorcycles.owner_id', '=', 'users.id')
            ->select(
                'users.name as owner_name',
                DB::raw('SUM(transactions.total_amount) as total_revenue'),
                DB::raw('COUNT(transactions.id) as transaction_count')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $revenues = [];
        $colors = [
            '#6366F1', '#8B5CF6', '#EC4899', '#EF4444', '#F59E0B',
            '#10B981', '#06B6D4', '#84CC16', '#F97316', '#3B82F6',
        ];

        foreach ($data as $item) {
            $labels[] = $item->owner_name;
            $revenues[] = $item->total_revenue;
        }

        // If no data, show message
        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $revenues = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $revenues,
                    'backgroundColor' => array_slice($colors, 0, count($revenues)),
                    'borderColor' => array_slice($colors, 0, count($revenues)),
                    'borderWidth' => 1,
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
            'indexAxis' => 'y', // Horizontal bar chart
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return "Rp " + context.parsed.x.toLocaleString("id-ID"); }',
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Pendapatan (Rp)',
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Pemilik',
                    ],
                ],
            ],
        ];
    }
}
