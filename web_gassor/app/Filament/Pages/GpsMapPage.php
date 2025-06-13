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
        $this->motorcyclesWithGps = Motorcycle::where('has_gps', true)->get();
    }

    public function getGpsData()
    {
        return $this->gpsData;
    }
}
