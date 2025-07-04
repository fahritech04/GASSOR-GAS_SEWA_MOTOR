<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotorbikeRentalResource\Pages;
use App\Models\MotorbikeRental;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MotorbikeRentalResource extends Resource
{
    protected static ?string $model = MotorbikeRental::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Umum')
                            ->schema([
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->required()
                                    ->directory('motorbike_rental'),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->debounce(500)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                                Forms\Components\Select::make('city_id')
                                    ->relationship('city', 'name')
                                    ->required(),
                                Forms\Components\RichEditor::make('description')
                                    ->required(),
                                Forms\Components\Textarea::make('address')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Bonus Sewa')
                            ->schema([
                                Forms\Components\Repeater::make('bonuses')
                                    ->relationship('bonuses')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->required()
                                            ->directory('bonuses'),
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('description')
                                            ->required(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Motor')
                            ->schema([
                                Forms\Components\Repeater::make('motorcycles')
                                    ->relationship('motorcycles')
                                    ->schema([
                                        Forms\Components\Select::make('category_id')
                                            ->label('Kategori')
                                            ->relationship('category', 'name')
                                            ->required(),
                                        Forms\Components\Select::make('owner_id')
                                            ->label('Pemilik')
                                            ->options(
                                                \App\Models\User::where('role', 'pemilik')->pluck('name', 'id')
                                            )
                                            ->searchable()
                                            ->required(),
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('vehicle_number_plate')
                                            ->required(),
                                        Forms\Components\TextInput::make('stnk')
                                            ->required(),
                                        Forms\Components\FileUpload::make('stnk_images')
                                            ->label('STNK (Depan & Belakang)')
                                            ->multiple()
                                            ->image()
                                            ->directory('stnk')
                                            ->maxFiles(2)
                                            ->helperText('Upload gambar STNK depan dan belakang (maksimal 2 gambar)')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('price_per_day')
                                            ->numeric()
                                            ->prefix('IDR')
                                            ->required(),
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stok Total')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(1)
                                            ->required(),
                                        Forms\Components\TextInput::make('available_stock')
                                            ->label('Stok Tersedia')
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0)
                                            ->required(),
                                        Forms\Components\Toggle::make('has_gps')
                                            ->label('Ada GPS IoT?')
                                            ->helperText('Centang jika motor ini sudah terpasang GPS IoT.'),
                                        Forms\Components\Repeater::make('images')
                                            ->relationship('images')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->image()
                                                    ->required()
                                                    ->directory('motorcycles'),
                                            ]),
                                        Forms\Components\TextInput::make('start_rent_hour')
                                            ->label('Jam Awal Bisa Pinjam')
                                            ->type('time')
                                            ->default('08:00')
                                            ->required(),
                                        Forms\Components\TextInput::make('end_rent_hour')
                                            ->label('Jam Akhir Bisa Pinjam')
                                            ->type('time')
                                            ->default('20:00')
                                            ->required(),
                                    ]),
                            ]),
                    ])->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('city.name'),
                Tables\Columns\ImageColumn::make('thumbnail'),
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
            'index' => Pages\ListMotorbikeRentals::route('/'),
            'create' => Pages\CreateMotorbikeRental::route('/create'),
            'edit' => Pages\EditMotorbikeRental::route('/{record}/edit'),
        ];
    }
}
