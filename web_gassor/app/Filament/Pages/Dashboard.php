<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard WebGassor';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            // Widgets will be handled in the view
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'sm' => 1,
            'lg' => 2,
            'xl' => 3,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Widgets\RevenueChart::class,
            \App\Filament\Widgets\MonthlyComparisonChart::class,
            \App\Filament\Widgets\DailyTransactionChart::class,
            \App\Filament\Widgets\PopularMotorcyclesChart::class,
            \App\Filament\Widgets\PopularMotorbikeRentalsChart::class,
            \App\Filament\Widgets\UserRegistrationChart::class,
            \App\Filament\Widgets\CategoryDistributionChart::class,
            \App\Filament\Widgets\CityDistributionChart::class,
        ];
    }
}
