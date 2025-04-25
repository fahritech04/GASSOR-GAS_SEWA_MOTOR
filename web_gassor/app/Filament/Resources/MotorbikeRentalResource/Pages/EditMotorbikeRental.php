<?php

namespace App\Filament\Resources\MotorbikeRentalResource\Pages;

use App\Filament\Resources\MotorbikeRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMotorbikeRental extends EditRecord
{
    protected static string $resource = MotorbikeRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
