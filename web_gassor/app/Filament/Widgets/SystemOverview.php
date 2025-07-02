<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Motorcycle;
use App\Models\MotorbikeRental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class SystemOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // System status metrics
        $lowStockMotorcycles = Motorcycle::where('motorcycles.available_stock', '<=', 2)
            ->where('motorcycles.available_stock', '>', 0)
            ->count();

        $outOfStockMotorcycles = Motorcycle::where('motorcycles.available_stock', 0)->count();

        $activeRentals = Transaction::where('transactions.rental_status', 'active')->count();

        $pendingPayments = Transaction::where('transactions.payment_status', 'pending')->count();

        // Recent registrations (last 7 days)
        $recentUsers = User::where('users.created_at', '>=', Carbon::now()->subDays(7))->count();

        $recentRentals = MotorbikeRental::where('motorbike_rentals.created_at', '>=', Carbon::now()->subDays(7))->count();

        return [
            Stat::make('Stock Menipis', $lowStockMotorcycles)
                ->description('Motor dengan stock ≤ 2')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),

            Stat::make('Stock Habis', $outOfStockMotorcycles)
                ->description('Motor tidak tersedia')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Sewa Aktif', $activeRentals)
                ->description('Sedang dalam masa sewa')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            Stat::make('Pembayaran Pending', $pendingPayments)
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('User Baru (7 hari)', $recentUsers)
                ->description('Registrasi minggu ini')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),

            Stat::make('Rental Baru (7 hari)', $recentRentals)
                ->description('Rental terdaftar minggu ini')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}
