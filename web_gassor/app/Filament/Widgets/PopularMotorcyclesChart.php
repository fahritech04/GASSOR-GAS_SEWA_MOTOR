<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PopularMotorcyclesChart extends ChartWidget
{
    protected static ?string $heading = 'Motor Paling Populer';

    protected static ?string $description = 'Top 10 motor yang paling banyak disewa';

    protected static string $color = 'info';

    protected function getData(): array
    {
        $data = Transaction::where('transactions.payment_status', 'success')
            ->select('transactions.motorcycle_id', DB::raw('COUNT(*) as rental_count'))
            ->with('motorcycle')
            ->groupBy('transactions.motorcycle_id')
            ->orderBy('rental_count', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $counts = [];
        $colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1',
        ];

        foreach ($data as $item) {
            $motorcycleName = $item->motorcycle ? $item->motorcycle->name : 'Motor #'.$item->motorcycle_id;
            $labels[] = $motorcycleName;
            $counts[] = $item->rental_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penyewaan',
                    'data' => $counts,
                    'backgroundColor' => array_slice($colors, 0, count($counts)),
                    'borderColor' => array_slice($colors, 0, count($counts)),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
