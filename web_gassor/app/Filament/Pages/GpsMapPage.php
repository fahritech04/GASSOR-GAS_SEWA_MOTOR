<?php

namespace App\Filament\Pages;

use App\Http\Controllers\MapController;
use Filament\Pages\Page;

class GpsMapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.gps-map-page';

    public $gpsData = null;

    public function mount()
    {
        $controller = new MapController();
        $this->gpsData = $controller->getGps()->getData();
    }

    public function getGpsData()
    {
        return $this->gpsData;
    }
}
