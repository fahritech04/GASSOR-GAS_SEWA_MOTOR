<?php

namespace App\Filament\Widgets;

use App\Models\Motorcycle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopRatedMotorcyclesWidget extends BaseWidget
{
    protected static ?string $heading = 'Motor dengan Rating Tertinggi';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Motorcycle::query()
                    ->with(['category', 'motorbikeRental.city'])
                    ->withAvg('reviews as average_rating', 'rating')
                    ->withCount('reviews')
                    ->having('reviews_count', '>', 0)
                    ->orderByDesc('average_rating')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Motor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating Rata-rata')
                    ->badge()
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 4.5 => 'success',
                        $state >= 4.0 => 'success',
                        $state >= 3.5 => 'warning',
                        $state >= 3.0 => 'info',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (?float $state): string => $state ? number_format($state, 1).' ★' : 'Belum ada rating'
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Jumlah Review')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_day')
                    ->label('Harga/Hari')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('motorbikeRental.city.name')
                    ->label('Kota')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_reviews')
                    ->label('Lihat Review')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->url(fn (Motorcycle $record): string => route('filament.admin.resources.motorcycle-reviews.index').'?tableFilters[motorcycle][value]='.$record->id
                    ),
            ])
            ->emptyStateHeading('Belum ada motor dengan review')
            ->emptyStateDescription('Motor dengan review akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-star');
    }
}
