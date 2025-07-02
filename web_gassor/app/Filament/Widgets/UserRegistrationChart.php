<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserRegistrationChart extends ChartWidget
{
    protected static ?string $heading = 'Registrasi User';
    protected static ?string $description = 'Grafik registrasi pemilik dan penyewa per bulan';
    protected static string $color = 'primary';

    protected function getData(): array
    {
        // Get user registration data for the last 12 months
        $pemilikData = User::where('users.role', 'pemilik')
            ->select(
                DB::raw('YEAR(users.created_at) as year'),
                DB::raw('MONTH(users.created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('users.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $penyewaData = User::where('users.role', 'penyewa')
            ->select(
                DB::raw('YEAR(users.created_at) as year'),
                DB::raw('MONTH(users.created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('users.created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $labels = [];
        $pemilikCounts = [];
        $penyewaCounts = [];

        // Generate last 12 months labels
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');

            // Find counts for this month
            $pemilikCount = $pemilikData->where('year', $date->year)
                                      ->where('month', $date->month)
                                      ->first();

            $penyewaCount = $penyewaData->where('year', $date->year)
                                       ->where('month', $date->month)
                                       ->first();

            $pemilikCounts[] = $pemilikCount ? $pemilikCount->count : 0;
            $penyewaCounts[] = $penyewaCount ? $penyewaCount->count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemilik',
                    'data' => $pemilikCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.8)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Penyewa',
                    'data' => $penyewaCounts,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.8)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2,
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
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Registrasi',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan',
                    ],
                ],
            ],
        ];
    }
}
