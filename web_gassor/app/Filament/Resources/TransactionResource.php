<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required(),
                Forms\Components\Select::make('motorbike_rental_id')
                    ->relationship('motorbikeRental', 'name')
                    ->required(),
                Forms\Components\Select::make('motorcycle_id')
                    ->relationship('motorcycle', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->required(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'down_payment' => 'Down Payment',
                        'full_payment' => 'Full Payment',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\Select::make('rental_status')
                    ->options([
                        'pending' => 'Pending',
                        'on_going' => 'On Going',
                        'finished' => 'Finished',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_amount')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('motorbikeRental.name'),
                Tables\Columns\TextColumn::make('motorcycle.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('payment_method'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('rental_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'on_going' => 'info',
                        'finished' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_amount'),
                Tables\Columns\TextColumn::make('transaction_date'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
