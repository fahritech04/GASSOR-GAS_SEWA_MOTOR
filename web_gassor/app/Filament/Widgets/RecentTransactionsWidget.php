<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentTransactionsWidget extends BaseWidget
{
    protected static ?string $heading = 'Transaksi Terbaru';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()
                    ->with(['motorcycle', 'motorbikeRental'])
                    ->latest('transactions.created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Penyewa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('motorcycle.name')
                    ->label('Motor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('motorbikeRental.name')
                    ->label('Rental')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->colors([
                        'success' => 'success',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'success' => 'Berhasil',
                        'pending' => 'Pending',
                        'failed' => 'Gagal',
                        default => ucfirst($state),
                    }),

                Tables\Columns\BadgeColumn::make('rental_status')
                    ->label('Status Rental')
                    ->colors([
                        'primary' => 'active',
                        'success' => 'completed',
                        'warning' => 'pending',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'pending' => 'Pending',
                        'cancelled' => 'Dibatalkan',
                        default => $state ? ucfirst($state) : 'N/A',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Transaction $record): string => '#')
                    ->openUrlInNewTab(false),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
