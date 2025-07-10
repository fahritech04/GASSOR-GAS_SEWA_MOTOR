<?php

namespace App\Filament\Widgets;

use App\Models\MotorcycleReview;
use Filament\Widgets\ChartWidget;

class ReviewRatingDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Rating Review';

    protected static ?string $description = 'Distribusi rating dari semua review motor';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $ratingCounts = [];

        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = MotorcycleReview::where('rating', $i)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Review',
                    'data' => array_values($ratingCounts),
                    'backgroundColor' => [
                        '#ef4444', // 1 star - red
                        '#f97316', // 2 stars - orange
                        '#eab308', // 3 stars - yellow
                        '#22c55e', // 4 stars - green
                        '#16a34a', // 5 stars - dark green
                    ],
                    'borderColor' => [
                        '#dc2626',
                        '#ea580c',
                        '#ca8a04',
                        '#16a34a',
                        '#15803d',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['1 ★', '2 ★', '3 ★', '4 ★', '5 ★'],
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
