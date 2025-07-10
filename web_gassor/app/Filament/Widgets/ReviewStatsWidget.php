<?php

namespace App\Filament\Widgets;

use App\Models\MotorcycleReview;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalReviews = MotorcycleReview::count();
        $averageRating = MotorcycleReview::avg('rating');
        $fiveStarReviews = MotorcycleReview::where('rating', 5)->count();
        $lowRatingReviews = MotorcycleReview::whereIn('rating', [1, 2])->count();

        return [
            Stat::make('Total Reviews', $totalReviews)
                ->description('Total review yang diterima')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('primary'),

            Stat::make('Rating Rata-rata', number_format($averageRating, 1).' ★')
                ->description('Rating rata-rata semua motor')
                ->descriptionIcon('heroicon-m-star')
                ->color($averageRating >= 4 ? 'success' : ($averageRating >= 3 ? 'warning' : 'danger')),

            Stat::make('Review 5 Bintang', $fiveStarReviews)
                ->description($totalReviews > 0 ? number_format(($fiveStarReviews / $totalReviews) * 100, 1).'% dari total' : '0%')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make('Review Rendah (1-2★)', $lowRatingReviews)
                ->description($totalReviews > 0 ? number_format(($lowRatingReviews / $totalReviews) * 100, 1).'% dari total' : '0%')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
