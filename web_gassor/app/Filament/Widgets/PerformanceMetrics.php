<?php

namespace App\Filament\Widgets;

use App\Models\MotorbikeRental;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PerformanceMetrics extends BaseWidget
{
    protected function getStats(): array
    {
        $todayRevenue = Transaction::where('transactions.payment_status', 'success')
            ->whereDate('transactions.created_at', today())
            ->sum('transactions.total_amount');

        $todayTransactions = Transaction::where('transactions.payment_status', 'success')
            ->whereDate('transactions.created_at', today())
            ->count();

        $monthRevenue = Transaction::where('transactions.payment_status', 'success')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->sum('transactions.total_amount');

        $monthTransactions = Transaction::where('transactions.payment_status', 'success')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->count();

        $avgTransactionValue = Transaction::where('transactions.payment_status', 'success')
            ->avg('transactions.total_amount');

        $popularRental = Transaction::where('transactions.payment_status', 'success')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->select('transactions.motorbike_rental_id', DB::raw('COUNT(*) as count'))
            ->groupBy('transactions.motorbike_rental_id')
            ->orderBy('count', 'desc')
            ->with('motorbikeRental')
            ->first();

        $lastMonthRevenue = Transaction::where('transactions.payment_status', 'success')
            ->whereMonth('transactions.created_at', now()->subMonth()->month)
            ->whereYear('transactions.created_at', now()->subMonth()->year)
            ->sum('transactions.total_amount');

        $growthRate = $lastMonthRevenue > 0
            ? (($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        $activeRentals = MotorbikeRental::whereHas('motorcycles', function ($query) {
            $query->where('motorcycles.available_stock', '>', 0);
        })->count();

        return [
            Stat::make('Hari Ini', 'Rp '.number_format($todayRevenue, 0, ',', '.'))
                ->description($todayTransactions.' transaksi hari ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make('Bulan Ini', 'Rp '.number_format($monthRevenue, 0, ',', '.'))
                ->description($monthTransactions.' transaksi bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Rata-rata Transaksi', 'Rp '.number_format($avgTransactionValue ?: 0, 0, ',', '.'))
                ->description('Nilai rata-rata per transaksi')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'),

            Stat::make('Pertumbuhan', number_format($growthRate, 1).'%')
                ->description('Dibanding bulan lalu')
                ->descriptionIcon($growthRate >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($growthRate >= 0 ? 'success' : 'danger'),

            Stat::make('Rental Aktif', $activeRentals)
                ->description('Rental dengan motor tersedia')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Rental Terpopuler', $popularRental?->motorbikeRental?->name ?? 'Belum ada data')
                ->description(($popularRental?->count ?? 0).' transaksi bulan ini')
                ->descriptionIcon('heroicon-m-star')
                ->color('yellow'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
