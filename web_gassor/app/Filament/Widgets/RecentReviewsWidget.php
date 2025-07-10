<?php

namespace App\Filament\Widgets;

use App\Models\MotorcycleReview;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReviewsWidget extends BaseWidget
{
    protected static ?string $heading = 'Review Terbaru';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MotorcycleReview::with(['motorcycle', 'user'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('motorcycle.name')
                    ->label('Motor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        '2' => 'warning',
                        '3' => 'info',
                        '4' => 'success',
                        '5' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => $state.' ★')
                    ->sortable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Komentar')
                    ->limit(80)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn (MotorcycleReview $record): string => route('filament.admin.resources.motorcycle-reviews.view', $record)
                    ),
            ])
            ->emptyStateHeading('Belum ada review')
            ->emptyStateDescription('Review akan muncul di sini setelah ada penyewa yang memberikan review.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-ellipsis');
    }
}
