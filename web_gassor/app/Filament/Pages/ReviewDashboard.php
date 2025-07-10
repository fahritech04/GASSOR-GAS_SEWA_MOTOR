<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentReviewsWidget;
use App\Filament\Widgets\ReviewRatingDistributionChart;
use App\Filament\Widgets\ReviewStatsWidget;
use App\Filament\Widgets\TopRatedMotorcyclesWidget;
use Filament\Pages\Page;

class ReviewDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static string $view = 'filament.pages.review-dashboard';

    protected static ?string $navigationLabel = 'Dashboard Review';

    protected static ?string $title = 'Dashboard Review Motor';

    protected static ?string $navigationGroup = 'Manajemen Review';

    protected static ?int $navigationSort = 0;

    protected function getHeaderWidgets(): array
    {
        return [
            ReviewStatsWidget::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            ReviewRatingDistributionChart::class,
            RecentReviewsWidget::class,
            TopRatedMotorcyclesWidget::class,
        ];
    }

    public function getWidgetData(): array
    {
        return [];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 4;
    }
}
