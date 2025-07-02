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

        {{-- <!-- Charts Section -->
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
            <!-- Category Distribution -->
            <div class="widget-container">
                @livewire(\App\Filament\Widgets\CategoryDistributionChart::class)
            </div>
        </div>

        <!-- City Distribution -->
        <div class="widget-container">
            @livewire(\App\Filament\Widgets\CityDistributionChart::class)
        </div> --}}
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
