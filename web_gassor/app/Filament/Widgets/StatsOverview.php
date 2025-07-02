<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\City;
use App\Models\Motorcycle;
use App\Models\MotorbikeRental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Transaction::where('transactions.payment_status', 'success')
            ->sum('transactions.total_amount');

        $totalUsers = User::count();
        $pemilikCount = User::where('users.role', 'pemilik')->count();
        $penyewaCount = User::where('users.role', 'penyewa')->count();

        $totalMotorcycles = Motorcycle::count();
        $totalRentals = MotorbikeRental::count();
        $totalCategories = Category::count();
        $totalCities = City::count();

        $completedTransactions = Transaction::where('transactions.payment_status', 'success')->count();
        $pendingTransactions = Transaction::where('transactions.payment_status', 'pending')->count();

        return [
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Total revenue dari semua transaksi')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Users', $totalUsers)
                ->description("Pemilik: {$pemilikCount} | Penyewa: {$penyewaCount}")
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Motor', $totalMotorcycles)
                ->description('Motor yang terdaftar')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),

            Stat::make('Total Rental', $totalRentals)
                ->description('Motorbike rental terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Kategori', $totalCategories)
                ->description('Total kategori motor')
                ->descriptionIcon('heroicon-m-tag')
                ->color('gray'),

            Stat::make('Wilayah', $totalCities)
                ->description('Total kota tersedia')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('gray'),

            Stat::make('Transaksi Sukses', $completedTransactions)
                ->description('Transaksi berhasil')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Transaksi Pending', $pendingTransactions)
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
