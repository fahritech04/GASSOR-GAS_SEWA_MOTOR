<?php

namespace App\Filament\Widgets;

use App\Models\City;
use Filament\Widgets\ChartWidget;

class CityDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Wilayah';

    protected static ?string $description = 'Jumlah rental per kota';

    protected static string $color = 'gray';

    protected function getData(): array
    {
        $data = City::select('cities.*')
            ->selectRaw('(SELECT COUNT(*) FROM motorbike_rentals
                         WHERE motorbike_rentals.city_id = cities.id
                         AND motorbike_rentals.deleted_at IS NULL) as motorbike_rentals_count')
            ->having('motorbike_rentals_count', '>', 0)
            ->orderBy('motorbike_rentals_count', 'desc')
            ->get();

        $labels = [];
        $counts = [];

        foreach ($data as $city) {
            $labels[] = $city->name;
            $counts[] = $city->motorbike_rentals_count;
        }

        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $counts = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Rental',
                    'data' => $counts,
                    'backgroundColor' => [
                        'rgba(75, 85, 99, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                        'rgba(156, 163, 175, 0.8)',
                        'rgba(209, 213, 219, 0.8)',
                        'rgba(243, 244, 246, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(75, 85, 99)',
                        'rgb(107, 114, 128)',
                        'rgb(156, 163, 175)',
                        'rgb(209, 213, 219)',
                        'rgb(243, 244, 246)',
                    ],
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
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": " + context.parsed.y + " rental"; }',
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Rental',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Kota',
                    ],
                    'ticks' => [
                        'maxRotation' => 45,
                    ],
                ],
            ],
        ];
    }
}
