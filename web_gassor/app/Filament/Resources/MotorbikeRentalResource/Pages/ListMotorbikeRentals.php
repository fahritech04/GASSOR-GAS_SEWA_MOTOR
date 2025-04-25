<?php

namespace App\Filament\Resources\MotorbikeRentalResource\Pages;

use App\Filament\Resources\MotorbikeRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMotorbikeRentals extends ListRecords
{
    protected static string $resource = MotorbikeRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
