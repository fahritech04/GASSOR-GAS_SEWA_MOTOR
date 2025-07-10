<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotorcycleReviewResource\Pages;
use App\Models\MotorcycleReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MotorcycleReviewResource extends Resource
{
    protected static ?string $model = MotorcycleReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Reviews Motor';

    protected static ?string $modelLabel = 'Review Motor';

    protected static ?string $pluralModelLabel = 'Reviews Motor';

    protected static ?string $navigationGroup = 'Manajemen Review';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('motorcycle_id')
                    ->relationship('motorcycle', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ])
                    ->required()
                    ->native(false),

                Forms\Components\Textarea::make('comment')
                    ->label('Komentar')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('motorcycle.name')
                    ->label('Motor')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->sortable()
                    ->searchable(),

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
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        return $state;
                    }),

                Tables\Columns\TextColumn::make('transaction.id')
                    ->label('ID Transaksi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Review')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ]),

                Tables\Filters\SelectFilter::make('motorcycle')
                    ->relationship('motorcycle', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Review')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('motorcycle.name')
                                    ->label('Motor'),

                                Infolists\Components\TextEntry::make('user.name')
                                    ->label('Penyewa'),

                                Infolists\Components\TextEntry::make('rating')
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
                                    ->formatStateUsing(fn (string $state): string => $state.' ★'),

                                Infolists\Components\TextEntry::make('transaction.id')
                                    ->label('ID Transaksi'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal Review')
                                    ->dateTime('d M Y H:i'),
                            ]),

                        Infolists\Components\TextEntry::make('comment')
                            ->label('Komentar')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMotorcycleReviews::route('/'),
            'create' => Pages\CreateMotorcycleReview::route('/create'),
            'view' => Pages\ViewMotorcycleReview::route('/{record}'),
            'edit' => Pages\EditMotorcycleReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
