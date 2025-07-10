<?php

namespace App\Filament\Resources\MotorcycleReviewResource\Pages;

use App\Filament\Resources\MotorcycleReviewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMotorcycleReview extends EditRecord
{
    protected static string $resource = MotorcycleReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
