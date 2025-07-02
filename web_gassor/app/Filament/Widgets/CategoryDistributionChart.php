<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Motorcycle;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CategoryDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Kategori Motor';
    protected static ?string $description = 'Jumlah motor per kategori';
    protected static string $color = 'danger';

    protected function getData(): array
    {
        $data = Category::select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM motorcycles
                         INNER JOIN motorbike_rentals ON motorbike_rentals.id = motorcycles.motorbike_rental_id
                         WHERE motorbike_rentals.category_id = categories.id
                         AND motorcycles.deleted_at IS NULL
                         AND motorbike_rentals.deleted_at IS NULL) as motorcycles_count')
            ->having('motorcycles_count', '>', 0)
            ->orderBy('motorcycles_count', 'desc')
            ->get();

        $labels = [];
        $counts = [];
        $colors = [
            '#EF4444', '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6',
            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
        ];

        foreach ($data as $category) {
            $labels[] = $category->name;
            $counts[] = $category->motorcycles_count;
        }

        // If no data, show message
        if (empty($labels)) {
            $labels = ['Tidak ada data'];
            $counts = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Motor',
                    'data' => $counts,
                    'backgroundColor' => array_slice($colors, 0, count($counts)),
                    'borderColor' => array_slice($colors, 0, count($counts)),
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": " + context.parsed + " motor"; }',
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
