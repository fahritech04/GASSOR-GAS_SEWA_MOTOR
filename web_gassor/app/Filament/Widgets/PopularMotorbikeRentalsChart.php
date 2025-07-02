<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\MotorbikeRental;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularMotorbikeRentalsChart extends ChartWidget
{
    protected static ?string $heading = 'Rental Paling Populer';
    protected static ?string $description = 'Top 10 motorbike rental dengan transaksi terbanyak';
    protected static string $color = 'warning';

    protected function getData(): array
    {
        $data = Transaction::where('transactions.payment_status', 'success')
            ->select('transactions.motorbike_rental_id', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(transactions.total_amount) as total_revenue'))
            ->with('motorbikeRental')
            ->groupBy('transactions.motorbike_rental_id')
            ->orderBy('transaction_count', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $transactionCounts = [];
        $revenues = [];

        foreach ($data as $item) {
            $rentalName = $item->motorbikeRental ? $item->motorbikeRental->name : 'Rental #' . $item->motorbike_rental_id;
            $labels[] = $rentalName;
            $transactionCounts[] = $item->transaction_count;
            $revenues[] = $item->total_revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $transactionCounts,
                    'backgroundColor' => 'rgba(251, 146, 60, 0.8)',
                    'borderColor' => 'rgb(251, 146, 60)',
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Total Revenue (Rp)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                    'type' => 'line',
                    'yAxisID' => 'y1',
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
                        'text' => 'Revenue (Rp)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                    ],
                ],
            ],
        ];
    }
}
