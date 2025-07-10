<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminRevenueStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Transaction::where('payment_status', 'success')->sum('total_amount');
        $revenueWithoutVAT = $totalRevenue / 1.11;
        $vatOnly = $totalRevenue - $revenueWithoutVAT;

        return [
            Stat::make('Total Pendapatan (Tanpa PPN 11%)', 'Rp '.number_format($revenueWithoutVAT, 0, ',', '.'))
                ->description('Total pendapatan tanpa PPN 11%')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('primary'),

            Stat::make('Pendapatan PPN 11% (Keuntungan Admin)', 'Rp '.number_format($vatOnly, 0, ',', '.'))
                ->description('Total PPN 11% dari transaksi sukses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 2;
    }
}
