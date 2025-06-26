<?php

namespace App\Filament\Pages;

use App\Http\Controllers\MapController;
use App\Models\Motorcycle;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class GpsMapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static string $view = 'filament.pages.gps-map-page';

    protected static ?string $navigationGroup = 'Maps GPS';

    public $gpsData = null;

    public $motorcyclesWithGps = [];

    public function mount()
    {
        $controller = new MapController;
        $this->gpsData = $controller->getGps()->getData();
        // Ambil motor yang sedang disewa (stok tersedia lebih kecil dari total stok)
        $this->motorcyclesWithGps = Motorcycle::where('available_stock', '<', DB::raw('stock'))
            ->where('has_gps', true)
            ->get();
    }

    public function getGpsData()
    {
        return $this->gpsData;
    }
}
