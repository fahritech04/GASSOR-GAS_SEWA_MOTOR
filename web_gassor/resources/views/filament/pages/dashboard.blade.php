<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Selamat Datang di Dashboard WebGassor</h1>
            <p class="text-blue-100">Kelola sistem penyewaan motor Anda dengan mudah dan efisien</p>
        </div>

        <!-- Stats Overview -->
        <div class="widgets-overview">
            @livewire(\App\Filament\Widgets\StatsOverview::class)
        </div>

        <!-- Performance Metrics -->
        <div class="widgets-overview">
            @livewire(\App\Filament\Widgets\PerformanceMetrics::class)
        </div>

        <!-- System Overview -->
        <div class="widgets-overview">
            @livewire(\App\Filament\Widgets\SystemOverview::class)
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Chart -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\RevenueChart::class)
            </div>

            <!-- Monthly Comparison Chart -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\MonthlyComparisonChart::class)
            </div>
        </div>

        <!-- Daily Transaction Chart -->
        <div class="widget-container">
            @livewire(\App\Filament\Widgets\DailyTransactionChart::class)
        </div>

        <!-- User Registration Chart -->
        <div class="widget-container">
            @livewire(\App\Filament\Widgets\UserRegistrationChart::class)
        </div>

        <!-- Motor & Rental Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Popular Motorcycles -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\PopularMotorcyclesChart::class)
            </div>

            <!-- Popular Motorbike Rentals -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\PopularMotorbikeRentalsChart::class)
            </div>
        </div>

        <!-- Performance Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Performing Owners -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\TopPerformingOwnersChart::class)
            </div>

            <!-- Category Distribution -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\CategoryDistributionChart::class)
            </div>
        </div>

        <!-- City Distribution -->
        <div class="widget-container">
            @livewire(\App\Filament\Widgets\CityDistributionChart::class)
        </div>

        <!-- Recent Transactions -->
        <div class="widget-container">
            @livewire(\App\Filament\Widgets\RecentTransactionsWidget::class)
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Aksi Cepat</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors cursor-pointer">
                    <div class="text-center">
                        <div class="text-2xl mb-2">💰</div>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Kelola Transaksi</span>
                    </div>
                </div>

                <div class="flex items-center justify-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors cursor-pointer">
                    <div class="text-center">
                        <div class="text-2xl mb-2">👥</div>
                        <span class="text-sm font-medium text-green-700 dark:text-green-300">Kelola User</span>
                    </div>
                </div>

                <div class="flex items-center justify-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors cursor-pointer">
                    <div class="text-center">
                        <div class="text-2xl mb-2">🏪</div>
                        <span class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Kelola Rental</span>
                    </div>
                </div>

                <div class="flex items-center justify-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors cursor-pointer">
                    <div class="text-center">
                        <div class="text-2xl mb-2">🏷️</div>
                        <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Kelola Kategori</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .widget-container {
            @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700;
        }

        .widgets-overview {
            @apply w-full;
        }
    </style>
</x-filament-panels::page>
