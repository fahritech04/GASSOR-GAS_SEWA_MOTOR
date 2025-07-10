<?php

namespace App\Filament\Resources\MotorbikeRentalResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class MotorcyclesRelationManager extends RelationManager
{
    protected static string $relationship = 'motorcycles';
    protected static ?string $title = 'Daftar Motor';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Motor'),
                Tables\Columns\TextColumn::make('vehicle_number_plate')->label('No Polisi'),
                Tables\Columns\TextColumn::make('category.name')->label('Kategori'),
                Tables\Columns\TextColumn::make('physicalCheck.checklist')
                    ->label('Checklist Fisik')
                    ->formatStateUsing(fn($state) => $state ? implode(', ', json_decode($state, true)) : '-')
                    ->limit(40),
                Tables\Columns\IconColumn::make('physicalCheck.video_path')
                    ->label('Video Fisik')
                    ->boolean()
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-x-mark'),
                Tables\Columns\TextColumn::make('physicalCheck.video_path')
                    ->label('Preview Video')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            $url = asset('storage/' . $state);
                            return '<video width="200" controls style="max-height:120px;max-width:100%"><source src="' . $url . '" type="video/mp4">Video tidak didukung</video>';
                        }
                        return '-';
                    })
                    ->html(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
