<?php

namespace App\Filament\Widgets;

/**
 * Widget Registry untuk Dashboard WebGassor
 *
 * File ini mendaftarkan semua widget yang tersedia untuk dashboard
 */
class WidgetRegistry
{
    /**
     * Daftar semua widget statistik
     */
    public static function getStatsWidgets(): array
    {
        return [
            StatsOverview::class,
            PerformanceMetrics::class,
            SystemOverview::class,
        ];
    }

    /**
     * Daftar semua widget chart
     */
    public static function getChartWidgets(): array
    {
        return [
            RevenueChart::class,
            MonthlyComparisonChart::class,
            DailyTransactionChart::class,
            UserRegistrationChart::class,
            PopularMotorcyclesChart::class,
            PopularMotorbikeRentalsChart::class,
            TopPerformingOwnersChart::class,
            CategoryDistributionChart::class,
            CityDistributionChart::class,
        ];
    }

    /**
     * Daftar semua widget table
     */
    public static function getTableWidgets(): array
    {
        return [
            RecentTransactionsWidget::class,
        ];
    }

    /**
     * Mendapatkan semua widget
     */
    public static function getAllWidgets(): array
    {
        return array_merge(
            self::getStatsWidgets(),
            self::getChartWidgets(),
            self::getTableWidgets()
        );
    }
}
