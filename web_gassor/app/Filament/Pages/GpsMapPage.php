<?php

namespace App\Filament\Pages;

use App\Http\Controllers\MapController;
use App\Models\Motorcycle;
use Filament\Pages\Page;

class GpsMapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.gps-map-page';

    public $gpsData = null;
    public $motorcyclesWithGps = [];

    public function mount()
    {
        $controller = new MapController();
        $this->gpsData = $controller->getGps()->getData();
        // Ambil motor yang sedang disewa (is_available = 0) dan punya GPS (has_gps = 1)
        $this->motorcyclesWithGps = Motorcycle::where('is_available', false)->where('has_gps', true)->get();
    }

    public function getGpsData()
    {
        return $this->gpsData;
    }
}
